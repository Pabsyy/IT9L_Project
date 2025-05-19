@extends('layouts.app')

@push('styles')
<style>
    .date-picker-dropdown,
    .status-dropdown-menu {
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.2s ease-in-out;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 0.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }

    .date-picker.active .date-picker-dropdown,
    .status-dropdown.active .status-dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .date-preset.active {
        background-color: rgba(99, 102, 241, 0.1);
        color: #6366F1;
    }

    .date-preset:hover:not(.active) {
        background-color: rgba(99, 102, 241, 0.05);
        color: #6366F1;
    }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage and track all customer orders</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">Show:</label>
                        <select onchange="updatePerPage(this.value)" 
                                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-gray-600">per page</span>
                    </div>
                    <a href="{{ route('admin.orders', ['show_all' => !$showAll] + request()->except('show_all', 'page')) }}" 
                       class="inline-flex items-center px-4 py-2 {{ $showAll ? 'bg-gray-100 text-gray-700' : 'bg-white text-gray-600' }} border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <i class="ri-list-unordered mr-2"></i>
                        {{ $showAll ? 'Show Paginated' : 'Show All' }}
                    </a>
                    <button class="flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-download-line mr-2"></i>
                        Export Orders
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Pending Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-yellow-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Pending Orders</span>
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                            <i class="ri-time-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $pendingCount }}</h3>
                    <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="text-sm text-yellow-600 hover:text-yellow-700 flex items-center">
                        View pending orders
                        <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>

                <!-- Processing Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-blue-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Processing Orders</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <i class="ri-loader-4-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $processingCount }}</h3>
                    <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="text-sm text-blue-600 hover:text-blue-700 flex items-center">
                        View processing orders
                        <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-green-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Completed Orders</span>
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <i class="ri-checkbox-circle-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $completedCount }}</h3>
                    <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="text-sm text-green-600 hover:text-green-700 flex items-center">
                        View completed orders
                        <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>

                <!-- Cancelled Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-red-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Cancelled Orders</span>
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                            <i class="ri-close-circle-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $cancelledCount }}</h3>
                    <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}" class="text-sm text-red-600 hover:text-red-700 flex items-center">
                        View cancelled orders
                        <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-700">Filter Orders</h3>
                    <a href="{{ route('admin.orders') }}" class="text-sm text-gray-500 hover:text-primary flex items-center">
                        <i class="ri-refresh-line mr-1"></i>
                        Reset Filters
                    </a>
                </div>
                <form action="{{ route('admin.orders') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Search Orders</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Order ID, customer name..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Order Status</label>
                            <select name="status" 
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">From Date</label>
                            <input type="date" 
                                   name="from_date" 
                                   value="{{ request('from_date') }}"
                                   class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">To Date</label>
                            <input type="date" 
                                   name="to_date" 
                                   value="{{ request('to_date') }}"
                                   class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center">
                            <i class="ri-filter-3-line mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                <span class="text-sm font-medium text-primary">
                                                    {{ strtoupper(substr($order->user->first_name ?? 'U', 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'Unknown User' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $order->user->email ?? 'No email' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₱{{ number_format($order->grand_total, 2) }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->items_count }} items</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->order_status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->order_status === 'delivered' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="text-primary hover:text-primary/80">
                                            View Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="ri-inbox-line text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 mb-2">No orders found</p>
                                        <p class="text-sm text-gray-400">Try adjusting your search or filter to find what you're looking for.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(!$showAll && $orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-700">
                            Showing <span class="font-medium mx-1">{{ $orders->firstItem() }}</span> to
                            <span class="font-medium mx-1">{{ $orders->lastItem() }}</span> of
                            <span class="font-medium mx-1">{{ $orders->total() }}</span> orders
                        </div>
                        
                        <div class="flex items-center justify-center space-x-2">
                            @if($orders->onFirstPage())
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">&laquo;</span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1 bg-white text-primary hover:bg-primary/10 rounded transition-colors duration-200">&laquo;</a>
                            @endif

                            <div class="flex items-center space-x-1">
                                @if($orders->currentPage() > 3)
                                    <a href="{{ $orders->url(1) }}" class="px-3 py-1 bg-white hover:bg-primary/10 text-gray-600 hover:text-primary rounded transition-colors duration-200">1</a>
                                    @if($orders->currentPage() > 4)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                @endif

                                @foreach(range(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page)
                                    @if($page == $orders->currentPage())
                                        <span class="px-3 py-1 bg-primary text-white rounded">{{ $page }}</span>
                                    @else
                                        <a href="{{ $orders->url($page) }}" class="px-3 py-1 bg-white hover:bg-primary/10 text-gray-600 hover:text-primary rounded transition-colors duration-200">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($orders->currentPage() < $orders->lastPage() - 2)
                                    @if($orders->currentPage() < $orders->lastPage() - 3)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $orders->url($orders->lastPage()) }}" class="px-3 py-1 bg-white hover:bg-primary/10 text-gray-600 hover:text-primary rounded transition-colors duration-200">{{ $orders->lastPage() }}</a>
                                @endif
                            </div>

                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1 bg-white text-primary hover:bg-primary/10 rounded transition-colors duration-200">&raquo;</a>
                            @else
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed transition-colors duration-200">&raquo;</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date picker functionality
    const datePickerBtn = document.querySelector('.date-picker button');
    const datePicker = document.querySelector('.date-picker');
    const datePresets = document.querySelectorAll('.date-preset');
    const statusDropdownBtn = document.querySelector('.status-dropdown button');
    const statusDropdown = document.querySelector('.status-dropdown');

    // Remove any previously active presets
    function clearActivePresets() {
        datePresets.forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('bg-indigo-100');
            btn.classList.add('text-gray-500');
        });
    }

    // Handle date preset selection
    datePresets?.forEach(preset => {
        // Check if this preset matches the current date range
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.round((end - start) / (1000 * 60 * 60 * 24));

            if (days.toString() === preset.dataset.days ||
               (preset.dataset.days === 'this-month' && start.getDate() === 1)) {
                preset.classList.add('active', 'bg-indigo-100', 'text-gray-700');
            }
        }

        preset.addEventListener('click', function(e) {
            e.preventDefault();
            clearActivePresets();

            // Add active classes
            this.classList.add('active', 'bg-indigo-100', 'text-gray-700');

            const days = this.dataset.days;
            const endDate = new Date();
            const startDate = new Date();

            if (days === 'this-month') {
                startDate.setDate(1);
            } else {
                startDate.setDate(startDate.getDate() - parseInt(days));
            }

            document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
            document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
        });
    });

    // Toggle dropdowns with animation
    if (datePickerBtn && datePicker) {
        datePickerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isActive = datePicker.classList.contains('active');

            // Close status dropdown if open
            statusDropdown?.classList.remove('active');

            // Toggle date picker with animation
            if (!isActive) {
                datePicker.classList.add('active');
                datePickerBtn.style.borderColor = '#6366F1';
            } else {
                datePicker.classList.remove('active');
                datePickerBtn.style.borderColor = '#6366F1';
            }
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!datePicker?.contains(e.target)) {
            datePicker?.classList.remove('active');
            datePickerBtn.style.borderColor = '#6366F1';
        }
        if (!statusDropdown?.contains(e.target)) {
            statusDropdown?.classList.remove('active');
        }
    });

    // Prevent dropdown menus from closing when clicking inside
    const dropdownMenus = document.querySelectorAll('.date-picker-dropdown, .status-dropdown-menu');
    dropdownMenus.forEach(menu => {
        menu?.addEventListener('click', (e) => e.stopPropagation());
    });
});
</script>
@endpush
