@extends('layout')

@section('title', ' - Profile')

@section('content')
    @use('App\Models\Order')

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Profile Header Section -->
        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Profile Image -->
                <div
                        class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-md">

                    <img src="{{ $user->getImage() }}"
                         alt="Profile" class="w-full h-full object-cover">
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $user->name }}</h1>
                            <p class="text-neutral-600 dark:text-neutral-400">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                    class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                                <i class="fas fa-crown mr-1"></i>
                                Joined {{ $user->created_at->diffForHumans(['parts' => 2, 'short' => true]) }}
                            </span>
                            @if(auth()->user()->isEmployee())
                                <a href="{{ route('profile.edit') }}"
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i> Change Password
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}"
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i> Edit Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @unless(auth()->user()->isEmployee())

            @if(session('success'))
                <div
                        class="mb-6 p-4 rounded-xl border border-green-200 dark:border-green-800/50 bg-gradient-to-br from-green-50/70 to-green-100/30 dark:from-green-900/20 dark:to-green-900/10 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-check-circle text-green-500 dark:text-green-400 fa-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-green-800 dark:text-green-200">
                                Success!
                            </h3>
                            <div class="mt-1 text-green-700 dark:text-green-300">
                                <p>{{ session('success') }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            <!-- User Details Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Account Details -->
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Account Details</h2>
                        <a href="{{ route('profile.edit') }}"
                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
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
                            <span class="text-neutral-600 dark:text-neutral-400">Payment Reference</span>
                            <span
                                    class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->default_payment_reference ?? "Not Defined" }}</span>
                        </div>
                    </div>
                </div>

                <!-- Virtual Card Section -->
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Virtual Card</h2>

                    @if($user->card)
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-12 -mr-12 z-0"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -mb-8 -ml-8 z-0"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm opacity-80">Current Balance</p>
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
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i> Add Funds
                            </button>
                            <button
                                    class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg transition-colors">
                                <i class="fas fa-history mr-2"></i> View All
                            </button>
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
                                <p class="text-sm opacity-80 max-w-sm">Create a virtual card to make payments easier and
                                    track your purchases in one place.</p>
                                <a href="{{ route('card.create') }}"
                                   class="mt-2 px-6 py-2 bg-white dark:bg-neutral-100 text-blue-700 dark:text-blue-600 rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-neutral-200 font-medium">
                                    <i class="fas fa-plus-circle mr-2"></i>Create Card
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Purchases Section -->
            @if($lastOrder != null)
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Recent Purchases</h2>
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
        @else
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="text-center">
                    <p class="text-neutral-800 dark:text-neutral-100">Hi {{ $user->name }}, </p>
                    <p class="text-neutral-800 dark:text-neutral-100">You are logged in as an employee.</p>
                    <p class="text-neutral-800 dark:text-neutral-100">Which means you don't have a profile page. If you
                        need to update any of your information please talk with your superior.</p>
                    <p class="text-neutral-800 dark:text-neutral-100">Thanks!</p>
                </div>
            </div>
        @endunless
    </div>
@endsection
