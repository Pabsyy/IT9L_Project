<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\inventory.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gradient-to-r from-purple-500 to-blue-500 text-white">
    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content -->
    <div class="flex-1">
        @include('partials.header', ['title' => 'Inventory Management'])
        <main class="p-6">
            <!-- Inventory Search and Add Button -->
            <div class="flex justify-between items-center mb-6">
                <input type="text" placeholder="Search inventory..." class="border-gray-300 rounded-md w-64 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-300 text-gray-800" />
                <button onclick="openAddModal()" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-md shadow-md hover:from-purple-700 hover:to-blue-700 transition-all duration-300">Add New Item</button>
            </div>
            <!-- Inventory Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($products as $product)
                <div onclick="openDetailsModal('{{ $product->ProductName }}', '{{ $product->SKU }}', '{{ $product->Category }}', {{ $product->Quantity }}, {{ $product->Price }}, '{{ $product->Description }}', '{{ $product->Image }}', {{ $product->ProductID }})" 
                     class="bg-white text-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1 cursor-pointer">
                    <img src="{{ $product->Image }}" alt="{{ $product->ProductName }}" class="h-56 w-full object-cover rounded-md mb-3 border border-gray-200">
                    <div class="flex flex-col justify-between h-32">
                        <div class="mb-2">
                            <h3 class="text-base font-bold truncate" title="{{ $product->ProductName }}">{{ $product->ProductName }}</h3>
                            <p class="text-sm font-medium">${{ number_format($product->Price, 2) }}</p>
                        </div>
                        <div>
                            @if ($product->Quantity > 0)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">In Stock</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links('pagination::tailwind') }}
            </div>
        </main>
    </div>
</div>

<div id="toastContainer" class="fixed top-5 right-5 z-50 space-y-4"></div>

