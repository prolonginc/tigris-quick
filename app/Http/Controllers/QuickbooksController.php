<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuickbooksController extends Controller
{
    public function index()
    {
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
