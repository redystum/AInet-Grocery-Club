@use('App\Models\User')
<div x-data="{ activeDropdown: null }"
     x-init="document.addEventListener('click', () => { activeDropdown = null })"
     @scroll.window="activeDropdown = null"
     @keydown.escape.window="activeDropdown = null">

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Search Users
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-neutral-400"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Search by name or email"
                           class="pl-10 w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                </div>
            </div>

            <div>
                <label for="user-type" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    User Type
                </label>
                <select wire:model.live="userType" id="user-type"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="">All Types</option>
                    <option value="member">Member</option>
                    <option value="board">Board</option>
                    <option value="employee">Employee</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Status
                </label>
                <select wire:model.live="status" id="status"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="blocked">Blocked</option>
                </select>
            </div>

            <div>
                <label for="order-by" class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                    Order By
                </label>
                <select wire:model.live="orderBy" id="order-by"
                        class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="created_at_desc">Joined (Newest First)</option>
                    <option value="created_at_asc">Joined (Oldest First)</option>
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="orders_count_asc">Orders (Low to High)</option>
                    <option value="orders_count_desc">Orders (High to Low)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        User
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Orders
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Payment Method
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Joined
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($users as $user)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <!-- User Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="{{ $user->getImage() }}"
                                         alt="{{ $user->name }}">
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('board.users.show', $user->id) }}" class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $user->name }}</a>
                                    <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Type Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                @if($user->type === User::TYPE_BOARD)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
                                        Board
                                    </span>
                                @elseif($user->type === User::TYPE_EMPLOYEE)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                        Employee
                                    </span>
                                @elseif($user->type === User::TYPE_PENDING_MEMBER)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                        Pending Member
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                        Member
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Status Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->blocked)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 flex items-center">
                                    <i class="fas fa-ban mr-1"></i> Blocked
                                    @php
                                        $blockReason = App\Utils\CustomFieldManager::get_field($user, 'block_reason');
                                    @endphp
                                    @if($blockReason)
                                        <button @click="$dispatch('open-tooltip', {id: 'block-reason-{{ $user->id }}'})" 
                                                class="cursor-pointer ml-2 text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <div x-data="{ open: false }" 
                                             @open-tooltip.window="if ($event.detail.id === 'block-reason-{{ $user->id }}') open = true"
                                             @click.away="open = false"
                                             x-show="open"
                                             x-transition
                                             x-cloak
                                             class="absolute z-10 bg-white dark:bg-neutral-800 p-2 rounded-lg shadow-lg border border-neutral-200 dark:border-neutral-700 text-xs max-w-xs text-left mt-1">
                                            <strong>Reason:</strong> {{ $blockReason }}
                                        </div>
                                    @endif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @endif
                        </td>

                        <!-- Orders Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                {{ $user->orders_count }} orders
                                @if($user->orders_count > 0 && $user->orders->first())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400 block">Last: {{ $user->orders->first()->created_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Payment Method Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                @if($user->default_payment_type)
                                    <span class="capitalize">{{ $user->default_payment_type }}</span>
                                    @if($user->default_payment_reference)
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400 block">({{ $user->default_payment_reference }})</span>
                                    @endif
                                @else
                                    <span class="text-neutral-500 dark:text-neutral-400">Not set</span>
                                @endif
                            </div>
                        </td>

                        <!-- Joined Date Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-800 dark:text-neutral-100">
                                {{ $user->created_at->format('M d, Y') }}
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative" @click.outside="activeDropdown = null">
                                <button @click.stop="activeDropdown === 'user-{{ $user->id }}' ? activeDropdown = null : activeDropdown = 'user-{{ $user->id }}'"
                                        class="inline-flex cursor-pointer justify-center w-8 h-8 rounded-full bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors"
                                        aria-haspopup="true">
                                    <i class="fas fa-ellipsis-v text-neutral-600 dark:text-neutral-300 m-auto"></i>
                                </button>

                                <div x-show="activeDropdown === 'user-{{ $user->id }}'"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-neutral-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                     role="menu" aria-orientation="vertical" tabindex="-1">
                                    <div role="none" class="py-1">
                                        <a href="{{ route('board.users.show', $user->id) }}"
                                           class="cursor-pointer flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                           role="menuitem">
                                            <i class="fas fa-eye mr-3 text-blue-400"></i>
                                            View Profile
                                        </a>
                                        <a href="{{ route('board.users.edit', $user->id) }}"
                                           class="cursor-pointer flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                           role="menuitem">
                                            <i class="fas fa-user-edit mr-3 text-blue-400"></i>
                                            Edit User
                                        </a>
                                        @if($user->blocked)
                                            <button wire:click="openUnblockModal({{ $user->id }})"
                                                   class="cursor-pointer w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                   role="menuitem">
                                                <i class="fas fa-lock-open mr-3 text-green-400"></i>
                                                Unblock User
                                            </button>
                                        @else
                                            <button wire:click="openBlockModal({{ $user->id }})"
                                                   class="cursor-pointer w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                   role="menuitem">
                                                <i class="fas fa-user-lock mr-3 text-orange-400"></i>
                                                Block User
                                            </button>
                                        @endif
                                        <button wire:click="openDeleteModal({{ $user->id }})"
                                               class="cursor-pointer w-full text-left flex items-center px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                               role="menuitem">
                                            <i class="fas fa-user-minus mr-3 text-red-400"></i>
                                            Delete User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-neutral-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl mb-3 text-neutral-400 dark:text-neutral-600"></i>
                                <p>No users found</p>
                                @if($search || $userType || $status)
                                    <button wire:click="clearFilters" class="mt-2 text-sm text-blue-500 hover:underline">
                                        Clear all filters
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="flex flex-col sm:flex-row p-4 justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                of {{ $users->total() }} items
            </div>

            <div class="flex items-center gap-2">
                <!-- First Page Link -->
                <button wire:click="gotoPage(1)"
                        @if($users->currentPage() === 1) disabled @endif
                        class="px-3 @unless($users->currentPage() === 1) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $users->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-left"></i>
                </button>

                <!-- Previous Page Link -->
                <button wire:click="previousPage"
                        @if($users->onFirstPage()) disabled @endif
                        class="px-3 @unless($users->onFirstPage()) cursor-pointer @endunless py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $users->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-left"></i>
                </button>

                <!-- Page Number Input -->
                <div class="flex items-center gap-1">
                    <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                    <input type="number" min="1" max="{{ $users->lastPage() }}"
                           value="{{ $users->currentPage() }}" id="pageInput"
                           wire:keydown.enter="gotoPage($event.target.value)"
                           wire:blur="gotoPage($event.target.value)"
                           class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">

                    <span class="text-neutral-600 dark:text-neutral-400">of {{ $users->lastPage() }}</span>
                </div>

                <!-- Next Page Link -->
                <button wire:click="nextPage"
                        @unless($users->hasMorePages()) disabled @endunless
                        class="px-3 @if($users->hasMorePages()) cursor-pointer @endif py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$users->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-right"></i>
                </button>

                <!-- Last Page Link -->
                <button wire:click="gotoPage({{ $users->lastPage() }})"
                        @if($users->currentPage() === $users->lastPage()) disabled @endif
                        class="px-3 py-1 @unless($users->currentPage() === $users->lastPage()) cursor-pointer @endunless rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $users->currentPage() === $users->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Block Modal -->
    @if($showBlockModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="$wire.closeModals()" class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                    <i class="fas fa-user-lock text-orange-500 mr-2"></i>Block User {{ $selectedUserName }}
                </h3>
                <p class="mb-4 text-neutral-600 dark:text-neutral-400">
                    Are you sure you want to block this user? They will not be able to access their account until unblocked.
                </p>
                <div class="mb-4">
                    <label for="block-reason" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Block Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="blockReason" id="block-reason" rows="3" 
                              class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200"
                              placeholder="Please specify why this user is being blocked"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModals" class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button wire:click="blockUser"
                            class="cursor-pointer px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Block User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Unblock Modal -->
    @if($showUnblockModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="$wire.closeModals()" class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                    <i class="fas fa-lock-open text-green-500 mr-2"></i>Unblock User {{ $selectedUserName }}
                </h3>
                <p class="mb-4 text-neutral-600 dark:text-neutral-400">
                    Are you sure you want to unblock this user? They will regain access to their account.
                </p>
                @if($currentBlockReason)
                <div class="mb-4 p-3 bg-neutral-100 dark:bg-neutral-700 rounded-lg">
                    <h4 class="text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-1">
                        Current Block Reason:
                    </h4>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $currentBlockReason }}
                    </p>
                </div>
                @endif
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModals" class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button wire:click="unblockUser" 
                            class="cursor-pointer px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        Unblock User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="$wire.closeModals()" class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete User {{ $selectedUserName }}
                </h3>
                <p class="mb-2 text-neutral-600 dark:text-neutral-400">
                    Are you sure you want to delete this user? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModals" class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button wire:click="deleteUser" 
                            class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>