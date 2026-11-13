<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="my-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-700">{{ $bot->name }}</h2>
                <a href="{{ route('admin.bots.edit', $bot) }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                    <i class="ri-edit-line mr-2"></i>Edit Bot
                </a>
            </div>
        </div>
 {{-- Flash Messages --}}
        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

        <!-- Bot Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bot Details</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Type</dt>
                        <dd class="text-sm font-medium">{{ $bot->bot_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Amount Range</dt>
                        <dd class="text-sm font-medium">${{ number_format($bot->min_amount, 2) }} - ${{ number_format($bot->max_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Duration Options</dt>
                        <dd class="text-sm font-medium">{{ implode(', ', $botSettings->duration_options ?? []) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Total Subscriptions</dt>
                        <dd class="text-sm font-medium">{{ $bot->subscriptions_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Active Subscriptions</dt>
                        <dd class="text-sm font-medium">{{ $bot->active_subscriptions_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Total Investment</dt>
                        <dd class="text-sm font-medium">${{ number_format($bot->subscriptions_sum_amount ?? 0, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Total Profit</dt>
                        <dd class="text-sm font-medium">${{ number_format($bot->subscriptions_sum_total_profit ?? 0, 2) }}</dd>
                    </div>
                </dl>
            </div>

            @if($bot->description)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                    <p class="text-sm text-gray-600">{{ $bot->description }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Trades -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Trades</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Trading Pair</th>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Result</th>
                            <th class="px-4 py-3">Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($bot->trades as $trade)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{ $trade->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $trade->trading_pair }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $trade->action === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($trade->action) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    ${{ number_format($trade->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $trade->result === 'win' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($trade->result) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="{{ $trade->result === 'win' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $trade->result === 'win' ? '+' : '-' }}${{ number_format($trade->result === 'win' ? $trade->profit : $trade->amount, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No trades found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>