{{-- filepath: resources/views/pages/products.blade.php --}}
@extends('layout')

@section('title', ' - Products Catalog')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Barra Lateral de Categorias --}}
        <div class="col-span-1">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">Categories</h2>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li class="flex items-center space-x-3">
                        @if($category->image)
                        <img src="{{ asset('storage/categories/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            class="w-8 h-8 rounded-full object-cover">
                        @endif
                        <a href="{{ route('products.category', $category->id) }}"
                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Produtos --}}
        <div class="col-span-3">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-6">Products Catalog</h1>

            {{-- Filtros de Ordenação --}}
            <div class="flex justify-between items-center mb-4">
                <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-2">
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

                {{-- Filtro de Produtos com Desconto --}}
                <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="discount_only" value="1">
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                        Show Discounts Only
                    </button>
                </form>
            </div>

            {{-- Lista de Produtos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
                    <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h3>
                    <p class="text-gray-600 dark:text-neutral-400">€{{ $product->price }}</p>
                    @if($product->discount > 0)
                    <p class="text-green-600 dark:text-green-400 font-medium">-{{ $product->discount }}% Off</p>
                    @endif
                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View Details</a>
                </div>
                @empty
                <p class="text-gray-600 dark:text-neutral-400">No products available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Botão "Voltar ao Topo" --}}
<button id="back-to-top" title="Back to Top" 
    class="fixed bottom-4 right-4 bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-700 transition">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
    // Mostrar/Esconder o botão "Voltar ao Topo"
    const backToTopButton = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });

    // Scroll suave para o topo
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
@endsection