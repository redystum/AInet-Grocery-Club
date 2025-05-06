@extends('emails.layout')

@section('title', 'New Login Notification')

@section('content')
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">New Login Detected</h1>
        <p class="opacity-90 mt-1">We noticed a recent login to your {{ $appName }} account</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">A new login was detected on your account</p>
            </div>
        </div>

        <!-- Security Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                    <i class="fas fa-shield-alt fa-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        If you don't recognize this activity, please secure your account immediately.
                    </p>
                </div>
            </div>
        </div>

        <!-- Login Details Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i> Login Details
            </h3>

            <div class="space-y-4">
                <!-- Date & Time -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Date & Time</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ $loginTime }}</p>
                    </div>
                </div>

                <!-- Device Information -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        @if($deviceInfo['is_mobile'])
                            <i class="fas fa-mobile-alt"></i>
                        @else
                            <i class="fas fa-laptop"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Device</p>
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
                        <i class="fas fa-map-marker-alt"></i>
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

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <a href="#"
               class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-lock mr-2"></i> Change Password
            </a>
            <a href="#"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-history mr-2"></i> View All Activity
            </a>
        </div>

        <!-- Additional Security Tips -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i> Security Tips
            </h4>
            <ul class="text-sm text-neutral-600 dark:text-neutral-400 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 text-xs"></i>
                    <span>Use a unique password for your {{ $appName }} account</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 text-xs"></i>
                    <span>Review your account's authorized devices regularly</span>
                </li>
            </ul>
        </div>
    </div>
@endsection
