@extends('pages.layouts.public')

@section('title', 'My Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Welcome Section -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-md">
                    <img src="{{ auth()->user()->getImage() }}" alt="Profile" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Welcome back, {{ auth()->user()->name }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-400">Here's your shopping activity summary</p>
                </div>
                <div class="px-4 py-2 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">
                    <i class="fas fa-crown mr-1"></i> Member since {{ auth()->user()->created_at->format('M Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Virtual Card Balance</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">€{{ number_format(auth()->user()->card->balance, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300">
                        <i class="fas fa-credit-card text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Monthly Spending</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">€{{ number_format($monthlySpending, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Total Orders</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $totalOrders }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Favorite Category</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $favoriteCategory ?? 'N/A' }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-300">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Spending Chart -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Monthly Spending</h2>
                <div class="h-64">
                    <canvas id="spendingChart"></canvas>
                </div>
            </div>

            <!-- Spending by Category Chart -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Spending by Category</h2>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Card Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Order #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach(auth()->user()->lastOrders as $order)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">#{{ $order->id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">{{ $order->date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">€{{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $order->status === 'completed' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                                       ($order->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200' :
                                       'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Transactions -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Recent Transactions</h2>
                    <a href="{{ route('card.transactions') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                        <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full mr-3
                            {{ $transaction->amount < 0 ? 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-300' : 'bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300' }}">
                                    <i class="fas {{ $transaction->amount < 0 ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-neutral-800 dark:text-neutral-200">{{ $transaction->description }}</p>
                                    <p class="text-xs text-neutral-600 dark:text-neutral-400">{{ $transaction->created_at->format('M d, H:i') }}</p>
                                </div>
                            </div>
                            <div class="font-medium {{ $transaction->amount < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                {{ $transaction->amount < 0 ? '-' : '+' }}€{{ number_format(abs($transaction->amount), 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Shopping Insights -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Shopping Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">Most Purchased Products</h3>
                    <div class="space-y-3">
                        @foreach($topProducts as $product)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-md object-cover mr-3">
                                    <span class="text-neutral-800 dark:text-neutral-200">{{ $product->name }}</span>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-400">{{ $product->pivot->total_quantity }} purchased</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="font-medium text-neutral-800 dark:text-neutral-200 mb-2">Recommended For You</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($recommendedProducts as $product)
                            <a href="{{ route('products.show', $product) }}" class="group">
                                <div class="relative overflow-hidden rounded-lg h-24">
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div class="absolute bottom-2 left-2 text-white text-sm font-medium">{{ $product->name }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Spending Chart
        const spendingCtx = document.getElementById('spendingChart').getContext('2d');
        const spendingChart = new Chart(spendingCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Spending',
                    data: @json($monthlySpendingData),
                    backgroundColor: '#8B5CF6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($userCategoryLabels),
                datasets: [{
                    data: @json($userCategoryData),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#14B8A6', '#F97316', '#64748B', '#A855F7'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    }
                }
            }
        });
    </script>
@endsection