@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

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
</div>
@endsection 