<header class="bg-white shadow-sm z-10">
    <!-- Top Navigation -->
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <button class="md:hidden mr-4 text-gray-600">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-menu-line"></i>
                </div>
            </button>
<h1 class="text-xl font-semibold text-gray-800">
    @if (Route::is('dashboard'))
        Dashboard
    @elseif (Route::is('inventory'))
        Inventory
    @elseif (Route::is('orders'))
        Orders
    @elseif (Route::is('suppliers'))
        Suppliers
    @elseif (Route::is('analytics'))
        Analytics
    @else
        Dashboard
    @endif
</h1>
        </div>
        <div class="flex items-center">
            <div class="relative mr-4">
                <div class="w-6 h-6 flex items-center justify-center text-gray-600">
                    <i class="ri-notification-3-line"></i>
                </div>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </div>
            <div class="flex items-center">
                <div class="relative" id="userProfileDropdown">
                    <button id="userProfileButton" type="button" class="flex items-center space-x-2 focus:outline-none">
                        <img 
                        src="https://ui-avatars.com/api/?name={{ urlencode($userInitials ?? 'User') }}&background=6366F1&color=ffffff&size=32&bold=true&rounded=true"
                        alt="{{ $username ?? 'User' }}"
                            class="w-8 h-8 rounded-full"
                        />
                        <span class="text-sm font-medium text-gray-700 cursor-pointer select-none" id="userName">{{ $username }}</span>
                        <i class="ri-arrow-down-s-line text-gray-400 cursor-pointer" id="dropdownArrow"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="userDropdownMenu"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 hidden opacity-0 scale-95 transform transition-all duration-200 ease-out">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="ri-user-line mr-2 text-gray-400"></i>
                            My Profile
                        </a>
                        <a href="{{ route('settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="ri-settings-4-line mr-2 text-gray-400"></i>
                            Account Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="ri-list-settings-line mr-2 text-gray-400"></i>
                            Preferences
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="ri-question-line mr-2 text-gray-400"></i>
                            Help & Support
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="flex items-center px-4 py-2">
                            @csrf
                            <button type="submit" class="flex items-center text-sm text-red-600 hover:bg-gray-50 w-full">
                                <i class="ri-logout-box-r-line mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userProfileButton = document.getElementById('userProfileButton');
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        userProfileButton.addEventListener('click', () => {
            const isHidden = userDropdownMenu.classList.contains('hidden');
            if (isHidden) {
                userDropdownMenu.classList.remove('hidden', 'opacity-0', 'scale-95');
                userDropdownMenu.classList.add('opacity-100', 'scale-100');
            } else {
                userDropdownMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                userDropdownMenu.classList.remove('opacity-100', 'scale-100');
            }
        });

        // Optional: Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userProfileButton.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                userDropdownMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                userDropdownMenu.classList.remove('opacity-100', 'scale-100');
            }
        });
    });
</script>
