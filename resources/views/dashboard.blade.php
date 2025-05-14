@extends('layouts.app')     

@section('content')
<div class="flex">

    <!-- Main Content -->
    <div class="flex-1">

        <div class="bg-gray-100 min-h-screen p-2 pt-15">
            @if(isset($dbError))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Database Error!</strong>
                    <span class="block sm:inline">Unable to connect to database.</span>
                </div>
            @endif

            @if(isset($error))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Warning!</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center mr-4">
                        <span class="text-sm text-gray-600 mr-2">{{ now()->format('F d, Y') }}</span>
                        <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                            <i class="ri-calendar-line"></i>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 {{ $systemStatus['color'] }} rounded-full mr-2"></div>
                        <span class="text-sm {{ $systemStatus['status'] === 'offline' ? 'text-red-600' : 'text-gray-600' }}">
                            System {{ $systemStatus['status'] }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-3 text-lg rounded-button flex items-center justify-center whitespace-nowrap">
                        <div class="w-6 h-6 flex items-center justify-center mr-2">
                            <i class="ri-file-chart-line"></i>
                        </div>
                        <span>Generate Report</span>
                    </button>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Inventory Value -->
                <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Inventory Value</span>
                        <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center text-violet-500">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">₱{{ number_format($totalInventoryValue, 2) }}</h3>
                    <div class="flex items-center text-sm">
                        <span class="text-green-500 flex items-center mr-2">
                            <i class="ri-arrow-up-line mr-1"></i>
                            {{ number_format($inventoryGrowth, 1) }}%
                        </span>
                        <span class="text-gray-500">Compared to last month</span>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Low Stock Items</span>
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                            <i class="ri-alert-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $lowStockItems }}</h3>
                    <div class="flex items-center text-sm">
                        <span class="text-red-500 flex items-center mr-2">
                            <i class="ri-arrow-up-line mr-1"></i>
                            {{ $newLowStock }} new
                        </span>
                        <span class="text-gray-500">Items requiring restock</span>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Monthly Revenue</span>
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <i class="ri-line-chart-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">₱{{ number_format($monthlyRevenue, 2) }}</h3>
                    <div class="flex items-center text-sm">
                        <span class="text-green-500 flex items-center mr-2">
                            <i class="ri-arrow-up-line mr-1"></i>
                            {{ number_format($revenueGrowth, 1) }}%
                        </span>
                        <span class="text-gray-500">Compared to last month</span>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="metric-card bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Products</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <i class="ri-shopping-bag-line"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $totalProducts }}</h3>
                    <div class="flex items-center text-sm">
                        <span class="text-green-500 flex items-center mr-2">
                            <i class="ri-arrow-up-line mr-1"></i>
                            {{ $newProducts }} new
                        </span>
                        <span class="text-gray-500">Active products in inventory</span>
                    </div>
                </div>
            </div>
            <style>
                .metric-card {
                    transition: box-shadow 0.2s, transform 0.2s;
                }
                .metric-card:hover {
                    box-shadow: 0 8px 32px 0 rgba(99, 102, 241, 0.15), 0 1.5px 6px 0 rgba(0,0,0,0.08);
                    transform: translateY(-4px) scale(1.025);
                    z-index: 1;
                }
            </style>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Revenue Trends</h3>
                        <div class="flex items-center space-x-2">
                            <button data-period="monthly" class="period-button active bg-indigo-600 text-white text-xs px-3 py-1.5 hover:bg-indigo-600 hover:text-white rounded-full">Monthly</button>
                            <button data-period="quarterly" class="period-button text-xs px-3 py-1.5 text-black hover:bg-indigo-600 hover:text-white rounded-full">Quarterly</button>
                            <button data-period="yearly" class="period-button text-xs px-3 py-1.5 text-black hover:bg-indigo-600 hover:text-white rounded-full">Yearly</button>
                        </div>
                    </div>
                    <div id="revenue-chart" class="w-full h-80"></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const revenueChart = echarts.init(document.getElementById('revenue-chart'));
                            let currentOption = null;

                            function updateChart(period) {
                                fetch(`/admin/dashboard/revenue-data?period=${period}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const option = {
                                            tooltip: {
                                                trigger: 'axis',
                                                backgroundColor: 'rgba(0, 0, 0, 0.85)',
                                                borderColor: 'rgba(255, 255, 255, 0.3)',
                                                borderWidth: 1,
                                                textStyle: {
                                                    color: '#fff',
                                                    fontSize: 14
                                                },
                                                formatter: function(params) {
                                                    return params[0].name + '<br/>' +
                                                        params[0].seriesName + ': ' + data.currency + 
                                                        params[0].value.toLocaleString();
                                                }
                                            },
                                            grid: {
                                                left: '3%',
                                                right: '4%',
                                                bottom: '3%',
                                                containLabel: true
                                            },
                                            xAxis: {
                                                type: 'category',
                                                data: data.labels,
                                                boundaryGap: false,
                                                axisLine: {
                                                    lineStyle: { color: '#ddd' }
                                                },
                                                axisLabel: { color: '#666' }
                                            },
                                            yAxis: {
                                                type: 'value',
                                                axisLine: {
                                                    show: false
                                                },
                                                axisTick: {
                                                    show: false
                                                },
                                                axisLabel: {
                                                    color: '#666',
                                                    formatter: value => data.currency + value.toLocaleString()
                                                },
                                                splitLine: {
                                                    lineStyle: {
                                                        color: '#ddd',
                                                        type: 'dashed'
                                                    }
                                                }
                                            },
                                            series: [{
                                                name: 'Revenue',
                                                type: 'line',
                                                data: data.data,
                                                smooth: true,
                                                showSymbol: false,
                                                itemStyle: { color: '#10B981' },
                                                areaStyle: {
                                                    color: {
                                                        type: 'linear',
                                                        x: 0, y: 0, x2: 0, y2: 1,
                                                        colorStops: [{
                                                            offset: 0,
                                                            color: 'rgba(16, 185, 129, 0.2)'
                                                        }, {
                                                            offset: 1,
                                                            color: 'rgba(16, 185, 129, 0.02)'
                                                        }]
                                                    }
                                                }
                                            }]
                                        };
                                        revenueChart.setOption(option);
                                        currentOption = option;
                                    });
                            }

                            // Handle period switches
                            document.querySelectorAll('.period-button').forEach(button => {
                                button.addEventListener('click', function() {
                                    document.querySelectorAll('.period-button').forEach(btn => {
                                        btn.classList.remove('active', 'bg-indigo-600', 'text-white');
                                        btn.classList.add('text-black');
                                    });
                                    this.classList.add('active', 'bg-indigo-600', 'text-white');
                                    this.classList.remove('text-black');
                                    // Update chart
                                    updateChart(this.dataset.period);
                                });
                            });

                            // Initial load
                            updateChart('monthly');

                            // Handle window resize
                            window.addEventListener('resize', function() {
                                revenueChart.resize();
                            });
                        });
                    </script>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="chart-container" style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; width: 100%; max-width: 500px; margin: 0 auto;">
                        <div class="chart-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                            <h3 class="chart-title" style="font-size: 18px; font-weight: 500; color: #1f2937; margin: 0;">Inventory by Category</h3>
                            <button class="more-button" style="background: none; border: none; color: #6b7280; cursor: pointer; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; padding: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </button>
                        </div>
                        <div id="category-chart" style="width: 100%; height: 320px;"></div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const categoryChart = echarts.init(document.getElementById('category-chart'));
                            const categories = @json($categories);

                            // Define a color palette for categories
                            const palette = [
                                'rgba(87, 181, 231, 1)',
                                'rgba(141, 211, 199, 1)',
                                'rgba(251, 191, 114, 1)',
                                'rgba(252, 141, 98, 1)',
                                'rgba(190, 174, 212, 1)',
                                'rgba(255, 255, 179, 1)',
                                'rgba(255, 112, 67, 1)',      
                                'rgba(102, 187, 106, 1)',     
                                'rgba(255, 202, 40, 1)',      
                                'rgba(66, 165, 245, 1)'       
                            ];

                            // Build data array for ECharts
                            const data = categories.map((cat, idx) => ({
                                value: cat.total_products,
                                name: cat.name,
                                itemStyle: { color: palette[idx % palette.length] }
                            }));

                            const categoryOption = {
                                animation: false,
                                tooltip: {
                                    trigger: 'item',
                                    formatter: '{a} <br/>{b}: {c} ({d}%)',
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    borderColor: '#f1f5f9',
                                    borderWidth: 1,
                                    padding: 10,
                                    textStyle: {
                                        color: '#1f2937'
                                    }
                                },
                                legend: {
                                    orient: 'vertical',
                                    right: 10,
                                    top: 'center',
                                    textStyle: {
                                        color: '#1f2937'
                                    },
                                    itemWidth: 10,
                                    itemHeight: 10,
                                    itemGap: 10
                                },
                                series: [
                                    {
                                        name: 'Inventory Distribution',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        center: ['40%', '50%'],
                                        avoidLabelOverlap: false,
                                        itemStyle: {
                                            borderRadius: 8,
                                            borderColor: '#fff',
                                            borderWidth: 2
                                        },
                                        label: {
                                            show: false
                                        },
                                        emphasis: {
                                            label: {
                                                show: false
                                            },
                                            itemStyle: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: data
                                    }
                                ]
                            };

                            categoryChart.setOption(categoryOption);

                            window.addEventListener('resize', function() {
                                categoryChart.resize();
                            });
                        });
                    </script>
                </div>
            </div>

            <!-- Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800">Recent Activities</h3>
                        <div class="flex items-center">
                            <label class="custom-switch mr-3">
                                <input type="checkbox" checked>
                                <span class="switch-slider"></span>
                            </label>
                            <span class="text-sm text-gray-600">Auto refresh</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="px-6 py-3 font-medium">Transaction ID</th>
                                    <th class="px-6 py-3 font-medium">User</th>
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $transaction->SalesTransactionID }}</td>
                                    <td class="px-6 py-4">{{ $transaction->user->username ?? 'Deleted User' }}</td>
                                    <td class="px-6 py-4">{{ $transaction->TransactionDate }}</td>
                                    <td class="px-6 py-4">₱{{ number_format($transaction->grand_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        <a href="#" class="text-sm text-primary font-medium flex items-center">
                            <span>View all activities</span>
                            <div class="w-4 h-4 flex items-center justify-center ml-1">
                                <i class="ri-arrow-right-line"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800">Low Stock Alert</h3>
                        <button class="text-xs px-3 py-1.5 bg-red-100 text-red-600 rounded-full flex items-center">
                            <div class="w-3 h-3 flex items-center justify-center mr-1">
                                <i class="ri-alert-line"></i>
                            </div>
                            <span>{{ $lowStockItems }} items</span>
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="px-6 py-3 font-medium">PRODUCT</th>
                                    <th class="px-6 py-3 font-medium">CURRENT STOCK</th>
                                    <th class="px-6 py-3 font-medium">STATUS</th>
                                    <th class="px-6 py-3 font-medium">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr class="border-b border-gray-50 hover:bg-gray-50">
                                    <td class="px-6 py-4 flex items-center">
                                        <img src="{{ asset($product->Image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        <span class="text-sm">{{ $product->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="h-2 rounded-full {{ $product->Quantity <= 3 ? 'bg-red-500' : 'bg-yellow-500' }}" 
                                                     style="width: {{ min(($product->Quantity / 10) * 100, 100) }}%">
                                                </div>
                                            </div>
                                            <span class="text-sm">{{ $product->Quantity }} units</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $product->Quantity <= 3 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">
                                            {{ $product->Quantity <= 3 ? 'Critical' : 'Low' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Reorder</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        <a href="#" class="text-sm text-primary font-medium flex items-center">
                            <span>View all low stock items</span>
                            <div class="w-4 h-4 flex items-center justify-center ml-1">
                                <i class="ri-arrow-right-line"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
