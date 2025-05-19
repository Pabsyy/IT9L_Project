@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Account Dashboard</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-8">
                <h3 class="text-2xl font-semibold text-gray-900">
                    Welcome, {{ auth()->user()->name }}!
                </h3>
                <p class="mt-2 text-gray-600">
                    Manage your account settings and view your orders.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ auth()->user()->email }}
                        </dd>
                    </div>
                    <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ auth()->user()->created_at->format('M d, Y') }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ auth()->user()->phone ?? 'Not provided' }}
                        </dd>
                    </div>
                    <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ auth()->user()->address ?? 'Not provided' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Order History</h2>
            @if($orders->isEmpty())
                <p class="text-gray-600">You have no orders yet.</p>
            @else
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <li>
                                <a href="{{ route('customer.orders.show', $order) }}" class="block hover:bg-gray-50">
                                    <div class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-medium text-indigo-600 truncate">
                                                Order #{{ $order->id }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($order->status) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    Total: ₱{{ number_format($order->total, 2) }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <p>
                                                    Placed on {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Wishlist</h2>
            @if($wishlist->isEmpty())
                <p class="text-gray-600">Your wishlist is empty.</p>
            @else
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($wishlist as $item)
                            <li>
                                <div class="px-6 py-4">
                                    <p class="text-lg font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Account Settings</h2>
            <a href="{{ route('account.settings') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Edit Profile
            </a>
            <a href="{{ route('account.password') }}" class="ml-4 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Change Password
            </a>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Methods</h2>
            <a href="{{ route('account.payment-methods') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Manage Payment Methods
            </a>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Shipping Addresses</h2>
            <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Manage Addresses
            </a>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews and Ratings</h2>
            <a href="{{ route('account.reviews') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                View Reviews
            </a>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Support and Help</h2>
            <a href="{{ route('support') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Contact Support
            </a>
            <a href="{{ route('faq') }}" class="ml-4 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                FAQ
            </a>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Promotions and Offers</h2>
            <p class="text-gray-600">Check out our latest promotions and offers!</p>
            <a href="{{ route('promotions') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View Promotions
            </a>
        </div>

        <div class="mt-8">
            <a href="{{ route('logout') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Logout
            </a>
        </div>
    </div>
</div>
@endsection 