<div id="offCanvasCart" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="absolute right-0 top-0 w-full sm:w-[400px] max-w-full bg-white dark:bg-neutral-900 h-full shadow-2xl flex flex-col transition-all duration-300">
        <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-700">
            <h2 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Carrinho de Compras</h2>
            <button id="closeCart" class="text-2xl text-neutral-500 hover:text-red-500 transition p-2">&times;</button>
        </div>
        <div id="cartItems" class="flex-1 overflow-y-auto p-6 space-y-4 bg-neutral-50 dark:bg-neutral-900">
            <!-- Itens do carrinho serão renderizados por JS -->
        </div>
        <div class="p-6 border-t border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-lg text-neutral-700 dark:text-neutral-200">TOTAL <span class="text-xs font-normal text-neutral-400 ml-2">Excluindo custos de envio</span></span>
                <span id="cartTotal" class="font-bold text-2xl text-red-600">€0.00</span>
            </div>
            <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-neutral-900 font-semibold py-3 rounded-lg mb-3 transition">Ver o meu cesto</button>
            <button class="w-full border-2 border-neutral-300 dark:border-neutral-600 text-neutral-900 dark:text-neutral-100 font-semibold py-3 rounded-lg transition hover:bg-neutral-100 dark:hover:bg-neutral-700">Compra Rápida</button>
        </div>
    </div>
</div>