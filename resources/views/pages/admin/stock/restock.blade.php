@extends('pages.layouts.admin')

@section('title', 'Restock')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Restock Products</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Confirm restock quantities for low inventory items</p>
            </div>
        </div>

        <!-- Restock Form -->
        <form id="restockForm" method="POST" action="{{ route('board.restock.confirm') }}">
            @csrf
            <input type="hidden" id="restockData" name="restock_data" value="">

            <!-- Restock Table -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Current Stock
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Restock Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                New Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700" id="restockItems">
                        @forelse($products as $product)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors restock-item" data-product-id="{{ $product->id }}">
                                <!-- Product Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-md object-cover"
                                                 src="{{ $product->getImage() }}"
                                                 alt="{{ $product->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                                {{ $product->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Current Stock Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                        <span class="font-medium">{{ $product->stock }}</span> units
                                    </div>
                                </td>

                                <!-- Restock Quantity Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center w-fit border border-neutral-300 dark:border-neutral-600 rounded-lg overflow-hidden bg-white dark:bg-neutral-700">
                                        <button type="button" class="quantity-minus px-3 py-2 h-full text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-600">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number"
                                               class="restock-quantity appearance-textfield w-12 text-center border-0 bg-transparent text-neutral-800 dark:text-neutral-200 focus:ring-0"
                                               min="1" autocomplete="off"
                                               max="{{ $product->restock }}"
                                               value="{{ $product->restock }}">
                                        <button type="button" class="quantity-plus px-3 py-2 h-full text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-600">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- New Total Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400 new-total">
                                        {{ $product->stock + $product->restock }} units
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td class="whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" class="px-6 py-4 cursor-pointer text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors remove-item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    No products available for restock.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary and Actions -->
                <div class="p-4 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/30">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="text-neutral-700 dark:text-neutral-300">
                            <p><span class="font-medium" id="itemCount">{{ count($products) }} items</span> selected for restocking</p>
                            <p class="text-sm">Total unit items: <span class="font-medium text-blue-600 dark:text-blue-400" id="totalUnitItems">
                                @if($products->isNotEmpty())
                                    {{ number_format(array_sum($products->pluck('restock')->toArray()), 0) }}
                                @else
                                    0
                                @endif
                                </span></p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('board.stock') }}" class="cursor-pointer px-6 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                                Cancel Restock
                            </a>
                            <button type="submit"  @disabled($products->isNotEmpty() == 0)
                                    class="not-disabled:cursor-pointer px-6 py-2 dark:disabled:bg-blue-900 disabled:bg-blue-400 disabled:text-gray-300 dark:disabled:text-gray-500 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-check-circle mr-2"></i> Confirm Supply Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restockForm = document.getElementById('restockForm');
            const restockItems = document.getElementById('restockItems');
            const itemCount = document.getElementById('itemCount');
            const totalUnitItems = document.getElementById('totalUnitItems');
            const restockDataInput = document.getElementById('restockData');

            // Base price for calculation
            const basePricePerUnit = {{ $averagePricePerUnit ?? 1 }};

            // Quantity button handlers
            restockItems.addEventListener('click', function(e) {
                const row = e.target.closest('.restock-item');
                if (!row) return;

                const input = row.querySelector('.restock-quantity');
                const currentValue = parseInt(input.value);
                const min = parseInt(input.min);
                const max = parseInt(input.max);

                // Handle minus button
                if (e.target.closest('.quantity-minus')) {
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                        updateRowTotal(row);
                    }
                }

                // Handle plus button
                if (e.target.closest('.quantity-plus')) {
                    if (currentValue < max) {
                        input.value = currentValue + 1;
                        updateRowTotal(row);
                    }
                }

                // Handle remove button
                if (e.target.closest('.remove-item')) {
                    row.remove();
                    updateItemCount();
                    updateTotalUnitItems();
                }
            });

            // Input change handler
            restockItems.addEventListener('input', function(e) {
                if (e.target.classList.contains('restock-quantity')) {
                    const row = e.target.closest('.restock-item');
                    updateRowTotal(row);
                }
            });

            // Update row calculations
            function updateRowTotal(row) {
                const currentStock = parseInt(row.querySelector('td:nth-child(2) span').textContent);
                const quantity = parseInt(row.querySelector('.restock-quantity').value);
                const newTotal = currentStock + quantity;

                row.querySelector('.new-total').textContent = newTotal + ' units';
                updateTotalUnitItems();
            }

            // Update item count display
            function updateItemCount() {
                const count = document.querySelectorAll('.restock-item').length;
                itemCount.textContent = count + (count === 1 ? ' item' : ' items');
            }

            // Update total price display
            function updateTotalUnitItems() {
                let total = 0;
                document.querySelectorAll('.restock-item').forEach(row => {
                    const quantity = parseInt(row.querySelector('.restock-quantity').value) || 0;
                    total += quantity * basePricePerUnit;
                });
                totalUnitItems.textContent = total.toFixed(0);
            }

            // Form submission handling
            restockForm.addEventListener('submit', function(e) {
                // Prepare simplified data structure {id => quantity}
                const restockData = {};
                document.querySelectorAll('.restock-item').forEach(row => {
                    const productId = row.dataset.productId;
                    restockData[productId] = parseInt(row.querySelector('.restock-quantity').value);
                });

                // Validate at least one item
                if (Object.keys(restockData).length === 0) {
                    e.preventDefault();
                    alert('Please add at least one product to restock');
                    return false;
                }

                // Set the hidden input value as JSON
                restockDataInput.value = JSON.stringify(restockData);
                return true;
            });
        });
    </script>
@endsection