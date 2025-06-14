<div>
    <!-- Notification -->
    @if($notification)
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="$dispatch('dismiss-notification', () => { show = false })"
            class="mb-6 rounded-lg p-4 {{ $notificationType === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400' }} flex items-center justify-between"
        >
            <div class="flex items-center">
                @if($notificationType === 'success')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                {{ $notification }}
            </div>
            <button type="button" @click="show = false" wire:click="dismissNotification" class="cursor-pointer text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Membership Fee Section -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Membership Fee</h2>
        </div>

        <form wire:submit.prevent="updateMembershipFee" class="space-y-4">
            <div>
                <label for="membership_fee"
                       class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Current Membership Fee
                </label>
                <div class="flex items-center gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-neutral-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" id="membership_fee" wire:model="membershipFee" step="0.01" min="0"
                               class="appearance-textfield pl-8 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    </div>
                </div>
                @error('membershipFee') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Update Membership Fee
            </button>
        </form>
    </div>

    <!-- Delivery Costs Section -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Delivery Costs</h2>
        </div>

        <!-- Add New Delivery Cost -->
        <form wire:submit.prevent="addDeliveryCost"
              class="mb-8 p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
            <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200 mb-4">Add New Delivery Cost</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="min_amount"
                           class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                        Minimum Order Amount (€)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-neutral-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" id="min_amount" wire:model="newMinAmount" step="0.01" min="0"
                               class="appearance-textfield pl-8 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    </div>
                    @error('newMinAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                        Delivery Cost (€)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-neutral-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" id="cost" wire:model="newCost" step="0.01" min="0"
                               class="appearance-textfield pl-8 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    </div>
                    @error('newCost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="cursor-pointer w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Add Delivery Cost
                    </button>
                </div>
            </div>
        </form>

        <!-- Delivery Costs Table -->
        <div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Minimum Order Amount
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Delivery Cost
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($deliveryCosts as $index => $cost)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">
                            <span class="font-medium">€{{ number_format($cost['min_amount'], 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">
                            @if($cost['cost'] > 0)
                                <span class="font-medium">€{{ number_format($cost['cost'], 2) }}</span>
                            @else
                                <span class="font-medium text-green-600 dark:text-green-500">Free</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <div class="flex gap-2 justify-end">
                                <button wire:click="editDeliveryCost({{ $index }})" type="button"
                                        class="cursor-pointer text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $index }})" type="button"
                                        class="cursor-pointer text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-neutral-600 dark:text-neutral-400">
                            No delivery costs configured yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        @if($editingIndex !== null)
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200 mb-4">Edit Delivery Cost</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                                Minimum Order Amount (€)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-neutral-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" wire:model="editingMinAmount" step="0.01" min="0"
                                       class="appearance-textfield pl-8 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            </div>
                            @error('editingMinAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                                Delivery Cost (€)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-neutral-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" wire:model="editingCost" step="0.01" min="0"
                                       class="appearance-textfield pl-8 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                            </div>
                            @error('editingCost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button wire:click="cancelEdit" type="button"
                                class="cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="updateDeliveryCost" type="button"
                                class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($confirmingDeleteIndex !== null)
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-medium text-neutral-800 dark:text-neutral-200 mb-2">Confirm Deletion</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                        Are you sure you want to delete the delivery cost for orders starting at 
                        <span class="font-semibold">€{{ number_format($deliveryCosts[$confirmingDeleteIndex]['min_amount'], 2) }}</span>?
                        This action cannot be undone.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('confirmingDeleteIndex', null)" type="button"
                                class="cursor-pointer px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="deleteDeliveryCost" type="button"
                                class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
