@extends('layout')

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

        <!-- Cart Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                @if(count($cartItems) > 0)
                    <!-- Cart Items List -->
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($cartItems as $item)
                            <div class="p-4 hover:bg-neutral-100/50 dark:hover:bg-neutral-700/50 transition-colors" id="cart-item-{{ $item->id }}">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden bg-white dark:bg-neutral-700">
                                        <img class="w-full h-full object-contain" src="{{ $item->product->getImage() }}" alt="{{ $item->product->name }}">
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
                                            <button class="remove-item text-neutral-400 hover:text-red-500 transition-colors" data-id="{{ $item->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <!-- Price and Quantity -->
                                        <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <!-- Price -->
                                            <div class="text-lg font-medium text-neutral-800 dark:text-neutral-100">
                                                @if($item->product->discount_price)
                                                    <span class="text-red-600 dark:text-red-400">${{ number_format($item->product->discount_price, 2) }}</span>
                                                    <span class="ml-2 text-sm text-neutral-500 dark:text-neutral-400 line-through">${{ number_format($item->product->price, 2) }}</span>
                                                @else
                                                    ${{ number_format($item->product->price, 2) }}
                                                @endif
                                            </div>

                                            <!-- Quantity Controls -->
                                            <div class="flex items-center">
                                                <button class="quantity-btn px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-l-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors" data-action="decrease" data-id="{{ $item->id }}">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <input type="number" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" class="appearance-textfield quantity-input w-12 px-2 py-1 text-center border-t border-b border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100" data-id="{{ $item->id }}">
                                                <button class="quantity-btn px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-r-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors" data-action="increase" data-id="{{ $item->id }}">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>

                                            <!-- Item Total -->
                                            <div class="text-lg font-medium text-neutral-800 dark:text-neutral-100 item-total" data-original-price="{{ $item->product->price }} " data-discount="{{ $item->product->discount ?? 0 }}" data-discount-min-qty="{{ $item->product->discount_min_qty ?? 0 }}">
                                                ${{ number_format($item->total, 2) }}
                                            </div>
                                        </div>
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
                                <span class="font-medium text-neutral-800 dark:text-neutral-100" id="cart-subtotal">${{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Shipping</span>
                                <span class="font-medium text-neutral-800 dark:text-neutral-100">Free</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Discounts</span>
                                <span class="font-medium text-neutral-800 dark:text-neutral-100" id="cart-tax">- ${{ number_format($discounts, 2) }}</span>
                            </div>

                            <div class="pt-4 mt-4 border-t border-neutral-200 dark:border-neutral-700">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Total</span>
                                    <span class="text-lg font-semibold text-blue-600 dark:text-blue-400" id="cart-total">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <div class="pt-6">
                                <a href="{{ route('checkout') }}" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-center transition-colors flex items-center justify-center">
                                    Proceed to Checkout
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 invisible">
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

        // Calculate and update totals
        function updateTotals() {
            let subtotal = 0, totalDiscount = 0;

            document.querySelectorAll('.item-total').forEach(item => {
                const input = item.closest('.flex').querySelector('.quantity-input');
                const quantity = parseInt(input.value) || 1;
                const { originalPrice, discount, discountMinQty } = item.dataset;

                const price = parseFloat(originalPrice);
                const disc = parseFloat(discount);
                const minQty = parseInt(discountMinQty);

                // Aplica desconto se aplicável
                const isDiscounted = (disc > 0 && quantity >= minQty);
                const unitPrice = isDiscounted ? (price - disc) : price;
                const discountLine = isDiscounted ? disc * quantity : 0;

                subtotal += price * quantity;
                totalDiscount += discountLine;

                // Atualiza visual
                item.textContent = '$' + (unitPrice * quantity).toFixed(2);
            });

            const total = subtotal - totalDiscount;
            document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('cart-tax').textContent = '- $' + totalDiscount.toFixed(2);
            document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
        }

        // Input change handler
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value < 1) this.value = 1;

                // Update the cart via AJAX
                updateCartItem(this.dataset.id, this.value, false);

                // Update totals instantly
                updateTotals();
            });
        });

        // Remove item from cart
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    removeCartItem(this.dataset.id);
                }
            });
        });

        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function () {
                const action = this.dataset.action;
                const input = this.closest('.flex').querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('max'));
                let value = parseInt(input.value);

                if (action === 'increase' && value < max) {
                    input.value = value + 1;
                } else if (action === 'decrease' && value > 1) {
                    input.value = value - 1;
                }
                // Chama o input.change para verificar limites novamente e AJAX
                input.dispatchEvent(new Event('change'));
            });
        });

        // Bloquear manualmente valores acima do stock ou letras
        document.querySelectorAll('.quantity-input').forEach(input => {
            // Só impede letras e caracteres não numéricos
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '');
            });

            // Só ao perder foco valida limites
            input.addEventListener('blur', function () {
                const max = parseInt(this.getAttribute('max'));
                let value = parseInt(this.value);

                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > max) {
                    this.value = max;
                }
                // Chama AJAX para atualizar quantidade ao perder foco (opcional)
                updateCartItem(this.dataset.id, this.value, false);
                updateTotals();
            });
        });


        // AJAX functions
        function updateCartItem(itemId, quantity, reload = true) {
            fetch(`/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: quantity })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && reload) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function removeCartItem(itemId) {
            fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove item from DOM
                        document.getElementById(`cart-item-${itemId}`).remove();

                        // Update totals
                        updateTotals();

                        // If cart is now empty, reload to show empty state
                        if (document.querySelectorAll('.item-total').length === 0) {
                            window.location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
