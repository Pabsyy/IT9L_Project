@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content -->
    <div class="flex-1">
        @include('partials.header', ['title' => 'Dashboard Overview'])
        <main class="p-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Revenue -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Revenue</p>
                            <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($totalRevenue, 2) }}</h3>
                            <p class="text-sm text-green-500 mt-1">+{{ $revenueGrowth }}% from last month</p>
                        </div>
                        <div class="text-custom">
                            <i class="fas fa-dollar-sign text-2xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Orders</p>
                            <h3 class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</h3>
                            <p class="text-sm text-green-500 mt-1">+{{ $orderGrowth }}% from last month</p>
                        </div>
                        <div class="text-custom">
                            <i class="fas fa-shopping-bag text-2xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Average Order Value -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Average Order Value</p>
                            <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($averageOrderValue, 2) }}</h3>
                            <p class="text-sm {{ $aovGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} mt-1">
                                {{ $aovGrowth >= 0 ? '+' : '' }}{{ $aovGrowth }}% from last month
                            </p>
                        </div>
                        <div class="text-custom">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Products</p>
                            <h3 class="text-2xl font-semibold text-gray-900">{{ $totalProducts }}</h3>
                            <p class="text-sm text-green-500 mt-1">+{{ $newProducts }} new items</p>
                        </div>
                        <div class="text-custom">
                            <i class="fas fa-box text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart & Top Selling Parts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Overview -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                        <select class="border-gray-200 rounded-md text-sm">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                        </select>
                    </div>
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Top Selling Parts -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Parts</h3>
                    <ul>
                        @foreach($topSellingParts as $part)
                        <li class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center border mr-4">
                                Put your image here
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $part->name }}</h4>
                                <p class="text-sm text-gray-600">SKU: {{ $part->sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">${{ number_format($part->price, 2) }}</p>
                                <p class="text-sm text-green-500">+{{ $part->sales }} sold</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Recent Orders & Low Stock Alert -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white p-6 rounded-lg shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr>
                                <th class="pb-2">Order ID</th>
                                <th class="pb-2">Customer</th>
                                <th class="pb-2">Product</th>
                                <th class="pb-2">Amount</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->product_name }}</td>
                                <td>${{ number_format($order->amount, 2) }}</td>
                                <td class="{{ $order->status_class }}">{{ $order->status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Low Stock Alert -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Low Stock Alert</h3>
                    <ul>
                        @forelse($lowStockProducts as $product)
                        <li class="flex items-center mb-4">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $product->ProductName }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->Quantity }} units remaining</p>
                            </div>
                            <button class="text-blue-500 hover:underline">Restock</button>
                        </li>
                        @empty
                        <li class="text-gray-500">No low stock items.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Include the reusable sidebar script -->
<script src="{{ asset('js/sidebar.js') }}"></script>
@endsection
