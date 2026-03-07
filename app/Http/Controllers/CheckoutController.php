<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\CustomerOrderReceived;
use App\Notifications\AdminOrderPlaced;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'pickup_info'  => 'required|string',
            'pickup_time'  => 'required|string',
            'items'        => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $userId = auth()->id();
        $orderNumber = Order::generateOrderNumber();

        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $total += $product->price * $item['quantity'];
        }

        $order = DB::transaction(function () use ($validated, $userId, $total, $orderNumber) {
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'total_price' => $total,
                'pickup_info' => $validated['pickup_info'],
                'pickup_time' => $validated['pickup_time'],
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

            return $order;
        });

        $user = auth()->user();
        $user->notify(new CustomerOrderReceived($order));

        Notification::route('mail', config('app.order_notification_email'))
            ->notify(new AdminOrderPlaced($order));

        return response()->json([
            'order_number' => $order->order_number,
            'pickup_info' => $order->pickup_info,
            'pickup_time' => $order->pickup_time,
            'success' => true,
            'message' => 'Order placed successfully!',
        ]);
    }
}
