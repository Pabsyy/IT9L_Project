@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    @include('sidebar')

    <div class="flex-1">
        @include('partials.header', ['title' => 'Inventory Management'])

        <main class="p-6">
            <!-- Search and Add New -->
            <div class="flex justify-between items-center mb-6">
                <input type="search" placeholder="Search inventory..." class="w-full md:w-96 pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2.5 rounded-button flex items-center justify-center">
                    <i class="ri-add-line mr-2"></i>
                    <span>Add New Item</span>
                </button>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="product-card bg-white rounded-lg shadow-sm overflow-hidden">
                        <img src="{{ asset($product->Image) }}" alt="{{ $product->ProductName }}" class="h-48 w-full object-cover">
                        <div class="p-4">
                            <h3 class="text-gray-800 font-medium mb-1">{{ $product->ProductName }}</h3>
                            <p class="text-primary font-semibold mb-2">₱{{ number_format($product->Price, 2) }}</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $product->Quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="ri-checkbox-circle-fill mr-1"></i>
                                {{ $product->Quantity > 0 ? 'In Stock (' . $product->Quantity . ')' : 'Out of Stock' }}
                            </span>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 flex justify-end">
                            <button onclick="openEditModal({{ $product->ProductID }}, '{{ $product->ProductName }}', {{ $product->Price }}, {{ $product->Quantity }}, '{{ $product->Category }}', '{{ $product->Description }}')" class="text-gray-500 hover:text-primary mr-3">
                                <i class="ri-edit-line"></i>
                            </button>
                            <form method="POST" action="{{ route('products.destroy', ['product' => $product->ProductID]) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                        <p class="text-sm text-gray-500 mb-4">Start by adding some products to your inventory.</p>
                        <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                            <i class="ri-add-line mr-2"></i>
                            Add New Item
                        </button>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
        <div class="border-b px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Edit Product</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <form id="editProductForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="ProductName" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" id="ProductName" name="ProductName" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="Price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" id="Price" name="Price" step="0.01" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="Quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="Quantity" name="Quantity" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="Category" class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" id="Category" name="Category" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="Description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="Description" name="Description" rows="3" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary"></textarea>
                </div>
                <div>
                    <label for="Image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" id="Image" name="Image" accept="image/*" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" onclick="closeEditModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, price, quantity, category, description) {
        // Set the form action to the update route
        document.getElementById('editProductForm').action = `/products/${id}`;
        document.getElementById('ProductName').value = name;
        document.getElementById('Price').value = price;
        document.getElementById('Quantity').value = quantity;
        document.getElementById('Category').value = category;
        document.getElementById('Description').value = description;

        // Show the modal
        const modal = document.getElementById('editProductModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        // Hide the modal
        const modal = document.getElementById('editProductModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection