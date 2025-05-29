<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}@yield('title')</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white dark:bg-neutral-900 text-gray-900 dark:text-neutral-100">

    <x-navbar/>

    <x-cart-offcanvas />

    <main class="h-main min-h-screen">
        @yield('content')
    </main>

    {{-- <x-footer/> --}}

    @livewireScripts

    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
        document.addEventListener('DOMContentLoaded', function () {
            const cartButton = document.getElementById('cartButton');
            const offCanvasCart = document.getElementById('offCanvasCart');
            const closeCart = document.getElementById('closeCart');
            const cartItems = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');

            function renderCart(items) {
                if (!cartItems) return;
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
                cartItems.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-cart-item')) {
                        fetch('/cart/remove', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.Laravel.csrfToken},
                            body: JSON.stringify({product_id: e.target.dataset.id})
                        }).then(fetchCart);
                    }
                });

                cartItems.addEventListener('change', function (e) {
                    if (e.target.classList.contains('cart-qty')) {
                        fetch('/cart/update', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.Laravel.csrfToken},
                            body: JSON.stringify({product_id: e.target.dataset.id, quantity: e.target.value})
                        }).then(fetchCart);
                    }
                });
            }

            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetch('/cart/add', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.Laravel.csrfToken},
                        body: JSON.stringify({product_id: btn.dataset.id, quantity: 1})
                    }).then(fetchCart);
                });
            });

            fetchCart();
        });
    </script>
</body>
</html>