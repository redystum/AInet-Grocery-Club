@use('App\Models\SupplyOrder')
<div x-data="{ activeDropdown: null }"
     x-init="document.addEventListener('click', () => { activeDropdown = null })"
     @scroll.window="activeDropdown = null"
     @keydown.escape.window="activeDropdown = null">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Supply Orders</h1>
            <p class="text-neutral-600 dark:text-neutral-400">Manage your pending and received supply orders</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 cursor-pointer py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-neutral-200 dark:border-neutral-700 mb-6">
        <button wire:click="$set('tab', 'pending')"
                class="cursor-pointer px-4 py-2 font-medium text-sm border-b-2 {{ $tab === 'pending' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-neutral-600 dark:text-neutral-400' }}">
            Pending Orders
        </button>
        <button wire:click="$set('tab', 'received')"
                class="cursor-pointer px-4 py-2 font-medium text-sm border-b-2 {{ $tab === 'received' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-neutral-600 dark:text-neutral-400' }}">
            Completed Orders
        </button>
        <button wire:click="$set('tab', 'all')"
                class="cursor-pointer px-4 py-2 font-medium text-sm border-b-2 {{ $tab === 'all' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-neutral-600 dark:text-neutral-400' }}">
            All Orders
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-2">
                <label for="search"
                       class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-neutral-400"></i>
                    </div>
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                           placeholder="Search orders..."
                           class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                </div>
            </div>

            <div>
                <label for="orderBy" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Order
                    By</label>
                <select id="orderBy" wire:model.live="orderBy"
                        class="w-full cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    <option value="date_desc">Date Recent-Old</option>
                    <option value="date_asc">Date Old-Recent</option>
                    <option value="quantity_low_high">Quantity Low-High</option>
                    <option value="quantity_high_low">Quantity High-Low</option>
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                </select>
            </div>

            <div>
                <label for="dateRange" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Date
                    Range</label>
                <select id="dateRange" wire:model.live="dateRange"
                        class="w-full cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Product
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Date Ordered
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Date Received
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Quantity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($supplyOrders as $order)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $order->product->getImage() }}" class="h-10 w-10 rounded-md object-cover"
                                     alt="{{ $order->product->name }}">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $order->product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">{{ $order->created_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">{{ $order->delivered_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">{{ $order->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status == SupplyOrder::STATUS_COMPLETED)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200"><i
                                            class="fas fa-check mr-1"></i> Delivered</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200"><i
                                            class="fas fa-clock mr-1"></i> Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($tab == "received")
                                <a href="#"
                                   class="text-right text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    <i class="fas fa-receipt mr-3"></i>
                                    Invoice
                                </a>
                            @else
                                <div class="relative">
                                    <button @click="$event.stopPropagation(); activeDropdown === 'order-{{ $order->id }}' ? activeDropdown = null : activeDropdown = 'order-{{ $order->id }}'"
                                            class="inline-flex cursor-pointer justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors"
                                            :aria-expanded="activeDropdown === 'order-{{ $order->id }}'"
                                            aria-haspopup="true">
                                        <i class="fas fa-ellipsis-v text-neutral-600 dark:text-neutral-300 m-auto"></i>
                                    </button>

                                    <div x-show="activeDropdown === 'order-{{ $order->id }}'"
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
                                                <i class="fas fa-receipt mr-3 text-neutral-400"></i>
                                                Download Invoice
                                            </a>
                                            @if($order->status != SupplyOrder::STATUS_COMPLETED && $order->created_at->diffInHours(now(), false) < 24)
                                                <a href="{{ route('board.supply.edit', $order->id) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                   role="menuitem">
                                                    <i class="fas fa-edit mr-3 text-blue-400"></i>
                                                    Edit Order
                                                </a>
                                            @else
                                                <a href="#"
                                                   class="flex items-center px-4 py-2 text-sm text-neutral-500 cursor-not-allowed"
                                                   role="menuitem">
                                                    <i class="fas fa-edit mr-3 text-neutral-500"></i>
                                                    Edit Order
                                                </a>
                                            @endif
                                            @if($order->status != SupplyOrder::STATUS_COMPLETED)
                                                <a href="{{ route('board.supply.cancel', $order->id) }}"
                                                   class="w-full rounded-b-md text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                   role="menuitem">
                                                    <i class="fas fa-cancel mr-3 text-red-400"></i>
                                                    Cancel Order
                                                </a>
                                            @else
                                                <a href="#"
                                                   class="w-full cursor-not-allowed rounded-b-md text-left flex items-center px-4 py-2 text-sm text-neutral-500"
                                                   role="menuitem">
                                                    <i class="fas fa-cancel mr-3 text-neutral-500"></i>
                                                    Cancel Order
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-neutral-500 dark:text-neutral-400">No orders
                            found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="flex flex-col sm:flex-row p-4 justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                Showing {{ $supplyOrders->firstItem() }} to {{ $supplyOrders->lastItem() }}
                of {{ $supplyOrders->total() }} items
            </div>

            <div class="flex items-center gap-2">
                <!-- First Page Link -->
                <button wire:click="gotoPage(1)"
                        @if($supplyOrders->currentPage() === 1) disabled @endif
                        class="px-3 @unless($supplyOrders->currentPage() === 1) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $supplyOrders->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-left"></i>
                </button>

                <!-- Previous Page Link -->
                <button wire:click="previousPage"
                        @if($supplyOrders->onFirstPage()) disabled @endif
                        class="px-3 @unless($supplyOrders->onFirstPage()) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $supplyOrders->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-left"></i>
                </button>

                <!-- Page Number Input -->
                <div class="flex items-center gap-1">
                    <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                    <input type="number" min="1" max="{{ $supplyOrders->lastPage() }}"
                           value="{{ $supplyOrders->currentPage() }}" id="pageInput"
                           wire:keydown.enter="gotoPage($event.target.value)"
                           wire:blur="gotoPage($event.target.value)"
                           class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">

                    <span class="text-neutral-600 dark:text-neutral-400">of {{ $supplyOrders->lastPage() }}</span>
                </div>

                <!-- Next Page Link -->
                <button wire:click="nextPage"
                        @unless($supplyOrders->hasMorePages()) disabled @endunless
                        class="px-3 @if($supplyOrders->hasMorePages()) cursor-pointer @endif py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$supplyOrders->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-right"></i>
                </button>

                <!-- Last Page Link -->
                <button wire:click="gotoPage({{ $supplyOrders->lastPage() }})"
                        @if($supplyOrders->currentPage() === $supplyOrders->lastPage()) disabled @endif
                        class="px-3 py-1 @unless($supplyOrders->currentPage() === $supplyOrders->lastPage()) disabled @endunless rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $supplyOrders->currentPage() === $supplyOrders->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-right"></i>
                </button>

            </div>
        </div>

    </div>
</div>
