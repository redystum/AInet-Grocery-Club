@extends('pages.layouts.admin')

@section('title', 'Cancel Supply Order')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Cancel Supply Order</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Select products to cancel from the supply order</p>
            </div>
        </div>

        <!-- Cancel Form -->
        <form id="cancelForm" method="POST" action="{{ route('board.supply.destroy') }}">
            @csrf
            @method('DELETE')
            <div id="cancelDataContainer">
                <!-- Dynamic cancel_data[] inputs will be inserted here -->
            </div>

            <!-- Reason Input -->
            <div class="mb-6 bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden p-6">
                <div class="space-y-2">
                    <label for="cancelReason" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Reason
                        for Cancellation</label>
                    <textarea id="cancelReason" name="reason" rows="3"
                              class="mt-1 block w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-neutral-200 @error('reason') border-red-500 dark:border-red-500 @enderror"
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Please provide a detailed reason for
                        cancelling these items.</p>
                </div>
            </div>

            <!-- Error for cancel data -->
            @error('cancel_data')
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 rounded-xl">
                <p class="font-medium">{{ $message }}</p>
                <p class="text-sm">Please ensure you've selected at least one product to cancel.</p>
            </div>
            @enderror
            <!-- Main Product Table -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Ordered Quantity
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700"
                               id="cancelItems">
                        @if($order)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors cancel-item"
                                data-product-id="{{ $order->id }}">
                                <!-- Product Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-md object-cover"
                                                 src="{{ $order->product->getImage() }}"
                                                 alt="{{ $order->product->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                                {{ $order->product->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Ordered Quantity Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                        <span class="font-medium">{{ $order->quantity }}</span> units
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-red-600 dark:text-red-400">
                                        Will be cancelled
                                        <input type="hidden" class="cancel-quantity" value="{{ $order->quantity }}">
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td class="whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button"
                                            class="px-6 py-4 cursor-pointer text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-400 transition-colors remove-item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    No main product found in this supply order.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Other Products Section -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-neutral-800 dark:text-neutral-200 mb-4">Other Products Ordered
                    on {{ $order->created_at->format('Y-m-d') }}</h2>

                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Product
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Ordered Quantity
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse($otherOrders as $otherOrder)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors"
                                    id="other-product-{{ $otherOrder->id }}"
                                    @if($loop->first) style="display: none" @endif>
                                    <!-- Product Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover"
                                                     src="{{ $otherOrder->product->getImage() }}"
                                                     alt="{{ $otherOrder->product->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                                    {{ $otherOrder->product->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Ordered Quantity Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                            <span class="font-medium">{{ $otherOrder->quantity }}</span> units
                                        </div>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button"
                                                class="px-6 py-4 cursor-pointer text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors add-to-cancel"
                                                data-product-id="{{ $otherOrder->id }}"
                                                data-quantity="{{ $otherOrder->quantity }}">
                                            <i class="fas fa-plus-circle mr-1"></i> Add to Cancellation
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                        No other products ordered on this date.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary and Actions -->
            <div class="p-4 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/30 rounded-xl">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-neutral-700 dark:text-neutral-300">
                        <p><span class="font-medium" id="itemCount">{{ $order ? 1 : 0 }} item</span> selected for
                            cancellation</p>
                        <p class="text-sm">Total units affected: <span
                                    class="font-medium text-blue-600 dark:text-blue-400" id="totalUnitItems">
                                {{ $order ? $order->quantity : 0 }}
                            </span></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('board.supply.index') }}"
                           class="cursor-pointer px-6 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                            Back to Supply Orders
                        </a>
                        <button type="submit" id="submitButton" @disabled(!$order)
                        class="not-disabled:cursor-pointer px-6 py-2 dark:disabled:bg-red-900 disabled:bg-red-400 disabled:text-gray-300 dark:disabled:text-gray-500 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-times-circle mr-2"></i> Confirm Cancellation
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cancelForm = document.getElementById('cancelForm');
            const cancelItems = document.getElementById('cancelItems');
            const itemCount = document.getElementById('itemCount');
            const totalUnitItems = document.getElementById('totalUnitItems');
            const submitButton = document.getElementById('submitButton');

            // Add to cancel list buttons
            document.querySelectorAll('.add-to-cancel').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.productId;
                    const quantity = parseInt(this.dataset.quantity);

                    // Check if product already exists in cancel list
                    const existingRow = document.querySelector(`.cancel-item[data-product-id="${productId}"]`);

                    if (!existingRow) {
                        // Add new row with product details
                        const sourceRow = this.closest('tr');
                        const productName = sourceRow.querySelector('.text-sm.font-medium').textContent;
                        const productImage = sourceRow.querySelector('img').src;

                        // Get the source row ID and hide it
                        const sourceRowId = `other-product-${productId}`;
                        const otherOrderRow = document.getElementById(sourceRowId);
                        if (otherOrderRow) {
                            otherOrderRow.style.display = 'none';
                        }

                        const newRow = document.createElement('tr');
                        newRow.className = 'hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors cancel-item';
                        newRow.dataset.productId = productId;
                        newRow.dataset.sourceRowId = productId; // Store a reference to the source row ID

                        newRow.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-md object-cover" src="${productImage}" alt="${productName}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                                            ${productName}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                    <span class="font-medium">${quantity}</span> units
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-red-600 dark:text-red-400">
                                    Will be cancelled
                                    <input type="hidden" class="cancel-quantity" value="${quantity}">
                                </div>
                            </td>
                            <td class="whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" class="px-6 py-4 cursor-pointer text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-400 transition-colors remove-item">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        `;

                        cancelItems.appendChild(newRow);
                    }

                    updateItemCount();
                    updateTotalUnitItems();
                });
            });

            // Remove item handler
            cancelItems.addEventListener('click', function (e) {
                if (e.target.closest('.remove-item')) {
                    const row = e.target.closest('.cancel-item');
                    const productId = row.dataset.productId;

                    // Unhide the corresponding row in the other products table
                    const sourceRow = document.getElementById(`other-product-${productId}`);
                    if (sourceRow) {
                        sourceRow.style.display = '';
                    }

                    row.remove();
                    updateItemCount();
                    updateTotalUnitItems();
                }
            });

            // Update item count display
            function updateItemCount() {
                const count = document.querySelectorAll('.cancel-item').length;
                itemCount.textContent = count + (count === 1 ? ' item' : ' items');
                submitButton.disabled = count === 0;
            }

            // Update total units to cancel
            function updateTotalUnitItems() {
                let total = 0;
                document.querySelectorAll('.cancel-item').forEach(row => {
                    const quantity = parseInt(row.querySelector('.cancel-quantity').value) || 0;
                    total += quantity;
                });
                totalUnitItems.textContent = total;
            }

            // Form submission handling
            cancelForm.addEventListener('submit', function (e) {
                // Validate reason
                const reason = document.getElementById('cancelReason').value.trim();
                if (!reason) {
                    e.preventDefault();
                    alert('Please provide a reason for cancellation');
                    return false;
                }

                // Clear previous data
                const container = document.getElementById('cancelDataContainer');
                container.innerHTML = '';
            
                // Prepare data for complete order cancellation
                const cancelItems = document.querySelectorAll('.cancel-item');
                
                // Validate at least one item
                if (cancelItems.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one product to cancel');
                    return false;
                }
                
                // Create input fields with array notation for Laravel
                cancelItems.forEach(row => {
                    const productId = row.dataset.productId;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'cancel_data[]';
                    input.value = productId;
                    container.appendChild(input);
                });
                
                return true;
            });
        });
    </script>
@endsection