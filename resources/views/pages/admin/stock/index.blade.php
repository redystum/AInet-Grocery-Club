@extends('pages.layouts.admin')

@section('title', 'Stock Management')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Stock Management</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage your product inventory and stock levels</p>
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center cursor-pointer">
                    <i class="fas fa-plus mr-2"></i> Add New Product
                </button>
                <a href="{{ route("board.restock.auto") }}"
                   class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-boxes-stacked mr-2"></i> Restock necessary
                </a>
            </div>
        </div>


        <livewire:stock-table />
    </div>

@endsection
