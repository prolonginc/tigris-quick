<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="lg:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Auto Glass Parts</h1>
            </div>
            <div class="flex-auto" x-data="adminSearch()" x-init="init()">
                <div class="mt-4 relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        x-model="query"
                        @input.debounce.300ms="search()"
                        @keydown.escape="query = ''; clearSearch()"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 pl-11 pr-10 py-3 rounded-full"
                        placeholder="Search by part number, vehicle, or glass type..."
                        autocomplete="off"
                    >
                    <div x-show="loading" class="absolute right-4 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <button x-show="query.length > 0 && !loading" @click="query = ''; clearSearch()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Product Number</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SKU</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Available</th>
                                </tr>
                            </thead>
                            <tbody id="products-tbody" class="divide-y divide-gray-200 bg-white">
                                @foreach($products as $product)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                            @if($product->quantity)
                                                <span class="sm:hidden inline-block w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                                            @else
                                                <span class="sm:hidden inline-block w-2 h-2 rounded-full bg-pink-500 flex-shrink-0"></span>
                                            @endif
                                        </div>
                                        <div class="text-gray-500 text-xs">{{ $product->description }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-blue-600 font-semibold">{{ $product->sku ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->quantity }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($product->quantity)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">In Stock</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination-container" class="mt-4 mb-4 px-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function adminSearch() {
    return {
        query: '',
        loading: false,
        originalTbody: '',
        originalPagination: '',

        init() {
            this.originalTbody = document.getElementById('products-tbody').innerHTML;
            this.originalPagination = document.getElementById('pagination-container').innerHTML;
        },

        async search() {
            if (this.query.length < 2) {
                this.clearSearch();
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
                const results = await response.json();
                this.renderResults(results);
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                this.loading = false;
            }
        },

        renderResults(products) {
            const tbody = document.getElementById('products-tbody');
            if (products.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No products found.</td></tr>`;
            } else {
                tbody.innerHTML = products.map(p => `
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">${this.escapeHtml(p.name)}</span>
                                <span class="sm:hidden inline-block w-2 h-2 rounded-full flex-shrink-0 ${p.quantity > 0 ? 'bg-green-500' : 'bg-pink-500'}"></span>
                            </div>
                            <div class="text-gray-500 text-xs">${this.escapeHtml(p.description || '')}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-blue-600 font-semibold">${this.escapeHtml(p.sku || '—')}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${p.quantity}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            ${p.quantity > 0
                                ? '<span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">In Stock</span>'
                                : '<span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">Out of Stock</span>'
                            }
                        </td>
                    </tr>
                `).join('');
            }
            document.getElementById('pagination-container').innerHTML = '';
        },

        clearSearch() {
            document.getElementById('products-tbody').innerHTML = this.originalTbody;
            document.getElementById('pagination-container').innerHTML = this.originalPagination;
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };
}
</script>

</x-app-layout>
