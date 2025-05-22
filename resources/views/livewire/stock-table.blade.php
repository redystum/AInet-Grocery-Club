<!-- Filters and Search -->
<div x-data="{ activeDropdown: null }"
     x-init="document.addEventListener('click', () => { activeDropdown = null })"
     @scroll.window="activeDropdown = null"
     @keydown.escape.window="activeDropdown = null">
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
                    <input type="text" id="search" placeholder="Search product" wire:model.live.debounce.300ms="search"
                           class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                </div>
            </div>

            <div>
                <label for="category"
                       class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Category</label>
                <select id="category" wire:model.live="category"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="stock-status"
                       class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Stock Status
                </label>
                <select id="stock-status" wire:model.live="stock_status"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="">All</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>

            <div>
                <label for="order-by" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Order By
                </label>
                <select id="order-by" wire:model.live="order_by"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="stock_low_high">Stock Low-High</option>
                    <option value="stock_high_low">Stock High-Low</option>
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                    <option value="category_asc">Category A-Z</option>
                    <option value="category_desc">Category Z-A</option>
                    <option value="price_high_low">Price High-Low</option>
                    <option value="price_low_high">Price Low-High</option>
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
                            <div class="relative">
                                <button @click="$event.stopPropagation(); activeDropdown === 'product-{{ $product->id }}' ? activeDropdown = null : activeDropdown = 'product-{{ $product->id }}'"
                                        class="inline-flex cursor-pointer justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors"
                                        :aria-expanded="activeDropdown === 'product-{{ $product->id }}'" aria-haspopup="true">
                                    <i class="fas fa-ellipsis-v text-neutral-600 dark:text-neutral-300 m-auto"></i>
                                </button>

                                <div x-show="activeDropdown === 'product-{{ $product->id }}'"
                                     x-cloak
                                     @click.outside="activeDropdown = null"
                                     @click.stop="$event.stopPropagation()"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                     role="menu" aria-orientation="vertical" tabindex="-1">
                                    <div role="none">
                                        <a href="#"
                                           class="flex items-center rounded-t-md px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
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
                                        <a href="{{ route('board.restock.product', $product->id) }}"
                                           class="w-full rounded-b-md text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                           role="menuitem">
                                            <i class="fas fa-boxes mr-3 text-green-400"></i>
                                            Restock
                                        </a>
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
                <button wire:click="gotoPage(1)"
                        @if($products->currentPage() === 1) disabled @endif
                        class="px-3 @unless($products->currentPage() === 1) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-left"></i>
                </button>

                <!-- Previous Page Link -->
                <button wire:click="previousPage"
                        @if($products->onFirstPage()) disabled @endif
                        class="px-3 @unless($products->onFirstPage()) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-left"></i>
                </button>

                <!-- Page Number Input -->
                <div class="flex items-center gap-1">
                    <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                    <input type="number" min="1" max="{{ $products->lastPage() }}"
                           value="{{ $products->currentPage() }}" id="pageInput"
                           wire:keydown.enter="gotoPage($event.target.value)"
                           wire:blur="gotoPage($event.target.value)"
                           class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">

                    <span class="text-neutral-600 dark:text-neutral-400">of {{ $products->lastPage() }}</span>
                </div>

                <!-- Next Page Link -->
                <button wire:click="nextPage"
                        @unless($products->hasMorePages()) disabled @endunless
                        class="px-3 @if($products->hasMorePages()) cursor-pointer @endif py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$products->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-right"></i>
                </button>

                <!-- Last Page Link -->
                <button wire:click="gotoPage({{ $products->lastPage() }})"
                        @if($products->currentPage() === $products->lastPage()) disabled @endif
                        class="px-3 py-1 @unless($products->currentPage() === $products->lastPage()) disabled @endunless rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === $products->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-right"></i>
                </button>

            </div>
        </div>

    </div>
</div>
