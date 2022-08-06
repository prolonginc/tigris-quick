<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(! Auth::user()->is_approved) {
            return redirect(RouteServiceProvider::PENDING);
        }
        $products = Product::search($request->product)->paginate(20);
        return view('dashboard')->with(compact('products'));
    }
}
