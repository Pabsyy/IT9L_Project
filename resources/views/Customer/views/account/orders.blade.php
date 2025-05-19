@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-2 text-sm text-gray-600">View and track your order history</p>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($orders->isEmpty())
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-shopping-bag text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                    <p class="text-gray-500 mb-6">Start shopping to see your orders here</p>
                    <a href="{{ route('customer.products.shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Start Shopping
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            Order #{{ $order->id }}
                                        </h3>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-2 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                                        <div class="mt-2 sm:mt-0 flex items-center text-sm text-gray-500">
                                            <i class="far fa-calendar-alt mr-1.5"></i>
                                            Placed on {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="mt-2 sm:mt-0 flex items-center text-sm text-gray-500">
                                            <i class="fas fa-shopping-cart mr-1.5"></i>
                                            {{ $order->items_count ?? 0 }} items
                                        </div>
                                        <div class="mt-2 sm:mt-0 flex items-center text-sm text-gray-500">
                                            <i class="fas fa-tag mr-1.5"></i>
                                            Total: ₱{{ number_format($order->total, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0 sm:ml-6 flex items-center space-x-4">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    @if($order->status === 'processing')
                                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-truck mr-2"></i>
                                            Track Order
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection 