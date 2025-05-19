@extends('customer.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Payment Methods</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your saved payment methods</p>
        </div>

        <!-- Add New Payment Method Button -->
        <div class="mb-8">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Add New Payment Method
            </button>
        </div>

        <!-- Payment Methods List -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($paymentMethods ?? [] as $method)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($method->type === 'credit_card')
                                        <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                                    @elseif($method->type === 'gcash')
                                        <i class="fas fa-mobile-alt text-2xl text-gray-400"></i>
                                    @else
                                        <i class="fas fa-wallet text-2xl text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ ucfirst($method->type) }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        @if($method->type === 'credit_card')
                                            **** **** **** {{ $method->last_four }}
                                        @else
                                            {{ $method->account_number }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($method->is_default)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Default
                                </span>
                            @endif
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="button" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Edit
                                </button>
                                <form action="{{ route('account.payment-methods.delete', $method) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @if(!$method->is_default)
                                <form action="{{ route('account.payment-methods.make-default', $method) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                                        Set as Default
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No payment methods yet</h3>
                            <p class="text-gray-500 mb-6">Add your first payment method to get started</p>
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Add New Payment Method
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Add/Edit Payment Method Modal -->
        <div class="hidden" id="paymentMethodModal">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add New Payment Method</h3>
                            <form action="{{ route('account.payment-methods.store') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="credit_card">Credit Card</option>
                                            <option value="gcash">GCash</option>
                                        </select>
                                    </div>

                                    <!-- Credit Card Fields -->
                                    <div id="creditCardFields" class="space-y-4">
                                        <div>
                                            <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                            <input type="text" name="card_number" id="card_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                                <input type="text" name="cvv" id="cvv" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- GCash Fields -->
                                    <div id="gcashFields" class="space-y-4 hidden">
                                        <div>
                                            <label for="account_number" class="block text-sm font-medium text-gray-700">GCash Account Number</label>
                                            <input type="text" name="account_number" id="account_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_default" id="is_default" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as default payment method</label>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Cancel
                                    </button>
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Save Payment Method
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
    // Toggle payment method fields based on type
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const creditCardFields = document.getElementById('creditCardFields');
        const gcashFields = document.getElementById('gcashFields');

        if (type === 'credit_card') {
            creditCardFields.classList.remove('hidden');
            gcashFields.classList.add('hidden');
        } else {
            creditCardFields.classList.add('hidden');
            gcashFields.classList.remove('hidden');
        }
    });

    // Format card number input
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})/g, '$1 ').trim();
        e.target.value = value;
    });

    // Format expiry date input
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2,4);
        }
        e.target.value = value;
    });
</script>
@endpush
@endsection 