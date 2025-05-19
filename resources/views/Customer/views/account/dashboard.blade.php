@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-6 py-8 sm:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            Welcome back, {{ auth()->user()->name }}!
                        </h1>
                        <p class="mt-2 text-indigo-100">
                            Manage your account and track your orders
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" 
                             class="h-20 w-20 rounded-full border-4 border-white shadow-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('account.settings') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                        <i class="fas fa-user-cog text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Profile Settings</h3>
                        <p class="text-sm text-gray-500">Update your information</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('account.orders') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">My Orders</h3>
                        <p class="text-sm text-gray-500">Track your purchases</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('account.addresses') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Addresses</h3>
                        <p class="text-sm text-gray-500">Manage shipping addresses</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('account.payment-methods') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Payment Methods</h3>
                        <p class="text-sm text-gray-500">Manage your cards</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Account Information</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->address ?? 'Not provided' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('account.orders') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-200">
                @if($orders->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-shopping-bag text-4xl mb-4"></i>
                        <p>You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.products.shop') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Start Shopping
                        </a>
                    </div>
                @else
                    @foreach($orders->take(3) as $order)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Order #{{ $order->id }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-lg font-medium text-gray-900">₱{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Wishlist -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Wishlist</h2>
                <a href="{{ route('customer.wishlist.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-200">
                @if($wishlist->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-heart text-4xl mb-4"></i>
                        <p>Your wishlist is empty.</p>
                        <a href="{{ route('customer.products.shop') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Browse Products
                        </a>
                    </div>
                @else
                    @foreach($wishlist->take(3) as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-16 w-16 object-cover rounded-lg">
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-500">₱{{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                </div>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Support Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-8 sm:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Need Help?</h2>
                        <p class="mt-2 text-blue-100">
                            Our support team is here to help you
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('support') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                            Contact Support
                        </a>
                        <a href="{{ route('faq') }}" class="inline-flex items-center px-4 py-2 border border-white text-sm font-medium rounded-md text-white hover:bg-indigo-500">
                            View FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 