@extends('layout')

@section('title', ' - Order History')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">Order History</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <select
                        class="appearance-none bg-neutral-50 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-800 dark:text-neutral-200 py-2 pl-4 pr-8 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Orders</option>
                        <option>Last 30 Days</option>
                        <option>Last 6 Months</option>
                        <option>Last Year</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="relative">
                    <select
                        class="appearance-none bg-neutral-50 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-800 dark:text-neutral-200 py-2 pl-4 pr-8 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Order by Date</option>
                        <option>Last 30 Days</option>
                        <option>Last 6 Months</option>
                        <option>Last Year</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
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
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700"
                       id="ordersTable">
                @foreach($orders as $order)
                    <tr class="order-row transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-700/50 cursor-pointer"
                        data-order="order-{{ $order->id }}">
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
                            @if($order->status == 'completed')
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                    Delivered
                                </span>
                            @elseif($order->status == 'pending')
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                    Pending
                                </span>
                            @elseif($order->status == 'canceled')
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                    Canceled
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank">
                                <button
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-receipt"></i>
                                </button>
                            </a>
                            <button
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Details (Collapsible) -->
                    <tr class="order-details hidden" id="order-{{ $order->id }}">
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
                                                @if($item->discount > 0)
                                                    <div
                                                        class="absolute -top-2 -right-2 bg-red-400 border border-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                        -{{ number_format(($item->discount / $item->product->price) * 100) }}
                                                        %
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100 truncate">
                                                    {{ $item->product->name }}
                                                </h4>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                                    Quantity: <span class="font-medium">{{ $item->quantity }}</span>
                                                </p>
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
                                                    <div class="text-xs text-red-500 dark:text-red-400 mt-1">
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
                                    <div class=" pt-4">
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
                                            class="flex justify-between mb-1 mt-2 pt-2 border-t border-neutral-200 dark:border-neutral-600 mt-4">
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
                                                {{ $order->total }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button
                                        class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                                        <i class="fas fa-redo mr-2"></i> Reorder
                                    </button>
                                    <a href="{{ route('orders.receipt', $order->id) }}" target="_blank">
                                        <button
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-receipt mr-2"></i> Download Receipt
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span
                    class="font-medium">12</span> orders
            </div>
            <div class="flex space-x-2">
                <button
                    class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700 disabled:opacity-50"
                    disabled>
                    Previous
                </button>
                <button
                    class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-700">
                    Next
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all order rows and details
            const orderRows = document.querySelectorAll('.order-row');
            const orderDetails = document.querySelectorAll('.order-details');

            // Add click event to each order row
            orderRows.forEach(row => {
                row.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order');
                    const detailsRow = document.getElementById(orderId);

                    // Toggle the clicked order details
                    detailsRow.classList.toggle('hidden');

                    // Add/remove active class to the parent row for styling
                    this.classList.toggle('bg-neutral-50', !detailsRow.classList.contains('hidden'));
                    this.classList.toggle('dark:bg-neutral-700/50', !detailsRow.classList.contains('hidden'));

                    // Close other open details
                    orderDetails.forEach(detail => {
                        if (detail.id !== orderId && !detail.classList.contains('hidden')) {
                            detail.classList.add('hidden');
                            // Remove active class from other rows
                            const otherRow = document.querySelector(`.order-row[data-order="${detail.id}"]`);
                            if (otherRow) {
                                otherRow.classList.remove('bg-neutral-100', 'dark:bg-neutral-700');
                            }
                        }
                    });
                });
            });

            // Prevent event propagation when clicking on action buttons
            const actionButtons = document.querySelectorAll('.order-row button, .order-row a');
            actionButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
        });
    </script>

@endsection
