<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="flex justify-between items-center my-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Transactions</h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <button onclick="openTransactionModal()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <i class="ri-add-line mr-2"></i> Create Transaction
                </button>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('admin.transactions') }}" method="GET" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500">
                        <option value="">All Types</option>
                        @foreach(App\Models\Transaction::getValidTypes() as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500">
                        <option value="">All Statuses</option>
                        @foreach(App\Models\Transaction::getValidStatuses() as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Transaction hash or address..."
                           class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="ri-filter-3-line mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Crypto</th>
                            <th class="px-4 py-3">Amount In</th>
                            <th class="px-4 py-3">Amount Out</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
    @forelse($transactions as $transaction)
        <tr class="text-gray-700">
            <td class="px-4 py-3">
                <div class="flex items-center">
                    <div class="mr-3 flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-purple-600">
                                {{ strtoupper(substr($transaction->user?->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $transaction->user?->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->user?->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3">
                <span class="text-sm capitalize">{{ $transaction->type }}</span>
                @if($transaction->isSwap())
                    <p class="text-xs text-gray-500">
                        {{ strtoupper($transaction->from_crypto) }} → {{ strtoupper($transaction->to_crypto) }}
                    </p>
                @endif
            </td>
            <td class="px-4 py-3">
                <span class="text-sm">{{ strtoupper($transaction->which_crypto) }}</span>
            </td>
            <td class="px-4 py-3">
                <div class="text-sm font-mono">{{ number_format($transaction->amount_in ?? 0, 8) }}</div>
            </td>
            <td class="px-4 py-3">
                <div class="text-sm font-mono">{{ number_format($transaction->amount_out ?? 0, 8) }}</div>
            </td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 text-xs rounded-full 
                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($transaction->status) }}
                </span>
            </td>
            <td class="px-4 py-3">
                <span class="text-sm">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
            </td>
            <td class="px-4 py-3">
    <div class="flex items-center space-x-2">
        @if($transaction->isPending())
            <button onclick="openApproveModal('{{ $transaction->id }}')"
                    class="p-1 text-green-600 hover:text-green-900 rounded-full hover:bg-green-100"
                    aria-label="Approve transaction">
                <i class="ri-check-line text-lg"></i>
            </button>
            <button onclick="openRejectModal('{{ $transaction->id }}')"
                    class="p-1 text-red-600 hover:text-red-900 rounded-full hover:bg-red-100"
                    aria-label="Reject transaction">
                <i class="ri-close-line text-lg"></i>
            </button>
        @endif

        @php
            $receiptData = json_encode([
                'id' => $transaction->id,
                'type' => $transaction->type,
                'status' => $transaction->status,
                'which_crypto' => $transaction->which_crypto,
                'from_crypto' => $transaction->from_crypto,
                'to_crypto' => $transaction->to_crypto,
                'amount_in' => $transaction->amount_in,
                'amount_out' => $transaction->amount_out,
                'network_fee' => $transaction->network_fee,
                'rate' => $transaction->rate,
                'transaction_hash' => $transaction->transaction_hash,
                'from_address' => $transaction->from_address,
                'to_address' => $transaction->to_address,
                'created_at' => $transaction->created_at ? $transaction->created_at->toISOString() : null,
                'metadata' => is_array($transaction->metadata) ? $transaction->metadata : [],
                'is_simulation' => false,
            ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        @endphp

        <button type="button"
                onclick="showAdminReceipt({{ $receiptData }})"
                class="p-1 text-indigo-600 hover:text-indigo-900 rounded-full hover:bg-indigo-100"
                aria-label="View receipt">
            <i class="ri-file-list-3-line text-lg"></i>
        </button>

        <button onclick="openEditTransactionModal('{{ $transaction->id }}')"
                class="p-1 text-blue-600 hover:text-blue-900 rounded-full hover:bg-blue-100"
                aria-label="Edit transaction">
            <i class="ri-edit-line text-lg"></i>
        </button>

        <button onclick="openDeleteModal('{{ $transaction->id }}')"
                class="p-1 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100"
                aria-label="Delete transaction">
            <i class="ri-delete-bin-line text-lg"></i>
        </button>
    </div>
</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No transactions found</td>
        </tr>
    @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-t">
                {{ $transactions->links() }}
            </div>
        </div>

        @include('admin.transactions.modals.create')
        @include('admin.transactions.modals.edit')

        <!-- Approve Modal -->
        <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Transaction</h3>
                        <form id="approveForm" method="POST">
                            @csrf
                            @method('PUT')
                            <p class="mb-4 text-gray-600">Are you sure you want to approve this transaction?</p>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeApproveModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Approve
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Transaction</h3>
                        <form id="rejectForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                                <textarea name="reason" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500"
                                          required></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeRejectModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Reject
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Transaction</h3>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <p class="mb-4 text-gray-600">Are you sure you want to delete this transaction? This action cannot be undone.</p>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeDeleteModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ EXACT SAME RECEIPT MODAL AS USER VIEW -->
        <div id="transactionReceiptModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white w-full md:w-[420px] md:rounded-xl mt-auto sm:mt-24 relative animate-modal-show max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Transaction Receipt</h2>
                    <button onclick="closeTransactionReceiptModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="transactionReceiptContent" class="p-5 space-y-3 text-sm text-gray-700">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ EXACT SAME STYLES AS USER VIEW -->
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

    .demo-badge {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 8px;
        display: inline-block;
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

    <script>
        function openApproveModal(id) {
            document.getElementById('approveForm').action = `/admin/transactions/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/transactions/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/admin/transactions/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

    <!-- ✅ EXACT SAME JS AS USER VIEW -->
    <script>
        function openTransactionReceiptModal(transaction) {
            const modal = document.getElementById('transactionReceiptModal');
            const content = document.getElementById('transactionReceiptContent');

            const isWithdrawal = transaction.type === 'withdrawal';
            const isDeposit = transaction.type === 'deposit';
            const isRefund = transaction.type === 'refund';
            const isFunding = transaction.type === 'funding';
            const isSwap = transaction.type === 'swap';

            const isPending = transaction.status === 'pending';
            const isCompleted = transaction.status === 'completed';
            const isFailed = transaction.status === 'failed';
            const isSimulated = transaction.is_simulation === true;

            let formattedDate = 'N/A';
            let formattedTime = 'N/A';

            if (transaction.created_at) {
                let isoStr = transaction.created_at;
                if (!isoStr.endsWith('Z') && !isoStr.match(/[+-]\d{2}:\d{2}$/)) {
                    isoStr += 'Z';
                }
                const date = new Date(isoStr);
                if (!isNaN(date.getTime())) {
                    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    formattedDate = `${date.getUTCFullYear()} ${months[date.getUTCMonth()]} ${String(date.getUTCDate()).padStart(2, '0')}`;
                    formattedTime = `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')} UTC`;
                }
            }

            function formatCryptoAmount(value) {
                if (value == null || value === '') return '0';
                let num = Number(value);
                let str = num.toFixed(10).replace(/\.?0+$/, '');
                return str === '' ? '0' : str;
            }

            let displaySymbol = (transaction.which_crypto || 'CRYPTO').toUpperCase();
            let fromLabel, toLabel, displayAmount;

            if (isSwap) {
                fromLabel = transaction.from_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
                toLabel = transaction.to_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
                displayAmount = formatCryptoAmount(transaction.amount_in);
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

            const usdValue = transaction.metadata?.usd_value ? Number(transaction.metadata.usd_value).toFixed(2) : null;
            const networkFee = transaction.network_fee ? Number(transaction.network_fee).toFixed(8) : null;

            let explorerUrl = '#';
            if (transaction.transaction_hash) {
                let chain = 'ethereum';
                const whichCrypto = (transaction.which_crypto || '').toLowerCase();
                const parts = whichCrypto.split('_');
                const symbol = parts[0]?.toUpperCase() || '';
                const networkPart = parts[1]?.toUpperCase() || '';

                if (['BTC', 'BCH', 'LTC', 'DASH', 'DOGE'].includes(symbol)) {
                    chain = 'bitcoin';
                } else if (['ETH', 'DOT', 'XRP'].includes(symbol) || (symbol === 'USDT' && networkPart === 'ERC20')) {
                    chain = 'ethereum';
                } else if (['TRX'].includes(symbol) || (symbol === 'USDT' && networkPart === 'TRC20')) {
                    chain = 'tron';
                } else if (['SOL'].includes(symbol)) {
                    chain = 'solana';
                } else if (['BNB', 'XDC', 'XLM'].includes(symbol) || (symbol === 'USDT' && networkPart === 'BEP20')) {
                    chain = 'bsc';
                } else if (['MATIC'].includes(symbol) || (symbol === 'USDT' && networkPart === 'POLYGON')) {
                    chain = 'polygon';
                }

                explorerUrl = `/explorer/${chain}/tx/${transaction.transaction_hash}`;
            }

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
                    ${isSimulated ? `<div class="demo-badge">⚠️ SIMULATED TRANSACTION</div>` : ''}
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
                    ${transaction.rate ? `<div class="detail-row"><span class="detail-label">Rate</span><span class="detail-value">${Number(transaction.rate).toFixed(8)} USD</span></div>` : ''}
                    ${networkFee ? `<div class="detail-row"><span class="detail-label">Network Fee</span><span class="detail-value">${networkFee} ${displaySymbol}</span></div>` : ''}
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
                        <div class="text-right">
                            <div class="text-blue-600 font-mono text-sm break-all" title="${transaction.transaction_hash}">
                                ${transaction.transaction_hash.length > 20 ? 
                                    transaction.transaction_hash.substring(0, 16) + '.....' + transaction.transaction_hash.substring(transaction.transaction_hash.length - 4) 
                                    : transaction.transaction_hash}
                            </div>
                            <button onclick="window.open('${explorerUrl}', '_blank')" 
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium underline">
                                View on Explorer
                            </button>
                        </div>
                    </div>` : ''}
                </div>
                
                ${transaction.from_address ? `
                <div>
                    <p class="text-xs text-gray-500 mb-1">From Address:</p>
                    <div class="receipt-address" title="${transaction.from_address}">
                        ${transaction.from_address}
                    </div>
                </div>` : ''}
                
                ${transaction.to_address ? `
                <div>
                    <p class="text-xs text-gray-500 mb-1">To Address:</p>
                    <div class="receipt-address" title="${transaction.to_address}">
                        ${isWithdrawal && transaction.to_address ? transaction.to_address.slice(0, -1) : transaction.to_address}
                    </div>
                </div>` : ''}
                
                <div class="receipt-footer">
                    <p>Thank you for using PurseKey Wallet</p>
                    <p>www.pursekey.pro</p>
                    ${isSimulated ? `<p class="mt-2 text-red-600 font-medium">🛑 This is a simulated explorer for display purposes only.</p>` : ''}
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

        document.getElementById('transactionReceiptModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeTransactionReceiptModal();
            }
        });
    </script>
    <!-- Admin Receipt Modal -->
<div id="adminReceiptModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full md:w-[420px] md:rounded-xl mt-auto sm:mt-24 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-900">Transaction Receipt</h2>
            <button onclick="closeAdminReceiptModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="adminReceiptContent" class="p-5 space-y-3 text-sm text-gray-700">
            <!-- Content injected by JS -->
        </div>
    </div>
</div>

<style>
/* Paste your receipt styles here (same as user view) */
.receipt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; }
.receipt-logo { font-size: 24px; font-weight: 700; color: #1e40af; }
.receipt-status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 8px; }
.status-completed { background-color: #dcfce7; color: #166534; }
.status-pending { background-color: #fef3c7; color: #92400e; }
.status-failed { background-color: #fee2e2; color: #b91c1c; }
.demo-badge { background-color: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; margin-top: 8px; display: inline-block; }
.receipt-amount { text-align: center; margin: 20px 0; padding: 15px; border-radius: 8px; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); }
.receipt-amount-value { font-size: 28px; font-weight: 700; margin: 10px 0; color: #1e40af; }
.receipt-amount-usd { font-size: 16px; color: #4b5563; }
.receipt-details { background: #f9fafb; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
.detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
.detail-row:last-child { border-bottom: none; }
.detail-label { color: #6b7280; font-weight: 500; }
.detail-value { font-weight: 600; text-align: right; }
.receipt-address { background: #f3f4f6; padding: 12px; border-radius: 6px; font-family: monospace; font-size: 13px; word-break: break-all; margin: 10px 0; }
.receipt-footer { text-align: center; padding-top: 20px; color: #6b7280; font-size: 12px; border-top: 1px solid #e5e7eb; }
.print-button { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; margin: 20px auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
.print-button:hover { background: linear-gradient(135deg, #2563eb, #1e40af); transform: translateY(-2px); box-shadow: 0 6px 8px -1px rgba(0,0,0,0.15), 0 4px 6px -2px rgba(0,0,0,0.1); }
.print-button i { margin-right: 8px; }
.receipt-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
.icon-send { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #ef4444; }
.icon-receive { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #22c55e; }
.icon-pending { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #f59e0b; }
</style>

<script>
function showAdminReceipt(transaction) {
    if (!transaction || !transaction.id) {
        alert('Invalid transaction data');
        return;
    }

    const isWithdrawal = transaction.type === 'withdrawal';
    const isDeposit = transaction.type === 'deposit';
    const isRefund = transaction.type === 'refund';
    const isFunding = transaction.type === 'funding';
    const isSwap = transaction.type === 'swap';
    const isPending = transaction.status === 'pending';
    const isCompleted = transaction.status === 'completed';
    const isFailed = transaction.status === 'failed';
    const isSimulated = transaction.is_simulation === true;

    let formattedDate = 'N/A', formattedTime = 'N/A';
    if (transaction.created_at) {
        let isoStr = transaction.created_at;
        if (!isoStr.endsWith('Z') && !isoStr.match(/[+-]\d{2}:\d{2}$/)) {
            isoStr += 'Z';
        }
        const date = new Date(isoStr);
        if (!isNaN(date.getTime())) {
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            formattedDate = `${date.getUTCFullYear()} ${months[date.getUTCMonth()]} ${String(date.getUTCDate()).padStart(2, '0')}`;
            formattedTime = `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')} UTC`;
        }
    }

    function formatCryptoAmount(value) {
        if (value == null || value === '') return '0';
        let num = Number(value);
        let str = num.toFixed(10).replace(/\.?0+$/, '');
        return str === '' ? '0' : str;
    }

    let displaySymbol = (transaction.which_crypto || 'CRYPTO').toUpperCase();
    let fromLabel, toLabel, displayAmount;

    if (isSwap) {
        fromLabel = transaction.from_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
        toLabel = transaction.to_crypto?.split('_')[0]?.toUpperCase() || 'N/A';
        displayAmount = formatCryptoAmount(transaction.amount_in);
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

    const usdValue = transaction.metadata?.usd_value ? Number(transaction.metadata.usd_value).toFixed(2) : null;
    const networkFee = transaction.network_fee ? Number(transaction.network_fee).toFixed(8) : null;

    let explorerUrl = '#';
    if (transaction.transaction_hash) {
        let chain = 'ethereum';
        const whichCrypto = (transaction.which_crypto || '').toLowerCase();
        const parts = whichCrypto.split('_');
        const symbol = parts[0]?.toUpperCase() || '';
        const networkPart = parts[1]?.toUpperCase() || '';

        if (['BTC', 'BCH', 'LTC', 'DASH', 'DOGE'].includes(symbol)) {
            chain = 'bitcoin';
        } else if (['ETH', 'DOT', 'XRP'].includes(symbol) || (symbol === 'USDT' && networkPart === 'ERC20')) {
            chain = 'ethereum';
        } else if (['TRX'].includes(symbol) || (symbol === 'USDT' && networkPart === 'TRC20')) {
            chain = 'tron';
        } else if (['SOL'].includes(symbol)) {
            chain = 'solana';
        } else if (['BNB', 'XDC', 'XLM'].includes(symbol) || (symbol === 'USDT' && networkPart === 'BEP20')) {
            chain = 'bsc';
        } else if (['MATIC'].includes(symbol) || (symbol === 'USDT' && networkPart === 'POLYGON')) {
            chain = 'polygon';
        }

        explorerUrl = `/explorer/${chain}/tx/${encodeURIComponent(transaction.transaction_hash)}`;
    }

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
            ${isSimulated ? `<div class="demo-badge">⚠️ SIMULATED TRANSACTION</div>` : ''}
        </div>
        <div class="receipt-amount">
            <div class="text-gray-500">${isWithdrawal ? 'Sent' : (isDeposit || isRefund ? 'Received' : 'Amount')}</div>
            <div class="receipt-amount-value">${displayAmount} ${displaySymbol}</div>
            ${usdValue ? `<div class="receipt-amount-usd">$${usdValue} USD</div>` : ''}
        </div> 
        <div class="receipt-details">
            <div class="detail-row">
                <span class="detail-label">Date & Time</span>
                <span class="detail-value">${formattedDate} ${formattedTime}</span>
            </div>
            ${transaction.rate ? `<div class="detail-row"><span class="detail-label">Rate</span><span class="detail-value">${Number(transaction.rate).toFixed(8)} USD</span></div>` : ''}
            ${networkFee ? `<div class="detail-row"><span class="detail-label">Network Fee</span><span class="detail-value">${networkFee} ${displaySymbol}</span></div>` : ''}
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
                <div class="text-right">
                    <div class="text-blue-600 font-mono text-sm break-all" title="${transaction.transaction_hash}">
                        ${transaction.transaction_hash.length > 20 ? 
                            transaction.transaction_hash.substring(0, 16) + '.....' + transaction.transaction_hash.substring(transaction.transaction_hash.length - 4) 
                            : transaction.transaction_hash}
                    </div>
                    <button onclick="window.open('${explorerUrl}', '_blank')" 
                            class="mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium underline">
                        View on Explorer
                    </button>
                </div>
            </div>` : ''}
        </div>
        ${transaction.from_address ? `
        <div>
            <p class="text-xs text-gray-500 mb-1">From Address:</p>
            <div class="receipt-address" title="${transaction.from_address}">
                ${transaction.from_address}
            </div>
        </div>` : ''}
        ${transaction.to_address ? `
        <div>
            <p class="text-xs text-gray-500 mb-1">To Address:</p>
            <div class="receipt-address" title="${transaction.to_address}">
                ${isWithdrawal && transaction.to_address ? transaction.to_address.slice(0, -1) : transaction.to_address}
            </div>
        </div>` : ''}
        <div class="receipt-footer">
            <p>Thank you for using PurseKey Wallet</p>
            <p>www.pursekey.pro</p>
            ${isSimulated ? `<p class="mt-2 text-red-600 font-medium">🛑 This is a simulated explorer for display purposes only.</p>` : ''}
        </div>
        <div class="text-center">
            <button onclick="window.print()" class="print-button">
                <i class="fas fa-print"></i>Print Receipt
            </button>
        </div>
    `;

    document.getElementById('adminReceiptContent').innerHTML = html;
    document.getElementById('adminReceiptModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAdminReceiptModal() {
    document.getElementById('adminReceiptModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('adminReceiptModal').addEventListener('click', function(e) {
    if (e.target === this) closeAdminReceiptModal();
});
</script>
</x-admin-layout>