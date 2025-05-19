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
                    <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->order_id }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Created {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" class="flex items-center">
                        @csrf
                        @method('PUT')
                        <select name="order_status" 
                                onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Order Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Amount -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-indigo-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Total Amount</span>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <i class="ri-money-dollar-circle-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">₱{{ number_format($order->grand_total, 2) }}</h3>
                    <div class="text-sm text-gray-500">{{ $order->items->count() }} items</div>
                </div>

                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 
                    {{ $order->order_status === 'completed' ? 'border-green-500' : '' }}
                    {{ $order->order_status === 'processing' ? 'border-blue-500' : '' }}
                    {{ $order->order_status === 'pending' ? 'border-yellow-500' : '' }}
                    {{ $order->order_status === 'cancelled' ? 'border-red-500' : '' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Order Status</span>
                        <div class="w-10 h-10 rounded-full 
                            {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}
                            {{ $order->order_status === 'processing' ? 'bg-blue-100 text-blue-500' : '' }}
                            {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                            {{ $order->order_status === 'cancelled' ? 'bg-red-100 text-red-500' : '' }}
                            flex items-center justify-center">
                            <i class="ri-checkbox-circle-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ ucfirst($order->order_status) }}</h3>
                    <div class="text-sm text-gray-500">Last updated {{ $order->updated_at->diffForHumans() }}</div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-purple-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Customer</span>
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                            <i class="ri-user-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold mb-1 truncate">{{ $order->customer_name }}</h3>
                    <div class="text-sm text-gray-500 truncate">{{ $order->customer_email }}</div>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-b-4 border-blue-500">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm">Payment</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <i class="ri-bank-card-line text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold mb-1">{{ ucfirst($order->payment_method) }}</h3>
                    <div class="text-sm text-gray-500">{{ ucfirst($order->payment_status) }}</div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Items -->
                <div class="lg:col-span-2 space-y-6">
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
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($order->items as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->product && $item->product->image_url)
                                                    <img class="h-10 w-10 rounded-lg object-cover" 
                                                         src="{{ asset('storage/' . $item->product->image_url) }}" 
                                                         alt="{{ $item->product->name }}">
                                                    @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <i class="ri-image-line text-gray-400"></i>
                                                    </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name ?? 'Unknown Product' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            SKU: {{ $item->product->sku ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-gray-900">₱{{ number_format($item->unit_price, 2) }}</div>
                                                <div class="text-xs text-gray-500">per unit</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->quantity }}</div>
                                                <div class="text-xs text-gray-500">units</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-gray-900">₱{{ number_format($item->subtotal, 2) }}</div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">₱{{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->tax > 0)
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Tax</td>
                                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">₱{{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->shipping_fee > 0)
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Shipping</td>
                                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">₱{{ number_format($order->shipping_fee, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->discount > 0)
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Discount</td>
                                            <td class="px-6 py-4 text-right text-sm font-medium text-red-600">-₱{{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="bg-gray-100">
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total Amount</td>
                                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Shipping Information</h2>
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                    <i class="ri-truck-line text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Delivery Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->delivery_method) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address }}</dd>
                                </div>
                                @if($order->contact_number)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->contact_number }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Order Timeline</h2>
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                                    <i class="ri-time-line text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @if($order->delivered_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center ring-8 ring-white">
                                                        <i class="ri-check-line text-green-500"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order delivered</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $order->delivered_at }}">{{ $order->delivered_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($order->shipped_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                                        <i class="ri-truck-line text-blue-500"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order shipped</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $order->shipped_at }}">{{ $order->shipped_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($order->paid_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center ring-8 ring-white">
                                                        <i class="ri-bank-card-line text-green-500"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Payment received</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $order->paid_at }}">{{ $order->paid_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                                        <i class="ri-shopping-cart-line text-gray-500"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order placed</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $order->created_at }}">{{ $order->created_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 