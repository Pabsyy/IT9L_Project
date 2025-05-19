@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your shipping addresses</p>
        </div>

        <!-- Add New Address Button -->
        <div class="mb-8">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Add New Address
            </button>
        </div>

        <!-- Addresses List -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($addresses ?? [] as $address)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $address->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $address->phone_number }}</p>
                            </div>
                            @if($address->is_default)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Default
                                </span>
                            @endif
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-900">{{ $address->street_address }}</p>
                            <p class="text-sm text-gray-900">{{ $address->city }}, {{ $address->state }}</p>
                            <p class="text-sm text-gray-900">{{ $address->postal_code }}</p>
                            <p class="text-sm text-gray-900">{{ $address->country }}</p>
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="button" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Edit
                                </button>
                                <button type="button" class="text-sm font-medium text-red-600 hover:text-red-500">
                                    Delete
                                </button>
                            </div>
                            @if(!$address->is_default)
                                <button type="button" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                                    Set as Default
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-map-marker-alt text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No addresses yet</h3>
                            <p class="text-gray-500 mb-6">Add your first shipping address to get started</p>
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Add New Address
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Add/Edit Address Modal -->
        <div class="hidden" id="addressModal">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add New Address</h3>
                            <form>
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="street" class="block text-sm font-medium text-gray-700">Street Address</label>
                                        <input type="text" name="street" id="street" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                            <input type="text" name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                                            <input type="text" name="state" id="state" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                            <input type="text" name="postal_code" id="postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                            <select name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="PH">Philippines</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_default" id="is_default" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as default address</label>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Cancel
                                    </button>
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Save Address
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add your JavaScript for handling the address modal and form submission here
</script>
@endpush
@endsection 