---
page:
title: 'Order Delivered'
---

<x-main>
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="@{{ $logoUrl }}" alt="@{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Your Order Has Arrived!</h1>
        <p class="opacity-90 mt-1">Order #@{{ $orderId }} was successfully delivered</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 overflow-hidden shadow-md mr-4">
                <img src="@{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, @{{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Your package has been delivered. The receipt is attached to this email.</p>
            </div>
        </div>

        <!-- Delivery Confirmation -->
        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-green-500 dark:text-green-400">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-700 dark:text-green-300">
                        Delivery confirmed on @{{ $deliveryDate }}
                    </h3>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        Delivered to: @{{ $deliveryLocation }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <i class="fas fa-box-open mr-2 text-green-500"></i> Your Items
            </h3>

            <div class="space-y-4">
                @foreach($items as $item)
                    <div class="flex items-start border-b border-neutral-200 dark:border-neutral-700 pb-4 last:border-0 last:pb-0">
                        <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden bg-white dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600">
                            <img src="@{{ $item['image'] }}" alt="@{{ $item['name'] }}" class="w-full h-full object-contain">
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-neutral-800 dark:text-neutral-200 font-medium">@{{ $item['name'] }}</p>
                            <p class="text-neutral-600 dark:text-neutral-400 text-sm">Quantity: @{{ $item['quantity'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Receipt Notice -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-blue-500">
                    <i class="fas fa-receipt fa-lg"></i>
                </div>
                <div class="ml-3">
                    <h4 class="font-medium text-blue-800 dark:text-blue-200">
                        Receipt Attached
                    </h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        Find your detailed receipt attached to this email for your records.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <a href="@{{ $orderLink }}"
               class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-eye mr-2"></i> View Order Details
            </a>
            <a href="@{{ route('home') }}"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-store mr-2"></i> Shop Again
            </a>
        </div>

        <!-- Support Information -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <i class="fas fa-question-circle mr-2 text-blue-500"></i> Need Help?
            </h4>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">
                If you have any issues with your order, please contact our support team.
            </p>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                Contact Customer Support
            </a>
        </div>
    </div>
</x-main>