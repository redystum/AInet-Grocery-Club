@extends('pages.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Total Members</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $totalMembers }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Monthly Revenue</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">€{{ number_format($monthlyRevenue, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300">
                        <i class="fas fa-euro-sign text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Pending Orders</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $pendingOrders }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-300">
                        <i class="fas fa-shopping-basket text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-600 dark:text-neutral-400">Low Stock Items</p>
                        <h3 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $lowStockItems }}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-300">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Revenue Chart -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Monthly Revenue</h2>
                    <select id="revenueYear" class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        @foreach($existentYears as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Sales by Category Chart -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Sales by Category</h2>
                    <select id="categoryYear" class="px-3 py-1 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                        @foreach($existentYears as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Low Stock Items -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Recent Orders</h2>
                    <a href="{{ route('board.orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Order #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">#{{ $order->id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">{{ $order->user->name }}</td>
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

            <!-- Low Stock Items -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100">Low Stock Items</h2>
                    <a href="{{ route('board.stock') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Stock</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($lowStockProducts as $product)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ $product->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-200">{{ $product->category->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-bold">{{ $product->stock }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Export Data</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('board.dashboard.export.orders') }}" class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    <div>
                        <h3 class="font-medium text-neutral-800 dark:text-neutral-200">Orders</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Export all order data</p>
                    </div>
                    <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                </a>
                <a href="{{ route('board.dashboard.export.users') }}" class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    <div>
                        <h3 class="font-medium text-neutral-800 dark:text-neutral-200">Members</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Export member information</p>
                    </div>
                    <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                </a>
                <a href="{{ route('board.dashboard.export.products') }}" class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    <div>
                        <h3 class="font-medium text-neutral-800 dark:text-neutral-200">Products</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Export product inventory</p>
                    </div>
                    <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Revenue',
                    data: @json($monthlyRevenueData),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
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
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryData),
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

        // Year select change handlers
        document.getElementById('revenueYear').addEventListener('change', function() {
            const selectedYear = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('year', selectedYear);
            window.location.href = url.toString();
        });

        document.getElementById('categoryYear').addEventListener('change', function() {
            const selectedYear = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('year', selectedYear);
            window.location.href = url.toString();
        });
    </script>
@endsection