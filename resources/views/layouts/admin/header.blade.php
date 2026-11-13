<header class="bg-white shadow-md py-4 px-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <button id="sidebar-toggle" class="md:hidden p-2 mr-4 text-gray-800">
                <i class="ri-menu-line text-2xl"></i>
            </button>
            <h1 class="text-xl font-semibold">Admin Dashboard</h1>
        </div>
        <div class="flex items-center space-x-4 relative">
            <button id="profileDropdownButton" class="p-2 rounded-full hover:bg-gray-100 flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                <i class="ri-user-line text-xl"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="profileDropdown" class="hidden absolute right-0 top-12 w-56 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="ri-user-settings-line mr-2"></i>Profile Settings
                </a>
                <a href="{{ route('admin.2fa.setup') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    @if(Auth::user()->has2faEnabled())
                        <span class="flex items-center justify-between">
                            <i class="ri-shield-keyhole-line mr-2"></i>2FA Settings
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                Enabled
                            </span>
                        </span>
                    @else
                        <span class="flex items-center justify-between">
                            <i class="ri-shield-keyhole-line mr-2"></i>2FA Settings
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                Disabled
                            </span>
                        </span>
                    @endif
                </a>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="ri-logout-box-line mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // Profile Dropdown Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('profileDropdownButton');
        const dropdown = document.getElementById('profileDropdown');
        
        // Toggle dropdown
        dropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !dropdownButton.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Close dropdown when pressing escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>