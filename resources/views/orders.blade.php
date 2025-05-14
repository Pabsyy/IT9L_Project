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
    <!-- Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <div class="flex items-center mr-4">
                <span class="text-sm text-gray-600 mr-2">April 11, 2025</span>
                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                    <i class="ri-calendar-line"></i>
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">System online</span>
            </div>
        </div>
        <div class="flex space-x-3">
            <button type="button" disabled
               class="bg-white border border-gray-300 text-gray-400 px-4 py-2.5 rounded-button flex items-center justify-center whitespace-nowrap cursor-not-allowed">
                <i class="ri-file-download-line w-5 h-5 mr-2"></i>
                <span>Export Orders</span>
            </button>
            <a href="{{ route('orders.create') }}"
               class="bg-[#6366F1] hover:bg-[#4F46E5] text-white px-4 py-2.5 rounded-button flex items-center justify-center whitespace-nowrap">
                <i class="ri-add-line w-5 h-5 mr-2"></i>
                <span>Create Order</span>
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('orders') }}" method="GET" class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ri-search-line w-5 h-5 text-gray-400"></i>
                </div>
                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-button text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                       placeholder="Search by Order ID or Username">
            </div>
            <div class="date-picker relative w-full md:w-60">
                <button type="button" class="w-full flex items-center justify-between bg-white border border-gray-300 rounded-button px-3 py-2.5 text-sm text-gray-700">
                    <div class="flex items-center">
                        <i class="ri-calendar-line w-5 h-5 mr-2 text-gray-400"></i>
                        <span>Date Range</span>
                    </div>
                    <i class="ri-arrow-down-s-line w-5 h-5 text-gray-400"></i>
                </button>
                <div class="date-picker-dropdown hidden absolute left-0 right-0 mt-2 p-4 bg-indigo-50 border border-indigo-100 rounded-lg shadow-lg z-50">
                    <div class="flex justify-between mb-4">
                        <button type="button" data-days="7"
                                class="date-preset text-xs px-2 py-1 text-indigo-600 hover:bg-indigo-100 rounded-full">
                            Last 7 days
                        </button>
                        <button type="button" data-days="30"
                                class="date-preset text-xs px-2 py-1 text-indigo-600 hover:bg-indigo-100 rounded-full">
                            Last 30 days
                        </button>
                        <button type="button" data-days="this-month"
                                class="date-preset text-xs px-2 py-1 text-indigo-600 hover:bg-indigo-100 rounded-full">
                            This month
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Start Date</label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ request('start_date') }}"
                                   class="w-full border border-gray-300 rounded-button px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">End Date</label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ request('end_date') }}"
                                   class="w-full border border-gray-300 rounded-button px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-3 py-1.5 rounded-button text-sm">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Quick Status Filters -->
    <div class="bg-white rounded-lg shadow-sm p-1 mb-6">
        <div class="flex flex-wrap">
            @php
                $statuses = [
                    '' => ['label' => 'All Orders', 'count' => $orders->total()],
                    'pending' => ['label' => 'Pending', 'count' => $pendingCount],
                    'processing' => ['label' => 'Processing', 'count' => $processingCount],
                    'completed' => ['label' => 'Completed', 'count' => $completedCount],
                    'cancelled' => ['label' => 'Cancelled', 'count' => $cancelledCount]
                ];
            @endphp

            @foreach($statuses as $status => $info)
                <a href="{{ route('orders', ['status' => $status]) }}"
                   class="flex-1 min-w-[120px] flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-medium
                          {{ request('status') == $status ? 'text-[#6366F1] bg-[#6366F1]/10' : 'text-gray-700 hover:bg-gray-100' }}
                          rounded-full">
                    <span>{{ $info['label'] }}</span>
                    <span class="px-1.5 py-0.5 {{ request('status') == $status ? 'bg-[#6366F1] text-white' : 'bg-gray-200 text-gray-700' }} text-xs rounded-full">
                        {{ $info['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium"
                                     style="background-color: {{ '#' . substr(md5($order->customer_name), 0, 6) }}20">
                                    {{ strtoupper(substr($order->customer_name, 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->items_count }} items
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('orders.show', $order) }}" class="text-gray-400 hover:text-gray-500">
                                    <i class="ri-eye-line w-5 h-5"></i>
                                </a>
                                <a href="{{ route('orders.edit', $order) }}" class="text-gray-400 hover:text-gray-500">
                                    <i class="ri-pencil-line w-5 h-5"></i>
                                </a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-500">
                                        <i class="ri-delete-bin-line w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        @if(request()->has('search'))
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found for your search.</td>
                        @else
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                        @endif
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200">
        <div class="flex items-center">
            @if ($orders->total() > 0)
                <span class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $orders->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $orders->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $orders->total() }}</span>
                    results
                </span>
            @endif
            <div class="ml-4 flex items-center">
                <span class="text-sm text-gray-700 mr-2">Items per page:</span>
                <div class="relative">
                    <select onchange="window.location.href=this.value" class="appearance-none bg-white border border-gray-300 rounded-button text-gray-700 py-1 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach ([10, 25, 50, 100] as $pageSize)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $pageSize]) }}"
                                    {{ request('per_page', 10) == $pageSize ? 'selected' : '' }}>
                                {{ $pageSize }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($orders->hasPages())
            <div class="flex items-center space-x-2">
                <!-- Previous Page Link -->
                @if ($orders->onFirstPage())
                    <button disabled class="px-3 py-1.5 border border-gray-200 rounded-button text-sm text-gray-400 flex items-center">
                        <div class="w-4 h-4 flex items-center justify-center mr-1">
                            <i class="ri-arrow-left-s-line"></i>
                        </div>
                        <span>Previous</span>
                    </button>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded-button text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                        <div class="w-4 h-4 flex items-center justify-center mr-1">
                            <i class="ri-arrow-left-s-line"></i>
                        </div>
                        <span>Previous</span>
                    </a>
                @endif

                <!-- Pagination Elements -->
                @foreach ($orders->links()->elements as $element)
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $orders->currentPage())
                                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-primary text-white text-sm">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-700 text-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded-button text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                        <span>Next</span>
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                @else
                    <button disabled class="px-3 py-1.5 border border-gray-200 rounded-button text-sm text-gray-400 flex items-center">
                        <span>Next</span>
                        <div class="w-4 h-4 flex items-center justify-center ml-1">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </button>
                @endif
            </div>
        @endif
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
            btn.classList.remove('active', 'bg-primary/10', 'text-primary');
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
                preset.classList.add('active');
                preset.classList.remove('text-gray-500');
                preset.classList.add('bg-primary/10', 'text-primary');
            }
        }

        preset.addEventListener('click', function(e) {
            e.preventDefault();
            clearActivePresets();

            // Add active classes
            this.classList.add('active');
            this.classList.remove('text-gray-500');
            this.classList.add('bg-primary/10', 'text-primary');

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
