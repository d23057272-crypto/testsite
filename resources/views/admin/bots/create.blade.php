<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="my-6">
            <h2 class="text-2xl font-semibold text-gray-700">Create Bot</h2>
        </div>
 
        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.bots.store') }}" method="POST">
                @csrf
                <div class="grid gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bot Type</label>
                        <input type="text" name="bot_type" value="{{ old('bot_type') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
                            <input type="number" name="min_amount" step="0.01" value="{{ old('min_amount') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
                            <input type="number" name="max_amount" step="0.01" value="{{ old('max_amount') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration Options</label>
                        <div class="space-y-2">
                            @foreach(['5m', '10m', '15m', '30m', '1h', '4h', '12h', '24h', '3d', '7d', '30d'] as $duration)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="duration_options[]" value="{{ $duration }}" class="rounded">
                                    <span>{{ str_replace(['m', 'h', 'd'], [' Minutes', ' Hours', ' Days'], $duration) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supported Pairs</label>
                        <div class="space-y-2">
                            @foreach(['BTC/USDT', 'ETH/USDT', 'SOL/USDT', 'XRP/USDT', 'ADA/USDT', 'LTC/USDT', 'BNB/USDT', 'DOGE/USDT', 'DASH/USDT', 'XLM/USDT'] as $pair)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="supported_pairs[]" value="{{ $pair }}" class="rounded">
                                    <span>{{ $pair }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parameters</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Grid Levels</label>
                                <input type="number" name="parameters[grid_levels]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                       value="{{ old('parameters.grid_levels', 10) }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Upper Limit</label>
                                <input type="number" name="parameters[upper_limit]" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                       value="{{ old('parameters.upper_limit', 1.05) }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Lower Limit</label>
                                <input type="number" name="parameters[lower_limit]" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                       value="{{ old('parameters.lower_limit', 0.95) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.bots.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        Create Bot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>