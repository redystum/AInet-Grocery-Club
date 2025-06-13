@extends('pages.layouts.admin')

@section('title', ' - Admin Settings')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Admin Settings</h1>
        </div>

        <livewire:admin-settings />
    </div>
@endsection