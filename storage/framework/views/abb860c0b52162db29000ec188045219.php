<nav class="flex items-center justify-between py-4 px-4 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
    <div class="flex items-center">
        <button onclick="toggleWalletDropdown()" class="flex items-center gap-2 bg-trust-gray dark:bg-gray-700 px-3 py-2 rounded-lg">
            <i class="fas fa-wallet text-trust-blue dark:text-blue-400 text-lg"></i>
            <span class="font-medium text-gray-900 dark:text-white" id="accountId"><?php echo e($accountId ?? 'My Wallet'); ?></span>
            <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-300" id="dropdownIcon"></i>
        </button>
    </div>
    <div class="flex items-center gap-2">
        <!-- Dark Mode Toggle Button -->
        <button
            onclick="toggleDarkMode()"
            id="darkModeToggleNav"
            class="p-2 rounded-full bg-trust-gray dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode"
        >
            <!-- Sun icon for dark mode -->
            <svg id="sunIconNav" class="w-5 h-5 text-yellow-400 hidden" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <!-- Moon icon for light mode -->
            <svg id="moonIconNav" class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
        
        <!-- Notification Icon -->
        <a href="<?php echo e(route('notifications')); ?>" class="relative bg-trust-gray dark:bg-gray-700 p-2 rounded-full">
            <i class="fas fa-bell text-trust-blue dark:text-blue-400 text-lg"></i>
            <span id="mobile-profile-notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">
                0
            </span>
        </a>
    </div>
    
    <!-- Dropdown Menu -->
    <div id="walletDropdown" class="absolute top-14 md:top-16 md:left-[270px] left-4 w-60 rounded-xl shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 z-50 hidden">
        <div class="py-1">
            <div class="border-b border-gray-100 dark:border-gray-700 my-1"></div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-900 dark:text-white hover:bg-trust-gray dark:hover:bg-gray-700">
                    <i class="fas fa-sign-out-alt mr-3 text-trust-blue dark:text-blue-400"></i>
                    <?php echo e(__('Logout')); ?>

                </button>
            </form>
        </div>
    </div>
</nav>

<script>
function toggleWalletDropdown() {
    const dropdown = document.getElementById('walletDropdown');
    const icon = document.getElementById('dropdownIcon');
    dropdown.classList.toggle('hidden');
    icon.classList.toggle('transform');
    icon.classList.toggle('rotate-180');
    
    // Close dropdown when clicking outside
    if (!dropdown.classList.contains('hidden')) {
        document.addEventListener('click', function closeDropdown(event) {
            if (!event.target.closest('#walletDropdown') && !event.target.closest('button[onclick="toggleWalletDropdown()"]')) {
                dropdown.classList.add('hidden');
                icon.classList.remove('transform', 'rotate-180');
                document.removeEventListener('click', closeDropdown);
            }
        });
    }
}

// Dark Mode Toggle Function
function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    const sunIcon = document.getElementById('sunIconNav');
    const moonIcon = document.getElementById('moonIconNav');
    
    if (isDark) {
        // Switch to light mode
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
        // Hide sun, show moon
        if (sunIcon) sunIcon.classList.add('hidden');
        if (moonIcon) moonIcon.classList.remove('hidden');
    } else {
        // Switch to dark mode
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
        // Show sun, hide moon
        if (sunIcon) sunIcon.classList.remove('hidden');
        if (moonIcon) moonIcon.classList.add('hidden');
    }
}

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const sunIcon = document.getElementById('sunIconNav');
    const moonIcon = document.getElementById('moonIconNav');
    
    // Check localStorage for dark mode preference
    const darkMode = localStorage.getItem('darkMode');
    
    if (darkMode === 'true') {
        html.classList.add('dark');
        if (sunIcon) sunIcon.classList.remove('hidden');
        if (moonIcon) moonIcon.classList.add('hidden');
    } else if (darkMode === 'false') {
        html.classList.remove('dark');
        if (sunIcon) sunIcon.classList.add('hidden');
        if (moonIcon) moonIcon.classList.remove('hidden');
    } else {
        // No preference set, check system preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) {
            html.classList.add('dark');
            if (sunIcon) sunIcon.classList.remove('hidden');
            if (moonIcon) moonIcon.classList.add('hidden');
            localStorage.setItem('darkMode', 'true');
        } else {
            html.classList.remove('dark');
            if (sunIcon) sunIcon.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            localStorage.setItem('darkMode', 'false');
        }
    }
});
</script><?php /**PATH C:\Tools\covachain.org\resources\views/layouts/user/navigation.blade.php ENDPATH**/ ?>