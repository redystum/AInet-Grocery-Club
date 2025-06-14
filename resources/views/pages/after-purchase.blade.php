@extends('pages.layouts.public')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-neutral-50 dark:bg-neutral-900 p-4">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-8 max-w-md w-full text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <i class="fas fa-thumbs-up text-3xl text-green-600 dark:text-green-400"></i>
            </div>

            <h1 class="text-2xl font-semibold text-neutral-800 dark:text-neutral-100 mb-3">
                Thank You for Your Order!
            </h1>

            <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                Your order has been received and is being processed. We’ll send you a confirmation email once your order has arrived.
            </p>

            <div class="pt-4">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg inline-block transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
