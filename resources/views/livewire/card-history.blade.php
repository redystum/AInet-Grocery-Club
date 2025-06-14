@use('App\Models\Operations')
<div>
    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="type" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Transaction
                    Type</label>
                <select id="type" wire:model.live="type"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    <option value="">All Types</option>
                    <option value="payment">Deposit</option>
                    <option value="order">Purchase</option>
                    <option value="refund">Refund</option>
                    <option value="membership_fee">Membership Fee</option>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">From
                    Date</label>
                <input type="date" id="start_date" wire:model.live="start_date"
                       class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">To
                    Date</label>
                <input type="date" id="end_date" wire:model.live="end_date"
                       class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-100 dark:bg-neutral-700">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Reference
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Amount
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Balance
                    </th>
                </tr>
                </thead>
                <tbody class="bg-neutral-50 dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($operations as $operation)
                    <tr class="hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $operation->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($operation->debit_type === Operations::TYPE_DEBIT_ORDER)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">Purchase</span>
                            @elseif($operation->debit_type === Operations::TYPE_DEBIT_MEMBERSHIP_FEE)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">Membership
                                    Fee</span>
                            @elseif($operation->credit_type === Operations::TYPE_CREDIT_PAYMENT)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">Deposit</span>
                            @elseif($operation->credit_type === Operations::TYPE_CREDIT_ORDER_CANCEL)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">Refund</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">{{ ucfirst($operation->type) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-800 dark:text-neutral-200">
                            @if($operation->debit_type === Operations::TYPE_DEBIT_ORDER)
                                Purchase: @if($operation->order_id)
                                    <a href="{{ route('orders', ['order' => $operation->order_id]) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        #{{ $operation->order_id }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            @elseif($operation->debit_type === Operations::TYPE_DEBIT_MEMBERSHIP_FEE)
                                Membership Fee
                            @elseif($operation->credit_type === Operations::TYPE_CREDIT_PAYMENT)
                                Deposit via {{ $operation->payment_type ?? 'N/A' }}
                            @elseif($operation->credit_type === Operations::TYPE_CREDIT_ORDER_CANCEL)
                                Order Refund
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">
                            @if($operation->payment_reference)
                                {{ $operation->payment_reference }}
                            @else
                                Virtual Card
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $operation->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $operation->type === 'credit' ? '+' : '-' }}{{ number_format($operation->value, 2) }}
                            €
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-neutral-800 dark:text-neutral-200">
                            {{ isset($operation->running_balance) ? number_format($operation->running_balance, 2) . ' €' : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                            No transactions found matching your criteria.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        @if($operations->hasPages())
            <div class="p-3 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                    Showing {{ $operations->firstItem() ?? 0 }} to {{ $operations->lastItem() ?? 0 }}
                    of {{ $operations->total() ?? 0 }} items
                </div>

                <div class="flex items-center gap-2">
                    <!-- First Page Link -->
                    <button wire:click="gotoFirstPage"
                            @if(!$operations->count() || $operations->currentPage() === 1) disabled
                            @endif
                            class="cursor-pointer disabled:cursor-not-allowed px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$operations->count() || $operations->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-double-left"></i>
                    </button>

                    <!-- Previous Page Link -->
                    <button wire:click="previousPage" @if(!$operations->count() || $operations->onFirstPage()) disabled
                            @endif
                            class="cursor-pointer disabled:cursor-not-allowed px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$operations->count() || $operations->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-left"></i>
                    </button>

                    <!-- Page Number Input -->
                    <div class="flex items-center gap-1">
                        <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                        <input type="number" min="1" max="{{ $operations->lastPage() ?? 1 }}"
                               wire:model.change="page"
                               class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300
                                       dark:border-neutral-700 focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100
                                       placeholder-gray-400 dark:placeholder-neutral-500">
                        <span class="text-neutral-600 dark:text-neutral-400">of {{ $operations->lastPage() ?? 1 }}</span>
                    </div>

                    <!-- Next Page Link -->
                    <button wire:click="nextPage" @if(!$operations->count() || !$operations->hasMorePages()) disabled
                            @endif
                            class="cursor-pointer disabled:cursor-not-allowed px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$operations->count() || !$operations->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-right"></i>
                    </button>

                    <!-- Last Page Link -->
                    <button wire:click="gotoLastPage({{ $operations->lastPage() }})"
                            @if(!$operations->count() || $operations->currentPage() === $operations->lastPage()) disabled
                            @endif
                            class="cursor-pointer disabled:cursor-not-allowed px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$operations->count() || $operations->currentPage() === $operations->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>