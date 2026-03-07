<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(! Auth::user()->is_approved) {
            return redirect('/pending');
        }
        $products = $request->product
            ? Product::search($request->product)->paginate(25)->appends(['product' => $request->product])
            : Product::paginate(25);
        return view('dashboard')->with(compact('products'));
    }
}
