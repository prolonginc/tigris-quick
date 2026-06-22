<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Purchase History</h2>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @forelse($orders as $order)
            @php
                $statusStyles = [
                    \App\Models\Order::STATUS_PENDING => 'bg-blue-100 text-blue-800',
                    \App\Models\Order::STATUS_CANCELLED => 'bg-red-100 text-red-800',
                    \App\Models\Order::STATUS_RETURNED => 'bg-amber-100 text-amber-800',
                    \App\Models\Order::STATUS_PARTIALLY_RETURNED => 'bg-amber-100 text-amber-800',
                ];
                $statusLabels = [
                    \App\Models\Order::STATUS_PENDING => 'Pending',
                    \App\Models\Order::STATUS_CANCELLED => 'Cancelled',
                    \App\Models\Order::STATUS_RETURNED => 'Returned',
                    \App\Models\Order::STATUS_PARTIALLY_RETURNED => 'Partially Returned',
                ];
                $hasReturnable = ! $order->isCancelled() && $order->items->sum(fn ($i) => $i->returnableQuantity()) > 0;
            @endphp

            <div class="bg-white shadow rounded-lg border border-gray-200 mb-6 overflow-hidden">
                <div class="px-4 py-4 sm:px-6 flex flex-wrap items-center justify-between gap-3 border-b border-gray-200">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>

                <div class="px-4 py-2 sm:px-6 divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Item' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->product->description ?? '' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Qty {{ $item->quantity }}
                                    @if($item->returned_quantity > 0)
                                        &middot; <span class="text-amber-700">{{ $item->returned_quantity }} returned</span>
                                    @endif
                                </p>
                            </div>

                            @if(! $order->isCancelled() && $item->returnableQuantity() > 0)
                                <form action="{{ route('orders.return', $order) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="items[0][order_item_id]" value="{{ $item->id }}">
                                    <input type="number" name="items[0][quantity]" min="1" max="{{ $item->returnableQuantity() }}" value="1"
                                           class="w-16 rounded-md border-gray-300 text-sm">
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-amber-100 text-amber-800 hover:bg-amber-200">
                                        Return
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($hasReturnable)
                    <div class="px-4 py-3 sm:px-6 bg-gray-50 flex flex-wrap items-center justify-end gap-3 border-t border-gray-200">
                        <form action="{{ route('orders.return', $order) }}" method="POST"
                              onsubmit="return confirm('Return all remaining items in this order?');">
                            @csrf
                            <input type="hidden" name="return_all" value="1">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-amber-600 text-white hover:bg-amber-700">
                                Return All Items
                            </button>
                        </form>
                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                              onsubmit="return confirm('Cancel this entire order?');">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Your purchase history will appear here.</p>
        @endforelse
    </div>
</x-app-layout>
