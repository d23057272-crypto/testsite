<x-admin-layout>
    <div class="p-4 md:p-6">
        <x-session-status class="mb-4" :status="session('status')" />

        <!-- Stat Cards -->
        <div class="grid gap-4 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- User Stats -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                    <i class="ri-user-line text-purple-500 text-xl"></i>
                </div>
                <div class="text-2xl font-bold">{{ $userStats['total'] }}</div>
                <div class="mt-2 flex justify-between text-xs">
                    <span class="text-gray-500">KYC Verified: {{ $userStats['kyc_verified'] }}</span>
                    <span class="text-green-500">+{{ $userStats['new_this_month'] }} this month</span>
                </div>
            </div>

            <!-- Transaction Stats -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Total Transactions</h3>
                    <i class="ri-exchange-dollar-line text-blue-500 text-xl"></i>
                </div>
                <div class="text-2xl font-bold">{{ $transactionStats['total_count'] }}</div>
                <div class="mt-2 flex justify-between text-xs">
                    <span class="text-gray-500">Volume: ${{ number_format($transactionStats['total_volume'], 2) }}</span>
                    <span class="text-green-500">{{ $transactionStats['pending_count'] }} pending</span>
                </div>
            </div>

            <!-- Card Stats -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Active Cards</h3>
                    <i class="ri-bank-card-line text-green-500 text-xl"></i>
                </div>
                <div class="text-2xl font-bold">{{ $cardStats['active_cards'] }}</div>
                <div class="mt-2 flex justify-between text-xs">
                    <span class="text-gray-500">Total Balance: ${{ number_format($cardStats['total_balance'], 2) }}</span>
                    <span class="text-yellow-500">{{ $cardStats['frozen_cards'] }} frozen</span>
                </div>
            </div>

            <!-- Crypto Stats -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Total Crypto Value</h3>
                    <i class="ri-coin-line text-yellow-500 text-xl"></i>
                </div>
                <div class="text-2xl font-bold">${{ number_format($cryptoStats['total_value_usd'], 2) }}</div>
                <div class="mt-2 flex justify-between text-xs">
                    <span class="text-gray-500">Active Wallets: {{ $cryptoStats['active_wallets'] }}</span>
                    <span class="text-blue-500">{{ $cryptoStats['currencies_count'] }} currencies</span>
                </div>
            </div>
        </div>

       <!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Recent Transactions</h2>
        <a href="{{ route('admin.transactions') }}" class="text-sm text-purple-600 hover:text-purple-800">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crypto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-purple-600">
                                        {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium">{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm capitalize">{{ $transaction->type }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ strtoupper($transaction->which_crypto) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaction->type === 'withdrawal')
                                ${{ number_format($transaction->amount_out * $transaction->rate, 2) }}
                            @else
                                ${{ number_format($transaction->amount_in * $transaction->rate, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($transaction->status === 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No transactions found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

        <!-- KYC and Card Stats Grid -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Pending KYC Submissions -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Pending KYC Verifications</h2>
                    <a href="{{ route('admin.kyc') }}?status=pending" class="text-sm text-purple-600 hover:text-purple-800">View All</a>
                </div>
                <div class="p-4">
                    @forelse($pendingKyc as $user)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-purple-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">Submitted {{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.kyc.show', $user) }}" 
                               class="text-sm text-purple-600 hover:text-purple-800">Review</a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No pending KYC verifications</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Card Activities -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Recent Card Activities</h2>
                </div>
                <div class="p-4">
                    @forelse($recentCardActivities as $activity)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <i class="ri-bank-card-line text-gray-400"></i>
                                <div>
                                    <div class="text-sm">{{ $activity->description }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm {{ $activity->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format(abs($activity->amount), 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent card activities</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>