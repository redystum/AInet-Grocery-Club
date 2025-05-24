@extends('pages.layouts.admin')

@section('title', 'Order Details')

@section('content')
    @use('App\Models\Order')
    @use('Carbon\Carbon')

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Order Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">Order #{{ $order->id }}</h1>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('F j, Y \a\t H:i') }}
                    </span>
                    @if($order->status == Order::STATUS_COMPLETED)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                            <i class="fas fa-check mr-1"></i> Delivered
                        </span>
                    @elseif($order->status == Order::STATUS_CANCELED)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                            <i class="fas fa-times mr-1"></i> Canceled
                        </span>
                    @else
                        @if($order->cancellationStatus && $order->cancellationStatus == Order::CANCEL_STATUS_PENDING)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-200">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Cancellation Requested
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @endif
                    @endif
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('board.orders.index', request()->query()) }}" class="mr-3">
                    <button class="cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                    </button>
                </a>
                <a href="{{ route('board.orders.receipt', $order->id) }}" target="_blank">
                    <button class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-receipt mr-2"></i> View Receipt
                    </button>
                </a>
            </div>
        </div>

        <!-- Order Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Delivery Information -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg mr-3">
                        <i class="fas fa-truck text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Delivery Information</h2>
                </div>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Delivery Address</p>
                        <p class="text-neutral-800 dark:text-neutral-100">{{ $order->delivery_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Status</p>
                        <p class="text-neutral-800 dark:text-neutral-100">
                            @if($order->status == 'completed')
                                Delivered on
                                {{ $order->delivered_at ? Carbon::parse($order->delivered_at)->format('F j, Y') : 'N/A' }}
                            @else
                                Expected by
                                {{ $order->expected_delivery_date ? Carbon::parse($order->expected_delivery_date)->format('F j, Y') : 'N/A' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg mr-3">
                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Customer Information</h2>
                </div>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Customer</p>
                        <p class="text-neutral-800 dark:text-neutral-100">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Email</p>
                        <p class="text-neutral-800 dark:text-neutral-100">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">NIF</p>
                        <p class="text-neutral-800 dark:text-neutral-100">{{ $order->nif ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg mr-3">
                        <i class="fas fa-credit-card text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Order Summary</h2>
                </div>
                <div class="space-y-2">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Subtotal</span>
                            <span class="text-neutral-800 dark:text-neutral-100">€{{ number_format($order->total_items, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Discounts</span>
                            <span class="text-red-600 dark:text-red-400">-€{{ number_format($order->total_discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Shipping</span>
                            <span class="text-neutral-800 dark:text-neutral-100">€{{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="border-t border-neutral-200 dark:border-neutral-700 pt-3 mt-3">
                            <div class="flex justify-between font-medium">
                                <span class="text-neutral-800 dark:text-neutral-100">Total</span>
                                <span class="text-lg text-blue-600 dark:text-blue-400">€{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-6">Order Items
                ({{ $order->items->count() }})</h2>

            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach($order->items as $item)
                    <div class="py-4 flex flex-col sm:flex-row">
                        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                            <img src="{{ asset('storage/products/' . $item->product->photo) }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-20 h-20 object-cover rounded-lg border border-neutral-200 dark:border-neutral-600">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-100 mb-3">
                                {{ $item->product->name }}
                            </h3>
                            <div class="flex items-center">
                                <span class="text-sm text-neutral-600 dark:text-neutral-400 mr-4">
                                    Qty: {{ $item->quantity }}
                                </span>
                                @if($item->discount > 0)
                                    <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-2 py-1 rounded-full">
                                        {{ number_format(($item->discount / $item->product->price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 text-right">
                            <p class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">
                                €{{ number_format($item->subtotal, 2) }}
                            </p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                {{ $item->quantity }} ×
                                €{{ number_format($item->product->price - $item->discount, 2) }}
                            </p>
                            @if($item->discount > 0)
                                <p class="text-xs text-green-600 dark:text-green-400">
                                    Saved €{{ number_format($item->discount * $item->quantity, 2) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($order->status == Order::STATUS_PENDING)
            <!-- Admin Controls Section -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-8">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-6">Order Management</h2>

                <!-- Cancellation Request (if exists) -->
                @if($order->cancellationStatus && $order->cancellationStatus == Order::CANCEL_STATUS_PENDING)
                    <div id="cancellation"
                         class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 dark:border-orange-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-orange-500 dark:text-orange-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-orange-800 dark:text-orange-200 mb-2">Cancellation
                                    Request</h3>
                                <div class="text-sm text-orange-700 dark:text-orange-300">
                                    <p class="mb-2"><strong>Reason:</strong> {{ $order->cancel_reason }}</p>
                                    <p class="mb-2"><strong>Details:</strong> {{ $order->cancellationDetails ?? "N/A" }}</p>
                                    <p><strong>Requested
                                            at:</strong> {{ $order->cancellationTime ? Carbon::parse($order->cancellationTime)->format('F j, Y \a\t H:i') : 'N/A' }}
                                    </p>
                                </div>

                                <div class="mt-4 flex space-x-3">
                                    <form action="{{ route('board.orders.cancel.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-check-circle mr-2"></i> Approve Cancellation
                                        </button>
                                    </form>
                                    <form action="{{ route('board.orders.cancel.reject', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-times-circle mr-2"></i> Reject Cancellation
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @unless($can_be_delivered)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex items start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-500 dark:text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200 mb-2">Order Not
                                    Ready
                                    for Delivery</h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    This order cannot be marked as delivered yet. Please add more stock of this products
                                    to mark as delivered.
                                </p>
                                <ul class="mt-4 space-y-2">
                                    @foreach($missing_products as $product)
                                        <li>
                                            <a href="{{-- route('board.products.show', $product->id) --}}"
                                               class="flex items-center">
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                     class="inline-block w-8 h-8 rounded-full mr-2">
                                                {{ $product->name }} ({{ $product->missing_quantity }} missing)
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>

                @endunless

                <!-- Order Action Buttons -->
                <div class="w-full flex items-center justify-end space-x-4">
                    <a href="{{ route('board.orders.cancel.show', $order->id) }}"
                       class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-times-circle mr-2"></i> Cancel Order
                    </a>

                    @if($can_be_delivered)
                        <form action="{{ route('board.orders.confirm', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="cursor-pointer px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i> Mark as Delivered
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        @endif

        @if($order->status == Order::STATUS_CANCELED)
            <!-- Cancellation Details Section -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-8">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-6">Cancellation</h2>
                <div
                     class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 mb-8 rounded-r-lg">
                    <div class="flex items start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-500 dark:text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-200 mb-2">Order Cancellation</h3>
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
            </div>

        @endif

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // if #cancellation exists, scroll to it
            const cancellationSection = document.getElementById('cancellation');
            if (cancellationSection) {
                cancellationSection.scrollIntoView({behavior: 'smooth'});
            }
        });
    </script>
@endsection