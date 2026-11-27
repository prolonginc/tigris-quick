<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\QuickBooks\Client;
use App\QuickBooks\ProductSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class UpdateQuantityCommand extends Command
{
    protected $signature = 'quantity:update
        {--dry-run : Sync items but only list the products that would be removed}
        {--force : Remove products even if more than the safety limit are missing}';

    protected $description = 'Syncs all QuickBooks items to the products table';

    /**
     * Refuse to remove more than this share of the catalog in one run without
     * --force, so a bad or partial QuickBooks response can't wipe the portal.
     */
    private const MAX_REMOVAL_RATIO = 0.2;

    public function handle()
    {
        Auth::loginUsingId(1);

        $quickbooks = app(Client::class);
        $dataService = $quickbooks->getDataService();

        $startPosition = 1;
        $pageSize = 100;
        $synced = 0;
        $seen = [];

        $this->info('Starting QuickBooks item sync...');

        do {
            // QuickBooks returns only ACTIVE items here; removed items are simply absent.
            $items = $dataService->Query(
                "SELECT * FROM Item WHERE Type = 'Inventory' STARTPOSITION {$startPosition} MAXRESULTS {$pageSize}"
            );

            $error = $dataService->getLastError();
            if ($error) {
                $this->error('QuickBooks API error: ' . $error->getResponseBody());
                return 1;
            }

            if (!$items || count($items) === 0) {
                break;
            }

            foreach ($items as $item) {
                if (! ProductSync::isActive($item)) {
                    continue; // left out of $seen, so it is removed below
                }

                try {
                    ProductSync::upsert($item);
                    $synced++;
                } catch (\Throwable $e) {
                    $this->warn("Failed to sync item {$item->Id}: {$e->getMessage()}");
                }

                // Mark seen even if the upsert failed: the item is still live in
                // QuickBooks, so it must not be removed from the portal.
                $seen[$item->Id] = true;
            }

            $this->info("Processed batch starting at {$startPosition} (" . count($items) . " items)");
            $startPosition += $pageSize;

        } while (count($items) === $pageSize);

        return $this->removeMissingProducts($seen, $synced);
    }

    /**
     * Remove every product that QuickBooks no longer returns as active
     * inventory — deleted, made inactive, or changed to a non-inventory type.
     */
    private function removeMissingProducts(array $seen, int $synced): int
    {
        if (count($seen) === 0) {
            $this->error('QuickBooks returned no active inventory items; not removing anything.');
            return 1;
        }

        $missing = Product::query()
            ->pluck('name', 'id')
            ->reject(fn ($name, $id) => isset($seen[$id]));

        $limit = (int) floor(Product::count() * self::MAX_REMOVAL_RATIO);

        if ($missing->isNotEmpty()) {
            $this->line("Products no longer active in QuickBooks ({$missing->count()}):");
            $missing->each(fn ($name, $id) => $this->line("  - {$name} (#{$id})"));
        }

        if ($this->option('dry-run')) {
            $this->info("Dry run. Synced: {$synced}, would remove: {$missing->count()}");
            return 0;
        }

        if ($missing->count() > $limit && ! $this->option('force')) {
            $this->error(
                "Refusing to remove {$missing->count()} products (safety limit {$limit}). " .
                'Check the list above, then re-run with --force if it is correct.'
            );
            return 1;
        }

        $removed = 0;
        foreach ($missing->keys()->chunk(100) as $ids) {
            Product::whereIn('id', $ids)->get()->each(function (Product $product) use (&$removed) {
                ProductSync::deactivate($product);
                $removed++;
            });
        }

        $this->info("Done. Synced: {$synced}, Removed: {$removed}");

        return 0;
    }
}
