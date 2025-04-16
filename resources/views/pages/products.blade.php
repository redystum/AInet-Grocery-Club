{{-- filepath: resources/views/pages/products.blade.php --}}
@extends('layout')

@section('title', ' - Products Catalog')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-6">Products Catalog</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {{-- Aqui você pode iterar sobre os produtos --}}
        @foreach($products as $product)
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4">
            <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h2>
            <p class="text-gray-600 dark:text-neutral-400">€{{ $product->price }}</p>
            <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View Details</a>
        </div>
        @endforeach
    </div>
</div>
@endsection