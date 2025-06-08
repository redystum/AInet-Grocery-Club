<!-- Footer Section -->
<footer class="bg-neutral-800 text-neutral-200 py-12 mt-28">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Column -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ config('app.name') }}</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-neutral-300 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-neutral-300 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-neutral-300 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white">Shop</a></li>
                    <li><a href="#" class="hover:text-white">About Us</a></li>
                    <li><a href="#" class="hover:text-white">Contact</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Customer Service</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white">FAQs</a></li>
                    <li><a href="#" class="hover:text-white">Shipping Policy</a></li>
                    <li><a href="#" class="hover:text-white">Returns & Refunds</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Contact Us</h4>
                <ul class="space-y-2">
                   <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                        <span>Serviços Centrais, Rua General Norton de Matos, Apartado 4133, 2411-901 Leiria – Portugal</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-location-dot mt-1 mr-3"></i>
                        <span>GPS 39°44'15.1"N 8°48'40.8"W</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3"></i>
                       <a href="tel:+351244830010" class="hover:text-white">(+351) 244830010</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3"></i>
                       <a href="mailto:ipleiria@ipleiria.pt" class="hover:text-white">ipleiria@ipleiria.pt</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-neutral-700 mt-8 pt-8 text-center text-neutral-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>