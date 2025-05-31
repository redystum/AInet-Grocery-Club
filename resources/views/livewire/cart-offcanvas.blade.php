<div
        x-data="{ open: @entangle('open') }"
        x-show="open"
        x-on:open-cart.window="$wire.openCart()"
        x-on:close-cart.window="$wire.closeCart()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex justify-end bg-black/50 backdrop-blur-sm"
        style="display: none;"
>
    <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-full max-w-md h-full bg-white dark:bg-neutral-900 text-neutral-800 dark:text-neutral-100 shadow-2xl flex flex-col backdrop-blur-xl"
            @click.away="$wire.closeCart()"
    >
        <div class="flex items-center justify-between px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
            <h2 class="text-2xl font-bold">Your Cart</h2>
            <button wire:click="closeCart"
                    class="cursor-pointer p-2 rounded-full hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                <i class="fas fa-times text-neutral-600 dark:text-neutral-300"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            <!-- Empty cart state -->
            @if(count($cartItems) === 0)
                <div class="flex flex-col items-center justify-center h-64 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-16 w-16 text-neutral-300 dark:text-neutral-700 mb-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-xl font-medium">Your cart is empty</p>
                    <p class="text-neutral-500 dark:text-neutral-400 mt-1">Add some products to your cart</p>
                    <button wire:click="closeCart"
                            class="mt-6 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Continue Shopping
                    </button>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-start space-x-4 py-4 border-b border-neutral-200 dark:border-neutral-800">
                            <img src="{{ $item['image'] }}" class="w-20 h-20 object-cover rounded-lg"
                                 alt="{{ $item['name'] }}">
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <h3 class="font-medium">{{ $item['name'] }}</h3>
                                    <button wire:click="removeCartItem({{ $item['id'] }})"
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                                <p class="text-neutral-500 dark:text-neutral-400 text-sm">{{ $item['description'] }}</p>
                                <div class="flex items-center mt-2">
                                    <button wire:click="decrementQuantity({{ $item['id'] }})"
                                            class="w-8 h-8 flex items-center justify-center border border-neutral-300 dark:border-neutral-700 rounded-l-md hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                        −
                                    </button>
                                    <span class="w-10 h-8 flex items-center justify-center border-t border-b border-neutral-300 dark:border-neutral-700">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $item['id'] }})"
                                            class="w-8 h-8 flex items-center justify-center border border-neutral-300 dark:border-neutral-700 rounded-r-md hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                        +
                                    </button>
                                    <span class="ml-auto font-medium">€{{ number_format($item['price'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="px-6 py-5 border-t border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center justify-between mb-4">
                <span class="font-semibold text-lg">Total</span>
                <span class="font-bold text-2xl text-blue-600 dark:text-blue-500">€{{ number_format($totalAmount, 2) }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button wire:click="closeCart"
                        class="px-6 py-3 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-300 font-medium rounded-xl transition hover:bg-neutral-100 dark:hover:bg-neutral-800 text-center">
                    Continue
                </button>
                <button class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition text-center">
                    Checkout
                </button>
            </div>
        </div>
    </div>
</div>
