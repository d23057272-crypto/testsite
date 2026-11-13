<nav class="md:hidden fixed bottom-4 left-4 right-4 z-40">
    <!-- Floating nav container with glass effect -->
    <div class="bg-white dark:bg-gray-800 bg-opacity-95 dark:bg-opacity-90 backdrop-blur-md rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.15)] dark:shadow-[0_4px_30px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="grid grid-cols-6 gap-1">
            <a href="{{ route('dashboard') }}" 
               class="relative group flex flex-col items-center py-3 px-1 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-home text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">{{ __('Dashboard') }}</span>
                
                @if(request()->routeIs('dashboard'))
                <!-- Animated background effect for active item -->
                <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>
            
            <a href="{{ route('markets') }}"
               class="relative group flex flex-col items-center py-3 px-1 {{ request()->routeIs('markets') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-chart-line text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">{{ __('Market') }}</span>
                @if(request()->routeIs('markets'))
                    <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                    <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>

            <a href="{{ route('bots') }}" 
               class="relative group flex flex-col items-center py-3 px-1 {{ request()->routeIs('bots') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-robot text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">Bots</span>
                
                @if(request()->routeIs('bots'))
                <!-- Animated background effect for active item -->
                <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>

            <a onclick="toggleModal('sendModal')" 
               class="relative group flex flex-col items-center py-3 px-1 cursor-pointer {{ request()->routeIs('deposit') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-download text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">Deposit</span>
                
                @if(request()->routeIs('deposit'))
                <!-- Animated background effect for active item -->
                <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>

            <a href="{{ route('swap') }}" 
               class="relative group flex flex-col items-center py-3 px-1 {{ request()->routeIs('swap') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-exchange-alt text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">Swap</span>
                
                @if(request()->routeIs('swap'))
                <!-- Animated background effect for active item -->
                <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>

            <a href="{{ route('settings') }}" 
               class="relative group flex flex-col items-center py-3 px-1 {{ request()->routeIs('settings') ? 'text-blue-600 dark:text-blue-400' : 'text-trust-text-secondary dark:text-gray-400' }}">
                <div class="relative z-10 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                    <i class="fas fa-user text-base"></i>
                </div>
                <span class="text-[0.65rem] mt-1 font-medium relative z-10">Me</span>
                
                @if(request()->routeIs('settings'))
                <!-- Animated background effect for active item -->
                <div class="absolute bottom-0 left-0 right-0 mx-auto w-6 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-t-full"></div>
                <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
                @endif
            </a>
        </div>
    </div>
</nav>

<style>
    /* Add minimal padding just for mobile screens */
    @media (max-width: 768px) {
        body {
            padding-bottom: 5rem !important;
        }
    }
    
    /* Ensure icons are properly sized on all mobile devices */
    @media (max-width: 480px) {
        .text-base {
            font-size: 0.875rem !important;
        }
        .text-[0.65rem] {
            font-size: 0.6rem !important;
        }
        .py-3 {
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
        }
    }
</style>