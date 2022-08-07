<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        Auth::logInUsingId(13);

        $quickbooks = app('Spinen\QuickBooks\Client');
        $item = $quickbooks->getDataService()->FindbyId('item', $this->id);
        if($item && $item->QtyOnHand != null) {
            if(Str::contains($item->Name, 'deleted')) {
                $product = Product::find($this->id);
                if($product) {
                    $product->delete();
                }
            } else {
                Product::updateOrCreate(
                    ['id' =>$this->id],
                    [
                        'name' => $item->Name,
                        'description' => $item->Description,
                        'quantity' => $item->QtyOnHand,
                        'price' => $item->UnitPrice,
                    ]
                );

            }
        }



    }
}
