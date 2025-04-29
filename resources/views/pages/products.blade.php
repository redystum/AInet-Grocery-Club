@extends('pages.layouts.public')

@section('title', ' - Products Catalog')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Hero Carousel -->
        <div class="relative rounded-xl overflow-hidden shadow-md mb-8 h-80" id="carousel">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-20"></div>
            <div class="flex h-full transition-transform duration-500 ease-in-out">
                <div class="flex-shrink-0 w-full h-full carouselSlide">
                    <img src="{{ asset('storage/products/highlight1.jpg') }}" alt="Highlight 1"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-full h-full carouselSlide">
                    <img src="{{ asset('storage/products/highlight2.jpg') }}" alt="Highlight 2"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-shrink-0 w-full h-full carouselSlide">
                    <img src="{{ asset('storage/products/highlight3.jpg') }}" alt="Highlight 3"
                         class="w-full h-full object-cover">
                </div>
            </div>
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2" id="carouselIndicators">
                <button
                        class="w-3 h-3 rounded-full bg-white opacity-70 hover:opacity-100 transition-opacity focus:outline-none"></button>
                <button
                        class="w-3 h-3 rounded-full bg-white opacity-70 hover:opacity-100 transition-opacity focus:outline-none"></button>
                <button
                        class="w-3 h-3 rounded-full bg-white opacity-70 hover:opacity-100 transition-opacity focus:outline-none"></button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 sticky top-20">
                    <!-- Sorting Options -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-3">Sort By</h2>
                        <select id="sidebarSort"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300
                                       dark:border-neutral-700 focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100
                                       placeholder-gray-400 dark:placeholder-neutral-500">
                            <option value="discount_desc" {{ request('sort') === 'discount_desc' ? 'selected' : '' }}>
                                Best Discounts
                            </option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name
                                (Z-A)
                            </option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price (Low
                                to High)
                            </option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price
                                (High to Low)
                            </option>
                        </select>
                    </div>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-3">Categories</h2>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" data-category=""
                                   class="flex items-center gap-3 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors categoryLink {{ !request('category') || request('category') === 'all' ? 'font-bold' : '' }}">
                                    <div
                                            class="w-8 h-8 rounded-full overflow-hidden bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                                        <i class="fas fa-boxes text-neutral-500"></i>
                                    </div>
                                    <span class="flex-1">All Products</span>
                                    <span
                                            class="bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 text-xs px-2 py-1 rounded-full">
                                        {{ $totalProducts }}
                                    </span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="#" data-category="{{ $category->id }}"
                                       class="flex items-center gap-3 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors categoryLink {{ request('category') == $category->id ? 'font-bold' : '' }}">
                                        <div
                                                class="w-8 h-8 rounded-full overflow-hidden bg-neutral-200 dark:bg-neutral-700">
                                            <img src="{{ asset('storage/categories/' . $category->image) }}"
                                                 alt="{{ $category->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <span class="flex-1">{{ $category->name }}</span>
                                        <span
                                                class="bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 text-xs px-2 py-1 rounded-full">
                                            {{ $category->products->count() }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Results Info -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-neutral-600 dark:text-neutral-400">
                        Showing <span
                                class="font-medium text-neutral-800 dark:text-neutral-200">{{ $products->firstItem() }}</span>
                        to
                        <span
                                class="font-medium text-neutral-800 dark:text-neutral-200">{{ $products->lastItem() }}</span>
                        of
                        <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $products->total() }}</span>
                        results
                    </div>
                </div>

                <!-- Products Display -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            <div
                                    class="product-card bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md relative group">
                                <!-- Wishlist Button (shown on hover) -->
                                <button
                                        class="absolute top-3 right-3 z-10 w-8 h-8 bg-white dark:bg-neutral-700 rounded-full flex items-center justify-center shadow-md hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-colors wishlist-btn opacity-0 group-hover:opacity-100"
                                        data-product-id="{{ $product->id }}">
                                    <i class="far fa-heart text-neutral-600 dark:text-neutral-300"></i>
                                </button>

                                <!-- Product Image -->
                                <div class="relative overflow-hidden h-48">
                                    <img src="{{ asset('storage/products/' . $product->photo) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                    <!-- Discount Badge -->
                                    @if($product->discount > 0)
                                        <div
                                                class="absolute top-3 left-3 bg-red-600 text-white px-2 py-1 rounded-md text-xs font-bold shadow-md">
                                            -{{ $product->discount }}%
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 mb-2">{{ $product->name }}</h3>

                                    <!-- Price Display -->
                                    <div class="flex items-center justify-between mb-3">
                                        @if($product->discount > 0)
                                            <div>
                                                <span
                                                        class="text-red-600 dark:text-red-400 font-bold text-lg">€{{ number_format($product->price * (1 - $product->discount/100), 2) }}</span>
                                                <span
                                                        class="ml-2 text-neutral-500 dark:text-neutral-400 text-sm line-through">€{{ number_format($product->price, 2) }}</span>
                                            </div>
                                        @else
                                            <span
                                                    class="text-blue-600 dark:text-blue-400 font-bold text-lg">€{{ number_format($product->price, 2) }}</span>
                                        @endif

                                        <span
                                                class="text-sm {{ $product->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex justify-between items-center">
                                        <a href="#"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                                            View Details
                                        </a>
                                        <button
                                                class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-full text-sm transition-colors add-to-cart-btn"
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-shopping-cart mr-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Custom Pagination -->
                    <div
                            class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-neutral-200 dark:border-neutral-700 pt-6">
                        <div class="text-sm text-neutral-600 dark:text-neutral-400">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                            of {{ $products->total() }} items
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- First Page Link -->
                            <a href="#" data-page="1"
                               class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <i class="fas fa-angle-double-left"></i>
                            </a>

                            <!-- Previous Page Link -->
                            <a href="#" data-page="{{ $products->currentPage() - 1 }}"
                               class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <i class="fas fa-angle-left"></i>
                            </a>

                            <!-- Page Number Input -->
                            <div class="flex items-center gap-1">
                                <span class="text-neutral-600 dark:text-neutral-400">Page</span>
                                <input type="number" min="1" max="{{ $products->lastPage() }}"
                                       value="{{ $products->currentPage() }}" id="pageInput"
                                       class="appearance-textfield w-12 px-2 py-1 text-center rounded-lg border border-gray-300
                                       dark:border-neutral-700 focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100
                                       placeholder-gray-400 dark:placeholder-neutral-500">
                                <span
                                        class="text-neutral-600 dark:text-neutral-400">of {{ $products->lastPage() }}</span>
                            </div>

                            <!-- Next Page Link -->
                            <a href="#" data-page="{{ $products->currentPage() + 1 }}"
                               class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ !$products->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <i class="fas fa-angle-right"></i>
                            </a>

                            <!-- Last Page Link -->
                            <a href="#" data-page="{{ $products->lastPage() }}"
                               class="paginationLink px-3 py-1 rounded-lg border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors {{ $products->currentPage() === $products->lastPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl p-8 text-center">
                        <i class="fas fa-box-open text-4xl text-neutral-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-neutral-800 dark:text-neutral-200 mb-2">No products
                            found</h3>
                        <p class="text-neutral-600 dark:text-neutral-400 mb-4">Try adjusting your filters or search
                            criteria</p>
                        <a href="#" data-category="all" id="resetFilters"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg inline-block">
                            Reset Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop"
            class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 invisible">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script>
        // Carousel functionality
        const carousel = document.getElementById('carousel');
        const slides = document.getElementsByClassName('carouselSlide');
        const indicators = document.querySelectorAll('#carouselIndicators button');
        let currentIndex = 0;

        function updateCarousel() {
            const offset = -currentIndex * 100;
            carousel.querySelector('.flex').style.transform = `translateX(${offset}%)`;

            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.remove('opacity-70');
                } else {
                    indicator.classList.add('opacity-70');
                }
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
        });

        // Auto-rotate carousel
        setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateCarousel();
        }, 5000);

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);

            if (key !== 'page') {
                params.delete('page');
            }

            if (value) {
                params.set(key, value);
            } else {
                params.delete(key);
            }

            window.location.href = `${url.pathname}?${params.toString()}`;
        }

        document.getElementById('sidebarSort').addEventListener('change', function () {
            updateQueryParam('sort', this.value);
        });

        // Category link functionality
        document.querySelectorAll('.categoryLink').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                updateQueryParam('category', this.dataset.category);
            });
        });

        // Reset filters
        document.getElementById('resetFilters')?.addEventListener('click', function (e) {
            e.preventDefault();
            const url = new URL(window.location.href);
            window.location.href = `${url.pathname}?category=all&sort=discount_desc&page=1`;
        });

        // Pagination links functionality
        document.querySelectorAll('.paginationLink').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                if (!this.classList.contains('cursor-not-allowed')) {
                    updateQueryParam('page', this.dataset.page);
                }
            });
        });

        // Manual page input functionality
        document.getElementById('pageInput')?.addEventListener('change', function () {
            const page = this.value;
            const lastPage = parseInt("{{ $products->lastPage() }}");

            if (page < 1) {
                this.value = 1;
                return;
            }
            if (page > lastPage) {
                this.value = lastPage;
                return;
            }

            updateQueryParam('page', page);
        });
    </script>
@endsection