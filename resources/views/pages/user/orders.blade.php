@extends('pages.layouts.public')

@section('title', ' - Order History')

@section('content')

    @use ("App\Models\Order")

    @if($orders->count() == 0)
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">No Orders Found</h2>
                <p class="text-neutral-600 dark:text-neutral-400">You have not placed any orders yet.</p>

                <hr class="my-4 border-neutral-200 dark:border-neutral-600">

                <a href="{{ route('profile') }}" class="mr-4">
                    <button
                            class="mt-4 px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Go Back
                    </button>
                </a>
                <a href="#">
                    <button
                            class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i> Start Shopping
                    </button>
                </a>
            </div>
        </div>
    @else

        <div class="container mx-auto px-4 py-8 max-w-6xl" x-data="{ 
            openOrderId: null,
            init() {
                // Check URL parameters for pre-selected order
                const urlParams = new URLSearchParams(window.location.search);
                const orderParam = urlParams.get('order');
                if (orderParam) {
                    this.openOrderId = 'order-' + orderParam;
                    // Scroll to the order after a small delay to ensure rendering is complete
                    setTimeout(() => {
                        const element = document.querySelector(`[data-order='order-${orderParam}']`);
                        if (element) element.scrollIntoView({behavior: 'smooth', block: 'center'});
                    }, 100);
                }
            },
            toggleOrder(orderId) {
                this.openOrderId = this.openOrderId === orderId ? null : orderId;
            },
            updateQueryParam(key, value) {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Reset to first page when changing filters
                if (key !== 'page') {
                    params.delete('page');
                }

                if (value) {
                    params.set(key, value);
                } else {
                    params.delete(key);
                }

                window.location.href = `${url.pathname}?${params.toString()}`;
            }
        }">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">Order History</h1>
                <div class="flex items-center space-x-4">
                    <!-- Items Per Page Selector -->
                    <div class="relative">
                        <select
                                @change="updateQueryParam('per_page', $event.target.value)" autocomplete="off"
                                class="appearance-none bg-neutral-50 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-800 dark:text-neutral-200 py-2 pl-4 pr-8 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>Show 20 per page
                            </option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>Show 50 per page
                            </option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>Show 100 per page
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Date Range Selector -->
                    <div class="relative">
                        <select
                                @change="updateQueryParam('date_range', $event.target.value)" autocomplete="off"
                                class="appearance-none bg-neutral-50 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-800 dark:text-neutral-200 py-2 pl-4 pr-8 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                            <option value="">All Orders</option>
                            <option value="30" {{ request('date_range') == '30' ? 'selected' : '' }}>Last 30 Days
                            </option>
                            <option value="180" {{ request('date_range') == '180' ? 'selected' : '' }}>Last 6 Months
                            </option>
                            <option value="365" {{ request('date_range') == '365' ? 'selected' : '' }}>Last Year
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Order By Selector -->
                    <div class="relative">
                        <select
                                @change="updateQueryParam('sort', $event.target.value)" autocomplete="off"
                                class="appearance-none bg-neutral-50 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-800 dark:text-neutral-200 py-2 pl-4 pr-8 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Order
                                by newest
                            </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Order by oldest
                            </option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Order by
                                price (low to high)
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Order by
                                price (high to low)
                            </option>
                            <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Order by status
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Export Button with current filters -->
                    <a href="{{ route('orders.export', request()->query()) }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i> Export
                    </a>
                </div>
            </div>

            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-100 dark:bg-neutral-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Order #
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Items
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach($orders as $order)
                        <tr @click="toggleOrder('order-{{ $order->id }}')" 
                            data-order="order-{{ $order->id }}"
                            :class="{'bg-neutral-50 dark:bg-neutral-700/50': openOrderId === 'order-{{ $order->id }}'}"
                            class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-700/50 cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                            #{{ $order->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                    {{ $order->items_count }} item{{ $order->items_count > 1 ? "s":"" }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                    €{{ $order->total }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == Order::STATUS_COMPLETED)
                                    <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                        <i class="fas fa-check mr-1"></i> Delivered
                                    </span>
                                @elseif($order->status == Order::STATUS_PENDING)
                                    @if($order->cancellationStatus == Order::CANCEL_STATUS_PENDING)
                                        <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                            <i class="fas fa-clock mr-1"></i> Cancellation Pending
                                        </span>
                                    @else
                                        <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                @elseif($order->status == Order::STATUS_CANCELED)
                                    <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                        <i class="fas fa-times mr-1"></i> Canceled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($order->pdf_receipt)
                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" @click.stop>
                                    <button
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 cursor-pointer">
                                        <i class="fas fa-receipt"></i> Receipt
                                    </button>
                                </a>
                                @else
                                    <span class="text-neutral-500 dark:text-neutral-400">
                                        No Receipt
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <!-- Details (Collapsible) -->
                        <tr id="order-{{ $order->id }}" x-show="openOrderId === 'order-{{ $order->id }}'" x-cloak>
                            <td colspan="6" class="bg-neutral-50 dark:bg-neutral-700/30 px-6 py-4">
                                <div class="border-t border-neutral-200 dark:border-neutral-600 pt-4">
                                    <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-100 mb-4">
                                        Order Details
                                    </h3>

                                    <!-- Order Items -->
                                    <div class="mb-6">
                                        @foreach($order->products as $item)
                                            <div
                                                    class="flex items-start gap-4 p-3 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                                                <div class="flex-shrink-0 relative">
                                                    <img src="{{ asset('storage/products/' . $item->product->photo) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="w-16 h-16 object-cover rounded-lg border border-neutral-200 dark:border-neutral-600">
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100 truncate">
                                                        {{ $item->product->name }}
                                                    </h4>
                                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                                        Quantity: <span class="font-medium">{{ $item->quantity }}</span>
                                                    </p>
                                                    @if($item->discount > 0)
                                                        <div
                                                                class="text-green-600 dark:text-green-400 text-xs mt-1">
                                                            Discount:
                                                            {{ number_format(($item->discount / $item->product->price) * 100) }}
                                                            %
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col items-end">
                                                    <div
                                                            class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                                                        €{{ number_format($item->subtotal, 2) }}
                                                    </div>
                                                    <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                                        {{ $item->quantity }} ×
                                                        €{{ number_format($item->product->price - $item->discount, 2) }}
                                                    </div>
                                                    @if($item->discount > 0)
                                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                            <span class="font-medium">Saved
                                                                €{{ number_format($item->discount * $item->quantity, 2) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="bg-neutral-100 dark:bg-neutral-700/50 rounded-lg p-4">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                                                Delivery Address
                                            </span>
                                            <span class="text-sm text-neutral-800 dark:text-neutral-100">
                                                {{ $order->delivery_address }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                                                Nif
                                            </span>
                                            <span class="text-sm text-neutral-800 dark:text-neutral-100">
                                                {{ $order->nif }}
                                            </span>
                                        </div>
                                        <div
                                                class="flex justify-between mb-1 pt-2 border-t border-neutral-200 dark:border-neutral-600 mt-4">
                                            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                                                Subtotal
                                            </span>
                                            <span class="text-sm text-neutral-800 dark:text-neutral-100">
                                                {{ $order->total_items }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                                                Discounts
                                            </span>
                                            <span class="text-sm text-neutral-800 dark:text-neutral-100">
                                                - {{ number_format($order->total_discount, 2) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                                                Delivery Fee
                                            </span>
                                            <span class="text-sm text-neutral-800 dark:text-neutral-100">
                                                {{ $order->shipping_cost }}
                                            </span>
                                        </div>
                                        <div
                                                class="flex justify-between font-medium mt-2 pt-2 border-t border-neutral-200 dark:border-neutral-600">
                                            <span class="text-neutral-800 dark:text-neutral-100">
                                                Total
                                            </span>
                                            <span class="text-neutral-800 dark:text-neutral-100">
                                                € {{ $order->total }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <a href="#"
                                           class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors cursor-pointer">
                                            <i class="fas fa-redo mr-2"></i> Reorder
                                        </a>
                                        @if($order->status == Order::STATUS_PENDING && $order->cancellationStatus == null)
                                            <a href="{{ route('orders.cancel', $order->id) }}" @click.stop
                                               class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-red-500 dark:text-red-400 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors cursor-pointer">
                                                <i class="fas fa-cancel mr-2"></i> Cancel
                                            </a>
                                        @endif
                                        <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" @click.stop
                                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors cursor-pointer">
                                            <i class="fas fa-receipt mr-2"></i> Download Receipt
                                        </a>
                                    </div>

                                    @if($order->status == Order::STATUS_CANCELED)
                                        <div
                                                class="mt-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 mb-8 rounded-r-lg">
                                            <div class="flex items start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-times-circle text-red-500 dark:text-red-400"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-lg font-medium text-red-800 dark:text-red-200 mb-2">
                                                        Order Cancellation</h3>
                                                    <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                                                        This order has been canceled.
                                                    </p>
                                                    <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                                                        <strong>Reason:</strong> {{ $order->cancel_reason }}
                                                    </p>
                                                    <p class="text-sm text-red-700 dark:text-red-300">
                                                        <strong>Details:</strong> {{ $order->cancellationDetails }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->cancellationStatus == Order::CANCEL_STATUS_REFUSED)
                                        <div
                                                class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 p-4 mb-8 rounded-r-lg">
                                            <div class="flex items start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-yellow-500 dark:text-yellow-400"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                                                        Cancellation Refused</h3>
                                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-2">
                                                        Your cancellation request for this order has been refused.
                                                    </p>
                                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-2">
                                                        <strong>Your reason:</strong> {{ $order->cancel_reason }}
                                                    </p>
                                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                        <strong>Your
                                                            details:</strong> {{ $order->cancellationDetails ?? "N/A" }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Showing
                    <span class="font-medium">{{ $orders->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $orders->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $orders->total() }}</span>
                    orders
                </div>

                <div class="flex space-x-2">
                    @if ($orders->onFirstPage())
                        <span class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 opacity-50 cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}"
                           class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                            Previous
                        </a>
                    @endif

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}"
                           class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                            Next
                        </a>
                    @else
                        <span class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 opacity-50 cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

@endsection
