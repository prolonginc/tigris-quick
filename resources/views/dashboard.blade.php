<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="lg:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Products</h1>
            </div>
            <div class="flex-auto">
                <form action="/dashboard">
                    <div class="mt-4">
                        <input type="text" name="product" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 px-4 rounded-full" placeholder="Search By Product">
                    </div>
                </form>
            </div>
        </div>

        <!-- <div class="absolute top-4 right-4 z-20">
            <button id="open-cart-button" class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.183 1.77.707 1.77H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">2</span>
            </button>
        </div> -->

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product Number</th>
                                    <!-- <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Product Description</th> -->
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Available</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($products as $product)
                                <tr>
                                    <!-- <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{$product->name}}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$product->description}}</td> -->
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-gray-500 text-xs">{{ $product->description }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{$product->price}}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($product->quantity)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">In Stock</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($product->quantity > 0)
                                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                                <input type="hidden" name="name" value="{{ $product->name }}">
                                                <input type="hidden" name="description" value="{{ $product->description }}">
                                                <input type="hidden" name="price" value="{{ $product->price }}">
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                                                Unavailable
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
    <!--MODEL  -->
    <!-- Modal Overlay -->
<div id="checkout-modal" 
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <!-- Modal Content -->
    <div class="bg-white rounded-lg w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button id="close-checkout" 
            class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">&times;</button>

        <h2 class="text-lg font-semibold mb-4">Checkout</h2>

        <!-- Pickup Info -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Pickup Info</label>
            <input type="text" 
                id="pickup-info"
                class="mt-1 block w-full border rounded-md p-2" 
                value="Sacramento, 1054 El Camino Ave, Sacramento, CA 95815" readonly>
        </div>

        <!-- Pickup Time -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Pickup Time (Hourly)</label>
            <select class="mt-1 block w-full border rounded-md p-2"  id="pickup-time">
                <option>8:00 AM</option>
                <option>9:00 AM</option>
                <option>10:00 AM</option>
            </select>
        </div>

        <!-- Order Summary -->
        <div class="mb-4">
            <h3 class="font-semibold">Order Summary</h3>
            <p id="order-number" class="text-gray-600">FD28884 GTY x1</p>
        </div>

        <!-- Place Order -->
        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
            Place Order
        </button>
    </div>
</div>
<!-- ✅ Confirmation Modal -->
<div id="order-success-modal" 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center relative">

        <!-- Close Button (optional) -->
        <button id="close-success-modal" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>

        <!-- ✅ Success Icon -->
        <div class="flex justify-center mb-4">
            <div class="h-14 w-14 flex items-center justify-center rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Thanks! Your will-call order is in.</h2>

        <!-- Order Number -->
        <p class="text-blue-600 font-semibold mb-1" id="success-order-number">
            Order #TIG-2025-000812
        </p>

        <!-- Pickup Info -->
        <p class="text-gray-600 text-sm mb-6" id="success-pickup-info">
            Pickup at Sacramento, 1054 El Camino Ave, CA 95815 at 10:00 AM.
        </p>

        <!-- Continue Button -->
        <button id="continue-shopping-btn" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
            Continue Shopping
        </button>
    </div>
