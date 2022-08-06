<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseQuickbooksQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $quickbooks = app('Spinen\QuickBooks\Client');
        $product = $quickbooks->getDataService()->FindbyId('item', $this->id)->Name;
        Product::updateOrCreate(
            ['id' =>$this->id],
            [
                'name' => $product->Name,
                'description' => $product->Description,
                'quantity' => $product->QtyOnHand,
                'price' => $product->UnitPrice,
            ]
        );




    }
}
