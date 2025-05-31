@extends('layout')

@section('content')
    <div class="relative h-[70vh] min-h-[600px] overflow-hidden gourmet-carousel-container">
        <div class="flex h-full transition-transform duration-700 ease-in-out gourmet-carousel-slides">
            <!-- Slide 1 -->
            <div class="flex-shrink-0 w-full h-full relative gourmet-carousel-slide">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" alt="Artisanal Food Selection"
                     class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-1/3 z-20"></div>
                <div class="absolute inset-0 bg-black/30 z-10"></div>
                <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 text-center z-30 text-white w-full px-4 max-w-4xl">
                    <h1 class="text-4xl md:text-5xl font-light mb-4">Curated Provisions for Discerning Tastes</h1>
                    <p class="text-xl opacity-90 mb-8">Discover exceptional quality from small producers who share
                        our passion for authentic flavors</p>
                    <a href="{{ route('products.index') }}"
                       class="cursor-pointer z-50 inline-block px-8 py-3 bg-white text-neutral-800 hover:bg-neutral-100 rounded-full font-medium">Explore
                        Selection</a>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="flex-shrink-0 w-full h-full relative gourmet-carousel-slide">
                <img src="https://images.unsplash.com/photo-1606787366850-de6330128bfc" alt="Fresh Organic Produce"
                     class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-1/3 z-20"></div>
                <div class="absolute inset-0 bg-black/30 z-10"></div>
                <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 text-center z-30 text-white w-full px-4 max-w-4xl">
                    <h1 class="text-4xl md:text-5xl font-light mb-4">Season's Finest, Direct to Your Kitchen</h1>
                    <p class="text-xl opacity-90 mb-8">Harvested at peak perfection, delivered with care</p>
                    <a href="{{ route('products.index') }}"
                       class="cursor-pointer z-50 inline-block px-8 py-3 border-2 border-white text-white hover:bg-white/10 rounded-full font-medium">View
                        Seasonal Picks</a>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="flex-shrink-0 w-full h-full relative gourmet-carousel-slide">
                <img src="https://images.unsplash.com/photo-1605000797499-95a51c5269ae" alt="Artisan Cheesemaking"
                     class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-1/3 z-20"></div>
                <div class="absolute inset-0 bg-black/30 z-10"></div>
                <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 text-center z-30 text-white w-full px-4 max-w-4xl">
                    <h1 class="text-4xl md:text-5xl font-light mb-4">Crafted With Generations of Knowledge</h1>
                    <p class="text-xl opacity-90 mb-8">Traditional methods that modern agriculture has forgotten</p>
                    <a href="{{ route('products.index') }}"
                       class="cursor-pointer z-50 inline-block px-8 py-3 bg-white text-neutral-800 hover:bg-neutral-100 rounded-full font-medium">Meet
                        the Makers</a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-2 z-30 gourmet-carousel-indicators">
            <button class="cursor-pointer w-3 h-3 rounded-full bg-white transition-opacity duration-300 gourmet-carousel-indicator active"></button>
            <button class="cursor-pointer w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300 gourmet-carousel-indicator"></button>
            <button class="cursor-pointer w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300 gourmet-carousel-indicator"></button>
        </div>
    </div>


    <!-- Brand Promise -->
    <section class="py-20 bg-white dark:bg-neutral-800">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <span class="text-green-600 dark:text-green-400 font-medium tracking-wider">OUR APPROACH</span>
                <h2 class="text-3xl md:text-4xl font-light mt-2 mb-6 text-neutral-800 dark:text-neutral-100">Quality
                    Without Compromise</h2>
                <p class="text-neutral-600 dark:text-neutral-400 text-lg max-w-3xl mx-auto">We partner directly with
                    small-scale producers who share our commitment to traditional methods, sustainable practices,
                    and exceptional flavor.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 max-w-6xl mx-auto">
                <div class="text-center">
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-leaf text-3xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-3 text-neutral-800 dark:text-neutral-100">Direct from
                        Producers</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">We eliminate unnecessary middlemen to bring
                        you the freshest products at fair prices.</p>
                </div>
                <div class="text-center">
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-seedling text-3xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-3 text-neutral-800 dark:text-neutral-100">Seasonal
                        Selection</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">Our offerings change with the seasons to
                        ensure peak flavor and quality.</p>
                </div>
                <div class="text-center">
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-circle text-3xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-3 text-neutral-800 dark:text-neutral-100">Rigorously
                        Vetted</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">Every product meets our exacting standards for
                        quality and production methods.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-12 bg-neutral-50 dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-light text-neutral-800 dark:text-neutral-100">Featured This Week</h2>
                <a href="{{ route('products.index') }}"
                   class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium flex items-center">
                    View All Products
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                @forelse($topProducts as $product)
                    <x-product-card :product="$product"/>
                @empty
                    <p class="text-center text-neutral-600 dark:text-neutral-400 col-span-full">No featured products
                        available at this time.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Producer Spotlight -->
    <section class="py-20 bg-white dark:bg-neutral-800">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <span class="text-green-600 dark:text-green-400 font-medium">MEET THE MAKERS</span>
                <h2 class="text-3xl md:text-4xl font-light mt-2 mb-6 text-neutral-800 dark:text-neutral-100">The
                    Hands Behind Your Food</h2>
                <p class="text-neutral-600 dark:text-neutral-400 text-lg">We're proud to introduce you to the
                    passionate producers who create the exceptional foods we carry.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
                <div class="order-1 md:order-none">
                    <div class="aspect-w-16 aspect-h-9 bg-neutral-100 dark:bg-neutral-700 rounded-xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1605000797499-95a51c5269ae"
                             alt="Cheesemaker at work" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <span class="text-green-600 dark:text-green-400 font-medium">ARTISAN CHEESEMAKER</span>
                    <h3 class="text-2xl font-light mt-2 mb-4 text-neutral-800 dark:text-neutral-100">The Pastures
                        Dairy</h3>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">For three generations, the Henderson
                        family has been crafting small-batch cheeses using traditional methods and milk from their
                        own grass-fed herd. Each wheel is aged to perfection in their underground caves.</p>
                    <a href="{{ route("products.index") }}"
                       class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">
                        Explore Products
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Showcase -->
    <section class="py-12 bg-neutral-50 dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-light text-neutral-800 dark:text-neutral-100 mb-8 text-center">Explore Our
                Collections</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                @forelse($topCategories as $category)
                    <a href="{{ route('products.category', $category->id) }}"
                       class="group relative rounded-xl overflow-hidden aspect-square">
                        <img src="{{ $category->getImage() }}" alt="{{ $category->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <h3 class="text-xl font-medium mb-1">{{ $category->name }}</h3>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-neutral-600 dark:text-neutral-400 col-span-full">
                        No categories available at this time.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-green-600 dark:bg-green-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-light mb-6">Experience the Difference</h2>
            <p class="text-lg text-white/90 max-w-2xl mx-auto mb-8">Join our community of customers who appreciate
                food as it's meant to be—thoughtfully sourced, carefully selected, and delivered with care.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                   class="px-8 py-3 bg-white text-green-700 hover:bg-neutral-100 transition-colors rounded-full font-medium">Shop
                    Now</a>
                <a href="#"
                   class="px-8 py-3 border-2 border-white text-white hover:bg-white/10 transition-colors rounded-full font-medium">Our
                    Story</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Carousel functionality
            const carouselContainer = document.querySelector('.gourmet-carousel-container');
            const carouselSlides = document.querySelector('.gourmet-carousel-slides');
            const slides = document.querySelectorAll('.gourmet-carousel-slide');
            const indicators = document.querySelectorAll('.gourmet-carousel-indicator');
            let currentIndex = 0;
            let interval;

            // Initialize carousel
            function initCarousel() {
                // Set initial active slide
                updateCarousel();
                // Start auto-rotation
                startAutoRotation();
                // Add event listeners to indicators
                addIndicatorListeners();
            }

            // Update carousel position and active indicators
            function updateCarousel() {
                const offset = -currentIndex * 100;
                carouselSlides.style.transform = `translateX(${offset}%)`;

                indicators.forEach((indicator, index) => {
                    if (index === currentIndex) {
                        indicator.classList.remove('opacity-50');
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('active');
                    }
                });
            }

            // Start auto-rotation
            function startAutoRotation() {
                clearInterval(interval); // Clear any existing interval
                interval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % slides.length;
                    updateCarousel();
                }, 7000); // Rotate every 7 seconds
            }

            // Add click event listeners to indicators
            function addIndicatorListeners() {
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        clearInterval(interval);
                        currentIndex = index;
                        updateCarousel();
                        startAutoRotation();
                    });
                });
            }

            // Pause on hover
            carouselContainer.addEventListener('mouseenter', () => {
                clearInterval(interval);
            });

            // Resume on mouse leave
            carouselContainer.addEventListener('mouseleave', () => {
                startAutoRotation();
            });

            // Initialize the carousel
            initCarousel();
        });
    </script>
@endsection