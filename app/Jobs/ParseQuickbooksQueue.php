<?php

namespace App\Jobs;

use App\Models\Product;
use App\QuickBooks\ProductSync;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ParseQuickbooksQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        Auth::loginUsingId(1);

        try {
            $quickbooks = app(\App\QuickBooks\Client::class);
            $item = $quickbooks->getDataService()->FindbyId('item', $this->id);

            if (! $item) {
                return;
            }

            if (! ProductSync::isActive($item)) {
                // Deleted / made inactive in QuickBooks: take it off the portal.
                if ($product = Product::find($this->id)) {
                    ProductSync::deactivate($product);
                }
            } elseif ($item->QtyOnHand !== null) {
                ProductSync::upsert($item);
            }
        } catch (\Throwable $e) {
            \Log::warning("ParseQuickbooksQueue failed for item {$this->id}: {$e->getMessage()}");
        }
    }
}
