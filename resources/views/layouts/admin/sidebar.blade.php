<aside id="sidebar" 
       class="bg-gray-800 text-white w-64 min-h-screen p-4 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-30 overflow-y-auto flex flex-col">

    <!-- Mobile Close Button (Visible only on mobile) -->
    <div class="md:hidden flex justify-end mb-2">
        <button id="sidebarClose" 
                class="w-8 h-8 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-gray-300 transition-colors"
                aria-label="Close sidebar">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>

    <!-- Logo/Header Section -->
    <div class="flex items-center justify-center space-x-2 py-5 border-b border-gray-700 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <span class="text-xl font-bold text-white">Admin<span class="text-purple-500">Panel</span></span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-4 flex-1">
        <ul class="space-y-1">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                    <i class="ri-dashboard-line text-xl"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Users Management --}}
            <li>
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.users*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                    <i class="ri-user-line text-xl"></i>
                    <span>Users</span>
                </a>
            </li>

            {{-- KYC Submissions --}}
            <li>
                <a href="{{ route('admin.kyc') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.kyc*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                    <i class="ri-shield-check-line text-xl"></i>
                    <span>KYC Verification</span>
                </a>
            </li>

            {{-- Trading Bots Section --}}
            <li class="mt-4">
                <div class="px-3 py-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">Trading Bots</div>
                <ul class="space-y-1 ml-2 border-l border-gray-700">
                    <li>
                        <a href="{{ route('admin.bots.index') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.bots.*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-robot-line text-xl"></i>
                            <span>Bots</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subscriptions.index') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.subscriptions.*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-funds-box-line text-xl"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.trades.index') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.trades.*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-exchange-line text-xl"></i>
                            <span>Trades</span>
                        </a>
                    </li>
                </ul>
            </li> 

            {{-- Transactions Section --}}
            <li class="mt-4">
                <div class="px-3 py-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">Transactions</div>
                <ul class="space-y-1 ml-2 border-l border-gray-700">
                    <li>
                        <a href="{{ route('admin.transactions') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.transactions*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-exchange-dollar-line text-xl"></i>
                            <span>Transactions</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Communication Section --}}
            <li class="mt-4">
                <div class="px-3 py-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">Communication</div>
                <ul class="space-y-1 ml-2 border-l border-gray-700">
                    {{-- Support Tickets Dropdown --}}
                    @php
                        $isSupportActive = request()->routeIs('admin.support.*');
                        $openSupportTickets = \App\Models\Ticket::where('status', 'open')->count();
                    @endphp
                    <li x-data="{ 
                            open: false,
                            init() {
                                const saved = localStorage.getItem('supportDropdownOpen');
                                if (saved !== null) {
                                    this.open = saved === 'true';
                                } else {
                                    this.open = {{ $isSupportActive ? 'true' : 'false' }};
                                }
                            }
                         }"
                        x-init="init()">
                        <button @click="open = !open; localStorage.setItem('supportDropdownOpen', open)" 
                                type="button"
                                class="w-full flex items-center justify-between p-3 rounded-lg {{ $isSupportActive ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <div class="flex items-center space-x-3">
                                <i class="ri-customer-service-2-line text-xl"></i>
                                <span>Support Tickets</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($openSupportTickets > 0)
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $openSupportTickets }}</span>
                                @endif
                                <i class="ri-arrow-down-s-line transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="ml-6 mt-1 space-y-1 border-l-2 border-gray-600 pl-3">
                            <a href="{{ route('admin.support.index') }}" 
                               class="flex items-center justify-between p-2 rounded-lg text-sm {{ request()->routeIs('admin.support.index') ? 'bg-purple-500/30 text-purple-200' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <span>All Tickets</span>
                            </a>
                            <a href="{{ route('admin.support.open') }}" 
                               class="flex items-center justify-between p-2 rounded-lg text-sm {{ request()->routeIs('admin.support.open') ? 'bg-purple-500/30 text-purple-200' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <span>Open Tickets</span>
                                @if($openSupportTickets > 0)
                                    <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $openSupportTickets }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.support.answered') }}" 
                               class="flex items-center justify-between p-2 rounded-lg text-sm {{ request()->routeIs('admin.support.answered') ? 'bg-purple-500/30 text-purple-200' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <span>Answered</span>
                            </a>
                            <a href="{{ route('admin.support.closed') }}" 
                               class="flex items-center justify-between p-2 rounded-lg text-sm {{ request()->routeIs('admin.support.closed') ? 'bg-purple-500/30 text-purple-200' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <span>Closed</span>
                            </a>
                        </div>
                    </li>

                    {{-- Newsletter --}}
                    <li>
                        <a href="{{ route('admin.newsletter') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.newsletter*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-mail-send-line text-xl"></i>
                            <span>Send Email</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Settings Section --}}
            <li class="mt-4">
                <div class="px-3 py-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">Settings</div>
                <ul class="space-y-1 ml-2 border-l border-gray-700">
                    <li>
                        <a href="{{ route('admin.settings.general') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.settings.general*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-settings-line text-xl"></i>
                            <span>General Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.email') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.settings.email*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-mail-settings-line text-xl"></i>
                            <span>Email Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.wallets') }}" 
                           class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.settings.wallets*') ? 'bg-purple-600 text-white' : 'hover:bg-gray-700' }} transition-colors">
                            <i class="ri-wallet-3-line text-xl"></i>
                            <span>Wallet Settings</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Footer Section -->
    <div class="mt-auto pt-6 pb-4 px-3 text-xs text-gray-500 border-t border-gray-700 flex-shrink-0">
        <p>&copy; {{ date('Y') }} CovaChain Admin</p>
    </div>
</aside>

<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarBackdrop" 
     class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden transition-opacity duration-300"
     aria-hidden="true"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (!sidebar) return;

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (backdrop) {
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.add('opacity-100'), 10);
        }
        if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (backdrop) {
            backdrop.classList.remove('opacity-100');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
        }
        if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    function setupMobileNavClose() {
        if (window.innerWidth < 768 && sidebar) {
            const navLinks = sidebar.querySelectorAll('a[href]');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const isExternal = this.getAttribute('target') === '_blank' 
                                    || this.getAttribute('href')?.startsWith('#')
                                    || this.getAttribute('href')?.startsWith('javascript:');

                    if (!isExternal) {
                        setTimeout(closeSidebar, 150);
                    }
                });
            });
        }
    }

    setupMobileNavClose();

    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (window.innerWidth >= 768) {
                if (sidebar) {
                    sidebar.classList.remove('-translate-x-full', 'translate-x-0');
                }
                if (backdrop) {
                    backdrop.classList.add('hidden', 'opacity-0');
                }
                document.body.style.overflow = '';
            }
            setupMobileNavClose();
        }, 150);
    });
});
</script>
@endpush

@push('styles')
<style>
#sidebarBackdrop {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
#sidebarBackdrop.opacity-100 {
    opacity: 1;
}
#sidebar {
    will-change: transform;
    -webkit-overflow-scrolling: touch;
}
@media (max-width: 767px) {
    #sidebar {
        max-height: 100vh;
    }
}
</style>
@endpush