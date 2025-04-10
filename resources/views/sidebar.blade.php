<aside id="sidebar" class="w-64 bg-gradient-to-b from-gray-100 to-gray-200 shadow-lg border-r border-gray-300 transition-all duration-300 ease-in-out transform hover:translate-x-0">
    <div class="p-6">
        <div class="h-28 flex items-center justify-center">
            <img src="{{ asset('images/UnderTheHoodLogo.png') }}" alt="UnderTheHood Logo" class="h-full w-auto rounded-lg">
        </div>
    </div>
    <nav class="mt-6">
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-4 text-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2 {{ Route::is('dashboard') ? 'text-gray-900 font-bold bg-gray-100 border-r-4 border-custom' : '' }}">
            <i class="fas fa-chart-line w-6 h-6"></i>
            <span class="mx-4">Dashboard</span>
        </a>
        <a href="{{ route('inventory') }}" class="flex items-center px-6 py-4 text-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2 {{ Route::is('inventory') ? 'text-gray-900 font-bold bg-gray-100 border-r-4 border-custom' : '' }}">
            <i class="fas fa-box w-6 h-6"></i>
            <span class="mx-4">Inventory</span>
        </a>
        <a href="{{ route('orders') }}" class="flex items-center px-6 py-4 text-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2 {{ Route::is('orders') ? 'text-gray-900 font-bold bg-gray-100 border-r-4 border-custom' : '' }}">
            <i class="fas fa-shopping-cart w-6 h-6"></i>
            <span class="mx-4">Orders</span>
        </a>
        <a href="{{ route('suppliers') }}" class="flex items-center px-6 py-4 text-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2 {{ Route::is('suppliers') ? 'text-gray-900 font-bold bg-gray-100 border-r-4 border-custom' : '' }}">
            <i class="fas fa-truck w-6 h-6"></i>
            <span class="mx-4">Suppliers</span>
        </a>
        <a href="{{ route('analytics') }}" class="flex items-center px-6 py-4 text-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2 {{ Route::is('analytics') ? 'text-gray-900 font-bold bg-gray-100 border-r-4 border-custom' : '' }}">
            <i class="fas fa-chart-bar w-6 h-6"></i>
            <span class="mx-4">Analytics</span>
        </a>
    </nav>
</aside>
