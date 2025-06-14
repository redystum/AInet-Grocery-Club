@extends('pages.layouts.public')

@section('title', ' - Shopping Cart')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100 mb-2">Your Shopping Cart</h1>
            <p class="text-neutral-600 dark:text-neutral-400">
                Review and manage your items before checkout
            </p>
        </div>

        <!-- Livewire Cart Component -->
        <livewire:cart-table />
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 invisible crusor-pointer">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script>
        // Back to top button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <!-- Wishlist Section -->
    <div class="container mx-auto px-4 py-8 mb-8 max-w-7xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Your Wishlist</h2>
            <a href="{{ route('wishlist') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-2">
                <span>View All</span> <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @livewire('wishlist-preview')
    </div>
@endsection
