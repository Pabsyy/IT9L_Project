<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\orders.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content for Orders -->
    <div class="flex-1">
        @include('partials.header', ['title' => 'Orders Management'])
        <main class="p-6">
            <!-- Orders Search and Create Button -->
            <div class="flex justify-between items-center mb-6">
                <input type="text" placeholder="Search orders..." class="border-gray-200 rounded-md w-64" />
                <button class="px-4 py-2 bg-custom text-white rounded-md">Create Order</button>
            </div>
            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-600">
                            <th class="pb-4">Order ID</th>
                            <th class="pb-4">Customer</th>
                            <th class="pb-4">Date</th>
                            <th class="pb-4">Total</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr class="border-t border-gray-100">
                            <td class="py-4">#{{ $order->TransactionID }}</td>
                            <td class="py-4">{{ $order->UserID }}</td>
                            <td class="py-4">{{ $order->TransactionDate }}</td>
                            <td class="py-4">${{ number_format($order->GrandTotal, 2) }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Completed
                                </span>
                            </td>
                            <td class="py-4">
                                <button class="text-blue-600 hover:text-blue-800 mr-2">View</button>
                                <button class="text-red-600 hover:text-red-800">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Include the reusable sidebar script -->
<script src="{{ asset('js/sidebar.js') }}"></script>
@endsection