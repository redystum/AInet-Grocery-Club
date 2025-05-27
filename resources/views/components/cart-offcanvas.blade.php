<div x-data="{ open: false }" x-cloak>
    <button @click="open = true" class="fixed bottom-8 right-8 bg-blue-600 text-white p-4 rounded-full shadow-lg z-50">
        <i class="fas fa-shopping-cart"></i>
        <span class="ml-2">{{ session('cart') ? count(session('cart')) : 0 }}</span>
    </button>
    <div x-show="open" class="fixed inset-0 bg-black/40 z-40" @click="open = false"></div>
    <aside x-show="open" class="fixed right-0 top-0 h-full w-96 bg-white dark:bg-neutral-900 shadow-lg z-50 transition-transform">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-bold">Shopping Cart</h2>
            <button @click="open = false"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-4">
            @if(session('cart') && count(session('cart')) > 0)
                @foreach(session('cart') as $item)
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('storage/products/' . $item['photo']) }}" class="w-16 h-16 rounded mr-4">
                        <div class="flex-1">
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div class="font-bold">€{{ number_format($item['price'], 2) }}</div>
                    </div>
                @endforeach
                <a href="{{ route('cart.page') }}" class="block w-full bg-blue-600 text-white text-center py-2 rounded mt-4">View Cart</a>
            @else
                <p class="text-gray-500 text-center mt-8">Your cart is empty.</p>
            @endif
        </div>
    </aside>
</div>