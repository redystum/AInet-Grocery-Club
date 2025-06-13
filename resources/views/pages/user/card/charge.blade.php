@extends('pages.layouts.public')

@section('title', ' - Charge Card')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Charge Card</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Add funds to your virtual payment card</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('card.index') }}"
                   class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Cards
                </a>
            </div>
        </div>

        <!-- Charge Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Payment Method Section -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Payment Method</h2>
                    <a href="{{ route('profile.edit') }}"
                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-edit mr-1"></i> Change
                    </a>
                </div>

                <!-- Current Payment Method Display -->
                <div id="payment-display" class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">Type</span>
                        <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->default_payment_type ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">@if($user->cvv)
                                Card Number
                            @else
                                Reference
                            @endif</span>
                        <span class="font-medium text-neutral-800 dark:text-neutral-200 @error('reference') !text-red-600 @enderror">{{ $user->default_payment_reference ?? 'N/A' }}</span>
                    </div>
                    @if($user->cvv)
                        <div class="flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">CVV</span>
                            <span class="font-medium text-neutral-800 dark:text-neutral-200 @error('reference') !text-red-600 @enderror">{{ $user->cvv }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">Name</span>
                        <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">Nif</span>
                        <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $user->nif ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Charge Details Section -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Charge Details</h2>

                <form x-data="{
                        amount: 150,
                        canChargeCard: {{ json_encode($canChargeCard) }}
                     }"
                      @submit.prevent="if (canChargeCard && amount >= 0) { $el.submit(); }"
                      action="{{ route('card.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">
                                Amount to Charge (€)
                            </label>
                            <div class="relative">
                                <input type="number" x-model.number="amount" name="amount" min="0" step="0.01"
                                       class="appearance-textfield w-full px-4 py-2 border @error('amount') border-red-500 @else border-neutral-300 dark:border-neutral-600 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200"
                                       placeholder="Amount">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-neutral-500 dark:text-neutral-400">€</span>
                                </div>
                            </div>
                            @error('amount')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    :disabled="!canChargeCard || amount <= 0"
                                    class="disabled:bg-blue-900 disabled:cursor-not-allowed disabled:text-neutral-500 cursor-pointer w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Charge Card
                            </button>
                            @unless($canChargeCard)
                                <p class="mt-2 text-sm text-red-500">You must have a valid payment method to charge your
                                    card.</p>
                                <a href="{{ route('profile.edit') }}"
                                   class="mt-1 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    Add Payment Method
                                </a>
                            @endunless
                            @error('reference')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-12 -mr-12 z-0"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -mb-8 -ml-8 z-0"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm opacity-80">{{ $user->card->nickname ?? "Current" }} Balance</p>
                                    <p class="text-2xl font-bold">
                                        €{{ number_format($user->card->balance, 2) }}
                                        <span x-show="amount > 0" class="text-sm opacity-80">
                                            → €<span x-text="({{ $user->card->balance }} + amount).toFixed(2)"></span>
                                        </span>
                                    </p></div>
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

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection