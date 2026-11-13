<x-user-layout :title="$asset['symbol']">
    <main class="md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 mb-4">
            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="text-center">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $asset['symbol'] }}</h1>
                @if($asset['network'])
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $asset['symbol'] }} | {{ $asset['network'] }}</p>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $asset['symbol'] }} | {{ ucfirst($coinId) }}</p>
                @endif
            </div>
            <button class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-chart-line text-xl"></i>
            </button>
        </div>

        <!-- Asset Icon and Balance -->
        <div class="flex flex-col items-center mx-6 mb-8">
            <div class="relative mb-6">
                @if($asset['icon_url'])
                    <div class="w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                        <img src="{{ $asset['icon_url'] }}" alt="{{ $asset['symbol'] }}" class="w-14 h-14">
                        @if(!empty($asset['network_url']))
                            <div class="absolute right-0 bottom-0 border-2 border-white dark:border-gray-800 rounded-full bg-white dark:bg-gray-800">
                                <img src="{{ $asset['network_url'] }}" 
                                     alt="{{ $asset['network'] }}" 
                                     class="w-7 h-7 rounded-full">
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <span class="text-2xl text-blue-500 dark:text-blue-400 font-bold">{{ $asset['symbol'] }}</span>
                    </div>
                @endif
            </div>
            
            <h2 class="text-3xl font-medium text-gray-900 dark:text-white mb-1">{{ number_format($asset['balance'], 8) }} {{ $asset['symbol'] }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ format_fiat((float) $asset['value']) }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-4 gap-4 mb-8 px-6">
            <div class="flex flex-col items-center">
                <button onclick="openAssetSendModal()" 
                        class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center mb-2 transition-colors">
                    <i class="fas fa-arrow-up text-blue-500 dark:text-blue-400 text-lg"></i>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">Send</span>
            </div>
            <div class="flex flex-col items-center">
                <button onclick="openAssetReceiveModal()" 
                        class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center mb-2 transition-colors">
                    <i class="fas fa-arrow-down text-blue-500 dark:text-blue-400 text-lg"></i>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">Receive</span>
            </div>
            <div class="flex flex-col items-center">
                <a href="{{ route('buy.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                        class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center mb-2 transition-colors">
                    <i class="fas fa-credit-card text-blue-500 dark:text-blue-400 text-lg"></i>
                </a>
                <span class="text-sm text-gray-600 dark:text-gray-400">Buy</span>
            </div>
            <div class="flex flex-col items-center">
                <a href="{{ route('swap') }}" 
                        class="w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center mb-2 transition-colors">
                    <i class="fas fa-exchange-alt text-blue-500 dark:text-blue-400 text-lg"></i>
                </a>
                <span class="text-sm text-gray-600 dark:text-gray-400">Swap</span>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="px-4">
            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
                <button class="text-blue-500 dark:text-blue-400 font-medium pb-2 border-b-2 border-blue-500 mr-6">
                    Transactions
                </button>
            </div>

            <!-- Transaction List -->
            <div class="space-y-3 pb-24">
                @forelse($transactions as $transaction)
                <div onclick='openTransactionReceiptModal(@json($transaction->toArray()))'
                    class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                    @php
                        $currentCrypto = strtolower($asset['symbol']);
                        if ($asset['network']) {
                            $currentCrypto = strtolower($asset['symbol']) . '_' . strtolower($asset['network']);
                        }
                        
                        // Check if this is a funding transaction
                        if ($transaction->type === 'funding') {
                            $isIncoming = false;
                            $amount = $transaction->amount_out;
                        }
                        // Check for swap transactions
                        elseif ($transaction->type === 'swap') {
                            $isIncoming = $transaction->to_crypto === $currentCrypto;
                            $amount = $isIncoming ? $transaction->amount_out : $transaction->amount_in;
                        }
                        // For other transaction types
                        else {
                            $isIncoming = in_array($transaction->type, ['deposit', 'refund']);
                            $amount = $isIncoming ? $transaction->amount_in : $transaction->amount_out;
                        }
                        
                        // Set icon and color based on status with dark mode support
                        if ($transaction->status === 'pending') {
                            $iconBg = 'bg-yellow-50 dark:bg-yellow-900/30';
                            $iconColor = 'text-yellow-500 dark:text-yellow-400';
                            $iconDirection = 'fa-spinner fa-spin';
                            $amountPrefix = '';
                            $amountClass = 'text-yellow-500 dark:text-yellow-400';
                        } else {
                            $iconBg = $isIncoming ? 'bg-green-50 dark:bg-green-900/30' : 'bg-red-50 dark:bg-red-900/30';
                            $iconColor = $isIncoming ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400';
                            $iconDirection = $isIncoming ? 'fa-arrow-down' : 'fa-arrow-up';
                            $amountPrefix = $isIncoming ? '+' : '-';
                            $amountClass = $isIncoming ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400';
                        }

                        // Format display names for swaps
                        $fromCryptoDisplay = strtoupper(explode('_', $transaction->from_crypto)[0]);
                        $toCryptoDisplay = strtoupper(explode('_', $transaction->to_crypto)[0]);
                        if (strpos($transaction->from_crypto, '_') !== false) {
                            $networkFrom = explode('_', $transaction->from_crypto)[1];
                            $fromCryptoDisplay .= ' ' . strtoupper($networkFrom);
                        }
                        if (strpos($transaction->to_crypto, '_') !== false) {
                            $networkTo = explode('_', $transaction->to_crypto)[1];
                            $toCryptoDisplay .= ' ' . strtoupper($networkTo);
                        }
                    @endphp
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $iconBg }} rounded-full flex items-center justify-center mr-3">
                            <i class="fas {{ $iconDirection }} {{ $iconColor }}"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">
                                @if($transaction->type === 'funding')
                                    Funded Card
                                @elseif($transaction->type === 'swap')
                                    @if($isIncoming)
                                        Swapped from {{ $fromCryptoDisplay }}
                                    @else
                                        Swapped to {{ $toCryptoDisplay }}
                                    @endif
                                @else
                                    {{ ucfirst($transaction->type) }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="{{ $amountClass }} font-medium">
                            {{ $amountPrefix }}{{ number_format($amount, 6) }} {{ $asset['symbol'] }}
                        </p>
                        @if(isset($transaction->metadata['usd_value']))
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ format_fiat((float) $transaction->metadata['usd_value']) }}
                            </p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exchange-alt text-gray-400 dark:text-gray-500 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium mb-6">No transactions yet</p>
                    <a href="{{ route('buy.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-colors inline-flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Buy {{ $asset['symbol'] }}
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Send Modal -->
        <div id="assetSendModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-80 flex items-center justify-center hidden z-50">
            <div class="bg-white dark:bg-gray-800 w-full md:w-96 md:rounded-xl mt-auto sm:mt-24 relative animate-modal-show">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Send {{ $asset['symbol'] }}</h2>
                    <button onclick="closeAssetSendModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <a href="{{ route('send.payid.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                        class="block bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-blue-500 dark:text-blue-400">Send via PurseKey Wallet Pay ID</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Send coins from PurseKey Wallet to PurseKey Wallet fast and free. No network fee</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('send.external.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                        class="block bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-coins text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Send via {{ $asset['symbol'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Send coins from your PurseKey Wallet crypto wallet. Network fees included</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Receive Modal -->
        <div id="assetReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-80 flex items-center justify-center hidden z-50">
            <div class="bg-white dark:bg-gray-800 w-full md:w-96 md:rounded-xl mt-auto sm:mt-24 relative animate-modal-show">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Receive {{ $asset['symbol'] }}</h2>
                    <button onclick="closeAssetReceiveModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <a href="{{ route('receive.payid.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                        class="block bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-blue-500 dark:text-blue-400">Receive via PurseKey Wallet Pay ID</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Receive coins from PurseKey Wallet to PurseKey Wallet fast and free. No network fee</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('receive.external.details', ['symbol' => strtolower($asset['symbol']), 'network' => $asset['network'] ?? 'native']) }}" 
                        class="block bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-coins text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Receive via {{ $asset['symbol'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Receive coins from your PurseKey Wallet crypto wallet. Network fees included</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction Receipt Modal (Always Light Mode for Printing) -->
        <div id="transactionReceiptModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-80 flex items-center justify-center hidden z-50">
            <div class="bg-white w-full md:w-[420px] md:rounded-xl mt-auto sm:mt-24 relative animate-modal-show max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Transaction Receipt</h2>
                    <button onclick="closeTransactionReceiptModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="transactionReceiptContent" class="p-5 space-y-3 text-sm text-gray-700 bg-white">
                    <!-- Dynamic transaction details will appear here - Always light mode for printing -->
                </div>
            </div>
        </div>
    </main>

    <!-- Styles remain unchanged for receipt printing consistency -->
    <style>
    .animate-modal-show {
        animation: modalShow 0.3s ease-out;
    }
    
    @keyframes modalShow {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Professional receipt styles - Kept in light mode for printing */
    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .receipt-logo {
        font-size: 24px;
        font-weight: 700;
        color: #1e40af;
    }
    
    .receipt-id {
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .receipt-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }
    
    .status-completed {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-failed {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .receipt-amount {
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    }
    
    .receipt-amount-value {
        font-size: 28px;
        font-weight: 700;
        margin: 10px 0;
        color: #1e40af;
    }
    
    .receipt-amount-usd {
        font-size: 16px;
        color: #4b5563;
    }
    
    .receipt-details {
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .detail-value {
        font-weight: 600;
        text-align: right;
    }
    
    .receipt-address {
        background: #f3f4f6;
        padding: 12px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 13px;
        word-break: break-all;
        margin: 10px 0;
    }
    
    .receipt-footer {
        text-align: center;
        padding-top: 20px;
        color: #6b7280;
        font-size: 12px;
        border-top: 1px solid #e5e7eb;
    }
    
    .print-button {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .print-button:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
    }
    
    .print-button i {
        margin-right: 8px;
    }
    
    .receipt-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }
    
    .icon-send {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #ef4444;
    }
    
    .icon-receive {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #22c55e;
    }
    
    .icon-pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #f59e0b;
    }
    </style>

    @push('scripts')
    <script>
        function openAssetSendModal() {
            document.getElementById('assetSendModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAssetSendModal() {
            const modal = document.getElementById('assetSendModal');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-0');
                document.body.style.overflow = '';
            }, 200);
        }

        function openAssetReceiveModal() {
            document.getElementById('assetReceiveModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAssetReceiveModal() {
            const modal = document.getElementById('assetReceiveModal');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-0');
                document.body.style.overflow = '';
            }, 200);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const sendModal = document.getElementById('assetSendModal');
            const receiveModal = document.getElementById('assetReceiveModal');

            if (event.target === sendModal) closeAssetSendModal();
            if (event.target === receiveModal) closeAssetReceiveModal();
        });
        
        function openTransactionReceiptModal(transaction) {
            const modal = document.getElementById('transactionReceiptModal');
            const content = document.getElementById('transactionReceiptContent');

            // Determine direction and status
            const isSwap = transaction.type === 'swap';
            const isWithdrawal = transaction.type === 'withdrawal';
            const isDeposit = transaction.type === 'deposit';
            const isRefund = transaction.type === 'refund';
            const isFunding = transaction.type === 'funding';

            const isPending = transaction.status === 'pending';
            const isCompleted = transaction.status === 'completed';
            const isFailed = transaction.status === 'failed';

            // Format date/time
            const date = new Date(transaction.created_at);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            const formattedTime = date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // Helper: format crypto amount naturally
            function formatCryptoAmount(value) {
                if (value == null || value === '') return '0';
                let num = Number(value);
                let str = num.toFixed(10).replace(/\.?0+$/, '');
                return str === '' ? '0' : str;
            }

            // Helper: truncate hash
            function truncateHash(hash, start = 15, end = 4) {
                if (!hash || hash.length <= start + end) return hash;
                return `${hash.substring(0, start)}.....${hash.substring(hash.length - end)}`;
            }

            // --- Determine crypto symbols and amounts ---
            let displaySymbol = (transaction.which_crypto || 'CRYPTO').toUpperCase();
            let fromLabel, toLabel, displayAmount;

            if (isSwap) {
                fromLabel = transaction.from_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
                toLabel = transaction.to_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
                displayAmount = formatCryptoAmount(transaction.amount_in);
                displaySymbol = fromLabel;
            } else if (isWithdrawal) {
                fromLabel = displaySymbol;
                toLabel = 'External Wallet';
                displayAmount = formatCryptoAmount(transaction.amount_in);
            } else if (isDeposit || isRefund || isFunding) {
                fromLabel = 'External Source';
                toLabel = displaySymbol;
                displayAmount = formatCryptoAmount(transaction.amount_in);
            } else {
                fromLabel = 'System';
                toLabel = displaySymbol;
                displayAmount = formatCryptoAmount(transaction.amount_in);
            }

            // USD value
            const usdValue = transaction.metadata?.usd_value ? Number(transaction.metadata.usd_value).toFixed(2) : null;
            const networkFee = transaction.network_fee ? Number(transaction.network_fee).toFixed(8) : null;

            // Build receipt HTML (always light mode for printing)
            const html = `
                <div class="receipt-header">
                    <div class="receipt-logo">PurseKey Wallet</div>
                </div>
                
                <div class="text-center">
                    <div class="receipt-icon ${isWithdrawal ? 'icon-send' : (isDeposit || isRefund ? 'icon-receive' : 'icon-pending')}">
                        <i class="fas ${isPending ? 'fa-spinner fa-spin' : (isDeposit || isRefund ? 'fa-arrow-down' : 'fa-arrow-up')} text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 capitalize">${transaction.type} Transaction</h3>
                    <div class="receipt-status ${isCompleted ? 'status-completed' : (isPending ? 'status-pending' : 'status-failed')}">
                        ${transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)}
                    </div>
                </div>
                
                <div class="receipt-amount">
                    <div class="text-gray-500">${isWithdrawal ? 'Sent' : (isDeposit || isRefund ? 'Received' : 'Amount')}</div>
                    <div class="receipt-amount-value">
                        ${displayAmount} ${displaySymbol}
                    </div>
                    ${usdValue ? `<div class="receipt-amount-usd">$${usdValue} USD</div>` : ''}
                </div> 
                
                <div class="receipt-details">
                    <div class="detail-row">
                        <span class="detail-label">Date & Time</span>
                        <span class="detail-value">${formattedDate} ${formattedTime}</span>
                    </div>
                    ${transaction.rate ? `
                    <div class="detail-row">
                        <span class="detail-label">Rate</span>
                        <span class="detail-value">${Number(transaction.rate).toFixed(8)} USD</span>
                    </div>` : ''}
                    ${networkFee ? `
                    <div class="detail-row">
                        <span class="detail-label">Network Fee</span>
                        <span class="detail-value">${networkFee} ${displaySymbol}</span>
                    </div>` : ''}
                    <div class="detail-row">
                        <span class="detail-label">From</span>
                        <span class="detail-value">${fromLabel}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">To</span>
                        <span class="detail-value">${toLabel}</span>
                    </div>
                    ${transaction.transaction_hash ? `
                    <div class="detail-row">
                        <span class="detail-label">Transaction Hash</span>
                        <span class="detail-value text-blue-600 hover:underline cursor-pointer" 
                              title="${transaction.transaction_hash}"
                              onclick="window.open('https://etherscan.io/tx/${transaction.transaction_hash}', '_blank')">
                            ${truncateHash(transaction.transaction_hash)}
                        </span>
                    </div>` : ''}
                </div>
                
                ${transaction.from_address ? `
                <div>
                    <p class="text-xs text-gray-500 mb-1">From Address:</p>
                    <div class="receipt-address">${transaction.from_address}</div>
                </div>` : ''}
                
                ${transaction.to_address ? `
                <div>
                    <p class="text-xs text-gray-500 mb-1">To Address:</p>
                    <div class="receipt-address">${transaction.to_address}</div>
                </div>` : ''}
                
                <div class="receipt-footer">
                    <p>Thank you for using PurseKey Wallet</p>
                    <p>www.PurseKey.pro</p>
                </div>
                
                <div class="text-center">
                    <button onclick="window.print()" class="print-button">
                        <i class="fas fa-print"></i>Print Receipt
                    </button>
                </div>
            `;

            content.innerHTML = html;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTransactionReceiptModal() {
            const modal = document.getElementById('transactionReceiptModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionReceiptModal');
            if (event.target === modal) {
                closeTransactionReceiptModal();
            }
        });
    </script>
    @endpush
</x-user-layout>