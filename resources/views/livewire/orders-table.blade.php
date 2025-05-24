@use('App\Models\Order')
<div x-data="{
    activeDropdown: null,
    showConfirmModal: false,
    confirmOrderId: null
}"
     x-init="document.addEventListener('click', () => { activeDropdown = null })"
     @scroll.window="activeDropdown = null"
     @keydown.escape.window="activeDropdown = null">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Orders</h1>
            <p class="text-neutral-600 dark:text-neutral-400">Manage your pending and received orders</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 cursor-pointer py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Confirm Delivery Modal -->
    <div x-show="showConfirmModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-dvh pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showConfirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/65 transition-opacity"
                 aria-hidden="true"
                 @click="showConfirmModal = false"></div>

            <!-- Modal panel -->
            <div x-show="showConfirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-neutral-800 dark:text-neutral-100"
                                id="modal-title">
                                Confirm Order Delivery
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Are you sure you want to mark this order as delivered? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 dark:bg-neutral-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a x-bind:href="'{{ route('board.orders.confirm', ['order' => '__ID__']) }}'.replace('__ID__', confirmOrderId)"
                       class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Delivery
                    </a>
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-neutral-200 dark:border-neutral-700 mb-6">
        <button wire:click="$set('tab', 'pending')"
                class="cursor-pointer px-4 py-2 font-medium text-sm border-b-2 {{ $tab === 'pending' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-neutral-600 dark:text-neutral-400' }}">
            Pending Orders
        </button>
        <button wire:click="$set('tab', 'cancellation')"
                class="cursor-pointer px-4 py-2 font-medium text-sm border-b-2 {{ $tab === 'cancellation' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-neutral-600 dark:text-neutral-400' }}">
            Canceled Orders
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
                           placeholder="Search by product or user..."
                           class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                </div>
            </div>

            <div>
                <label for="orderBy" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Order
                    By</label>
                <select id="orderBy" wire:model.live="orderBy" wire:key="orderBy-{{ $tab }}"
                        class="w-full cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    @if($tab == 'cancellation')
                        <option value="requests">Requests On Top</option>
                    @endif
                    <option value="date_desc">Date Old-Recent</option>
                    <option value="date_asc">Date Recent-Old</option>
                    <option value="user_asc">User A-Z</option>
                    <option value="user_desc">User Z-A</option>
                    <option value="price_desc">Price High-Low</option>
                    <option value="price_asc">Price Low-High</option>
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
                        Order #
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Customer
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Items
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Total
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
                @forelse($orders as $order)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('board.orders.show', $order->id) }}?search={{ $search }}&tab={{ $tab }}&orderBy={{ $orderBy }}&dateRange={{ $dateRange }}" class="flex items-center">
                                <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div
                                            class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                        #{{ $order->id }}</div>
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">
                            <a href="{{-- route('board.users.show', $order->user_id) --}}"
                               class="cursor-pointer">
                                {{ $order->user->name ?? 'Unknown' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100">
                            {{ $order->items_count ?? $order->items->sum('quantity') }} item(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-800 dark:text-neutral-100">
                            €{{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status == Order::STATUS_COMPLETED)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check mr-1"></i> Delivered
                                </span>
                            @elseif($order->status == Order::STATUS_CANCELED)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                    <i class="fas fa-times mr-1"></i> Canceled
                                </span>
                            @else
                                @if($order->cancellationStatus)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-200">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Cancellation Requested
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($tab == "received" || ($tab == "cancellation" && $order->status == Order::STATUS_CANCELED))
                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
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
                                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                                               class="flex items-center rounded-t-md px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                               role="menuitem">
                                                <i class="fas fa-receipt mr-3 text-neutral-400"></i>
                                                Download Invoice
                                            </a>
                                            @if($order->status == Order::STATUS_PENDING)
                                                @if($order->cancellationStatus)
                                                    <a href="{{ route('board.orders.cancel', $order->id) }}"
                                                       class="w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                       role="menuitem">
                                                        <i class="fas fa-scroll mr-3 text-red-400"></i>
                                                        View cancellation request
                                                    </a>
                                                @endif
                                                <a href="{{ route('board.orders.cancel', $order->id) }}"
                                                   class="w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                   role="menuitem">
                                                    <i class="fas fa-times mr-3 text-red-400"></i>
                                                    Cancel Order
                                                </a>
                                                <button
                                                        @click="
                                                        confirmOrderId = '{{ $order->id }}';
                                                        showConfirmModal = true;
                                                        activeDropdown = null;
                                                   "
                                                        class="w-full rounded-b-md text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                        role="menuitem">
                                                    <i class="fas fa-check mr-3 text-green-400"></i>
                                                    Mark as Delivered
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-neutral-500 dark:text-neutral-400">
                            No orders found.
                            <div class="mt-2 text-sm">
                                Try adjusting your filters
                                @if($tab != 'all')
                                    , search or
                                    <span wire:click="$set('tab', 'all')"
                                          class="cursor-pointer underline">go to all orders tab</span>.
                                @else
                                    or search.
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="flex flex-col sm:flex-row p-4 justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }}
                of {{ $orders->total() }} items
            </div>

            <div class="flex items-center gap-2">
                <!-- First Page Link -->
                <button wire:click="gotoPage(1)"
                        @if($orders->currentPage() === 1) disabled @endif
                        class="px-3 @unless($orders->currentPage() === 1) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $orders->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-left"></i>
                </button>

                <!-- Previous Page Link -->
                <button wire:click="previousPage"
                        @if($orders->onFirstPage()) disabled @endif
                        class="px-3 @unless($orders->onFirstPage()) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $orders->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-left"></i>
                </button>

                <!-- Page Number Input -->
                <div class="flex items-center gap-1">
                    <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                    <input type="number" min="1" max="{{ $orders->lastPage() }}"
                           value="{{ $orders->currentPage() }}" id="pageInput"
                           wire:keydown.enter="gotoPage($event.target.value)"
                           wire:blur="gotoPage($event.target.value)"
                           class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">

                    <span class="text-neutral-600 dark:text-neutral-400">of {{ $orders->lastPage() }}</span>
                </div>

                <!-- Next Page Link -->
                <button wire:click="nextPage"
                        @unless($orders->hasMorePages()) disabled @endunless
                        class="px-3 @if($orders->hasMorePages()) cursor-pointer @endif py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$orders->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-right"></i>
                </button>

                <!-- Last Page Link -->
                <button wire:click="gotoPage({{ $orders->lastPage() }})"
                        @if($orders->currentPage() === $orders->lastPage()) disabled @endif
                        class="px-3 py-1 @unless($orders->currentPage() === $orders->lastPage()) disabled @endunless rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $orders->currentPage() === $orders->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>