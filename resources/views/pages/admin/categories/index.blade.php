@extends('pages.layouts.admin')

@section('title', 'Categories Management')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Categories</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage your categories</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route("board.categories.create") }}"
                   class="px-4 py-2 border bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-boxes-stacked mr-2"></i> Add New Category
                </a>
            </div>
        </div>

        @livewire('category-manager')
    </div>
@endsection
