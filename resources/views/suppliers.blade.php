<!-- filepath: d:\Ryan's not so important files\Documents\Projects\IT9L_Project\Admin Panel\resources\views\suppliers.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content for Suppliers -->
    <div class="flex-1">
        @include('partials.header', ['title' => 'Suppliers Management'])
        <main class="p-6">
            <!-- Suppliers Search and Add Button -->
            <div class="flex justify-between items-center mb-6">
                <input type="text" placeholder="Search suppliers..." class="border-gray-200 rounded-md w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300" />
                <button onclick="openAddSupplierModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-md hover:shadow-lg transition duration-300">Add Supplier</button>
            </div>
            <!-- Suppliers Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-600">
                            <th class="pb-4">Supplier Name</th>
                            <th class="pb-4">Contact</th>
                            <th class="pb-4">Email</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition duration-300">
                            <td class="py-4">{{ $supplier->SupplierName }}</td>
                            <td class="py-4">{{ $supplier->ContactNumber }}</td>
                            <td class="py-4">{{ $supplier->Email }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                            </td>
                            <td class="py-4">
                                <button onclick="openContactSupplierModal('{{ $supplier->SupplierName }}', '{{ $supplier->Email }}', '{{ $supplier->ContactNumber }}')" class="text-blue-600 hover:text-blue-800 mr-2 transition duration-300">Contact</button>
                                <button class="text-red-600 hover:text-red-800 transition duration-300">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<div id="addSupplierModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Add Supplier</h2>
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="SupplierName" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                <input type="text" name="SupplierName" id="SupplierName" class="border-gray-300 rounded-md w-full" required>
            </div>
            <div class="mb-4">
                <label for="ContactNumber" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="ContactNumber" id="ContactNumber" class="border-gray-300 rounded-md w-full" required>
            </div>
            <div class="mb-4">
                <label for="Email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="Email" id="Email" class="border-gray-300 rounded-md w-full" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('addSupplierModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
            </div>
        </form>
    </div>
</div>

<div id="contactSupplierModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4 text-center">Contact Supplier</h2>
        <form action="{{ route('suppliers.contact') }}" method="POST" class="text-center">
            @csrf
            <input type="hidden" name="Email" id="contactSupplierEmailInput">
            <input type="hidden" name="ContactNumber" id="contactSupplierNumberInput">
            <div class="mb-4">
                <label for="SupplierName" class="block text-sm font-medium text-gray-700 text-center">Supplier Name</label>
                <input type="text" id="contactSupplierNameInput" class="border-gray-300 rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 text-center" disabled>
            </div>
            <div class="mb-4">
                <label for="Message" class="block text-sm font-medium text-gray-700 text-center">Message</label>
                <textarea name="Message" id="Message" rows="4" class="border-gray-300 rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 text-center" required></textarea>
            </div>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeModal('contactSupplierModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send</button>
            </div>
        </form>
    </div>
</div>

<!-- Include the reusable sidebar script -->
<script src="{{ asset('js/sidebar.js') }}"></script>
<script>

    function openAddSupplierModal() {
        const modal = document.getElementById('addSupplierModal');
        modal.classList.remove('hidden');
    }

    function openContactSupplierModal(name, email, contactNumber) {
        const modal = document.getElementById('contactSupplierModal');
        document.getElementById('contactSupplierNameInput').value = name; // Set supplier name
        document.getElementById('contactSupplierEmailInput').value = email; // Set supplier email
        document.getElementById('contactSupplierNumberInput').value = contactNumber; // Set supplier contact number
        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }
</script>
@endsection