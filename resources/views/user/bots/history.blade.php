<x-user-layout :title="'Bot History'">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="py-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4">
                <h2 class="text-xl text-gray-900 dark:text-white mb-4">Trading History</h2>

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
                        @foreach($trades as $trade)
                            <div class="grid grid-cols-5 px-6 py-3">
                                <div class="text-gray-800 dark:text-gray-300">{{ $trade->bot->name }}</div>
                                <div class="text-gray-800 dark:text-gray-300">{{ format_fiat((float) $trade->amount) }}</div>
                                <div class="text-gray-500">Completed</div>
                                <div class="{{$trade->result === 'win' ? 'text-green-500' : 'text-red-500'}}">
                                    {{ ucfirst($trade->result) }}
                                </div>
                                <div class="{{$trade->result === 'win' ? 'text-green-500' : 'text-red-500'}}">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trade->result === 'win' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $trade->result === 'win' ? '+$'.number_format($trade->profit, 2) : '-$'.number_format($trade->amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden space-y-4">
                    @foreach($trades as $trade)
                        <div class="bg-gray-100 dark:bg-[#232F46] p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-gray-800 dark:text-gray-300 font-medium">{{ $trade->bot->name }}</h3>
                                <span class="text-gray-500">Completed</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-white dark:bg-gray-700/50 p-2 rounded">
                                    <span class="text-gray-800 dark:text-gray-400 text-xs">Amount</span>
                                    <p class="text-gray-800 dark:text-gray-300">{{ format_fiat((float) $trade->amount) }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-700/50 p-2 rounded">
                                    <span class="text-gray-800 dark:text-gray-400 text-xs">Result</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trade->result === 'win' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $trade->result === 'win' ? '+$'.number_format($trade->profit, 2) : '-$'.number_format($trade->amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $trades->links() }}
                </div>
            </div>
        </div>
    </div>
</x-user-layout>