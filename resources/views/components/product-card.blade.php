<a href="{{ route('product.show', $product->id) }}"
    class="product-card bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md relative group">
    <!-- Wishlist Button (shown on hover) -->
    <button
        class="absolute top-3 right-3 z-10 w-8 h-8 bg-white dark:bg-neutral-700 rounded-full flex items-center justify-center shadow-md hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-colors wishlist-btn opacity-0 group-hover:opacity-100 cursor-pointer"
        data-product-id="{{ $product->id }}">
        <i class="far fa-heart text-neutral-600 dark:text-neutral-300"></i>
    </button>

    <!-- Product Image -->
    <div class="relative overflow-hidden h-48 cursor-pointer">
        <img src="{{ $product->getImage() }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

        <!-- Discount Badge -->
        @if($product->discount > 0)
        <span class="absolute top-3 left-3 shadow-md text-xs bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 px-2 py-1 rounded-full">
            {{ number_format(($product->discount / $product->price) * 100) }}% OFF
        </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-5">
        <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-2 cursor-pointer">{{ $product->name }}</h3>

        <!-- Price Display -->
        <div class="flex items-center justify-between mb-3">
            @if($product->discount > 0)
            <div>
                <span class="text-green-600 dark:text-green-400 font-bold text-xl">
                    €{{ number_format($product->price * (1 - $product->discount/100), 2) }}
                </span>
                <span class="ml-2 text-neutral-500 dark:text-neutral-400 text-sm line-through">
                    €{{ number_format($product->price, 2) }}
                </span>
            </div>
            @else
            <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">
                €{{ number_format($product->price, 2) }}
            </span>
            @endif

            @if($product->stock > 0 && $product->stock < $product->stock_lower_limit)
                <span class="text-yellow-600 dark:text-yellow-400 text-sm">
                    Low Stock
                </span>
                @else
                <span
                    class="text-sm {{ $product->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
                @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <span
                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                View Details
            </span>
            <button
                class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm transition-colors add-to-cart-btn cursor-pointer"
                data-id="{{ $product->id }}"
                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-shopping-cart mr-1"></i> Add
            </button>
        </div>
    </div>
</a>