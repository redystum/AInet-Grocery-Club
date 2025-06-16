---
page:
title: 'Activate Your Account'
---

<x-main>
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Activate Your Account</h1>
        <p class="opacity-90 mt-1">One more step to access {{ $appName }}</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover"
                     alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}!</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Let's activate your account</p>
            </div>
        </div>

        <!-- Activation Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                    <span class="material-symbols-outlined">email</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Please verify your email address to complete your registration.
                    </p>
                </div>
            </div>
        </div>

        <!-- Activation Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <span class="material-symbols-outlined text-blue-500 mr-2">vpn_key</span> Account Activation
            </h3>

            <div class="space-y-4">
                <!-- Activation Button -->
                <div class="text-center my-6">
                    <a href="{{ $url }}"
                       class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md transition-colors">
                        <i class="material-symbols-outlined mr-2">check_circle</i> Activate Account
                    </a>
                </div>

                <!-- Expiration Notice -->
                <div class="flex items-baseline">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">
                            This activation link expires in 24 hours
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alternative Instructions -->
        <div class="text-center mb-6">
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-2">
                Can't click the button? Copy this link to your browser:
            </p>
            <p class="text-xs break-all text-blue-600 dark:text-blue-400 bg-neutral-100 dark:bg-neutral-700 p-2 rounded">
                {{ $url }}
            </p>
        </div>

        <!-- Support Section -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <span class="material-symbols-outlined text-blue-500 mr-2">help</span> Need Help?
            </h4>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                If you didn't request this or need assistance, please
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">contact support</a>.
            </p>
        </div>
    </div>
</x-main>