<!-- Mobile top navbar -->
<nav class="lg:hidden fixed w-full bg-white dark:bg-neutral-800 shadow-sm z-50">
    <div class="flex items-center justify-between p-4">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8">
            <span
                    class="ml-2 text-xl font-semibold text-neutral-800 dark:text-neutral-100 whitespace-nowrap transition-all duration-300">
                {{ config('app.name') }}
            </span>
        </a>
        <button id="mobileMenuButton"
                class="text-neutral-600 dark:text-neutral-300 hover:text-blue-600 dark:hover:text-blue-400">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
</nav>

<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-neutral-800 shadow-lg transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out"
     id="sidebar">
    <!-- Collapse Toggle Button -->
    <button id="sidebarToggle"
            class="absolute -right-3 top-5 w-6 h-6 bg-white dark:bg-neutral-700 rounded-full shadow-md border border-neutral-200 dark:border-neutral-600 flex items-center justify-center text-neutral-600 dark:text-neutral-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-all">
        <i class="fas fa-chevron-left text-xs transition-transform" id="toggleIcon"></i>
    </button>

    <!-- Logo -->
    <div class="flex items-center justify-center p-6 border-b border-neutral-200 dark:border-neutral-700">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 transition-all duration-300">
            <span id="LogoText"
                  class="ml-2 text-xl font-semibold text-neutral-800 dark:text-neutral-100 whitespace-nowrap transition-all duration-300">
                {{ config('app.name') }}
            </span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="p-4 overflow-y-auto" id="navigationLinks">
        <ul class="space-y-2">
            @can('admin-dash')
                <li>
                    <a href="{{ route('board.dashboard.index') }}"
                       class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.dashboard.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                        <i class="fas fa-home mr-3 {{ request()->routeIs('board.dashboard.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                        <span class="whitespace-nowrap transition-all duration-300">Dashboard</span>
                    </a>
                </li>
            @endcan
            @can('admin-users')
                <li>
                    <a href="{{ route('board.users.index') }}"
                       class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.users.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                        <i class="fas fa-user mr-3 {{ request()->routeIs('board.users.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                        <span class="whitespace-nowrap transition-all duration-300">Users</span>
                    </a>
                </li>
            @endcan
                <li>
                    <a href="{{ route('board.categories.index') }}"
                       class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.categories', 'board.restock.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                        <i class="fas fa-object-group mr-3 {{ request()->routeIs('board.categories', 'board.restock.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                        <span class="whitespace-nowrap transition-all duration-300">Category</span>
                    </a>
                </li>
            <li>
                <a href="{{ route('board.stock') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.stock', 'board.restock.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                    <i class="fas fa-boxes-stacked mr-3 {{ request()->routeIs('board.stock', 'board.restock.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                    <span class="whitespace-nowrap transition-all duration-300">Stock</span>
                </a>
            </li>
            <li>
                <a href="{{ route('board.supply.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.supply.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                    <i class="fas fa-parachute-box mr-3 {{ request()->routeIs('board.supply.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                    <span class="whitespace-nowrap transition-all duration-300">Supplies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('board.orders.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.orders.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                    <i class="fas fa-receipt mr-3 {{ request()->routeIs('board.orders.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                    <span class="whitespace-nowrap transition-all duration-300">Orders</span>
                </a>
            </li>
            @can('admin-settings')
                <li>
                    <a href="{{ route('board.settings') }}"
                       class="flex items-center p-3 rounded-lg {{ request()->routeIs('board.settings.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700' }} group transition-colors">
                        <i class="fas fa-cog mr-3 {{ request()->routeIs('board.settings.*') ? 'text-blue-600 dark:text-blue-400' : 'text-neutral-500 dark:text-neutral-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-all duration-300"></i>
                        <span class="whitespace-nowrap transition-all duration-300">Settings</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>

    <!-- User Info at Bottom -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900/50">
        <div class="flex items-center" id="fullUser">
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden flex-shrink-0">
                <img src="{{ auth()->user()->getImage() }}" alt="User" class="w-full h-full object-cover">
            </div>
            <div class="ml-3 overflow-hidden transition-all duration-300">
                <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200 whitespace-nowrap">{{ auth()->user()->name }}</p>
                <p class="text-xs text-neutral-600 dark:text-neutral-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('logout') }}"
               class="ml-auto text-neutral-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 flex-shrink-0">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>

        <div class="hidden" id="collapsedUser">
            <div class="flex items-center mb-4">
                <a href="{{ route('logout') }}"
                   class="mx-auto text-neutral-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 flex-shrink-0">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden flex-shrink-0">
                <img src="{{ auth()->user()->getImage() }}" alt="User" class="w-full h-full mx-auto object-cover">
            </div>
        </div>
    </div>
</div>
