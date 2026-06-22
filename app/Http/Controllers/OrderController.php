<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomerOrderCancelled;
use App\Notifications\AdminOrderCancelled;
use App\Notifications\CustomerOrderReturned;
use App\Notifications\AdminOrderReturned;

class OrderController extends Controller
{
    /**
     * List the authenticated user's orders for the purchase history page.
     */
    public function history()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('purchase-history', ['orders' => $orders]);
    }

    /**
     * Cancel an entire order.
     */
    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->isCancelled()) {
            return back()->with('error', 'This order is already cancelled.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        $order->user->notify(new CustomerOrderCancelled($order));
        Notification::route('mail', config('app.order_notification_email'))
            ->notify(new AdminOrderCancelled($order));

        return back()->with('success', "Order {$order->order_number} was cancelled.");
    }

    /**
     * Return some or all items in an order.
     */
    public function returnItems(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->isCancelled()) {
            return back()->with('error', 'Cancelled orders cannot be returned.');
        }

        $validated = $request->validate([
            'return_all'        => 'sometimes|boolean',
            'items'             => 'required_without:return_all|array',
            'items.*.order_item_id' => 'required|integer',
            'items.*.quantity'      => 'required|integer|min:1',
        ]);

        $order->load('items.product');
        $returnAll = $request->boolean('return_all');

        // Build a map of how many units to return per order item.
        $toReturn = [];
        if ($returnAll) {
            foreach ($order->items as $item) {
                if ($item->returnableQuantity() > 0) {
                    $toReturn[$item->id] = $item->returnableQuantity();
                }
            }
        } else {
            foreach ($validated['items'] as $line) {
                $toReturn[$line['order_item_id']] = ($toReturn[$line['order_item_id']] ?? 0) + $line['quantity'];
            }
        }

        if (empty($toReturn)) {
            return back()->with('error', 'There are no items available to return.');
        }

        $returnedItems = DB::transaction(function () use ($order, $toReturn) {
            $returned = [];
            $refund = 0;

            foreach ($order->items as $item) {
                if (! isset($toReturn[$item->id])) {
                    continue;
                }

                $qty = min($toReturn[$item->id], $item->returnableQuantity());
                if ($qty <= 0) {
                    continue;
                }

                $item->increment('returned_quantity', $qty);
                $refund += $qty * (float) $item->price;

                $returned[] = [
                    'name' => $item->product->name ?? 'Item',
                    'description' => $item->product->description ?? '',
                    'quantity' => $qty,
                ];
            }

            // Reduce the order total by the value of the returned items.
            $order->total_price = max(0, (float) $order->total_price - $refund);

            $order->load('items');
            $order->syncReturnStatus();

            return $returned;
        });

        if (empty($returnedItems)) {
            return back()->with('error', 'The selected items have already been returned.');
        }

        $order->user->notify(new CustomerOrderReturned($order, $returnedItems));
        Notification::route('mail', config('app.order_notification_email'))
            ->notify(new AdminOrderReturned($order, $returnedItems));

        return back()->with('success', "Return processed for order {$order->order_number}.");
    }

    /**
     * Ensure the order belongs to the authenticated user.
     */
    protected function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth()->id()) {
            throw ValidationException::withMessages([
                'order' => 'You are not allowed to modify this order.',
            ]);
        }
    }
}
