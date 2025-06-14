@extends('layout')

@section('title', ' - My Wishlist')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100 mb-2">My Wishlist</h1>
            <p class="text-neutral-600 dark:text-neutral-400">Products you've saved for later</p>
        </div>

        <!-- Wishlist Content -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" :in-wishlist="true" :wishlist-page="true" />
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-8 text-center">
                <i class="far fa-heart text-6xl text-neutral-400 mb-4"></i>
                <h2 class="text-2xl font-semibold text-neutral-800 dark:text-neutral-100 mb-2">Your wishlist is empty</h2>
                <p class="text-neutral-600 dark:text-neutral-400 mb-6">Save items you like to your wishlist and revisit them later</p>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg inline-block transition-colors">
                    <i class="cursor-pointer"></i> Browse Products
                </a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup wishlist buttons using global handler
            setupWishlistButtons();
        });
    </script>
@endsection
