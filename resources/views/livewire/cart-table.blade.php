
<div>
    <!-- Cart Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            @if(count($cartItems) > 0)
                <!-- Cart Items List -->
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach($cartItems as $item)
                        <div class="p-4 hover:bg-neutral-100/50 dark:hover:bg-neutral-700/50 rounded-lg transition-colors" id="cart-item-{{ $item->id }}" wire:key="cart-item-{{ $item->id }}">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden bg-white dark:bg-neutral-700">
                                    <img class="w-full h-full object-cover" src="{{ $item->product->getImage() }}" alt="{{ $item->product->name }}">
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="font-medium text-neutral-800 dark:text-neutral-100">{{ $item->product->name }}</h3>
                                            @if($item->product->category)
                                                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $item->product->category->name }}</p>
                                            @endif
                                        </div>
                                        <button class="text-neutral-400 hover:text-red-500 transition-colors cursor-pointer" wire:click="removeItem({{ $item->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- Price and Quantity -->
                                    <table class="w-full mt-4">
                                        <tr>
                                            <!-- Price Column -->
                                            <td class="w-1/4 text-lg font-medium text-neutral-800 dark:text-neutral-100 align-middle">
                                                <div class="min-w-[120px]">
                                                    @if($item->is_discounted)
                                                        <div class="inline-flex items-center flex-nowrap">
                                                            <span class="text-red-600 dark:text-red-400">€{{ number_format($item->unit_price, 2) }}</span>
                                                            <span class="ml-2 text-sm text-neutral-500 dark:text-neutral-400 line-through">€{{ number_format($item->original_unit_price, 2) }}</span>
                                                            <span class="ml-2 text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-2 py-1 rounded-full whitespace-nowrap">{{ $item->discount_percent }}% OFF</span>
                                                        </div>
                                                    @else
                                                        €{{ number_format($item->original_unit_price, 2) }}
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Quantity Controls Column -->
                                            <td class="w-1/2 px-4 align-middle">
                                                <div class="flex items-center justify-center">
                                                    <button class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-l-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors cursor-pointer"
                                                            wire:click="decrement({{ $item->id }})">
                                                        <i class="fas fa-minus text-xs"></i>
                                                    </button>
                                                    <input type="number"
                                                           min="1"
                                                           max="{{ $item->product->stock }}"
                                                           value="{{ $item->quantity }}"
                                                           wire:model.blur="cartItems.{{ $loop->index }}.quantity"
                                                           wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                                           class="appearance-textfield w-12 px-2 py-1 text-center border-t border-b border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                                    <button class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-r-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors cursor-pointer"
                                                            wire:click="increment({{ $item->id }})">
                                                        <i class="fas fa-plus text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- Item Total Column -->
                                            <td class="w-1/4 text-lg font-medium text-neutral-800 dark:text-neutral-100 align-middle text-right">
                                                <div class="min-w-[100px]">
                                                    €{{ number_format($item->total, 2) }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Continue Shopping Button -->
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                        Continue Shopping
                    </a>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-3xl text-neutral-400"></i>
                        </div>
                        <h3 class="text-xl font-medium text-neutral-800 dark:text-neutral-200 mb-3">Your cart is empty</h3>
                        <p class="text-neutral-600 dark:text-neutral-400 mb-6">Looks like you haven't added any items to your cart yet</p>
                        <a href="{{ route('products.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg inline-block transition-colors">
                            Browse Products
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Cart Summary -->
        @if(count($cartItems) > 0)
            <div class="lg:col-span-1">
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 sticky top-20">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-6 pb-4 border-b border-neutral-200 dark:border-neutral-700">Order Summary</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Subtotal</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">€{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Shipping</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">
                                @if($shipping > 0)
                                    €{{ number_format($shipping, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Discounts</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">- €{{ number_format($discounts, 2) }}</span>
                        </div>

                        <div class="pt-4 mt-4 border-t border-neutral-200 dark:border-neutral-700">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Total</span>
                                <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    €{{ number_format($total_with_shipping, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-6">
                            <a href="#" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-center transition-colors flex items-center justify-center">
                                Pay
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
