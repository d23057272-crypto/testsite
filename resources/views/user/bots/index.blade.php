<x-user-layout :title="'Bots'">
    <!-- Main Container -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <!-- Welcome & Stats Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-2">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome 🎉</h1>
                    <button 
                        onclick="window.location.href='{{ route('bots.show') }}'" 
                        class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Subscribe Now
                    </button>
                </div>
                <img src="/images/bot.svg" alt="Trading illustration" class="h-32 w-auto"/>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-2">
                <!-- Active Subscriptions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-2 md:pt-8 flex flex-col items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $userSubscriptions->count() }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Active Subs</span>
                </div>

                <!-- Total Investment -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-2 md:pt-8 flex flex-col items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                       {{ number_format($totalInvestment, 2) }} $
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Investment</span>
                </div>

                <!-- Win Rate -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-2 md:pt-8 flex flex-col items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $winRate ?? '0' }}%
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Win Rate</span>
                </div>

                <!-- Total Profit -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-2 md:pt-8 flex flex-col items-center">
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($totalProfit, 2) }} $
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Profit</span>
                </div>
            </div>
        </div>

        <!-- Combined Bots Section -->
        <div class="py-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4">
                <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl text-gray-900 dark:text-white">Bot History</h2>
    <a href="{{ route('bots.history') }}" class="text-blue-500 hover:text-blue-600">View All</a>
</div>

                <!-- Desktop View -->
                <div class="hidden md:block">
                    <div class="bg-gray-100 dark:bg-[#232F46] bg-opacity-50">
                        <div class="grid grid-cols-5 px-6 py-3">
                            <div class="text-sm text-gray-800 dark:text-gray-400 font-medium">BOT NAME</div>
                            <div class="text-sm text-gray-800 dark:text-gray-400 font-medium">AMOUNT</div>
                            <div class="text-sm text-gray-800 dark:text-gray-400 font-medium">STATUS</div>
                            <div class="text-sm text-gray-800 dark:text-gray-400 font-medium">RESULT</div>
                            <div class="text-sm text-gray-800 dark:text-gray-400 font-medium">PROFIT/LOSS</div>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-700">
                        @foreach($userSubscriptions as $subscription)
                            <div class="grid grid-cols-5 px-6 py-3 trade-row" data-type="active">
                                <div class="text-gray-800 dark:text-gray-300">
                                    {{ $subscription->bot->name }}
                                </div>
                                <div class="text-gray-800 dark:text-gray-300">
                                    {{ format_fiat((float) $subscription->amount) }}
                                </div>
                                <div class="text-blue-500">Active</div>
                                <div class="text-yellow-500">Pending</div>
                               <div class="text-gray-500">
    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
    </svg>
</div>
                            </div>
                        @endforeach

                        @foreach($trades as $trade)
                            <div class="grid grid-cols-5 px-6 py-3 trade-row" data-type="completed">
                                <div class="text-gray-800 dark:text-gray-300">
                                    {{ $trade->bot->name }}
                                </div>
                                <div class="text-gray-800 dark:text-gray-300">
                                    {{ format_fiat((float) $trade->amount) }}
                                </div>
                                <div class="text-gray-500">Completed</div>
                                <div class="{{$trade->result === 'win' ? 'text-green-500' : 'text-red-500'}}">
                                    {{ ucfirst($trade->result) }}
                                </div>
                                <div class="{{$trade->result === 'win' ? 'text-green-500' : 'text-red-500'}}">
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trade->result === 'win' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
        {{ ($trade->result === 'win' ? '+' : '-') . format_fiat(abs((float) $trade->profit)) }}
    </span>
</div>
                            </div>
                        @endforeach

                        @if($userSubscriptions->isEmpty() && $trades->isEmpty())
                            <div class="p-6 text-center text-gray-800 dark:text-gray-400">
                                No bot trades found!
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden space-y-4">
                    @foreach($userSubscriptions as $subscription)
                        <div class="bg-gray-100 dark:bg-[#232F46] p-4 rounded-lg trade-row" data-type="active">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-gray-800 dark:text-gray-300 font-medium">
                                    {{ $subscription->bot->name }}
                                </h3>
                                <span class="text-blue-500">Active</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-white dark:bg-gray-700/50 p-2 rounded">
                                    <span class="text-gray-800 dark:text-gray-400 text-xs">Amount</span>
                                    <p class="text-gray-800 dark:text-gray-300">
                                        {{ format_fiat((float) $subscription->amount) }}
                                    </p>
                                </div>
                                <div class="bg-white dark:bg-gray-700/50 p-2 rounded">
                                    <span class="text-gray-800 dark:text-gray-400 text-xs">Result</span>
                                    <p class="text-yellow-500">Pending</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($trades as $trade)

       <div class="bg-gray-100 dark:bg-[#232F46] p-4 rounded-lg trade-row" data-type="completed">

           <div class="flex justify-between items-start mb-3">

               <h3 class="text-gray-800 dark:text-gray-300 font-medium">

                   {{ $trade->bot->name }}

               </h3>

               <span class="text-gray-500">Completed</span>

           </div>

           <div class="grid grid-cols-2 gap-2">

               <div class="bg-white dark:bg-gray-700/50 p-2 rounded">

                   <span class="text-gray-800 dark:text-gray-400 text-xs">Amount</span>

                   <p class="text-gray-800 dark:text-gray-300">

                       {{ format_fiat((float) $trade->amount) }}

                   </p>

               </div>

               <div class="bg-white dark:bg-gray-700/50 p-2 rounded">

                   <span class="text-gray-800 dark:text-gray-400 text-xs">Result</span>

                   <p class="{{$trade->result === 'win' ? 'text-green-500' : 'text-red-500'}}">

                       @if($trade->result === 'win')

                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">

                               +{{ format_fiat((float) $trade->profit) }}

                           </span>

                       @else

                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">

                               -{{ format_fiat((float) $trade->profit) }}

                           </span>

                       @endif

                   </p>

               </div>

           </div>

       </div>

   @endforeach
                    @if($userSubscriptions->isEmpty() && $trades->isEmpty())
                        <div class="text-center text-gray-800 dark:text-gray-400">
                            No bot trades found!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Trade Filtering -->
    <script>
        function filterTrades(type) {
            const rows = document.querySelectorAll('.trade-row');
            
            rows.forEach(row => {
                if (type === 'all') {
                    row.classList.remove('hidden');
                } else {
                    if (row.dataset.type === type) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                }
            });

            // Update active state of filter buttons
            const buttons = document.querySelectorAll('[onclick^="filterTrades"]');
            buttons.forEach(button => {
                if (button.getAttribute('onclick').includes(type)) {
                    button.classList.add('bg-purple-600', 'text-white');
                    button.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                } else {
                    button.classList.remove('bg-purple-600', 'text-white');
                    button.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                }
            });
        }

        // Auto-update trades every 30 seconds
        function updateTrades() {
            fetch('{{ route("bots.trades.update") }}')
                .then(response => response.json())
                .then(data => {
                    // Update trades logic here
                    console.log('Trades updated');
                })
                .catch(error => console.error('Error updating trades:', error));
        }

        // Initialize with all trades visible
        document.addEventListener('DOMContentLoaded', function() {
            filterTrades('all');
            // Set interval for auto-update
            setInterval(updateTrades, 30000);
        });

        // Dark mode sync
        function syncTradesWithTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.bg-gray-100').forEach(el => {
                el.classList.toggle('dark:bg-[#232F46]', isDark);
            });
        }

        // Observe theme changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    syncTradesWithTheme();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>
</x-user-layout>