<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::user()->is_approved) {
            return redirect('/pending');
        }

        $products = $request->product
            ? Product::search($request->product)->paginate(24)->appends(['product' => $request->product])
            : Product::paginate(24);

        return view('dashboard', compact('products'));
    }

    public function adminIndex(Request $request)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $products = $request->product
            ? Product::search($request->product)->paginate(25)->appends(['product' => $request->product])
            : Product::paginate(25);

        return view('admin.parts')->with(compact('products'));
    }

    public function searchApi(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::search($query)->take(10)->get();

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'description' => $product->description,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'in_stock' => $product->quantity > 0,
            ];
        }));
    }
}
