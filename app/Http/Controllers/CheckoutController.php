<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\CustomerOrderReceived;
use App\Notifications\AdminOrderPlaced;
use Illuminate\Support\Facades\Notification;


class CheckoutController extends Controller
{
    private function generateOrderId(): string
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }

    public function getOrderId()
    {
        $orderId = $this->generateOrderId();
        session(['pending_order_id' => $orderId]);

        return response()->json([
            'success'  => true,
            'order_id' => $orderId,
        ]);
    }
    public function placeOrder(Request $request)
    {
            $validated = $request->validate([
                'order_number' => 'required|string',
                'pickup_info'  => 'required|string',
                'pickup_time'  => 'required|string',
                'items'        => 'required|array',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity'   => 'required|integer|min:1',
            ]);
    

        $userId = auth()->id();
        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $total += $product->price * $item['quantity'];
        }
        DB::transaction(function () use ($validated, $userId ,$total) {
            $order = Order::create([
                    'order_number' => $validated['order_number'],
                    'user_id' => auth()->user()->id,
                    'total_price' => $total,
                    'pickup_info' => $validated['pickup_info'] ?? null,
                    'pickup_time' => $validated['pickup_time'] ?? null,
                    'status' => 'pending',
                ]);
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }
                Cart::where('user_id', $userId)->delete();

        });
        // Send notifications
        $user = auth()->user();
        $order = Order::where('order_number' ,$validated['order_number'])->first();

        // 1️⃣ Send to customer
        $user->notify(new CustomerOrderReceived($order));

        // 2️⃣ Send to admin
        Notification::route('mail', 'order@tigrisautoglass.com')
            ->notify(new AdminOrderPlaced($order));
        return response()->json([
            'order_number' => $validated['order_number']??null,
            'pickup_info' => $validated['pickup_info'] ?? null,
            'pickup_time' => $validated['pickup_time'] ?? null,
            'success' => true,
            'message' => 'Order placed successfully!',
        ]);

    }
}
