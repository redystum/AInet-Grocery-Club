@extends('pages.layouts.admin')

@section('title', ' - User Management')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">User Management</h1>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Manage your users, view their details, and perform actions on accounts.
            </div>

            <div class="flex gap-3">
                <a href="{{ route("board.users.create") }}"
                   class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Add New User
                </a>
            </div>
        </div>

        <livewire:users-table />
    </div>
@endsection
