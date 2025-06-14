@extends('pages.layouts.admin')

@section('title', ' - Profile')

@section('content')
    @use('App\Models\Order')
    @use('App\Models\User')
    <div class="container mx-auto px-4 py-8 max-w-7xl" x-data="{
        showBlockModal: false, 
        showUnblockModal: false, 
        showDeleteModal: false, 
        blockReason: '' 
    }">
        <!-- Admin Toolbar -->
        <div class="mb-6 rounded-xl shadow-sm p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <a href="{{ route('board.users.index') }}"
                   class="cursor-pointer flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users List
                </a>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('board.users.edit', $user->id) }}"
                       class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="fas fa-user-edit mr-2"></i> Edit User
                    </a>
                    @if($user->type == User::TYPE_MEMBER || $user->type == User::TYPE_PENDING_MEMBER)
                        @if($user->blocked)
                            <button @click="showUnblockModal = true" type="button"
                                    class="cursor-pointer px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center">
                                <i class="fas fa-user-check mr-2"></i> Unblock User
                            </button>
                        @else
                            <button @click="showBlockModal = true" type="button"
                                    class="cursor-pointer px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors flex items-center">
                                <i class="fas fa-user-lock mr-2"></i> Block User
                            </button>
                        @endif
                    @endif

                    @if($user->deleted_at == null && $user->id != auth()->user()->id)
                        <button @click="showDeleteModal = true" type="button"
                                class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center">
                            <i class="fas fa-user-slash mr-2"></i> Delete User
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <!-- Profile Header Section with Status Badge -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6 border border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <!-- Profile Image -->
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-md flex-shrink-0">
                        <img src="{{ $user->getImage() }}"
                             alt="Profile" class="w-full h-full object-cover">
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $user->name }}</h1>
                                </div>
                                <p class="text-neutral-600 dark:text-neutral-400 mt-1">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                                    <i class="fas fa-crown mr-1"></i>
                                    Joined {{ $user->created_at->diffForHumans(['parts' => 2, 'short' => true]) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Details Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Account Details -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 border border-neutral-200 dark:border-neutral-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                            <i class="fas fa-user-circle mr-2 text-blue-600 dark:text-blue-400"></i>Account Details
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Account Type</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200">
                                {{ ucwords(str_replace('_', ' ',$user->type)) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Status</span>
                            @if($user->blocked)
                                <span class="font-medium text-red-600 dark:text-red-400">Blocked</span>
                            @elseif($user->deleted_at != null)
                                <span class="font-medium text-red-600 dark:text-red-400">Deleted</span>
                            @elseif($user->type == User::TYPE_PENDING_MEMBER)
                                <span class="font-medium text-yellow-600 dark:text-yellow-400">Pending</span>
                            @else
                                <span class="font-medium text-green-600 dark:text-green-400">Active</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Gender</span>
                            <span
                                    class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->gender == "M" ? "Male" : "Female" }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">NIF</span>
                            <span
                                    class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->nif ?? "Not Defined" }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Default Delivery</span>
                            <span
                                    class="font-medium text-right max-w-xs text-neutral-800 dark:text-neutral-200">{{ $user->default_delivery_address ?? "Not Defined" }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Payment Method</span>
                            <span
                                    class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->default_payment_type ?? "Not Defined" }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">@if($user->cvv)
                                    Card number
                                @else
                                    Payment Reference
                                @endif</span>
                            <span
                                    class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->default_payment_reference ?? "Not Defined" }}</span>
                        </div>
                        @if($user->cvv)
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">CVV</span>
                                <span
                                        class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->cvv }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Virtual Card Section -->
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 border border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">
                        <i class="fas fa-credit-card mr-2 text-blue-600 dark:text-blue-400"></i>Virtual Card
                    </h2>

                    @if($user->card)
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-12 -mr-12 z-0"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -mb-8 -ml-8 z-0"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm opacity-80">{{ $user->card->nickname ?? "Current" }}
                                        Balance</p>
                                    <p class="text-2xl font-bold">€{{ number_format($user->card->balance, 2) }}</p>
                                </div>
                                @if($user->card->deleted_at == null)
                                    <div
                                            class="bg-white dark:bg-neutral-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">
                                        ACTIVE
                                    </div>
                                @else
                                    <div
                                            class="bg-white dark:bg-neutral-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">
                                        DELETED
                                    </div>
                                @endif
                            </div>
                            @if($lastOrder != null)
                                <div class="mt-6">
                                    <p class="text-sm opacity-80">Last Transaction</p>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="font-medium">{{ $lastOrder->items_count }}
                                            Item{{ $lastOrder->items_count > 1 ? "s":"" }}</p>
                                        <p class="font-bold">-€{{ number_format($lastOrder->total, 2) }}</p>
                                    </div>
                                    <p class="text-xs opacity-70 mt-1"
                                       title="{{ $lastOrder->created_at }}">{{ $lastOrder->created_at->diffForHumans(['parts' => 2, 'short' => true]) }}</p>
                                </div>
                            @else
                                <div class="my-6">
                                    <p class="text-sm opacity-80">No transactions yet</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('board.users.transactions', $user->id) }}"
                               class="cursor-pointer ml-auto px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg transition-colors">
                                <i class="fas fa-history mr-2"></i> View All Transactions
                            </a>
                        </div>

                    @else
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-12 -mr-12 z-0"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -mb-8 -ml-8 z-0"></div>
                            <div class="flex flex-col items-center text-center py-8 space-y-4 relative z-10">
                                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-2">
                                    <i class="fas fa-credit-card text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold">No Virtual Card Yet</h3>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Purchases Section -->
            @if($lastOrder != null)
                <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 border border-neutral-200 dark:border-neutral-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">
                            <i class="fas fa-shopping-bag mr-2 text-blue-600 dark:text-blue-400"></i>Recent Purchases
                        </h2>
                        <a href="{{ route('orders') }}"
                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

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
                                    Order #
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Items
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody
                                    class="bg-neutral-50 dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach($user->lastOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $order->items_count }} item{{ $order->items_count > 1 ? "s":"" }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        €{{ number_format($order->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->status == Order::STATUS_COMPLETED)
                                            <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                                <i class="fas fa-check mr-1"></i> Delivered
                                            </span>
                                        @elseif($order->status == Order::STATUS_PENDING)
                                            <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @elseif($order->status == Order::STATUS_CANCELED)
                                            <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                                <i class="fas fa-times mr-1"></i> Canceled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        <a href="{{ route('orders.receipt', $order->id) }}"><i
                                                    class="fas fa-receipt mr-1"></i>
                                            Receipt</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        @if($user->type == User::TYPE_MEMBER || $user->type == User::TYPE_PENDING_MEMBER)
            <!-- Block Modal -->
            <div x-show="showBlockModal"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div @click.outside="showBlockModal = false"
                     class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                            <i class="fas fa-user-lock text-orange-500 mr-2"></i>Block User {{ $user->name }}
                        </h3>
                        <p class="mb-4 text-neutral-600 dark:text-neutral-400">
                            Are you sure you want to block this user? They will not be able to access their account
                            until unblocked.
                        </p>
                        <form action="{{ route('board.users.block', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="block-reason"
                                       class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Block Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea x-model="blockReason" name="reason" id="block-reason" rows="3"
                                          class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200"
                                          placeholder="Please specify why this user is being blocked"
                                          required></textarea>
                            </div>
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showBlockModal = false"
                                        class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="cursor-pointer px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors"
                                        :disabled="!blockReason.trim()">
                                    Block User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Unblock Modal -->
            <div x-show="showUnblockModal"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div @click.outside="showUnblockModal = false"
                     class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                            <i class="fas fa-lock-open text-green-500 mr-2"></i>Unblock User {{ $user->name }}
                        </h3>
                        <p class="mb-4 text-neutral-600 dark:text-neutral-400">
                            Are you sure you want to unblock this user? They will regain access to their account.
                        </p>
                        @if($user->block_reason)
                            <div class="mb-4 p-3 bg-neutral-100 dark:bg-neutral-700 rounded-lg">
                                <h4 class="text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-1">
                                    Current Block Reason:
                                </h4>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $user->block_reason }}
                                </p>
                            </div>
                        @endif
                        <form action="{{ route('board.users.unblock', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showUnblockModal = false"
                                        class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="cursor-pointer px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                    Unblock User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if($user->id != auth()->user()->id)
            <!-- Delete Modal -->
            <div x-show="showDeleteModal"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div @click.outside="showDeleteModal = false"
                     class="bg-white dark:bg-neutral-800 rounded-lg max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete User {{ $user->name }}
                        </h3>
                        <p class="mb-2 text-neutral-600 dark:text-neutral-400">
                            Are you sure you want to delete this user? This action cannot be undone.
                        </p>
                        <form action="{{ route('board.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showDeleteModal = false"
                                        class="cursor-pointer px-4 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add x-cloak style to hide modals by default -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection