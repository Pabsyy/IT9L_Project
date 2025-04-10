<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <button id="menu-toggle" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="ml-6 text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <button class="relative text-gray-600 hover:text-gray-900">
                <i class="fas fa-bell"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 rounded-full w-2 h-2"></span>
            </button>
            <div class="relative flex items-center">
                <img src="{{ Auth::user()->profile_picture_url ? asset('images/' . Auth::user()->profile_picture_url) : asset('images/default-avatar.png') }}" alt="User Image" class="w-8 h-8 rounded-full border">
                <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->username }}</span>
                <button id="settings-button" class="text-gray-600 hover:text-gray-900 ml-4 relative">
                    <i class="fas fa-cog"></i>
                </button>
                <!-- Dropdown Menu -->
                <div id="settings-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('settings-button').addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent click from propagating to document
        const menu = document.getElementById('settings-menu');
        menu.classList.toggle('hidden');
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function () {
        const menu = document.getElementById('settings-menu');
        menu.classList.add('hidden');
    });
</script>
