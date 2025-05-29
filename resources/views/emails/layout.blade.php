<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-neutral-100 dark:bg-neutral-900 font-sans">

    <x-cart-offcanvas />

    <div class="max-w-2xl mx-auto my-8 bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">

        @yield('content')

        <footer class="text-center text-neutral-500 dark:text-neutral-400 text-sm mb-6">
            <p class="mb-2">This is an automated message. Please do not reply.</p>
            <p>© {{ now()->year }} {{ $appName }}. All rights reserved.</p>
            <div class="mt-4">
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-4">
                    Help Center
                </a>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-4">
                    Privacy Policy
                </a>
            </div>
        </footer>
    </div>

    <script>
        window.Laravel = {
            !!json_encode(['csrfToken' => csrf_token()]) !!
        };
    </script>
    <script>
        // Carrinho Off-Canvas JS
        document.addEventListener('DOMContentLoaded', function() {
            const cartButton = document.getElementById('cartButton');
            const offCanvasCart = document.getElementById('offCanvasCart');
            const closeCart = document.getElementById('closeCart');
            const cartItems = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');

            function renderCart(items) {
                cartItems.innerHTML = '';
                let total = 0;
                Object.values(items).forEach(item => {
                    total += (item.price - (item.discount ?? 0)) * item.quantity;
                    cartItems.innerHTML += `
                    <div class="flex items-center justify-between mb-2">
                        <img src="/storage/products/${item.photo}" class="w-12 h-12 rounded mr-2" />
                        <div class="flex-1">
                            <div class="font-medium">${item.name}</div>
                            <div class="text-sm text-gray-500">Qtd: 
                                <input type="number" min="1" value="${item.quantity}" data-id="${item.id}" class="cart-qty w-12 border rounded text-center" />
                            </div>
                        </div>
                        <div class="text-right">
                            <div>€${(item.price - (item.discount ?? 0)).toFixed(2)}</div>
                            <button class="remove-cart-item text-red-500" data-id="${item.id}">&times;</button>
                        </div>
                    </div>
                `;
                });
                cartItems.innerHTML += `<div class="font-bold mt-4 text-right">Total: €${total.toFixed(2)}</div>`;
                cartCount && (cartCount.textContent = Object.values(items).reduce((sum, i) => sum + i.quantity, 0));
            }

            function fetchCart() {
                fetch('/cart')
                    .then(res => res.json())
                    .then(renderCart);
            }

            if (cartButton) {
                cartButton.addEventListener('click', () => {
                    offCanvasCart.classList.remove('hidden');
                    fetchCart();
                });
            }

            if (closeCart) {
                closeCart.addEventListener('click', () => {
                    offCanvasCart.classList.add('hidden');
                });
            }

            if (cartItems) {
                cartItems.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-cart-item')) {
                        fetch('/cart/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.Laravel.csrfToken
                            },
                            body: JSON.stringify({
                                product_id: e.target.dataset.id
                            })
                        }).then(fetchCart);
                    }
                });

                cartItems.addEventListener('change', function(e) {
                    if (e.target.classList.contains('cart-qty')) {
                        fetch('/cart/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.Laravel.csrfToken
                            },
                            body: JSON.stringify({
                                product_id: e.target.dataset.id,
                                quantity: e.target.value
                            })
                        }).then(fetchCart);
                    }
                });
            }

            // Para adicionar ao carrinho em qualquer página:
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        },
                        body: JSON.stringify({
                            product_id: btn.dataset.id,
                            quantity: 1
                        })
                    }).then(fetchCart);
                });
            });

            // Atualiza o contador ao carregar a página
            fetchCart();
        });
    </script>
</body>

</html>