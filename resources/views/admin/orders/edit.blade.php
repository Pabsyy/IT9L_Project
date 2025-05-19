@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            <!-- Back Button and Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center mb-1">
                        <a href="{{ route('admin.orders') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                            <i class="ri-arrow-left-line mr-2"></i>
                            Back to Orders
                        </a>
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Order #{{ $order->id }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Update order details and items</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.show', $order) }}" 
                       class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        <i class="ri-eye-line mr-2"></i>
                        View Order
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="max-w-4xl mx-auto">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Order Status -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">Order Status</h2>
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                        <i class="ri-settings-3-line text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <select name="status" 
                                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                                        <i class="ri-shopping-cart-line text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($order->items as $index => $item)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-primary/30 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1">
                                                @if($item->product && $item->product->image_url)
                                                <img src="{{ asset('images/' . $item->product->image_url) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="h-16 w-16 rounded-lg object-cover">
                                                @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <i class="ri-image-line text-gray-400 text-xl"></i>
                                                </div>
                                                @endif
                                                <div class="ml-4">
                                                    <h3 class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name ?? 'Unknown Product' }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        SKU: {{ $item->product->sku ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-28">
                                                    <label class="block text-sm text-gray-600 mb-1">Price</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                                        <input type="number" 
                                                               name="items[{{ $index }}][price]" 
                                                               value="{{ $item->price }}" 
                                                               step="0.01"
                                                               class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                                    </div>
                                                </div>
                                                <div class="w-24">
                                                    <label class="block text-sm text-gray-600 mb-1">Quantity</label>
                                                    <input type="number" 
                                                           name="items[{{ $index }}][quantity]" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1"
                                                           class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                                </div>
                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                                <button type="button" 
                                                        onclick="removeItem(this)" 
                                                        class="mt-6 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-red-500 transition-colors">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Side Panel -->
                    <div class="space-y-6">
                        <!-- Supplier Selection -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">Supplier</h2>
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                                        <i class="ri-truck-line text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <select name="supplier_id" 
                                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    <option value="">Select a supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">Customer</h2>
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                                        <i class="ri-user-line text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Customer Name</label>
                                        <input type="text" 
                                               value="{{ $order->user ? $order->user->first_name . ' ' . $order->user->last_name : '' }}" 
                                               disabled
                                               class="w-full border border-gray-200 rounded-lg p-2 bg-gray-50 text-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Customer Email</label>
                                        <input type="email" 
                                               value="{{ $order->user->email ?? '' }}" 
                                               disabled
                                               class="w-full border border-gray-200 rounded-lg p-2 bg-gray-50 text-gray-600">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <button type="submit" 
                                    class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center mb-3">
                                <i class="ri-save-line mr-2"></i>
                                Save Changes
                            </button>
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center">
                                <i class="ri-close-line mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function removeItem(button) {
    if (confirm('Are you sure you want to remove this item?')) {
        button.closest('.border').remove();
    }
}
</script>
@endsection 