</div>

 <script>
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
    let count = 0;

    // -------------------------------
    //  SIDEBAR TOGGLE
    // -------------------------------
    function openSidebar() {
        sidebar.classList.remove('translate-x-full');
    }
    function closeSidebar() {
        sidebar.classList.add('translate-x-full');
    }
    openButton.addEventListener('click', openSidebar);
    closeButton.addEventListener('click', closeSidebar);

    // -------------------------------
    //  FETCH CART PRODUCTS
    // -------------------------------
    async function getCartProducts() {
        try {
            const response = await fetch('/cart', {
                method: 'GET',
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error("Failed to fetch cart products");

            const data = await response.json();
            if (data.success) renderCartItems(data.items);
        } catch (error) {
            console.error("Error loading cart:", error);
        }
    }

    // -------------------------------
    //  RENDER CART ITEMS
    // -------------------------------
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

    // -------------------------------
    //  ADD TO CART
    // -------------------------------
    async function sendCartRequest(form) {
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: form.method,
                headers: {
                    "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                    "Accept": "application/json",
                },
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                console.log("Server Response:", data);
            } else {
                console.error("Error adding to cart:", response.statusText);
            }
        } catch (error) {
            console.error("Request failed:", error);
        }
    }

    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const productId = this.querySelector('[name="product_id"]').value;
            const name = this.querySelector('[name="name"]').value;
            const description = this.querySelector('[name="description"]').value;
            const price = parseFloat(this.querySelector('[name="price"]').value);

            let existingItem = cartItemsContainer.querySelector(`[data-product-id="${productId}"]`);
            if (existingItem) {
                const qtySpan = existingItem.querySelector('.item-qty');
                const qty = parseInt(qtySpan.textContent) + 1;
                qtySpan.textContent = qty;
                existingItem.querySelector('.item-price').textContent = `$${(price * qty).toFixed(2)}`;
            } else {
                const item = document.createElement('div');
                item.classList.add('border-b', 'pb-3');
                item.setAttribute("data-product-id", productId);
                item.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-gray-900">${name}</h3>
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
                    <p class="text-sm text-gray-500 mt-1">${description}</p>
                    <p class="text-sm text-gray-900 font-bold item-price mt-1">$${price}</p>
                `;
                cartItemsContainer.appendChild(item);
            }

            count++;
            updateCartHeader();
            openSidebar();
            setTimeout(closeSidebar, 1000);
            sendCartRequest(this);
        });
    });

    // -------------------------------
    //  HANDLE +, -, DELETE
    // -------------------------------
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

        // Recalculate total count
        count = Array.from(cartItemsContainer.querySelectorAll('.item-qty'))
                     .reduce((sum, el) => sum + parseInt(el.textContent), 0);
        updateCartHeader();

        // Optional backend update
        try {
            await fetch('/cart/update-cart', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    items: Array.from(cartItemsContainer.querySelectorAll('[data-product-id]')).map(el => ({
                        product_id: el.getAttribute('data-product-id'),
                        quantity: parseInt(el.querySelector('.item-qty').textContent)
                    }))
                })
            });
        } catch (error) {
            console.error('Failed to update cart:', error);
        }
    });

    // -------------------------------
    //  CHECKOUT + PLACE ORDER
    // -------------------------------
    checkoutBtn.addEventListener("click", async () => {
        try {
            const items = Array.from(document.querySelectorAll('#cart-sidebar [data-product-id]')).map(el => ({
                product_id: el.getAttribute("data-product-id"),
                quantity: parseInt(el.querySelector(".item-qty").textContent)
            }));

            await updateCartBeforeCheckout(items);
            checkoutModal.classList.remove("hidden");
            checkoutModal.classList.add("flex");
        } catch (err) {
            console.error(err);
            alert("Failed to generate Order ID. Try again.");
        }
    });

    placeOrderBtn.addEventListener("click", async () => {
        try {
            const items = Array.from(document.querySelectorAll('#cart-sidebar [data-product-id]')).map(el => ({
                product_id: el.getAttribute("data-product-id"),
                quantity: parseInt(el.querySelector(".item-qty").textContent)
            }));

            if (items.length === 0) return alert("Your cart is empty.");

            const pickupInfo = document.getElementById('pickup-info').value;
            const pickupTime = document.getElementById('pickup-time').value;
            const orderNumber = document.getElementById('order-number').textContent;

            const result = await placeOrder(items, pickupInfo, pickupTime, orderNumber);
            if (result.success) {
                document.getElementById('success-order-number').textContent = `Order #${orderNumber}`;
                document.getElementById('success-pickup-info').textContent = `Pickup at ${pickupInfo} at ${pickupTime}.`;
                document.getElementById('order-success-modal').classList.remove('hidden');
                checkoutModal.classList.add('hidden');

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

    // -------------------------------
    //  INITIAL CART LOAD
    // -------------------------------
    await getCartProducts();
});
</script>

</x-app-layout>