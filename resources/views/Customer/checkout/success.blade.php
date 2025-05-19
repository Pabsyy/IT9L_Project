@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm p-8">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">Order Placed Successfully!</h2>
            <p class="mt-2 text-gray-600">Your order has been placed and is being processed.</p>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
            <dl class="mt-4 space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Order Number</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->id }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Total Amount</dt>
                    <dd class="text-sm font-medium text-gray-900">₱{{ number_format($order->total, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Status</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($order->status) }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8">
            <a href="{{ route('customer.orders') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View Orders
            </a>
        </div>
    </div>
</div>
@endsection 