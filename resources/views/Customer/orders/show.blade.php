@extends('Customer.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->reference_number }}</h1>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Shipping Information</h2>
                    @if($order->shipping_info)
                        <div class="text-sm text-gray-600">
                            <p>{{ $order->shipping_info['name'] ?? '' }}</p>
                            <p>{{ $order->shipping_info['address'] ?? '' }}</p>
                            <p>{{ $order->shipping_info['city'] ?? '' }}, {{ $order->shipping_info['state'] ?? '' }} {{ $order->shipping_info['zip'] ?? '' }}</p>
                            <p>{{ $order->shipping_info['phone'] ?? '' }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No shipping information available</p>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Payment Information</h2>
                    @if($order->payment_info)
                        <div class="text-sm text-gray-600">
                            <p>Payment Method: {{ $order->payment_info['method'] ?? 'N/A' }}</p>
                            <p>Transaction ID: {{ $order->payment_info['transaction_id'] ?? 'N/A' }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No payment information available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Order Items</h2>
            <div class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                <div class="py-4 flex items-center">
                    <div class="flex-shrink-0 w-16 h-16">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">
                            <a href="{{ route('customer.products.show', $item->product) }}" class="hover:text-primary">
                                {{ $item->product->name }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                        <p class="mt-1 text-sm text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Total -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Subtotal</span>
                <span>₱{{ number_format($order->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Tax</span>
                <span>₱{{ number_format($order->tax ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Shipping</span>
                <span>₱{{ number_format($order->shipping ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between text-lg font-medium text-gray-900 pt-2 border-t">
                <span>Total</span>
                <span>₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Order Actions -->
        @if($order->status === 'pending' || $order->status === 'processing')
        <div class="mt-6 flex justify-end">
            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel Order
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection 