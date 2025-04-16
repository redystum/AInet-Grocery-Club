{{-- filepath: resources/views/pages/products.blade.php --}}
@extends('layout')

@section('title', ' - Products Catalog')

@section('content')
<style>
    /* Estilos gerais */
    body {
        font-family: Arial, sans-serif;
    }

    /* Carrossel de destaques */
    #carousel {
        position: relative;
        overflow: hidden;
        height: 300px;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    #carousel-items {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    #carousel-items img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .carousel-indicators {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
    }

    .carousel-indicators button {
        width: 10px;
        height: 10px;
        background-color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0.7;
    }

    .carousel-indicators button.active {
        opacity: 1;
        background-color: #1d4ed8;
    }

    /* Barra lateral de categorias */
    .category-link {
        transition: color 0.3s ease-in-out;
    }

    .category-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    /* Estilos para os cards de produtos */
    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .product-card img {
        transition: transform 0.3s ease-in-out;
    }

    .product-card:hover img {
        transform: scale(1.1);
    }

    /* Botão "Voltar ao Topo" */
    #back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #1d4ed8;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease-in-out;
    }

    #back-to-top:hover {
        background-color: #2563eb;
    }
</style>

<div class="container mx-auto px-4 py-8">
    {{-- Carrossel de Destaques --}}
    <div id="carousel">
        <div id="carousel-items">
            <img src="{{ asset('storage/products/highlight1.jpg') }}" alt="Highlight 1">
            <img src="{{ asset('storage/products/highlight2.jpg') }}" alt="Highlight 2">
            <img src="{{ asset('storage/products/highlight3.jpg') }}" alt="Highlight 3">
        </div>
        <div class="carousel-indicators">
            <button onclick="moveCarousel(0)" class="active"></button>
            <button onclick="moveCarousel(1)"></button>
            <button onclick="moveCarousel(2)"></button>
        </div>
    </div>

    {{-- Filtros e Barra Lateral --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="col-span-1">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">Categories</h2>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="#{{ Str::slug($category->name) }}" 
                           class="category-link text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            {{ $category->name }} ({{ $category->products->count() }})
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Filtros Avançados --}}
            <form method="GET" action="{{ route('products.index') }}" class="mt-6">
                <input type="text" name="search" placeholder="Search products..."
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <select name="price_range" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 mt-4">
                    <option value="">Select Price Range</option>
                    <option value="0-10">€0 - €10</option>
                    <option value="10-20">€10 - €20</option>
                    <option value="20-50">€20 - €50</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg mt-4">
                    Apply Filters
                </button>
            </form>
        </div>

        {{-- Produtos agrupados por categorias --}}
        <div class="col-span-3">
            @foreach($categories as $category)
                <div id="{{ Str::slug($category->name) }}" class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-200 mb-4">{{ $category->name }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($category->products as $product)
                            <div class="product-card bg-white dark:bg-neutral-800 rounded-lg shadow-md p-4">
                                <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h3>
                                <p class="text-gray-600 dark:text-neutral-400">€{{ $product->price }}</p>
                                <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View Details</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Botão "Voltar ao Topo" --}}
<button id="back-to-top" title="Back to Top">↑</button>

<script>
    // Carrossel de Destaques
    let currentSlide = 0;
    const carouselItems = document.getElementById('carousel-items');
    const indicators = document.querySelectorAll('.carousel-indicators button');

    function moveCarousel(slide) {
        currentSlide = slide;
        carouselItems.style.transform = `translateX(-${slide * 100}%)`;
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === slide);
        });
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