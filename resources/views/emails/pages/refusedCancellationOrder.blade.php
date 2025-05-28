@extends('emails.layout')

@section('title', 'Cancellation Request Denied')

@section('content')
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-amber-600 to-yellow-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Cancellation Request Not Approved</h1>
        <p class="opacity-90 mt-1">Your request to cancel order #{{ $orderId }} was not approved</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-amber-500 to-yellow-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">We've reviewed your cancellation request</p>
            </div>
        </div>

        <!-- Status Alert -->
        <div class="bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-amber-500 dark:text-amber-400">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-700 dark:text-amber-300">
                        Your cancellation request was not approved
                    </h3>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                        Reason: {{ $cancellationReason }}
                    </p>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                        Details: {{ $cancellationDetails }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <i class="fas fa-info-circle mr-2 text-amber-500"></i> Order Status
            </h3>

            <div class="space-y-4">
                <!-- Order Number -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Order Number</p>
                        <p class="text-neutral-800 dark:text-neutral-200">#{{ $orderId }}</p>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Current Status</p>
                        <p class="text-neutral-800 dark:text-neutral-200">Pending</p>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">What Happens Next</p>
                        <p class="text-neutral-800 dark:text-neutral-200">
                            Your order is being prepared for shipping.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <a href="{{ $orderLink }}"
               class="px-4 py-3 bg-amber-600 hover:bg-amber-700 text-white text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-receipt mr-2"></i> View Order Details
            </a>
            <a href="#"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-envelope mr-2"></i> Contact Support
            </a>
        </div>

        <!-- Shipping Information -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <i class="fas fa-truck mr-2 text-green-500"></i> Shipping Updates
            </h4>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                You'll receive another email with tracking information once your order ships.
                Expected shipping date: {{ $expectedShipDate }}
            </p>
        </div>
    </div>
@endsection