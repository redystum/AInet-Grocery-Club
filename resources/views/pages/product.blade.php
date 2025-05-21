@extends('pages.layouts.public')

@section('title', ' - Product Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Product Header with Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}"
               class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>
        </div>

        <!-- Main Product Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
                <!-- Main Image -->
                <div class="relative overflow-hidden">
                    <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}"
                         id="mainImage"
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">

                    <!-- Discount Badge -->
                    @if($product->discount)
                        <div
                            class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                            - {{ number_format(($product->discount / $product->price) * 100) }}%
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                <div class="flex flex-wrap justify-center gap-2 p-3 items-center">
                    @foreach($images as $index => $image)
                        <div class="{{ $index === 0 ? 'border-2 border-blue-500' : 'border border-neutral-200 dark:border-neutral-700 hover:border-blue-500' }} rounded-lg overflow-hidden transition-colors w-24 h-24">
                            <img src="{{ asset('storage/products/' . $image) }}"
                                 alt="Thumbnail {{ $index + 1 }}"
                                 class="w-full h-full object-cover cursor-pointer thumbnailImage"
                                 onclick="changeMainImage(this);">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Details -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <!-- Product Title and Category -->
                <div class="items-center">
                    <a href="#"
                       class="text-blue-600 dark:text-blue-400 text-sm font-medium">{{ $product->category->name }}</a>
                    <h1 class="text-2xl md:text-3xl font-bold text-neutral-800 dark:text-neutral-100 mt-2">{{ $product->name }}</h1>
                </div>

                <div class="mt-2 mb-6">
                    @if($product->stock > 0)
                        <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-2"></span>
                        <span class="text-green-600 dark:text-green-400 text-sm font-medium">
                            In Stock ({{ $product->stock }})
                        </span>
                    @else
                        <span class="w-2 h-2 inline-block bg-red-500 rounded-full mr-2"></span>
                        <span class="text-red-600 dark:text-red-400 text-sm font-medium">Out of Stock</span>
                    @endif
                </div>

                <!-- Pricing -->
                <div class="mb-6">
                    <span id="originalPrice"
                          class="text-4xl font-bold text-blue-600 dark:text-blue-400">€{{ number_format($product->price, 2) }}</span>

                    @if($product->discount)
                        <div class="flex items-center @if($product->discount_min_qty > 1) hidden @endif"
                             id="finalDiscountPrice">
                            <span
                                class="text-4xl font-bold text-blue-600 dark:text-blue-400">€{{ number_format($product->price - $product->discount, 2) }}</span>
                            <span
                                class="ml-3 text-lg text-neutral-500 dark:text-neutral-400 line-through">€{{ number_format($product->price, 2) }}</span>
                            <span
                                class="ml-3 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 px-2 py-1 rounded-full text-xs font-medium">
                                - {{ number_format(($product->discount / $product->price) * 100) }}%
                            </span>
                        </div>

                        @if($product->discount_min_qty > 1)
                            <div
                                class="mt-2 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 p-3 rounded-lg text-sm">
                                <i class="fas fa-tags mr-2"></i> Buy {{ $product->discount_min_qty }}
                                or more and save an extra €{{ number_format($product->discount, 2) }}
                                ({{ number_format(($product->discount / $product->price) * 100) }}%)!
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Product Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-2">Description</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Section -->
                <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Quantity Selector -->
                        <div
                            class="flex items-center border border-neutral-300 dark:border-neutral-600 rounded-lg overflow-hidden bg-white dark:bg-neutral-700">
                            <button id="minus"
                                    class="px-3 py-2 h-full text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-600">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="1" min="1" max="{{ $product->stock }}" name="quantity"
                                   id="quantity" autocomplete="off"
                                   class="appearance-textfield w-12 text-center border-0 bg-transparent text-neutral-800 dark:text-neutral-200 focus:ring-0">
                            <button id="plus"
                                    class="px-3 py-2 h-full text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-600">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i> <span class="block md:hidden lg:block">Add to Cart</span>
                        </button>

                        <!-- Wishlist Button -->
                        <button
                            class="p-3 border border-neutral-300 dark:border-neutral-600 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                            <i class="far fa-heart text-neutral-600 dark:text-neutral-300"></i>
                        </button>
                    </div>

                    <!-- Delivery Info -->
                    <details class="group mt-4 text-sm text-neutral-600 dark:text-neutral-400">
                        <summary class="flex items-center cursor-pointer">
                            <i class="fas fa-truck mr-2"></i>
                            <h5 class="me-2">See delivery prices</h5>
                            <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        @foreach($delivery_prices as $delivery)
                            @if($loop->first)
                                <p class="mt-4">
                                    <i class="fas fa-truck-fast me-2"></i>Express Delivery: €{{ $delivery->cost }}
                                </p>
                            @elseif($loop->last)
                                <p class="mt-2 text-green-600 dark:text-green-400">
                                    <i class="fas fa-gift me-2"></i>Free delivery on orders over €{{ $delivery->min }}!
                                </p>
                            @else
                                <p class="mt-2">
                                    <i class="fas fa-tag me-2"></i>Delivery only €{{ $delivery->cost }} when you spend
                                    €{{ $delivery->min }} or more
                                </p>
                            @endif
                        @endforeach
                    </details>

                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 mb-6">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Related Product 1 -->
                @foreach($random_products as $randomProduct)
                    <a href="{{ route('product.show', $randomProduct->id) }}"
                       class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/products/' . $randomProduct->photo) }}"
                                 alt="{{ $randomProduct->name }}"
                                 class="w-full h-full object-cover">
                            @if($randomProduct->discount)
                                <div
                                    class="absolute top-4 right-4 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    - {{ number_format(($randomProduct->discount / $randomProduct->price) * 100) }}%
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-neutral-800 dark:text-neutral-100 mb-1">{{ $randomProduct->name }}</h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($randomProduct->discount)
                                        <span
                                            class="text-blue-600 dark:text-blue-400 font-bold">€{{ number_format($randomProduct->price - $randomProduct->discount, 2) }}</span>
                                        <span
                                            class="ml-2 text-neutral-500 dark:text-neutral-400 text-sm line-through">€{{ number_format($randomProduct->price, 2) }}</span>
                                    @else
                                        <span
                                            class="text-blue-600 dark:text-blue-400 font-bold">€{{ number_format($randomProduct->price, 2) }}</span>
                                    @endif
                                </div>
                                <span
                                    class="text-green-600 dark:text-green-400 text-xs font-medium">{{ $randomProduct->stock > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity');
            const minusButton = document.getElementById('minus');
            const plusButton = document.getElementById('plus');

            const originalPrice = document.getElementById('originalPrice');
            const finalDiscountPrice = document.getElementById('finalDiscountPrice');
            const discountMinQty = {{ $product->discount_min_qty ?? 999999999 }};

            const maxVal = {{ $product->stock }};

            // on lostfocus, verify if the value is less than 1 or greater than the stock
            quantityInput.addEventListener('blur', function () {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue < 1) {
                    quantityInput.value = 1;
                } else if (currentValue > maxVal) {
                    quantityInput.value = maxVal;
                }

                if (currentValue >= discountMinQty) {
                    finalDiscountPrice.classList.remove('hidden');
                    originalPrice.classList.add('hidden');
                } else {
                    finalDiscountPrice.classList.add('hidden');
                    originalPrice.classList.remove('hidden');
                }
            });

            minusButton.addEventListener('click', function () {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    currentValue--;
                    quantityInput.value = currentValue;
                }

                if (currentValue >= discountMinQty) {
                    finalDiscountPrice.classList.remove('hidden');
                    originalPrice.classList.add('hidden');
                } else {
                    finalDiscountPrice.classList.add('hidden');
                    originalPrice.classList.remove('hidden');
                }
            });

            plusButton.addEventListener('click', function () {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue < maxVal) {
                    currentValue++;
                    quantityInput.value = currentValue;
                }

                if (currentValue >= discountMinQty) {
                    finalDiscountPrice.classList.remove('hidden');
                    originalPrice.classList.add('hidden');
                } else {
                    finalDiscountPrice.classList.add('hidden');
                    originalPrice.classList.remove('hidden');
                }
            });

        });

        function changeMainImage(element) {
            document.getElementById('mainImage').src = element.src;

            document.querySelectorAll('.thumbnailImage').forEach(thumb => {
                thumb.parentElement.classList.remove('border-2', 'border-blue-500');
                thumb.parentElement.classList.add('border', 'border-neutral-200', 'dark:border-neutral-700', 'hover:border-blue-500');
            });

            element.parentElement.classList.remove('border', 'border-neutral-200', 'dark:border-neutral-700', 'hover:border-blue-500');
            element.parentElement.classList.add('border-2', 'border-blue-500');
        }
    </script>
@endsection
