<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="lg:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Products</h1>
            </div>
        </div>

        <div x-data="productSearch()" class="mt-6 max-w-2xl mx-auto">
            <div class="relative">
                <input
                    type="text"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @focus="if (results.length > 0) showResults = true"
                    @click.away="showResults = false"
                    @keydown.escape="showResults = false"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 px-4 py-3 rounded-full text-lg"
                    placeholder="Search for a part..."
                    autocomplete="off"
                >
                <div x-show="loading" class="absolute right-4 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>

            <div x-show="showResults" x-transition class="mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
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
                                        <span x-show="product.in_stock" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock</span>
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

    // Handle cart-updated event from Alpine autocomplete
    document.addEventListener('cart-updated', (e) => {
        const product = e.detail;
        const productId = product.id;

        let existingItem = cartItemsContainer.querySelector(`[data-product-id="${productId}"]`);
        if (existingItem) {
            const qtySpan = existingItem.querySelector('.item-qty');
            const qty = parseInt(qtySpan.textContent) + 1;
            qtySpan.textContent = qty;
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
        let qty = parseInt(qtySpan.textContent);

        if (btn.classList.contains('increase-btn')) {
            qty++;
            qtySpan.textContent = qty;
        }

        if (btn.classList.contains('decrease-btn') && qty > 1) {
            qty--;
            qtySpan.textContent = qty;
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
