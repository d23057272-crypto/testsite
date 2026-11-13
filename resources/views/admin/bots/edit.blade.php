<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="my-6">
            <h2 class="text-2xl font-semibold text-gray-700">Edit Bot</h2>
        </div>
 {{-- Flash Messages --}}
        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.bots.update', $bot) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $bot->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bot Type</label>
                        <input type="text" name="bot_type" value="{{ old('bot_type', $bot->bot_type) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
                            <input type="number" name="min_amount" step="0.01" 
                                   value="{{ old('min_amount', $bot->min_amount) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
                            <input type="number" name="max_amount" step="0.01" 
                                   value="{{ old('max_amount', $bot->max_amount) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
<!-- Parameters -->
   <div>
       <label class="block text-sm font-medium text-gray-700 mb-2">Parameters</label>
       <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
           @foreach($botSettings->parameters ?? [] as $key => $value)
               <div class="grid grid-cols-2 gap-4">
                   <input type="text" name="parameters[{{ $key }}]" value="{{ $key }}"
                          class="px-3 py-2 border border-gray-300 rounded-lg" readonly>
                   <input type="text" name="parameters[{{ $key }}]" value="{{ $value }}"
                          class="px-3 py-2 border border-gray-300 rounded-lg">
               </div>
           @endforeach
       </div>
   </div>

   <!-- Supported Pairs -->
   <div>
       <label class="block text-sm font-medium text-gray-700 mb-2">Supported Trading Pairs</label>
       <div class="grid grid-cols-3 gap-4">
           @foreach(['BTC/USDT', 'ETH/USDT', 'LTC/USDT', 'XRP/USDT', 'BNB/USDT', 'ADA/USDT', 'SOL/USDT', 'DOGE/USDT', 'DASH/USDT', 'XLM/USDT'] as $pair)
               <div class="flex items-center space-x-2">
                   <input type="checkbox" name="supported_pairs[]" value="{{ $pair }}"
                          {{ in_array($pair, $botSettings->supported_pairs ?? []) ? 'checked' : '' }}
                          class="rounded">
                   <span>{{ $pair }}</span>
               </div>
           @endforeach
       </div>
   </div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration Options</label>
<div class="space-y-2">
   @foreach(['5m', '10m', '15m', '30m', '1h', '4h', '12h', '24h', '3d', '7d', '30d'] as $duration)
       <div class="flex items-center space-x-2">
           <input type="checkbox" name="duration_options[]" value="{{ $duration }}"
                  {{ in_array($duration, $botSettings->duration_options ?? []) ? 'checked' : '' }}
                  class="rounded">
           <span>{{ str_replace(['m', 'h', 'd'], [' Minutes', ' Hours', ' Days'], $duration) }}</span>
       </div>
   @endforeach
</div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.bots.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        Update Bot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>