<!-- Add New Item Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl transform transition-transform duration-300 scale-95">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Item</h2>
        <form id="addForm" action="/products" method="POST" enctype="multipart/form-data" onsubmit="saveNewItem(event)">
            @csrf
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    <img id="addImagePreview" src="" alt="Product Image" class="h-64 w-64 object-cover rounded-md border border-gray-200 hidden">
                    <div class="mt-4">
                        <label for="addImage" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-md cursor-pointer hover:bg-blue-700 transition-all duration-300">
                            Choose File
                        </label>
                        <input type="file" name="Image" id="addImage" class="hidden" onchange="updateAddFileName(this)">
                        <span id="addFileName" class="ml-3 text-sm text-gray-600">No file chosen</span>
                    </div>
                </div>
                <!-- Form Fields -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="addProductName" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="ProductName" id="addProductName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="addSKU" class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="SKU" id="addSKU" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="addCategory" class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="Category" id="addCategory" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="addQuantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="Quantity" id="addQuantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="addPrice" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" name="Price" id="addPrice" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="col-span-2">
                        <label for="addDescription" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="Description" id="addDescription" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600 transition-all duration-300 mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition-all duration-300">Add Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl transform transition-transform duration-300 scale-95">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Product</h2>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data" onsubmit="saveChanges(event)">
            @csrf
            @method('PUT')
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    <img id="modalImagePreview" src="" alt="Product Image" class="h-64 w-64 object-cover rounded-md border border-gray-200 hidden">
                    <div class="mt-4">
                        <label for="modalImage" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-md cursor-pointer hover:bg-blue-700 transition-all duration-300">
                            Choose File
                        </label>
                        <input type="file" name="Image" id="modalImage" class="hidden" onchange="updateFileName(this)">
                        <span id="fileName" class="ml-3 text-sm text-gray-600">No file chosen</span>
                    </div>
                </div>
                <!-- Form Fields -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ProductName" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="ProductName" id="modalProductName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="SKU" class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="SKU" id="modalSKU" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="Category" class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="Category" id="modalCategory" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="Quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="Quantity" id="modalQuantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="Price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" name="Price" id="modalPrice" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="col-span-2">
                        <label for="Description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="Description" id="modalDescription" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600 transition-all duration-300 mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition-all duration-300">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl transform transition-transform duration-300 scale-95">
        <div class="flex flex-col md:flex-row">
            <!-- Product Image -->
            <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                <img id="detailsImage" src="" alt="Product Image" class="h-80 w-80 object-cover rounded-md border border-gray-200">
            </div>
            <!-- Product Details -->
            <div class="flex-1">
                <h2 class="text-4xl font-bold mb-6 text-gray-800" id="detailsProductName">Product Details</h2>
                <p class="text-2xl font-semibold text-gray-700 mb-4"><strong>Price:</strong> $<span id="detailsPrice"></span></p>
                <p class="text-lg text-gray-600 mb-3"><strong>SKU:</strong> <span id="detailsSKU"></span></p>
                <p class="text-lg text-gray-600 mb-3"><strong>Category:</strong> <span id="detailsCategory"></span></p>
                <p class="text-lg text-gray-600 mb-3"><strong>Stock:</strong> <span id="detailsQuantity"></span></p>
                <p class="text-lg text-gray-600 mb-4"><strong>Description:</strong></p>
                <p class="text-lg text-gray-600 text-justify mb-6" id="detailsDescription"></p>
                <!-- Action Buttons -->
                <div class="flex space-x-6">
                    <button onclick="closeDetailsModal(); openEditModal(currentProductID, document.getElementById('detailsProductName').textContent, document.getElementById('detailsSKU').textContent, document.getElementById('detailsCategory').textContent, document.getElementById('detailsQuantity').textContent, parseFloat(document.getElementById('detailsPrice').textContent), document.getElementById('detailsImage').src, document.getElementById('detailsDescription').textContent)" 
                            class="px-6 py-3 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition-all duration-300">
                        Edit
                    </button>
                    <button onclick="deleteProduct(currentProductID)" 
                            class="px-6 py-3 bg-red-500 text-white rounded-md shadow-md hover:bg-red-600 transition-all duration-300">
                        Delete
                    </button>
                    <button type="button" onclick="closeDetailsModal()" 
                            class="px-6 py-3 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600 transition-all duration-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentProductID = null;

    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function updateAddFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'No file chosen';
        document.getElementById('addFileName').textContent = fileName;

        const preview = document.getElementById('addImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }

    async function saveNewItem(event) {
        event.preventDefault();

        const form = document.getElementById('addForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                showToast('Product added successfully!', 'success');
                closeAddModal();
                location.reload();
            } else {
                const error = await response.json();
                showToast('Failed to add product: ' + (error.message || 'Unknown error'), 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('An error occurred while adding the product.', 'error');
        }
    }

    function openEditModal(ProductID, ProductName, SKU, Category, Quantity, Price, Image, Description) {
        document.getElementById('editForm').action = `/products/${ProductID}`;
        document.getElementById('modalProductName').value = ProductName;
        document.getElementById('modalSKU').value = SKU;
        document.getElementById('modalCategory').value = Category;
        document.getElementById('modalQuantity').value = Quantity;
        document.getElementById('modalPrice').value = Price;
        document.getElementById('modalDescription').value = Description;

        if (Image) {
            const imagePreview = document.getElementById('modalImagePreview');
            imagePreview.src = Image;
            imagePreview.classList.remove('hidden');
        }

        // Automatically open the modal
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'No file chosen';
        document.getElementById('fileName').textContent = fileName;
    }

    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `px-4 py-3 rounded-md shadow-md text-white text-sm ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } transition-opacity duration-300 opacity-0`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        // Fade in
        setTimeout(() => {
            toast.classList.add('opacity-100');
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('opacity-100');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    async function saveChanges(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        const form = document.getElementById('editForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const result = await response.json();
                showToast('Product updated successfully!', 'success');
                closeEditModal();
                location.reload(); // Reload the page to reflect the changes
            } else {
                const error = await response.json();
                showToast('Failed to update product: ' + (error.message || 'Unknown error'), 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('An error occurred while updating the product.', 'error');
        }
    }

    function openDetailsModal(ProductName, SKU, Category, Quantity, Price, Description, Image, ProductID) {
        currentProductID = ProductID;
        document.getElementById('detailsProductName').textContent = ProductName;
        document.getElementById('detailsSKU').textContent = SKU;
        document.getElementById('detailsCategory').textContent = Category;
        document.getElementById('detailsQuantity').textContent = Quantity;
        document.getElementById('detailsPrice').textContent = Price.toFixed(2);
        document.getElementById('detailsDescription').textContent = Description;

        if (Image) {
            const detailsImage = document.getElementById('detailsImage');
            detailsImage.src = Image;
            detailsImage.classList.remove('hidden');
        }

        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }

    function deleteProduct(ProductID) {
        if (confirm('Are you sure you want to delete this product?')) {
            fetch(`/products/${ProductID}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    showToast('Product deleted successfully!', 'success');
                    closeDetailsModal();
                    location.reload();
                } else {
                    showToast('Failed to delete product.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while deleting the product.', 'error');
            });
        }
    }
</script>

<!-- Include the reusable sidebar script -->
<script src="{{ asset('js/sidebar.js') }}"></script>
@endsection