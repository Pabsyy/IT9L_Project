<!-- Header HTML Structure -->
<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
  <h1 class="text-2xl font-semibold text-gray-800">{{ $title }}</h1>
  <div class="flex items-center space-x-4">
    <div class="relative">
      <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 cursor-pointer">
        <i class="ri-notification-3-line text-gray-600"></i>
      </div>
      <span class="absolute top-0 right-0 h-5 w-5 flex items-center justify-center text-xs text-white bg-red-500 rounded-full">3</span>
    </div>
    <div class="relative" id="userProfileDropdown">
      <div class="flex items-center cursor-pointer" id="userProfileButton">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-2">
          <i class="ri-user-line text-gray-600"></i>
        </div>
        <span class="font-medium text-gray-700">{{ Auth::user()->username }}</span>
        <div class="w-5 h-5 flex items-center justify-center ml-1">
          <i class="ri-arrow-down-s-line"></i>
        </div>
      </div>
      <div id="userDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 hidden opacity-0 transform -translate-y-2 transition-all duration-200">
        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-indigo-600 hover:bg-gray-50">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-user-settings-line"></i>
          </div>
          <span>My Profile</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-settings-3-line"></i>
          </div>

          <span>Preferences</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-question-line"></i>
          </div>
          <span>Help & Support</span>
        </a>
        <div class="h-px bg-gray-200 my-2"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-50" onclick="event.preventDefault(); this.closest('form').submit();">
                <div class="w-5 h-5 flex items-center justify-center mr-3">
                    <i class="ri-logout-box-line"></i>
                </div>
                <span>Logout</span>
            </a>
        </form>
      </div>
    </div>
  </div>
</header>

<!-- Required CSS for Header Functionality -->
<style>
  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  
  input[type="number"] {
    -moz-appearance: textfield;
  }
  
  /* Animation styles for dropdown */
  #userDropdownMenu {
    transition: opacity 0.2s ease, transform 0.2s ease;
  }
</style>

<!-- Header JavaScript Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // User profile dropdown functionality
  const userProfileButton = document.getElementById('userProfileButton');
  const userDropdownMenu = document.getElementById('userDropdownMenu');
  const userProfileDropdown = document.getElementById('userProfileDropdown');
  let isDropdownOpen = false;

  if (userProfileButton && userDropdownMenu && userProfileDropdown) {
    userProfileButton.addEventListener('click', function(e) {
      e.stopPropagation();
      if (!isDropdownOpen) {
        userDropdownMenu.classList.remove('hidden');
        setTimeout(() => {
          userDropdownMenu.classList.remove('opacity-0', '-translate-y-2');
        }, 50);
      } else {
        userDropdownMenu.classList.add('opacity-0', '-translate-y-2');
        setTimeout(() => {
          userDropdownMenu.classList.add('hidden');
        }, 200);
      }
      isDropdownOpen = !isDropdownOpen;
    });

    document.addEventListener('click', function(e) {
      if (!userProfileDropdown.contains(e.target) && isDropdownOpen) {
        userDropdownMenu.classList.add('opacity-0', '-translate-y-2');
        setTimeout(() => {
          userDropdownMenu.classList.add('hidden');
        }, 200);
        isDropdownOpen = false;
      }
    });
  }
});
</script>
