@extends('layout')

@section('title', ' - Unauthorized')

@section('content')

    <div class="h-full flex flex-col items-center justify-center bg-neutral-50 dark:bg-neutral-900 p-4">
        <div class="max-w-md w-full bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-8 text-center">

            <div class="mx-auto w-24 h-24 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-5xl"></i>
            </div>

            <h1 class="text-4xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">401</h1>
            <h2 class="text-2xl font-semibold text-neutral-700 dark:text-neutral-300 mb-4">Unauthorized Access</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                Oops! It seems you don't have permission to access this page.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i> Go to Homepage
                </a>
                <button onclick="window.history.back()"
                        class="px-6 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </button>
            </div>

            <div class="mt-8">
                <p class="text-neutral-600 dark:text-neutral-400 mb-3">Or try searching:</p>
                <div class="relative">
                    <input type="text"
                           class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400"
                           placeholder="Search...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 dark:text-neutral-500 text-sm"></i>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-neutral-600 dark:text-neutral-400 text-sm">
            <p>Need help? <a href="" class="text-blue-600 dark:text-blue-400 hover:underline">Contact our support team</a></p>
        </div>
    </div>

@endsection
