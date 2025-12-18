<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Http\Requests\StoreCartRequest;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function store(StoreCartRequest $request, Product $product)
    {
        $validated = $request->validated();

        $cart = Cart::firstOrNew([
            'user_id'    => Auth::id(),
            'product_id' => $validated['product_id'],
        ]);

        // If exists → increment
        $cart->quantity = ($cart->exists ? $cart->quantity : 0) + ($validated['quantity'] ?? 1);
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart'    => $cart->load('product'),
        ]);
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id',  auth()->id())
            ->get();

        return response()->json([
            'success' => true,
            'items' => $cartItems
        ]);

        return view('cart.index', compact('cartItems'));
    }

    public function updateCartBeforeCheckout(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        DB::transaction(function () use ($validated, $userId) {
            foreach ($validated['items'] as $item) {
                DB::table('carts')
                    ->updateOrInsert(
                        [
                            'user_id'    => $userId,
                            'product_id' => $item['product_id'],
                        ],
                        [
                            'quantity'   => $item['quantity'],
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated before checkout',
        ]);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->back()->with('success', 'Product removed from cart.');
    }
}
