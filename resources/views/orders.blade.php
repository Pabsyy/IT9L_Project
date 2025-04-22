<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\orders.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    @include('sidebar')

    <div class="flex-1">
        @include('partials.header', ['title' => 'Orders'])

        <main class="bg-gray-100 min-h-screen p-6 pt-15">
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
                    <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-button flex items-center justify-center whitespace-nowrap">
                        <i class="ri-file-download-line mr-2"></i>
                        <span>Export Orders</span>
                    </button>
                    <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2.5 rounded-button flex items-center justify-center whitespace-nowrap">
                        <i class="ri-add-line mr-2"></i>
                        <span>Create Order</span>
                    </button>
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
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2">
                                                <span class="text-xs font-medium">{{ substr($order->customer_name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">{{ $order->date }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">{{ $order->items_count }} items</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">₱{{ number_format($order->total, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">{{ $order->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button onclick="openModal({{ $order->id }})" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                                <i class="ri-eye-line text-gray-500"></i>
                                            </button>
                                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                                <i class="ri-edit-line text-gray-500"></i>
                                            </button>
                                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                                <i class="ri-more-2-fill text-gray-500"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                                <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">No orders yet</h3>
                                            <p class="text-sm text-gray-500 mb-4">Orders will appear here once customers start placing them.</p>
                                            <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                                                <i class="ri-add-line mr-2"></i>
                                                Create Order
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
        <div class="border-b px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="orderDetails" class="space-y-6">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add this script section -->
<script>
    function openModal(orderId) {
        // Fetch order details
        fetch(`/api/orders/${orderId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('orderDetails').innerHTML = `
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Order Information</h4>
                            <p class="text-sm text-gray-600">Order ID: #${data.id}</p>
                            <p class="text-sm text-gray-600">Date: ${data.date}</p>
                            <p class="text-sm text-gray-600">Status: 
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    ${data.status}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600">Total: ₱${data.total}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Customer Details</h4>
                            <p class="text-sm text-gray-600">${data.customer_name}</p>
                            <p class="text-sm text-gray-600">${data.customer_email}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">Order Items</h4>
                        <table class="w-full text-sm">
                            <!-- Add your order items table here -->
                        </table>
                    </div>
                `;
                document.getElementById('orderModal').classList.remove('hidden');
                document.getElementById('orderModal').classList.add('flex');
            });
    }

    function closeModal() {
        document.getElementById('orderModal').classList.add('hidden');
        document.getElementById('orderModal').classList.remove('flex');
    }
</script>
@endsection
