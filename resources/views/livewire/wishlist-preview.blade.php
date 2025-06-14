<div>
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" :in-wishlist="true" :wishlist-page="true" />
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 text-center">
            <i class="far fa-heart text-4xl text-neutral-400 mb-3"></i>
            <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200 mb-2">Your wishlist is empty</h3>
            <p class="text-neutral-600 dark:text-neutral-400 mb-4">Save items you like while shopping</p>
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg inline-block transition-colors">
                <i class="cursor-pointer"></i> Browse Products
            </a>
        </div>
    @endif
</div>
