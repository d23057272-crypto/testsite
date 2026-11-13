<x-user-layout>
    <div class="w-full mx-auto min-h-screen bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
            <button wire:navigate href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold">Buy Crypto</h1>
            <div class="w-8"><!-- Empty div for alignment --></div>
        </div>

        <!-- Search and Filter -->
        <div class="p-4 space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                <input 
                    type="text" 
                    placeholder="Enter coin name" 
                    class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-700 transition-colors"
                >
            </div>

            <!-- Network Filter -->
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                    All Networks
                </span>
            </div>
        </div>

        <!-- Crypto List -->
        <div class="space-y-4 p-4">
            @foreach($assets as $asset)
                <a href="{{ route('buy.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <div class="flex items-center">
                        <div class="relative">
                            @if($asset['icon_url'])
                                <img src="{{ $asset['icon_url'] }}" alt="{{ $asset['symbol'] }}" class="w-10 h-10 rounded-full">
                                @if(!empty($asset['network_url']))
                                    <img src="{{ $asset['network_url'] }}" 
                                         alt="{{ $asset['network'] }}" 
                                         class="absolute border border-white dark:border-gray-800" 
                                         style="width: 15px; height: auto; bottom: 0; right: 0; background: white; border-radius: 50%;">
                                @endif
                            @else
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $asset['symbol'] }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900 dark:text-gray-100 mr-2">{{ $asset['symbol'] }}</span>
                                @if($asset['network'])
                                    <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full text-gray-600 dark:text-gray-300">{{ $asset['network'] }}</span>
                                @endif
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400 mr-2">{{ format_fiat((float) $asset['price']) }}</span>
                                <span class="@if(str_starts_with($asset['change'], '-')) text-red-500 dark:text-red-400 @else text-green-500 dark:text-green-400 @endif font-medium">
                                    {{ $asset['change'] }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($asset['balance'], 4) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ format_fiat((float) $asset['value']) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-user-layout>