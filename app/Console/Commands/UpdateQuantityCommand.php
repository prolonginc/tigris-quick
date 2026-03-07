<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\QuickBooks\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdateQuantityCommand extends Command
{
    protected $signature = 'quantity:update';

    protected $description = 'Syncs all QuickBooks items to the products table';

    public function handle()
    {
        Auth::loginUsingId(1);

        $quickbooks = app(Client::class);
        $dataService = $quickbooks->getDataService();

        $startPosition = 1;
        $pageSize = 100;
        $synced = 0;
        $deleted = 0;

        $this->info('Starting QuickBooks item sync...');

        do {
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
                try {
                    if (Str::contains($item->Name, 'deleted')) {
                        $product = Product::find($item->Id);
                        if ($product) {
                            $product->delete();
                            $deleted++;
                        }
                    } else {
                        Product::updateOrCreate(
                            ['id' => $item->Id],
                            [
                                'name' => $item->Name,
                                'description' => $item->Description ?? '',
                                'quantity' => $item->QtyOnHand ?? 0,
                                'price' => $item->UnitPrice,
                            ]
                        );
                        $synced++;
                    }
                } catch (\Throwable $e) {
                    $this->warn("Failed to sync item {$item->Id}: {$e->getMessage()}");
                }
            }

            $this->info("Processed batch starting at {$startPosition} (" . count($items) . " items)");
            $startPosition += $pageSize;

        } while (count($items) === $pageSize);

        $this->info("Done. Synced: {$synced}, Deleted: {$deleted}");

        return 0;
    }
}
