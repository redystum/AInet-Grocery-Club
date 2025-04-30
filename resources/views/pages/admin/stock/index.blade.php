@extends('pages.layouts.admin')

@section('title', 'Stock Management')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Stock Management</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage your product inventory and stock levels</p>
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Product
                </button>
                <button class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                        Search Products
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-neutral-400"></i>
                        </div>
                        <input type="text" id="search" placeholder="Search product" value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    </div>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category</label>
                    <select id="category" autocomplete="off"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="stock-status"
                           class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Stock Status</label>
                    <select id="stock-status" autocomplete="off"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        <option value="">All</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div>
                    <label for="order-by" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Order By</label>
                    <select id="order-by" autocomplete="off"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        <option value="stock_low_high" {{ request('order_by') == 'stock_low_high' ? 'selected' : '' }}>Stock Low-High</option>
                        <option value="stock_high_low" {{ request('order_by') == 'stock_high_low' ? 'selected' : '' }}>Stock High-Low</option>
                        <option value="name_asc" {{ request('order_by') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('order_by') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="category_asc" {{ request('order_by') == 'category_asc' ? 'selected' : '' }}>Category A-Z</option>
                        <option value="category_desc" {{ request('order_by') == 'category_desc' ? 'selected' : '' }}>Category Z-A</option>
                        <option value="price_high_low" {{ request('order_by') == 'price_high_low' ? 'selected' : '' }}>Price High-Low</option>
                        <option value="price_low_high" {{ request('order_by') == 'price_low_high' ? 'selected' : '' }}>Price Low-High</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Product
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Stock
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach($products as $product)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                            <!-- Product Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-md object-cover"
                                             src="{{ asset('storage/products/' . $product->photo) }}"
                                             alt="{{ $product->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $product->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">{{ $product->category->name }}</div>
                            </td>

                            <!-- Price Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                    @if($product->discount > 0)
                                        <span class="text-red-600 dark:text-red-400">€{{ number_format($product->price - $product->discount, 2) }}</span>
                                        <span class="ml-1 text-neutral-500 dark:text-neutral-400 text-xs line-through">€{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span>€{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Stock Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">{{ $product->stock }}</div>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->stock > $product->stock_lower_limit)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                        In Stock
                                    </span>
                                @elseif($product->stock <= $product->stock_lower_limit && $product->stock > 0)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                        Out of Stock
                                    </span>
                                @endif
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                            class="inline-flex justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors"
                                            id="menu-button-{{ $product->id }}" aria-expanded="false"
                                            aria-haspopup="true">
                                        <i class="fas fa-ellipsis-v text-neutral-600 dark:text-neutral-300 m-auto"></i>
                                    </button>

                                    <div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                         id="menu-{{ $product->id }}" role="menu" aria-orientation="vertical"
                                         aria-labelledby="menu-button-{{ $product->id }}" tabindex="-1">
                                        <div class="py-1" role="none">
                                            <a href="#"
                                               class="flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                               role="menuitem">
                                                <i class="fas fa-eye mr-3 text-neutral-400"></i>
                                                View Details
                                            </a>
                                            <a href="#"
                                               class="flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                               role="menuitem">
                                                <i class="fas fa-edit mr-3 text-blue-400"></i>
                                                Edit Product
                                            </a>
                                            <button type="button"
                                                    class="w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                    role="menuitem">
                                                <i class="fas fa-boxes mr-3 text-green-400"></i>
                                                Restock
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Custom Pagination -->
            <div class="flex flex-col sm:flex-row p-4 justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                    of {{ $products->total() }} items
                </div>

                <div class="flex items-center gap-2">
                    <!-- First Page Link -->
                    <a href="#" data-page="1"
                       class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-double-left"></i>
                    </a>

                    <!-- Previous Page Link -->
                    <a href="#" data-page="{{ $products->currentPage() - 1 }}"
                       class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-left"></i>
                    </a>

                    <!-- Page Number Input -->
                    <div class="flex items-center gap-1">
                        <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                        <input type="number" min="1" max="{{ $products->lastPage() }}"
                               value="{{ $products->currentPage() }}" id="pageInput"
                               class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300
                                   dark:border-neutral-700 focus:ring-2 focus:ring-blue-500
                                   focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100
                                   placeholder-gray-400 dark:placeholder-neutral-500">
                        <span class="text-neutral-600 dark:text-neutral-400">of {{ $products->lastPage() }}</span>
                    </div>

                    <!-- Next Page Link -->
                    <a href="#" data-page="{{ $products->currentPage() + 1 }}"
                       class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$products->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-right"></i>
                    </a>

                    <!-- Last Page Link -->
                    <a href="#" data-page="{{ $products->lastPage() }}"
                       class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === $products->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // URL Management Functions
        function updateURLParams(params) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);

            // Update or add parameters
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    searchParams.set(key, params[key]);
                } else {
                    searchParams.delete(key);
                }
            });

            // Always reset to page 1 when changing filters
            if (params.search || params.category || params.stock_status || params.order_by) {
                searchParams.set('page', '1');
            }

            window.location.href = `${url.pathname}?${searchParams.toString()}`;
        }

        function getCurrentParams() {
            const url = new URL(window.location.href);
            return {
                search: url.searchParams.get('search') || '',
                category: url.searchParams.get('category') || '',
                stock_status: url.searchParams.get('stock_status') || '',
                order_by: url.searchParams.get('order_by') || 'stock_low_high',
                page: url.searchParams.get('page') || '1'
            };
        }

        // Filter and Sort functionality
        document.getElementById('search').addEventListener('change', function() {
            updateURLParams({
                ...getCurrentParams(),
                search: this.value
            });
        });

        document.getElementById('category').addEventListener('change', function() {
            updateURLParams({
                ...getCurrentParams(),
                category: this.value
            });
        });

        document.getElementById('stock-status').addEventListener('change', function() {
            updateURLParams({
                ...getCurrentParams(),
                stock_status: this.value
            });
        });

        document.getElementById('order-by').addEventListener('change', function() {
            updateURLParams({
                ...getCurrentParams(),
                order_by: this.value
            });
        });

        // Pagination functionality
        document.querySelectorAll('.paginationLink').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (!this.classList.contains('cursor-not-allowed')) {
                    updateURLParams({
                        ...getCurrentParams(),
                        page: this.dataset.page
                    });
                }
            });
        });

        document.getElementById('pageInput').addEventListener('change', function() {
            const page = this.value;
            const lastPage = parseInt("{{ $products->lastPage() }}");

            if (page < 1) {
                this.value = 1;
                return;
            }
            if (page > lastPage) {
                this.value = lastPage;
                return;
            }

            updateURLParams({
                ...getCurrentParams(),
                page: page
            });
        });

        // Dropdown menu functionality - Fixed version
        document.querySelectorAll('[id^="menu-button-"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const menuId = 'menu-' + this.id.split('-')[2];
                const menu = document.getElementById(menuId);

                // Close all other open menus
                document.querySelectorAll('[id^="menu-"]').forEach(m => {
                    if (m.id !== menuId) {
                        m.classList.add('hidden');
                    }
                });

                // Toggle current menu
                menu.classList.toggle('hidden');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('[id^="menu-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    </script>
@endsection