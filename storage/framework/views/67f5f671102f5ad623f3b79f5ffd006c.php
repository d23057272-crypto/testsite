<aside id="leftSidebar" class="hidden md:flex flex-col w-64 bg-white dark:bg-gray-800 h-screen fixed left-0 top-0 overflow-y-auto shadow-lg z-50">
    <!-- Header with gradient -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 py-6 px-6">
        <a href="<?php echo e(route('dashboard')); ?>" class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-wallet"></i>
            <span><?php echo e(config('app.name', 'Crypto Wallet')); ?></span>
        </a>
        <div class="mt-2 text-blue-100 text-sm opacity-80">Secure & Simple Crypto Wallet</div>
    </div>

    <!-- Account Section -->
    <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200">
            <div class="bg-blue-100 dark:bg-blue-900/30 h-10 w-10 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                <i class="fas fa-user"></i>
            </div>
            <div class="overflow-hidden">
                <div class="text-sm font-medium text-trust-text dark:text-white whitespace-nowrap overflow-hidden text-ellipsis" id="sidebarAccountId">
                    <?php echo e($accountId ?? 'My Wallet'); ?>

                </div>
                <div class="text-xs text-trust-text-secondary dark:text-gray-400 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span>Connected</span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-4">
        <!-- Menu Section -->
        <div class="mb-8">
            <h2 class="text-trust-blue dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-3 pl-2">Menu</h2>
            <nav class="space-y-1">
                <a href="<?php echo e(route('dashboard')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-home w-4 h-4 <?php echo e(request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span><?php echo e(__('Dashboard')); ?></span>
                </a>

                <a href="<?php echo e(route('markets')); ?>"
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('markets') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200
                    <?php echo e(request()->routeIs('markets') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-chart-line w-4 h-4 <?php echo e(request()->routeIs('markets') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span><?php echo e(__('Market')); ?></span>
                </a>

                <a href="<?php echo e(route('swap')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('swap') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('swap') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-arrows-rotate w-4 h-4 <?php echo e(request()->routeIs('swap') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>DEX</span>
                </a>

                <a onclick="toggleModal('sendModal')"
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                    <div class="w-8 h-8 mr-3 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center transition-all duration-200 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20">
                        <i class="fas fa-arrow-up w-4 h-4 text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400"></i>
                    </div>
                    <span>Send</span>
                </a>

                <a onclick="toggleModal('receiveModal')" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                    <div class="w-8 h-8 mr-3 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center transition-all duration-200 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20">
                        <i class="fas fa-arrow-down w-4 h-4 text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400"></i>
                    </div>
                    <span>Receive</span>
                </a>

                <a href="<?php echo e(route('bots')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('bots') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('bots') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-robot w-4 h-4 <?php echo e(request()->routeIs('bots') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>Trading Bots</span>
                </a>

                <a href="<?php echo e(route('referral.index')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('referral.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('referral.index') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-users w-4 h-4 <?php echo e(request()->routeIs('referral.index') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>Referrals</span>
                </a>

                <a href="<?php echo e(route('settings')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('settings') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('settings') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-cog w-4 h-4 <?php echo e(request()->routeIs('settings') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>Settings</span>
                </a>

                
                <?php
                    $unreadAdminReplies = \App\Models\Ticket::where('user_id', auth()->id())
                        ->whereHas('replies', function($query) {
                            $query->where('sender_type', 'admin')
                                  ->where('is_read', 0);
                        })
                        ->count();

                    $userOpenTickets = \App\Models\Ticket::where('user_id', auth()->id())
                        ->where('status', 'open')
                        ->count();

                    $isSupportActive = request()->routeIs('support*') || request()->routeIs('help.center');
                ?>

                <div x-data="{ open: <?php echo e($isSupportActive ? 'true' : 'false'); ?> }" class="relative">
                    
                    <button @click="open = !open" 
                            :class="open ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="w-full group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200 cursor-pointer">
                        <div :class="open ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'"
                             class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200">
                            <i class="fas fa-headset w-4 h-4" :class="open ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'"></i>
                        </div>
                        <span class="flex-1 text-left">Support Center</span>

                        
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>

                        
                        <?php if($unreadAdminReplies > 0): ?>
                            <span class="flex items-center gap-1 px-2 py-0.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-sm animate-pulse">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                <?php echo e($unreadAdminReplies); ?>

                            </span>
                        <?php elseif($userOpenTickets > 0): ?>
                            <span class="px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40 rounded-full">
                                <?php echo e($userOpenTickets); ?>

                            </span>
                        <?php endif; ?>
                    </button>

                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="ml-11 mt-1 space-y-1 overflow-hidden">

                        
                        <a href="<?php echo e(route('help.center')); ?>" 
                           class="group flex items-center px-3 py-2 rounded-xl text-sm text-gray-600 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-700 dark:hover:text-green-400 transition-all duration-200">
                            <div class="w-7 h-7 mr-3 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/40 transition-colors">
                                <i class="fas fa-envelope w-3.5 h-3.5 text-green-600 dark:text-green-400"></i>
                            </div>
                            <span class="font-medium">Send Email</span>
                        </a>

                        
                        <a href="<?php echo e(route('support')); ?>" 
                           class="group flex items-center px-3 py-2 rounded-xl text-sm text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-all duration-200
                           <?php echo e(request()->routeIs('support*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : ''); ?>">
                            <div class="w-7 h-7 mr-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/40 transition-colors">
                                <i class="fas fa-ticket-alt w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="font-medium">Open Ticket</span>
                            <?php if($userOpenTickets > 0): ?>
                                <span class="ml-auto px-2 py-0.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full">
                                    <?php echo e($userOpenTickets); ?>

                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Crypto Section -->
        <div class="mb-8">
            <h2 class="text-trust-blue dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-3 pl-2">Crypto</h2>
            <div class="space-y-1">
                <a href="<?php echo e(route('crypto.manage')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('crypto.manage') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('crypto.manage') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-layer-group w-4 h-4 <?php echo e(request()->routeIs('crypto.manage') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>Manage Crypto</span>
                </a>

                <a href="<?php echo e(route('crypto.address')); ?>" 
                   class="group flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('crypto.address') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('crypto.address') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-address-card w-4 h-4 <?php echo e(request()->routeIs('crypto.address') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span>Crypto Address</span>
                </a>

                <a href="<?php echo e(route('notifications')); ?>" 
                   class="group relative flex items-center text-trust-text dark:text-gray-200 px-3 py-2.5 rounded-xl transition-all duration-200
                   <?php echo e(request()->routeIs('notifications') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                    <div class="w-8 h-8 mr-3 rounded-lg flex items-center justify-center transition-all duration-200 
                    <?php echo e(request()->routeIs('notifications') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20'); ?>">
                        <i class="fas fa-bell w-4 h-4 <?php echo e(request()->routeIs('notifications') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400'); ?>"></i>
                    </div>
                    <span><?php echo e(__('Notifications')); ?></span>
                    <span id="sidebar-notification-badge" class="absolute top-2 right-3 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">
                        0
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar footer with status -->
    <div class="mt-auto p-4 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between text-xs text-trust-text-secondary dark:text-gray-400 p-2">
            <div class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span>Online</span>
            </div>
        </div>
    </div>
</aside><?php /**PATH C:\Tools\covachain.org\resources\views/layouts/user/sidebar.blade.php ENDPATH**/ ?>