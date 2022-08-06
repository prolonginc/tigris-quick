<?php

namespace App\Http\Controllers;

use App\Jobs\ParseQuickbooksQueue;
use Illuminate\Http\Request;

class QuickbooksController extends Controller
{
    public function index()
    {
        $delay = 0;
        for ($i=1; $i < 4000; $i++) {
            ParseQuickbooksQueue::onQueue('default')->dispatch($i)->delay(now()->addMinutes($delay));
            if($i %50 == 0) {
                $delay++;
            }
        }

        $quickbooks = app('Spinen\QuickBooks\Client');
        $items = [];
        for ($i=1; $i < 9; $i++) {
            $item = $quickbooks->getDataService()->FindbyId('item', $i);
            if($item) {
            $items[] = $item->Name;
            }
        }
      dd($items);
    }
}
