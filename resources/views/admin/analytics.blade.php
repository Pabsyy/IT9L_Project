<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\analytics.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Analytics</h1>
                    <p class="mt-1 text-sm text-gray-600">Comprehensive overview of your inventory performance</p>
                </div>
                <!-- Time Range Filter -->
                <div class="flex items-center space-x-4">
                    <select name="date_range" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>
                    <button type="button" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center text-sm">
                        <i class="ri-filter-3-line mr-1"></i>
                        Apply Filter
                    </button>
                </div>
            </div>

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-error-warning-line text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Inventory Value -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Inventory Value</span>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <i class="ri-money-dollar-circle-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">₱{{ number_format($inventoryMetrics['total_value']['current'], 2) }}</h3>
                    <div class="flex items-center text-sm">
                        @if($inventoryMetrics['total_value']['growth'] > 0)
                            <span class="text-green-500 flex items-center">
                                <i class="ri-arrow-up-line mr-1"></i>
                                +{{ number_format($inventoryMetrics['total_value']['growth'], 1) }}%
                            </span>
                        @else
                            <span class="text-red-500 flex items-center">
                                <i class="ri-arrow-down-line mr-1"></i>
                                {{ number_format($inventoryMetrics['total_value']['growth'], 1) }}%
                            </span>
                        @endif
                        <span class="text-gray-500 ml-2">vs. last period</span>
                    </div>
                </div>

                <!-- Stock Health -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-blue-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Stock Health</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <i class="ri-heart-pulse-line text-xl"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Healthy Stock</span>
                            <span class="text-green-500 font-medium">{{ number_format(100 - $inventoryMetrics['low_stock_items']['percentage'] - $inventoryMetrics['out_of_stock']['percentage'], 1) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Low Stock</span>
                            <span class="text-yellow-500 font-medium">{{ number_format($inventoryMetrics['low_stock_items']['percentage'], 1) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Out of Stock</span>
                            <span class="text-red-500 font-medium">{{ number_format($inventoryMetrics['out_of_stock']['percentage'], 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Stock Turnover -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-purple-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Stock Turnover</span>
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                            <i class="ri-refresh-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ number_format($inventoryMetrics['stock_turnover']['rate'], 1) }}x</h3>
                    <div class="flex items-center text-sm">
                        @if($inventoryMetrics['stock_turnover']['change'] > 0)
                            <span class="text-green-500 flex items-center">
                                <i class="ri-arrow-up-line mr-1"></i>
                                +{{ number_format($inventoryMetrics['stock_turnover']['change'], 1) }}
                            </span>
                        @else
                            <span class="text-red-500 flex items-center">
                                <i class="ri-arrow-down-line mr-1"></i>
                                {{ number_format($inventoryMetrics['stock_turnover']['change'], 1) }}
                            </span>
                        @endif
                        <span class="text-gray-500 ml-2">vs. last period</span>
                    </div>
                </div>

                <!-- Movement Activity -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-orange-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Movement Activity</span>
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                            <i class="ri-exchange-line text-xl"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Stock In</span>
                            <span class="text-green-500 font-medium">{{ $inventoryMetrics['movement_activity']['stock_in'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Stock Out</span>
                            <span class="text-red-500 font-medium">{{ $inventoryMetrics['movement_activity']['stock_out'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Adjustments</span>
                            <span class="text-blue-500 font-medium">{{ $inventoryMetrics['movement_activity']['adjustments'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Monthly Value Trend -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Monthly Value Trend</h3>
                        <button class="text-sm text-gray-500 hover:text-primary flex items-center">
                            <i class="ri-download-line mr-1"></i>
                            Export
                        </button>
                    </div>
                    <div id="monthly-trend-chart" class="w-full h-80"></div>
                </div>

                <!-- Category Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Category Distribution</h3>
                        <button class="text-sm text-gray-500 hover:text-primary flex items-center">
                            <i class="ri-download-line mr-1"></i>
                            Export
                        </button>
                    </div>
                    <div id="category-distribution-chart" class="w-full h-80"></div>
                </div>

                <!-- Stock Movement Analysis -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Stock Movement Analysis</h3>
                        <button class="text-sm text-gray-500 hover:text-primary flex items-center">
                            <i class="ri-download-line mr-1"></i>
                            Export
                        </button>
                    </div>
                    <div id="stock-movement-chart" class="w-full h-80"></div>
                </div>

                <!-- Price Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Price Distribution</h3>
                        <button class="text-sm text-gray-500 hover:text-primary flex items-center">
                            <i class="ri-download-line mr-1"></i>
                            Export
                        </button>
                    </div>
                    <div id="price-distribution-chart" class="w-full h-80"></div>
                </div>
            </div>

            <!-- Additional Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Top Products -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Top Products</h3>
                            <p class="text-sm text-gray-500">Highest value items in stock</p>
                        </div>
                        <button class="text-sm text-gray-500 hover:text-primary">View All</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($productMetrics['top_products'] as $product)
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $product['name'] }}</h4>
                                <span class="text-sm text-gray-500">Stock: {{ $product['stock'] }}</span>
                            </div>
                            <span class="text-primary font-medium">₱{{ number_format($product['value'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Stock Movements -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Recent Movements</h3>
                            <p class="text-sm text-gray-500">Latest inventory transactions</p>
                        </div>
                        <button class="text-sm text-gray-500 hover:text-primary">View All</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($inventoryMetrics['recent_movements'] as $movement)
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $movement['product_name'] }}</h4>
                                <span class="text-sm text-gray-500">{{ $movement['type'] }} • {{ $movement['date'] }}</span>
                            </div>
                            <span class="font-medium {{ $movement['type'] === 'purchase' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $movement['type'] === 'purchase' ? '+' : '-' }}{{ $movement['quantity'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Low Stock Alerts</h3>
                            <p class="text-sm text-gray-500">Items requiring attention</p>
                        </div>
                        <button class="text-sm text-gray-500 hover:text-primary">View All</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($inventoryMetrics['low_stock_alerts'] as $alert)
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $alert['product_name'] }}</h4>
                                <span class="text-sm text-gray-500">Current Stock: {{ $alert['current_stock'] }}</span>
                            </div>
                            <span class="text-yellow-500 font-medium flex items-center">
                                <i class="ri-alert-line mr-1"></i>
                                Low Stock
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Value Trend Chart
    const monthlyTrendOptions = {
        chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        series: [{
            name: 'Inventory Value',
            data: @json($financialMetrics['monthly_value_trend']->pluck('value'))
        }],
        xaxis: {
            categories: @json($financialMetrics['monthly_value_trend']->pluck('month')),
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        },
        yaxis: {
            labels: {
                style: { colors: '#64748b', fontSize: '12px' },
                formatter: function(value) {
                    return '₱' + new Intl.NumberFormat().format(value);
                }
            }
        },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 2 },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    return '₱' + new Intl.NumberFormat().format(value);
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#monthly-trend-chart"), monthlyTrendOptions).render();

    // Category Distribution Chart
    const categoryDistOptions = {
        chart: {
            type: 'donut',
            height: 320,
            fontFamily: 'Inter, sans-serif'
        },
        series: @json($productMetrics['category_distribution']->pluck('count')),
        labels: @json($productMetrics['category_distribution']->pluck('name')),
        colors: ['#6366f1', '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b', '#ef4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: { colors: '#64748b' }
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    return value + ' products';
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#category-distribution-chart"), categoryDistOptions).render();

    // Stock Movement Chart
    const stockMovementOptions = {
        chart: {
            type: 'bar',
            height: 320,
            stacked: true,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        series: [{
            name: 'Stock In',
            data: @json($inventoryMetrics['movement_trend']['stock_in'])
        }, {
            name: 'Stock Out',
            data: @json($inventoryMetrics['movement_trend']['stock_out'])
        }],
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        },
        colors: ['#22c55e', '#ef4444'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                borderRadius: 4
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            labels: { colors: '#64748b' }
        },
        tooltip: {
            theme: 'light'
        }
    };
    new ApexCharts(document.querySelector("#stock-movement-chart"), stockMovementOptions).render();

    // Price Distribution Chart
    const priceDistOptions = {
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        series: [{
            name: 'Products',
            data: Object.values(@json($productMetrics['price_distribution']))
        }],
        xaxis: {
            categories: Object.keys(@json($productMetrics['price_distribution'])),
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        },
        colors: ['#6366f1'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
                columnWidth: '60%'
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            theme: 'light'
        }
    };
    new ApexCharts(document.querySelector("#price-distribution-chart"), priceDistOptions).render();

    // Date Range Filter
    document.querySelector('select[name="date_range"]').addEventListener('change', function(e) {
        window.location.href = '{{ route("admin.analytics") }}?date_range=' + e.target.value;
    });
});
</script>
@endpush

@endsection