<div id="offCanvasCart" class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-50 transition-all duration-300 hidden">
    <div class="w-full max-w-md h-full bg-neutral-900 text-neutral-100 shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-5 border-b border-neutral-800">
            <h2 class="text-2xl font-bold">Your Cart</h2>
            <button id="closeCart" class="text-2xl text-neutral-400 hover:text-red-500 transition p-2">&times;</button>
        </div>
        <div id="cartItems" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            <!-- JS irá renderizar os produtos aqui -->
        </div>
        <div class="px-6 py-5 border-t border-neutral-800 bg-neutral-900">
            <div class="flex items-center justify-between mb-4">
                <span class="font-semibold text-lg text-neutral-300">Total</span>
                <span id="cartTotal" class="font-bold text-2xl text-blue-500">€0.00</span>
            </div>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl mb-3 transition text-lg">
                Checkout
            </button>
            <button class="w-full border border-neutral-700 text-neutral-300 font-semibold py-3 rounded-xl transition hover:bg-neutral-800">
                View Cart
            </button>
        </div>
    </div>
</div>