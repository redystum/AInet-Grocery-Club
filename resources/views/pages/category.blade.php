{{-- filepath: resources/views/pages/category.blade.php --}}
@extends('layout')

@section('title', ' - ' . $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Título --}}
    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-6">{{ $category->name }}</h1>

    {{-- Filtros de Ordenação --}}
    <div class="flex justify-end mb-4">
        <form method="GET" action="{{ route('products.category', $category->id) }}" class="flex items-center space-x-2">
            <label for="sort" class="text-sm font-medium text-gray-700 dark:text-neutral-300">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()"
                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100">
                <option value="discount_desc" {{ request('sort') == 'discount_desc' ? 'selected' : '' }}>Highest Discount</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>
        </form>
    </div>

    {{-- Produtos da Categoria --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
                <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h3>
                <p class="text-gray-600 dark:text-neutral-400">€{{ $product->price }}</p>
                <p class="text-green-600 dark:text-green-400 font-medium">-{{ $product->discount }}% Off</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View Details</a>
            </div>
        @endforeach
    </div>
</div>
@endsection