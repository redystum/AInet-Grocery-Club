@extends('pages.layouts.admin')

@section('title', ' - Cancel Order')

@section('content')
    @use('App\Models\Order')

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">Cancel Order</h1>
                <p class="text-neutral-600 dark:text-neutral-400 mt-2">Request to cancel this order
                    #{{ $order->id }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('board.orders.index') }}"
                   class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                </a>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Order
                        #{{ $order->id }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Placed on {{ $order->created_at->format('F j, Y \a\t H:i') }}
                    </p>
                </div>
                <div>
                    @if($order->status == Order::STATUS_COMPLETED)
                        <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                            <i class="fas fa-check mr-1"></i> Delivered
                        </span>
                    @elseif($order->status == Order::STATUS_PENDING)
                        <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </span>
                    @elseif($order->status == Order::STATUS_CANCELED)
                        <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                            <i class="fas fa-times mr-1"></i> Canceled
                        </span>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg overflow-hidden mb-6">
                @foreach($order->products as $item)
                    <div class="flex items-center p-4 border-b border-neutral-200 dark:border-neutral-700 last:border-b-0">
                        <div class="w-16 h-16 rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <img src="{{ asset('storage/products/' . $item->product->photo) }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="ml-4 flex-1">
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
                        <div class="text-right">
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
            <div class="bg-neutral-100 dark:bg-neutral-700/50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Subtotal:</span>
                    <span class="text-sm text-neutral-800 dark:text-neutral-100 text-right">€{{ number_format($order->total_items, 2) }}</span>

                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Discounts:</span>
                    <span class="text-sm text-neutral-800 dark:text-neutral-100 text-right">-
                        €{{ number_format($order->total_discount, 2) }}</span>

                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Delivery Fee:</span>
                    <span class="text-sm text-neutral-800 dark:text-neutral-100 text-right">€{{ number_format($order->shipping_cost, 2) }}</span>

                    <span class="text-sm text-neutral-600 dark:text-neutral-300 mt-2 pt-2 border-t border-neutral-200 dark:border-neutral-600">Total:</span>
                    <span class="text-sm font-medium text-neutral-800 dark:text-neutral-100 mt-2 pt-2 border-t border-neutral-200 dark:border-neutral-600 text-right">
                        €{{ number_format($order->total, 2) }}
                    </span>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-neutral-800 dark:text-neutral-100 mb-2">Delivery Address</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $order->delivery_address }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-neutral-800 dark:text-neutral-100 mb-2">Nif</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $order->nif }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cancellation Form -->
        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Cancel Order</h2>


            <form action="{{ route('board.orders.cancel.store', $order->id) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="reason"
                           class="block text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                        Reason for cancellation <span class="text-red-500">*</span>
                    </label>
                    @error('reason') <span class="text-red-500">{{ $message }}</span> @enderror
                    <select id="reason" name="reason" required autocomplete="off"
                            class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg
                                focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700
                                text-neutral-800 dark:text-neutral-200
                                @error('reason') border-red-500 dark:border-red-700 @enderror">
                        <option value="0" selected disabled>Select a reason...</option>
                        <option value="1" {{ old('reason') == 1 ? 'selected' : '' }}>Excessive processing time
                        </option>
                        <option value="2" {{ old('reason') == 2 ? 'selected' : '' }}>Contacted by the User</option>
                        <option value="3" {{ old('reason') == 3 ? 'selected' : '' }}>
                            Product arrived damaged or defective
                        </option>
                        <option value="4" {{ old('reason') == 4 ? 'selected' : '' }}>Company bankruptcy</option>
                        <option value="5" {{ old('reason') == 5 ? 'selected' : '' }}>Other reason</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="details"
                           class="block text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                        Additional details
                    </label>
                    @error('details') <span class="text-red-500">{{ $message }}</span> @enderror
                    <textarea id="details" name="details" rows="4" maxlength="255"
                              class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700
                                  text-neutral-800 dark:text-neutral-200
                                  @error('details') border-red-500 dark:border-red-700 @enderror"
                              placeholder="Please provide any additional information about your cancellation...">{{ old('details') }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('board.orders.show', $order->id) }}"
                       class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-200 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Go Back
                    </a>
                    <button type="submit"
                            class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-times-circle mr-2"></i> Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection