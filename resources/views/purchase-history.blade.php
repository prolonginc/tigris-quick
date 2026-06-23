<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @php
            $viewingOther = isset($user) && auth()->user()->isAdmin() && $user->id !== auth()->id();
        @endphp

        @if($viewingOther)
            <div class="mb-4">
                <a href="{{ route('admin.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">&larr; Back to Users</a>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Purchase History for {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ $user->email }} @if($user->business_name)&middot; {{ $user->business_name }}@endif</p>
        @else
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Purchase History</h2>
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
                $canManage = ! $viewingOther && $order->user_id === auth()->id();
                $hasReturnable = $canManage && ! $order->isCancelled() && $order->items->sum(fn ($i) => $i->returnableQuantity()) > 0;
            @endphp

            <div class="bg-white shadow rounded-lg border border-gray-200 mb-6 overflow-hidden">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                        @if($order->status !== \App\Models\Order::STATUS_PENDING)
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        @endif
                    </div>
                    @if($order->pickup_info || $order->pickup_time)
                        <div class="mt-2 text-xs text-gray-500">
                            @if($order->pickup_info)<span>Pickup: {{ $order->pickup_info }}</span>@endif
                            @if($order->pickup_time)<span class="ml-2">Time: {{ $order->pickup_time }}</span>@endif
                        </div>
                    @endif
                </div>

                <div class="px-4 py-2 sm:px-6 divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product ? $item->product->name : 'Deleted Product' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->product->description ?? '' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Qty {{ $item->quantity }}
                                    @if($item->returned_quantity > 0)
                                        &middot; <span class="text-amber-700">{{ $item->returned_quantity }} returned</span>
                                    @endif
                                </p>
                            </div>

                            @if($canManage && ! $order->isCancelled() && $item->returnableQuantity() > 0)
                                <form action="{{ route('orders.return', $order) }}" method="POST" class="flex items-center gap-2 js-confirm"
                                      data-confirm-title="Return item?"
                                      data-confirm-message="Return the selected quantity of this item? This can't be undone."
                                      data-confirm-label="Return"
                                      data-confirm-variant="warning">
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
                        <form action="{{ route('orders.return', $order) }}" method="POST" class="js-confirm"
                              data-confirm-title="Return all items?"
                              data-confirm-message="Return all remaining items in order {{ $order->order_number }}? This can't be undone."
                              data-confirm-label="Return All"
                              data-confirm-variant="warning">
                            @csrf
                            <input type="hidden" name="return_all" value="1">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-amber-600 text-white hover:bg-amber-700">
                                Return All Items
                            </button>
                        </form>
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="js-confirm"
                              data-confirm-title="Cancel order?"
                              data-confirm-message="Cancel order {{ $order->order_number }} entirely? This can't be undone."
                              data-confirm-label="Cancel Order"
                              data-confirm-variant="danger">
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
            <div class="bg-white rounded-lg shadow px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">No purchase history to display.</p>
            </div>
        @endforelse
    </div>

    {{-- Confirmation modal --}}
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center relative">
            <h2 id="confirm-title" class="text-lg font-semibold text-gray-900 mb-2"></h2>
            <p id="confirm-message" class="text-sm text-gray-600 mb-6"></p>
            <div class="flex justify-center gap-3">
                <button type="button" id="confirm-cancel"
                        class="px-4 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Keep
                </button>
                <button type="button" id="confirm-ok"
                        class="px-4 py-2 rounded-md text-sm font-medium text-white">
                </button>
            </div>
        </div>
    </div>

    {{-- Success modal --}}
    <div id="ph-success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center relative">
            <button type="button" id="ph-success-close"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <div class="flex justify-center mb-4">
                <div class="h-14 w-14 flex items-center justify-center rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">All set</h2>
            <p class="text-gray-600 text-sm mb-6" id="ph-success-message"></p>
            <button type="button" id="ph-success-ok"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                Done
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmModal = document.getElementById('confirm-modal');
            const confirmTitle = document.getElementById('confirm-title');
            const confirmMessage = document.getElementById('confirm-message');
            const confirmOk = document.getElementById('confirm-ok');
            const confirmCancel = document.getElementById('confirm-cancel');
            let pendingForm = null;

            const variantClasses = {
                danger: ['bg-red-600', 'hover:bg-red-700'],
                warning: ['bg-amber-600', 'hover:bg-amber-700'],
            };

            function openConfirm(form) {
                pendingForm = form;
                confirmTitle.textContent = form.dataset.confirmTitle || 'Are you sure?';
                confirmMessage.textContent = form.dataset.confirmMessage || '';
                confirmOk.textContent = form.dataset.confirmLabel || 'Confirm';
                confirmOk.className = 'px-4 py-2 rounded-md text-sm font-medium text-white';
                (variantClasses[form.dataset.confirmVariant] || variantClasses.warning)
                    .forEach(c => confirmOk.classList.add(c));
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            }

            function closeConfirm() {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                pendingForm = null;
            }

            document.querySelectorAll('form.js-confirm').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    openConfirm(form);
                });
            });

            confirmOk.addEventListener('click', () => {
                if (pendingForm) pendingForm.submit();
            });
            confirmCancel.addEventListener('click', closeConfirm);
            confirmModal.addEventListener('click', (e) => {
                if (e.target === confirmModal) closeConfirm();
            });

            @if(session('success'))
                const successModal = document.getElementById('ph-success-modal');
                document.getElementById('ph-success-message').textContent = @json(session('success'));
                const closeSuccess = () => {
                    successModal.classList.add('hidden');
                    successModal.classList.remove('flex');
                };
                successModal.classList.remove('hidden');
                successModal.classList.add('flex');
                document.getElementById('ph-success-ok').addEventListener('click', closeSuccess);
                document.getElementById('ph-success-close').addEventListener('click', closeSuccess);
                successModal.addEventListener('click', (e) => {
                    if (e.target === successModal) closeSuccess();
                });
            @endif
        });
    </script>
</x-app-layout>
