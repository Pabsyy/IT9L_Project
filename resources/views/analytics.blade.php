<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\analytics.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">

    <!-- Main Content for Analytics -->
    <div class="flex-1">
        <main class="p-2">
            <!-- Example Chart Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <select class="border-gray-200 rounded-md text-sm">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 90 Days</option>
                    </select>
                </div>
                <div id="revenue-chart" class="h-80"></div>
            </div>

            <!-- Revenue Table Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Overview</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b py-2">Date</th>
                            <th class="border-b py-2">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesData as $data)
                        <tr>
                            <td class="border-b py-2">{{ $data->date }}</td>
                            <td class="border-b py-2">${{ number_format($data->total, 2) }}</td>
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