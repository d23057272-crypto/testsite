<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cryptocurrency Management') }}
            </h2>
            <a href="{{ route('admin.cryptocurrencies.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i class="ri-add-line mr-2"></i>{{ __('Add Cryptocurrency') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid gap-6 mb-8 md:grid-cols-4">
                <!-- Total Cryptocurrencies -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                        <i class="ri-coins-line text-xl"></i>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Cryptocurrencies</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalCryptocurrencies }}
                        </p>
                    </div>
                </div>
                
                <!-- Active -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $activeCount }}
                        </p>
                    </div>
                </div>
                
                <!-- Inactive -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                        <i class="ri-close-circle-line text-xl"></i>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Inactive</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $inactiveCount }}
                        </p>
                    </div>
                </div>
                
                <!-- Networks -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                        <i class="ri-global-line text-xl"></i>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Networks</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $networkCount }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Cryptocurrencies Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b dark:bg-gray-700 dark:text-gray-400">
                                <th class="px-4 py-3">Icon</th>
                                <th class="px-4 py-3">Symbol</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Network</th>
                                <th class="px-4 py-3">Price (USD)</th>
                                <th class="px-4 py-3">24h Change</th>
                                <th class="px-4 py-3">Fees</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @forelse($cryptocurrencies as $crypto)
                            <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <!-- Icon -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <img src="{{ $crypto->logo_url }}" 
                                             alt="{{ $crypto->symbol }}"
                                             class="w-8 h-8 rounded-full object-cover"
                                             onerror="this.src='{{ asset('images/crypto/default.png') }}'">
                                    </div>
                                </td>
                                
                                <!-- Symbol -->
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($crypto->symbol === 'BTC') bg-orange-500 bg-opacity-20 text-gray-700
                                        @elseif($crypto->symbol === 'ETH') bg-blue-500 bg-opacity-20
                                        @elseif(str_contains($crypto->symbol, 'USDT')) bg-green-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'TRX') bg-red-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'BNB') bg-yellow-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'SOL') bg-purple-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'XRP') bg-blue-600 bg-opacity-20
                                        @elseif($crypto->symbol === 'NEAR') bg-green-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'DOT') bg-pink-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'LTC') bg-gray-500 bg-opacity-20
                                        @elseif($crypto->symbol === 'BCH') bg-green-600 bg-opacity-20
                                        @elseif($crypto->symbol === 'XLM') bg-blue-400 bg-opacity-20
                                        @elseif($crypto->symbol === 'DASH') bg-blue-600 bg-opacity-20
                                        @elseif($crypto->symbol === 'DOGE') bg-yellow-400 bg-opacity-20
                                        @elseif($crypto->symbol === 'XDC') bg-blue-700 bg-opacity-20
                                        @else bg-gray-200 @endif
                                        dark:text-gray-300">
                                        {{ $crypto->symbol }}
                                    </span>
                                </td>
                                
                                <!-- Name -->
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-semibold">{{ $crypto->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $crypto->description }}</p>
                                    </div>
                                </td>
                                
                                <!-- Network -->
                                <td class="px-4 py-3">
                                    @if($crypto->is_native)
                                        <span class="text-gray-400 text-xs">Native</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-md 
                                            @if($crypto->network === 'TRC20') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($crypto->network === 'ERC20') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($crypto->network === 'BNB') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $crypto->network }}
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Price -->
                                <td class="px-4 py-3">
                                    <span class="font-medium">${{ number_format($crypto->price_usd, 2) }}</span>
                                </td>
                                
                                <!-- 24h Change -->
                                <td class="px-4 py-3">
                                    @if($crypto->change_24h >= 0)
                                        <span class="flex items-center text-green-600">
                                            <i class="ri-arrow-up-line mr-1"></i>
                                            {{ number_format($crypto->change_24h, 2) }}%
                                        </span>
                                    @else
                                        <span class="flex items-center text-red-600">
                                            <i class="ri-arrow-down-line mr-1"></i>
                                            {{ number_format(abs($crypto->change_24h), 2) }}%
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Fees -->
                                <td class="px-4 py-3 text-xs">
                                    <div class="space-y-1">
                                        <div><span class="text-gray-500">W:</span> {{ number_format($crypto->withdrawal_fee, 4) }}%</div>
                                        <div><span class="text-gray-500">S:</span> {{ number_format($crypto->sell_fee, 4) }}%</div>
                                    </div>
                                </td>
                                
                                <!-- Status Toggle -->
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.cryptocurrencies.toggle', $crypto) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm">
                                            @if($crypto->is_active)
                                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                                    Inactive
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.cryptocurrencies.edit', $crypto) }}"
                                           class="p-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                                           title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.cryptocurrencies.destroy', $crypto) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this cryptocurrency? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                                                    title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <i class="ri-coins-line text-4xl mb-2 text-gray-300"></i>
                                        <p class="text-lg font-medium">No cryptocurrencies configured</p>
                                        <p class="text-sm text-gray-400 mt-1">Add your first cryptocurrency to get started</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($cryptocurrencies->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t dark:bg-gray-700 dark:border-gray-600">
                    {{ $cryptocurrencies->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>