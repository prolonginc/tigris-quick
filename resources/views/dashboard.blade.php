<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="text-center mt-4 mb-2">
            <h1 class="text-2xl font-bold text-gray-900">Auto Glass Parts</h1>
            <p class="mt-1 text-sm text-gray-500">Browse our inventory of windshields, back glass, door glass, and more</p>
        </div>

        <div x-data="productSearch()" class="mt-6 max-w-2xl mx-auto relative z-10">
            <div class="relative">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @focus="if (results.length > 0) showResults = true"
                    @click.away="showResults = false"
                    @keydown.escape="showResults = false"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 pl-11 pr-4 py-3 rounded-full text-lg"
                    placeholder="Search by part number, vehicle, or glass type..."
                    autocomplete="off"
                >
                <div x-show="loading" class="absolute right-4 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>

            <div x-show="showResults" x-transition class="absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden z-20">
                <template x-if="results.length === 0 && query.length >= 2 && !loading">
                    <div class="px-4 py-3 text-sm text-gray-500">No products found.</div>
                </template>
                <ul class="divide-y divide-gray-100">
                    <template x-for="product in results" :key="product.id">
                        <li class="px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900" x-text="product.name"></div>
                                    <div class="text-gray-500 text-xs" x-text="product.description"></div>
                                    <div class="text-sm text-gray-700 mt-1">
                                        $<span x-text="parseFloat(product.price).toFixed(2)"></span>
                                        <span x-show="product.in_stock" class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock</span>
                                        <span x-show="!product.in_stock" class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">Out of Stock</span>
                                    </div>
                                </div>
                                <div x-show="product.in_stock">
                                    <button
                                        @click="addToCart(product)"
                                        class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-white"
                                        style="background-color: #24C3EE;"
                                        @mouseover="$el.style.backgroundColor='#1aa8d0'"
                                        @mouseout="$el.style.backgroundColor='#24C3EE'">
                                        Add to Cart
                                    </button>
                                </div>
                                <div x-show="!product.in_stock">
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-200 text-gray-600">Unavailable</span>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-center gap-2 text-xs text-gray-400">
                <span>Try:</span>
                <button @click="query = 'windshield'; search()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">windshield</button>
                <button @click="query = 'back glass'; search()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">back glass</button>
                <button @click="query = 'door glass'; search()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">door glass</button>
                <button @click="query = 'Toyota'; search()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">Toyota</button>
                <button @click="query = 'Honda'; search()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 transition-colors">Honda</button>
            </div>
        </div>

        <!-- Product Card Grid -->
        <div class="mt-8 max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} available</p>
            </div>

            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow ring-1 ring-black ring-opacity-5 overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col">
                    <div class="p-4 flex-1">
                        <div class="flex items-start justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 leading-tight">{{ $product->name }}</h3>
                            @if($product->quantity > 0)
                                <span class="ml-2 flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock</span>
                            @else
                                <span class="ml-2 flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">Out of Stock</span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $product->description }}</p>
                        <p class="mt-3 text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="px-4 pb-4">
                        @if($product->quantity > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="description" value="{{ $product->description }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium text-white transition-colors"
                                    style="background-color: #24C3EE;"
                                    onmouseover="this.style.backgroundColor='#1aa8d0'"
                                    onmouseout="this.style.backgroundColor='#24C3EE'">
                                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 mb-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back soon for new parts.</p>
            </div>
            @endif
        </div>
    </div>

<div id="cart-sidebar"
    class="fixed top-0 right-0 z-50 h-full w-full sm:w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-6 h-full flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Your Cart </h2>
                <button id="close-cart-button" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div  class="flex-grow my-6 overflow-y-auto">
                <div id="cart-items-container" class="space-y-4">

                </div>
            </div>

            <div class="pb-6">
                <button id="checkout-button" class="w-full py-3 text-lg font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Checkout</button>
            </div>
        </div>
    </div>
<div id="checkout-modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-lg p-6 relative">
        <button id="close-checkout"
            class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Checkout</h2>
        <div class="mb-4">
            <label class="block text-sm font-medium">Pickup Info</label>
            <input type="text"
                id="pickup-info"
                class="mt-1 block w-full border rounded-md p-2"
                value="Sacramento, 1054 El Camino Ave, Sacramento, CA 95815" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Pickup Time (Hourly)</label>
            <select class="mt-1 block w-full border rounded-md p-2"  id="pickup-time">
                <option>Pickup Now</option>
                <option>8:00 AM</option>
                <option>9:00 AM</option>
                <option>10:00 AM</option>
            </select>
        </div>
        <div class="mb-4">
            <h3 class="font-semibold">Order Summary</h3>
            <p id="order-number" class="text-gray-600"></p>
        </div>
        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
            Place Order
        </button>
    </div>
</div>
<div id="order-success-modal"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center relative">
        <button id="close-success-modal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <div class="flex justify-center mb-4">
            <div class="h-14 w-14 flex items-center justify-center rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Thanks! Your will-call order is in.</h2>
        <p class="text-blue-600 font-semibold mb-1" id="success-order-number"></p>
        <p class="text-gray-600 text-sm mb-6" id="success-pickup-info"></p>
        <button id="continue-shopping-btn"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
            Continue Shopping
        </button>
    </div>
</div>

<script>
function productSearch() {
    return {
        query: '',
        results: [],
        showResults: false,
        loading: false,

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/api/products/search?q=${encodeURIComponent(this.query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                this.results = await response.json();
                this.showResults = true;
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                this.loading = false;
            }
        },

        async addToCart(product) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData = new FormData();
            formData.append('product_id', product.id);
            formData.append('quantity', 1);

            try {
                const response = await fetch('{{ route("cart.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (response.ok) {
                    document.dispatchEvent(new CustomEvent('cart-updated', { detail: product }));
                }
            } catch (error) {
                console.error('Add to cart failed:', error);
            }
        }
    };
}

