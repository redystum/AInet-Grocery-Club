<nav class="bg-white dark:bg-neutral-900 shadow-lg sticky top-0 z-50 backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-8 w-8 rounded-full">
                        <span
                            class="ml-2 font-medium text-gray-900 dark:text-neutral-100">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation links -->
                <div class="hidden md:ml-10 md:flex items-center space-x-8">
                    <a href="#"
                       class="text-indigo-600 dark:text-indigo-400 px-3 py-2 text-sm font-medium border-b-2 border-indigo-600 dark:border-indigo-400">Home</a>
                    <a href="{{ route('products.index') }}"
                       class="text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition duration-300">Products</a>
                    <a href="#"
                       class="text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition duration-300">Contacts</a>
                    <a href="#"
                       class="text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition duration-300">About
                        us</a>
                </div>
            </div>

            <!-- Search bar and user dropdown -->
            <div class="flex items-center space-x-4">
                <div class="hidden lg:block relative">
                    <input type="text"
                           class="w-40 lg:w-56 pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400"
                           placeholder="Search...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 dark:text-neutral-500 text-sm"></i>
                </div>

                <div class="lg:hidden">
                    <button
                        class="p-2 text-gray-500 dark:text-neutral-400 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none"
                        id="mobileSearchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- User dropdown -->
                <div class="relative ml-2 hidden md:block">
                    <div class="flex items-center space-x-1 focus:outline-none cursor-pointer" id="userNav">
                        @guest
                            <img class="h-8 w-8 rounded-full flex items-center justify-center"
                                 src="{{ asset('storage/users/anonymous.png') }}" alt="Account"/>
                        @endguest
                        @auth
                            <img class="h-8 w-8 rounded-full flex items-center justify-center"
                                 @if(auth()->user()->photo)
                                     src="{{ asset('storage/users/' . auth()->user()->photo) }}"
                                 @else
                                     src="{{ asset('storage/users/anonymous.png') }}"
                                 @endif
                                 alt="Account"/>
                            <span
                                class="hidden md:inline text-gray-700 dark:text-neutral-300 text-sm font-medium">{{ auth()->user()->name }}</span>
                        @endauth
                        <i class="hidden md:inline fas fa-chevron-down text-xs text-gray-500 dark:text-neutral-400"></i>
                    </div>

                    <!-- Dropdown menu -->
                    <div id="dropdown"
                         class="absolute right-0 mt-2 w-56 origin-top-right bg-white dark:bg-neutral-800 rounded-xl shadow-lg ring-1 ring-indigo-300 dark:ring-indigo-700 ring-opacity-5 focus:outline-none transform opacity-0 scale-95 transition duration-200 ease-out z-10 pointer-events-none">
                        <div class="">
                            @auth
                                <a href="{{ route('profile') }}"
                                   class="flex items-center rounded-t-xl px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <i class="fas fa-user-circle mr-3 text-indigo-500 dark:text-indigo-400 w-4"></i>
                                    Profile
                                </a>
                                <a href="#"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <i class="fas fa-shopping-cart mr-3 text-indigo-500 dark:text-indigo-400 w-4"></i>
                                    Cart
                                </a>
                                <a href="{{ route("logout") }}"
                                   class="flex items-center rounded-b-xl px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <i class="fas fa-sign-out-alt mr-3 text-indigo-500 dark:text-indigo-400 w-4"></i>
                                    Sign out
                                </a>
                            @endauth

                            @guest
                                <a href="{{ route("login")  }}"
                                   class="flex items-center rounded-t-xl px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <i class="fas fa-sign-in-alt mr-3 text-indigo-500 dark:text-indigo-400 w-4"></i>
                                    Sign in
                                </a>
                                <a href="{{ route("register") }}"
                                   class="flex items-center rounded-b-xl px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <i class="fas fa-user-plus mr-3 text-indigo-500 dark:text-indigo-400 w-4"></i>
                                    Register
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center ml-2">
                <button
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    id="mobileMenuButton">
                    <span class="sr-only">Open main menu</span>
                    <span id="openMobileDropdown" class="block">
                        <i class="fas fa-bars h-6 w-6"></i>
                    </span>
                    <span id="closeMobileDropdown" class="hidden">
                        <i class="fas fa-times h-6 w-6"></i>
                    </span>
                </button>
            </div>
        </div>

        <!-- Mobile search bar -->
        <div class="hidden lg:hidden px-2 py-2" id="mobileSearchBar">
            <div class="relative">
                <input type="text"
                       class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400"
                       placeholder="Search...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 dark:text-neutral-500 text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="hidden md:hidden" id="mobileMenu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#"
               class="block px-3 py-2 rounded-md text-base font-medium text-white bg-indigo-600 dark:bg-indigo-700">Home</a>
            <a href="#"
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">Products</a>
            <a href="#"
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">Contacts</a>
            <a href="#"
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">About
                us</a>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-neutral-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    @auth
                        <img class="h-10 w-10 rounded-full"
                             src="{{ asset('storage/users/' . auth()->user()->photo) }}" alt="Account"/>
                    @else
                        <img class="h-10 w-10 rounded-full"
                             src="{{ asset('storage/users/anonymous.png') }}" alt="Account"/>
                    @endauth
                </div>
                <div class="ml-3">
                    @auth
                        <div
                            class="text-base font-medium text-gray-800 dark:text-neutral-100">{{ auth()->user()->name }}</div>
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-neutral-400">{{ auth()->user()->email }}</div>
                    @else
                        <div class="text-base font-medium text-gray-800 dark:text-neutral-100">Guest</div>
                        <div class="text-sm font-medium text-gray-500 dark:text-neutral-400">Sign in to continue</div>
                    @endauth
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                @auth
                    <a href="#"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">
                        <i class="fas fa-user-circle mr-3 text-indigo-500 dark:text-indigo-400"></i>
                        Your Profile
                    </a>
                    <a href="#"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">
                        <i class="fas fa-sign-out-alt mr-3 text-indigo-500 dark:text-indigo-400"></i>
                        Sign out
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">
                        <i class="fas fa-sign-in-alt mr-3 text-indigo-500 dark:text-indigo-400"></i>
                        Sign in
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-neutral-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-neutral-800">
                        <i class="fas fa-user-plus mr-3 text-indigo-500 dark:text-indigo-400"></i>
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const menuBtn = document.getElementById("mobileMenuButton");
    const mobileMenu = document.getElementById("mobileMenu");
    const menuIcon = document.getElementById("openMobileDropdown")
    const closeIcon = document.getElementById("closeMobileDropdown");

    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
        closeIcon.classList.toggle("hidden");
        menuIcon.classList.toggle("hidden");
    });

    // User dropdown toggle
    const username = document.getElementById('userNav');
    const dropdown = document.getElementById('dropdown');

    username.addEventListener('click', function () {
        dropdown.classList.toggle('opacity-0');
        dropdown.classList.toggle('scale-95');
        dropdown.classList.toggle('pointer-events-none');
        dropdown.classList.toggle('opacity-100');
        dropdown.classList.toggle('scale-100');
        dropdown.classList.toggle('pointer-events-auto');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target) && !username.contains(event.target)) {
            dropdown.classList.add('opacity-0');
            dropdown.classList.add('scale-95');
            dropdown.classList.add('pointer-events-none');
            dropdown.classList.remove('opacity-100');
            dropdown.classList.remove('scale-100');
            dropdown.classList.remove('pointer-events-auto');
        }
    });

    // Mobile search toggle
    const mobileSearchBtn = document.getElementById('mobileSearchButton');
    const mobileSearchBar = document.getElementById('mobileSearchBar');

    mobileSearchBtn.addEventListener('click', function () {
        mobileSearchBar.classList.toggle('hidden');
    });
</script>
