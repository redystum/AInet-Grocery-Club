@extends('pages.layouts.public')

@section('title', ' - Gourmet Market')

@section('content')
    <div class="bg-neutral-50 dark:bg-neutral-900">
        <!-- Hero Banner -->
        <div class="relative h-96 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-neutral-900/80 via-neutral-900/40 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" alt="Fresh Food Selection"
                 class="w-full h-full object-cover">
            <div class="container mx-auto px-4 absolute inset-0 z-20 flex items-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Premium Quality Foods Delivered to Your
                        Door</h1>
                    <p class="text-xl text-neutral-100 mb-6">Discover our curated selection of artisan products from
                        local producers</p>
                    <a href="{{ route('products.index') }}"
                       class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center">
                        Shop Now <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="container mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 mb-8">Shop by Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 sm:grid-cols-2">
                @foreach($topCategories as $category)
                    <a href="{{ route('products.category', $category->id) }}"
                       class="group relative rounded-xl overflow-hidden h-48 shadow-md">
                        <img src="{{ $category->getImage() }}" alt="{{ $category->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 to-transparent flex items-end p-4">
                            <h3 class="text-xl font-semibold text-white">{{ $category->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Top Sellers -->
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Top Sellers</h2>
                <a href="{{ route('products.index') }}"
                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topProducts as $product)
                    <x-product-card :product="$product"/>
                @endforeach
            </div>
        </div>

        <!-- Top Discounts -->
        <div class="container mx-auto px-4 py-12" id="featured">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Top Discounts</h2>
                <a href="{{ route('products.index') }}"
                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topDiscount as $product)
                    <x-product-card :product="$product"/>
                @endforeach
            </div>
        </div>

        <!-- Premium Selection Banner -->
        <div class="container mx-auto px-4 py-8">
            <div class="bg-neutral-800 rounded-xl overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-8 md:p-12 flex flex-col justify-center">
                        <h2 class="text-3xl font-bold text-white mb-4">Our Premium Selection</h2>
                        <p class="text-neutral-300 mb-6">Carefully curated by our experts to bring you the finest
                            quality ingredients from trusted producers.</p>
                        <a href="{{ route('products.index') }}"
                           class="px-6 py-3 bg-white text-neutral-800 hover:bg-neutral-100 font-medium rounded-lg transition-colors inline-flex items-center self-start">
                            Discover More <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    <div class="h-64 md:h-auto">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947" alt="Premium Selection"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="container mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 mb-8 text-center">Why Choose Gourmet
                Market</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-2">Premium Quality</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">We source only the finest ingredients from trusted
                        producers.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-2">Fast Delivery</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">Next-day delivery on all orders placed before
                        6pm.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-2">Satisfaction
                        Guaranteed</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">Not happy? We'll make it right with our money-back
                        guarantee.</p>
                </div>
            </div>
        </div>
    </div>
@endsection