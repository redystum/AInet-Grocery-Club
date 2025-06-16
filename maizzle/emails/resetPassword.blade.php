---
page:
title: 'Password Reset Request'
---

<x-main>
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Password Reset Request</h1>
        <p class="opacity-90 mt-1">We received a request to reset your {{ $appName }} password</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">You requested to reset your password</p>
            </div>
        </div>

        <!-- Security Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        If you didn't request this, please ignore this email or contact support immediately.
                    </p>
                </div>
            </div>
        </div>

        <!-- Reset Details Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <span class="material-symbols-outlined text-blue-500 mr-2">vpn_key</span> Reset Request Information
            </h3>

            <div class="space-y-4">
                <!-- Request Time -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Request Time</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ now()->format('F j, Y \a\t g:i A T') }}</p>
                    </div>
                </div>

                <!-- Request Device -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        @if($deviceInfo['is_mobile'])
                            <span class="material-symbols-outlined">smartphone</span>
                        @else
                            <span class="material-symbols-outlined">laptop</span>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Request Device</p>
                        <div class="text-neutral-800 dark:text-neutral-200">
                            <p>{{ $deviceInfo['platform'] }} • {{ $deviceInfo['browser'] }}</p>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                {{ $deviceInfo['device'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Approximate Location</p>
                        <p class="text-neutral-800 dark:text-neutral-200">
                            {{ $loginLocation ?: 'Could not determine location' }}
                        </p>
                        @if($loginLocation)
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                (Based on IP: {{ $ipAddress }})
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Button -->
        <div class="text-center mb-6">
            <a href="{{ $url }}"
               class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-md">
                <span class="material-symbols-outlined mr-2">refresh</span> Reset Password
            </a>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-3">
                This link will expire in {{ $expirationTime }} hour{{ $expirationTime > 1 ? 's' : '' }}.
            </p>
        </div>

        <!-- Security Tips -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <span class="material-symbols-outlined text-yellow-500 mr-2">lightbulb</span> Creating a Strong Password
            </h4>
            <ul class="text-sm text-neutral-600 dark:text-neutral-400 space-y-2">
                <li class="flex items-start">
                    <span class="material-symbols-outlined text-green-500 mr-2 mt-1 text-xs">check_circle</span>
                    <span>Use at least 12 characters with a mix of letters, numbers and symbols</span>
                </li>
                <li class="flex items-start">
                    <span class="material-symbols-outlined text-green-500 mr-2 mt-1 text-xs">check_circle</span>
                    <span>Avoid personal information or common words</span>
                </li>
                <li class="flex items-start">
                    <span class="material-symbols-outlined text-green-500 mr-2 mt-1 text-xs">check_circle</span>
                    <span>Consider using a password manager to generate and store passwords</span>
                </li>
            </ul>
        </div>

        <!-- Support Info -->
        <div class="text-center text-neutral-500 dark:text-neutral-400 text-sm mt-6">
            <p>If you're having trouble with the button above, copy and paste this link into your browser:</p>
            <p class="break-all text-blue-600 dark:text-blue-400 mt-2">{{ $url }}</p>
        </div>
    </div>
</x-main>