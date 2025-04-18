{{-- filepath: resources/views/pages/products.blade.php --}}
@extends('layout')

@section('title', ' - Products Catalog')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Carrossel de Destaques --}}
    <div id="carousel" class="relative overflow-hidden rounded-lg mb-8">
        <div id="carousel-items" class="flex transition-transform duration-500 ease-in-out">
            <img src="{{ asset('storage/products/highlight1.jpg') }}" alt="Highlight 1" class="w-full h-64 object-cover">
            <img src="{{ asset('storage/products/highlight2.jpg') }}" alt="Highlight 2" class="w-full h-64 object-cover">
            <img src="{{ asset('storage/products/highlight3.jpg') }}" alt="Highlight 3" class="w-full h-64 object-cover">
        </div>
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button onclick="moveCarousel(0)" class="w-3 h-3 bg-gray-300 rounded-full"></button>
            <button onclick="moveCarousel(1)" class="w-3 h-3 bg-gray-300 rounded-full"></button>
            <button onclick="moveCarousel(2)" class="w-3 h-3 bg-gray-300 rounded-full"></button>
        </div>
    </div>

    {{-- Layout Principal --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Barra Lateral --}}
        <div class="col-span-1 sticky top-4">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">Categories</h2>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                        <li>
                            <a href="#{{ Str::slug($category->name) }}" 
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $category->name }} ({{ $category->products->count() }})
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- Filtros Avançados --}}
                <form method="GET" action="{{ route('products.index') }}" class="mt-6 space-y-4">
                    <div>
                        <input type="text" name="search" placeholder="Search products..."
                               value="{{ request('search') }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400">
                    </div>
                    <div>
                        <select name="price_range" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100">
                            <option value="">Select Price Range</option>
                            <option value="0-10" {{ request('price_range') == '0-10' ? 'selected' : '' }}>€0 - €10</option>
                            <option value="10-20" {{ request('price_range') == '10-20' ? 'selected' : '' }}>€10 - €20</option>
                            <option value="20-50" {{ request('price_range') == '20-50' ? 'selected' : '' }}>€20 - €50</option>
                        </select>
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        {{-- Produtos --}}
        <div class="col-span-3">
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div id="{{ Str::slug($category->name) }}" class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-200 mb-4">{{ $category->name }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($category->products as $product)
                                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
                                    <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h3>
                                    <p class="text-gray-600 dark:text-neutral-400">€{{ $product->price }}</p>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View Details</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

{{-- Botão "Voltar ao Topo" --}}
<button id="back-to-top" title="Back to Top" class="fixed bottom-4 right-4 bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-700 transition">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
    // Carrossel de Destaques
    let currentSlide = 0;
    const carouselItems = document.getElementById('carousel-items');

    function moveCarousel(slide) {
        currentSlide = slide;
        carouselItems.style.transform = `translateX(-${slide * 100}%)`;
    }

    // Botão "Voltar ao Topo"
    const backToTopButton = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
@endsection