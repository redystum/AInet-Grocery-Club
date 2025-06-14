@extends('layout')

@section('title', ' - My Wishlist')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100 mb-2">My Wishlist</h1>
            <p class="text-neutral-600 dark:text-neutral-400">Products you've saved for later</p>
        </div>

        <!-- Wishlist Content - Livewire Component -->
        @livewire('wishlist-page')
    </div>
@endsection
