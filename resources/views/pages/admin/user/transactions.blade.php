@extends('pages.layouts.public')

@section('title', ' - Card Transactions')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <a href="{{ route('board.users.index') }}"
           class="cursor-pointer mb-6 flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users List
        </a>

        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Card Transactions of {{ $user->name }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-400">History of all your card movements</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-lg">
                        <span class="font-medium">Current Balance:</span>
                        <span class="font-bold">€{{ number_format($currentBalance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <livewire:card-history :user="$user" />
    </div>
@endsection
