@extends('layout')

@section('title', ' - Card Transactions')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Card Transactions</h1>
                    <p class="text-neutral-600 dark:text-neutral-400">History of all your card movements</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-lg">
                        <span class="font-medium">Current Balance:</span>
                        <span class="font-bold">€{{ number_format($currentBalance, 2) }}</span>
                    </div>
                    <a href="{{ route('card.charge') }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Funds
                    </a>
                </div>
            </div>
        </div>

        <livewire:card-history/>
    </div>
@endsection
