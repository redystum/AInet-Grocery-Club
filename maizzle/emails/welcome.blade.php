---
page:
title: 'Welcome to {{ $appName }}'
---

<x-main>
    <!-- Header with Logo -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center relative">
        <div class="flex justify-center mb-4">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="h-10">
        </div>
        <h1 class="text-2xl font-bold">Welcome to {{ $appName }}!</h1>
        <p class="opacity-90 mt-1">We're excited to have you join our community</p>
    </div>

    <div class="p-6">
        <!-- User Greeting -->
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 overflow-hidden shadow-md mr-4">
                <img src="{{ asset('storage/users/' . $userPhoto) }}" class="w-full h-full object-cover" alt="User Photo">
            </div>
            <div>
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Hello, {{ $userName }}!</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Your account has been successfully created</p>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                    <span class="material-symbols-outlined">favorite</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Thank you for joining us! We can't wait for you to explore everything {{ $appName }} has to offer.
                    </p>
                </div>
            </div>
        </div>

        <!-- Get Started Card -->
        <div class="bg-neutral-50 dark:bg-neutral-700/30 rounded-lg p-5 mb-6">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                <span class="material-symbols-outlined text-blue-500 mr-2">rocket_launch</span> Get Started
            </h3>

            <div class="space-y-4">
                <!-- First Step -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <span class="material-symbols-outlined text-green-500">check_circle</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-neutral-800 dark:text-neutral-200 font-medium">Account created</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">You're all set up!</p>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-neutral-500 dark:text-neutral-400 mt-1">
                        <span class="material-symbols-outlined text-blue-500">arrow_forward</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-neutral-800 dark:text-neutral-200 font-medium">Start exploring</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Discover all our features</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <a href="{{ route("profile") }}"
               class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined mr-2">home</span> Go to your Profile
            </a>
            <a href="{{ route("home") }}"
               class="px-4 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-center rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined mr-2">play_arrow</span> Start Exploring
            </a>
        </div>
    </div>
</x-main>