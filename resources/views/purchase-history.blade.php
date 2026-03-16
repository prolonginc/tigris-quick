<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
            <div class="mb-4">
                <a href="{{ route('admin.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">&larr; Back to Users</a>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Purchase History for {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ $user->email }} @if($user->business_name)&middot; {{ $user->business_name }}@endif</p>
        @else
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Purchase History</h2>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">No purchase history to display.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</span>
                                    <span class="ml-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">${{ number_format($order->total_price, 2) }}</span>
                            </div>
                            @if($order->pickup_info || $order->pickup_time)
                                <div class="mt-2 text-sm text-gray-500">
                                    @if($order->pickup_info)
                                        <span>Pickup: {{ $order->pickup_info }}</span>
                                    @endif
                                    @if($order->pickup_time)
                                        <span class="ml-2">Time: {{ $order->pickup_time }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 pl-6 pr-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th scope="col" class="py-3 pl-3 pr-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-3 pl-6 pr-3 text-sm text-gray-900">
                                            {{ $item->product ? $item->product->name : 'Deleted Product' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 text-right">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-3 py-3 text-sm text-gray-500 text-right">{{ $item->quantity }}</td>
                                        <td class="py-3 pl-3 pr-6 text-sm font-medium text-gray-900 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
