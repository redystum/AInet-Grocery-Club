@extends('emails.layout')

@section('title', 'Order Cancellation Notification')

@section('content')
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-red-600 to-orange-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Order Cancellation Notice</h1>
        <p class="opacity-90 mt-1">Your recent order has been canceled</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-red-500 to-orange-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover"
                     alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">We're sorry to inform you that your order has been
                    canceled</p>
            </div>
        </div>

        <!-- Cancellation Alert -->
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-red-500 dark:text-red-400">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-700 dark:text-red-300">
                        Order #{{ $orderId }} has been canceled
                    </h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                        {{ $cancellationReason }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <i class="fas fa-info-circle mr-2 text-red-500"></i> Cancellation Details
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

                <!-- Order Date -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Order Date</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $orderDate }}</p>
                    </div>
                </div>

                <!-- Cancellation Details -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Additional Information</p>
                        <p class="text-neutral-800 dark:text-neutral-200">
                            {{ $cancellationDetails }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6 border border-green-200 dark:border-green-800">
            <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">
                <i class="fas fa-dollar-sign mr-2 text-green-500"></i> Refund Information
            </h4>
            <p class="text-sm text-green-700 dark:text-green-300">
                A refund of {{ $refundAmount }} has been issued to your original payment method.
                Please allow 3-5 business days for the refund to process.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <a href="{{ $orderLink }}"
               class="px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-receipt mr-2"></i> View Order Details
            </a>
            <a href="{{ route('home') }}"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-store mr-2"></i> Visit Our Store
            </a>
        </div>

        <!-- Customer Support -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <i class="fas fa-headset mr-2 text-blue-500"></i> Need Help?
            </h4>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">
                If you have any questions about this cancellation or need assistance, our support team is here to help.
            </p>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                Contact Customer Support
            </a>
        </div>
    </div>
@endsection