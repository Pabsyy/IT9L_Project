@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Create New Order</h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                    <input type="text" name="customer_name" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email</label>
                    <input type="email" name="customer_email" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select name="payment_method" class="w-full border border-gray-300 rounded-md p-2" required>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Order Items</h3>
                <div id="items-container">
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                            <select name="items[0][product_id]" class="w-full border border-gray-300 rounded-md p-2" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - ₱{{ number_format($product->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" name="items[0][quantity]" min="1" class="w-full border border-gray-300 rounded-md p-2" required>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addItem()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="ri-add-line"></i> Add Another Item
                </button>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('orders') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Order</button>
            </div>
        </form>
    </div>
</div>

<script>
let itemCount = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const newItem = `
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <select name="items[${itemCount}][product_id]" class="w-full border border-gray-300 rounded-md p-2" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - ₱{{ number_format($product->price, 2) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="number" name="items[${itemCount}][quantity]" min="1" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    itemCount++;
}
</script>
@endsection
