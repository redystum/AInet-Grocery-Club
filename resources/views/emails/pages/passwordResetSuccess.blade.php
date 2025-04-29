@extends('emails.layout')

@section('title', 'Password Reset Confirmation')

@section('content')
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Password Successfully Updated</h1>
        <p class="opacity-90 mt-1">Your {{ $appName }} password has been updated</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Your password was changed successfully</p>
            </div>
        </div>

        <!-- Success Alert -->
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-green-500 dark:text-green-400">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-300">
                        Your password was updated on {{ now()->format('F j, Y \a\t g:i A T') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Reset Details Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <i class="fas fa-shield-alt mr-2 text-blue-500"></i> Security Information
            </h3>

            <div class="space-y-4">
                <!-- Change Time -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <i class="far fa-clock"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Changed At</p>
                        <p class="text-neutral-800 dark:text-neutral-200">{{ now()->format('F j, Y \a\t g:i A T') }}</p>
                    </div>
                </div>

                <!-- Change Device -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        @if($deviceInfo['is_mobile'])
                            <i class="fas fa-mobile-alt"></i>
                        @else
                            <i class="fas fa-laptop"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Device Used</p>
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
                <i class="fas fa-user-shield mr-2"></i> Review Security
            </a>
            <a href="#"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <i class="fas fa-sign-in-alt mr-2"></i> Login Now
            </a>
        </div>

        <!-- Security Reminder -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-4 border border-neutral-200 dark:border-neutral-700">
            <h4 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">
                <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i> Important Reminder
            </h4>
            <ul class="text-sm text-neutral-600 dark:text-neutral-400 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 text-xs"></i>
                    <span>Never share your password with anyone</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 text-xs"></i>
                    <span>Use different passwords for different services</span>
                </li>
            </ul>
        </div>

        <!-- Support Info -->
        <div class="text-center text-neutral-500 dark:text-neutral-400 text-sm mt-6">
            <p>If you didn't make this change, please <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">contact support</a> immediately.</p>
        </div>
    </div>
@endsection