document.addEventListener('DOMContentLoaded', async () => {
    const openButton = document.getElementById('open-cart-button');
    const closeButton = document.getElementById('close-cart-button');
    const sidebar = document.getElementById('cart-sidebar');
    const cartItemsContainer = document.querySelector('#cart-sidebar .space-y-4');
    const cartCount = document.querySelector('#open-cart-button span');
    const cartTitle = document.querySelector('#cart-sidebar h2');
    const checkoutBtn = document.getElementById("checkout-button");
    const checkoutModal = document.getElementById("checkout-modal");
    const closeCheckout = document.getElementById("close-checkout");
    const placeOrderBtn = document.querySelector('#checkout-modal button.bg-green-600');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let count = 0;

    function openSidebar() {
        sidebar.classList.remove('translate-x-full');
    }
    function closeSidebar() {
        sidebar.classList.add('translate-x-full');
    }
    openButton.addEventListener('click', openSidebar);
    closeButton.addEventListener('click', closeSidebar);

    async function getCartProducts() {
        try {
            const response = await fetch('/cart', {
                method: 'GET',
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            });
            if (!response.ok) throw new Error("Failed to fetch cart products");
            const data = await response.json();
            if (data.success) renderCartItems(data.items);
        } catch (error) {
            console.error("Error loading cart:", error);
        }
    }

    function renderCartItems(items) {
        cartItemsContainer.innerHTML = '';
        let totalCount = 0;
        items.forEach(item => {
            totalCount += item.quantity;
            const el = document.createElement('div');
            el.classList.add('border-b', 'pb-3');
            el.setAttribute('data-product-id', item.product_id);
            el.innerHTML = `
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-gray-900">${item.product.name}</h3>
                    <div class="flex items-center space-x-2">
                        <button class="decrease-btn text-gray-600 hover:text-gray-800">-</button>
                        <span class="item-qty">${item.quantity}</span>
                        <button class="increase-btn text-gray-600 hover:text-gray-800">+</button>
                        <button class="text-red-600 hover:text-red-800 delete-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-1">${item.product.description}</p>
                <p class="text-sm text-gray-900 font-bold item-price mt-1">$${(item.product.price * item.quantity).toFixed(2)}</p>
            `;
            cartItemsContainer.appendChild(el);
        });
        count = totalCount;
        updateCartHeader();
    }

    function updateCartHeader() {
        cartCount.textContent = count;
        cartTitle.textContent = `Your Cart (${count} item${count !== 1 ? 's' : ''})`;
    }

    function getCartItemsFromSidebar() {
        return Array.from(cartItemsContainer.querySelectorAll('[data-product-id]')).map(el => ({
            product_id: el.getAttribute('data-product-id'),
            quantity: parseInt(el.querySelector('.item-qty').textContent)
        }));
    }

    async function updateCartOnServer(items) {
        await fetch('/cart/update-cart', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ items })
        });
    }

    async function placeOrder(items, pickupInfo, pickupTime) {
        const response = await fetch('/checkout/place-order', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                items,
                pickup_info: pickupInfo,
                pickup_time: pickupTime
            })
        });
        if (!response.ok) {
            const err = await response.json();
            throw new Error(err.message || 'Failed to place order');
        }
        return await response.json();
    }

    // Handle Add to Cart forms from the product grid
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const productId = this.querySelector('[name="product_id"]').value;
            const name = this.querySelector('[name="name"]').value;
            const description = this.querySelector('[name="description"]').value;
            const price = parseFloat(this.querySelector('[name="price"]').value);

            const formData = new FormData(this);
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                if (response.ok) {
                    document.dispatchEvent(new CustomEvent('cart-updated', {
                        detail: { id: productId, name, description, price }
                    }));
                }
            } catch (error) {
                console.error('Add to cart failed:', error);
            }
        });
    });

    // Handle cart-updated event from Alpine autocomplete and grid forms
    document.addEventListener('cart-updated', (e) => {
        const product = e.detail;
        const productId = product.id;

        let existingItem = cartItemsContainer.querySelector(`[data-product-id="${productId}"]`);
        if (existingItem) {
            const qtySpan = existingItem.querySelector('.item-qty');
            const qty = parseInt(qtySpan.textContent) + 1;
            qtySpan.textContent = qty;
            existingItem.querySelector('.item-price').textContent = `$${(parseFloat(product.price) * qty).toFixed(2)}`;
        } else {
            const item = document.createElement('div');
            item.classList.add('border-b', 'pb-3');
            item.setAttribute("data-product-id", productId);
            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-gray-900">${product.name}</h3>
                    <div class="flex items-center space-x-2">
                        <button class="decrease-btn text-gray-600 hover:text-gray-800">-</button>
                        <span class="item-qty">1</span>
                        <button class="increase-btn text-gray-600 hover:text-gray-800">+</button>
                        <button class="text-red-600 hover:text-red-800 delete-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-1">${product.description || ''}</p>
                <p class="text-sm text-gray-900 font-bold item-price mt-1">$${parseFloat(product.price).toFixed(2)}</p>
            `;
            cartItemsContainer.appendChild(item);
        }
        count++;
        updateCartHeader();
        openSidebar();
        setTimeout(closeSidebar, 1000);
    });

    cartItemsContainer.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        const itemEl = btn.closest('[data-product-id]');
        const qtySpan = itemEl.querySelector('.item-qty');
        const priceEl = itemEl.querySelector('.item-price');
        const basePrice = parseFloat(priceEl.textContent.replace('$', '')) / parseInt(qtySpan.textContent);
        let qty = parseInt(qtySpan.textContent);

        if (btn.classList.contains('increase-btn')) {
            qty++;
            qtySpan.textContent = qty;
            priceEl.textContent = `$${(basePrice * qty).toFixed(2)}`;
        }

        if (btn.classList.contains('decrease-btn') && qty > 1) {
            qty--;
            qtySpan.textContent = qty;
            priceEl.textContent = `$${(basePrice * qty).toFixed(2)}`;
        }

        if (btn.classList.contains('delete-item')) {
            itemEl.remove();
        }

        count = Array.from(cartItemsContainer.querySelectorAll('.item-qty'))
                     .reduce((sum, el) => sum + parseInt(el.textContent), 0);
        updateCartHeader();

        try {
            await updateCartOnServer(getCartItemsFromSidebar());
        } catch (error) {
            console.error('Failed to update cart:', error);
        }
    });

    checkoutBtn.addEventListener("click", async () => {
        const items = getCartItemsFromSidebar();
        if (items.length === 0) return alert("Your cart is empty.");

        try {
            await updateCartOnServer(items);
            const summaryParts = Array.from(cartItemsContainer.querySelectorAll('[data-product-id]')).map(el => {
                const name = el.querySelector('h3').textContent;
                const qty = el.querySelector('.item-qty').textContent;
                return `${name} x${qty}`;
            });
            document.getElementById('order-number').textContent = summaryParts.join(', ');
            checkoutModal.classList.remove("hidden");
            checkoutModal.classList.add("flex");
        } catch (err) {
            console.error(err);
            alert("Failed to prepare checkout. Try again.");
        }
    });

    placeOrderBtn.addEventListener("click", async () => {
        try {
            const items = getCartItemsFromSidebar();
            if (items.length === 0) return alert("Your cart is empty.");

            const pickupInfo = document.getElementById('pickup-info').value;
            const pickupTime = document.getElementById('pickup-time').value;

            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Placing Order...';

            const result = await placeOrder(items, pickupInfo, pickupTime);

            if (result.success) {
                document.getElementById('success-order-number').textContent = `Order #${result.order_number}`;
                document.getElementById('success-pickup-info').textContent = `Pickup at ${result.pickup_info} at ${result.pickup_time}.`;
                document.getElementById('order-success-modal').classList.remove('hidden');
                checkoutModal.classList.add('hidden');
                checkoutModal.classList.remove('flex');

                document.getElementById('continue-shopping-btn').addEventListener('click', () => {
                    document.getElementById('order-success-modal').classList.add('hidden');
                    window.location.reload();
                });
            } else {
                alert(result.message || 'Something went wrong');
            }
        } catch (err) {
            console.error(err);
            alert("Failed to place order. Try again.");
        } finally {
            placeOrderBtn.disabled = false;
            placeOrderBtn.textContent = 'Place Order';
        }
    });

    closeCheckout.addEventListener("click", () => {
        checkoutModal.classList.add("hidden");
        checkoutModal.classList.remove("flex");
    });

    checkoutModal.addEventListener("click", (e) => {
        if (e.target === checkoutModal) {
            checkoutModal.classList.add("hidden");
            checkoutModal.classList.remove("flex");
        }
    });

    await getCartProducts();
});
</script>

</x-app-layout>
