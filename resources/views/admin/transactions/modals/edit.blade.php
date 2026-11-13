@php
    $transactionCryptos = collect(App\Models\CryptoAsset::getSupportedCryptos())
        ->mapWithKeys(fn ($crypto, $symbol) => [$symbol => $crypto['name']]);
    $catalogCryptos = App\Models\Cryptocurrency::orderBy('name')
        ->orderBy('network')
        ->get();

    foreach ($catalogCryptos as $currency) {
        $label = $currency->name;
        if ($currency->network) {
            $label .= ' (' . strtoupper($currency->network) . ')';
        }
        $transactionCryptos[strtolower($currency->symbol)] = $label;
    }
@endphp

<!-- Edit Transaction Modal -->
<div id="editTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen p-4 pt-10 sm:p-6">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit Transaction</h3>
                <button type="button" onclick="closeEditTransactionModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="editTransactionForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editTransactionId">

                <!-- User - DISPLAY ONLY -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <div class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700">
                        <span id="editUserDisplay">—</span>
                    </div>
                </div>

                <!-- Type & Crypto - DISPLAY ONLY -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <div class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700">
                            <span id="editTypeDisplay">—</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cryptocurrency</label>
                        <div class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700">
                            <span id="editCryptoDisplay">—</span>
                        </div>
                    </div>
                </div>

                <!-- Swap Fields -->
                <div id="editSwapFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Crypto</label>
                        <select name="from_crypto" class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select</option>
                            @foreach($transactionCryptos as $symbol => $name)
                                <option value="{{ $symbol }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Crypto</label>
                        <select name="to_crypto" class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select</option>
                            @foreach($transactionCryptos as $symbol => $name)
                                <option value="{{ $symbol }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Amount In -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount In</label>
                    <input type="number" 
                           id="editAmountInInput"
                           step="0.00000001"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="0.00000000">
                    <div id="editAmountInDisplay" class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700 font-mono mt-1 hidden"></div>
                </div>

                <!-- Amount Out -->
                <div class="mb-6 hidden" id="editAmountOutField">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount Out</label>
                    <input type="number" 
                           id="editAmountOutInput"
                           step="0.00000001"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="0.00000000">
                    <div id="editAmountOutDisplay" class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700 font-mono mt-1 hidden"></div>
                </div>

                <!-- Network Fee -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Network Fee</label>
                    <input type="number" 
                           id="editNetworkFeeInput"
                           step="0.00000001"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="0.00000000">
                    <div id="editNetworkFeeDisplay" class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700 font-mono mt-1 hidden"></div>
                </div>

                <!-- Rate -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rate (USD per 1 unit)</label>
                    <input type="number" 
                           id="editRateInput"
                           step="0.000001"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Auto-filled">
                    <div id="editRateDisplay" class="w-full px-3 py-2 bg-gray-100 rounded-lg text-gray-700 mt-1 hidden"></div>
                </div>

                <!-- Always Editable -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Hash</label>
                        <input type="text" name="transaction_hash" id="editTransactionHash"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter transaction hash">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            @foreach(App\Models\Transaction::getValidStatuses() as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Address Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                        <input type="text" name="from_address" id="editFromAddress"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="From address">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Address</label>
                        <input type="text" name="to_address" id="editToAddress"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="To address">
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" onclick="closeEditTransactionModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditTransactionModal(transactionId) {
        document.getElementById('editTransactionForm').action = `/admin/transactions/${transactionId}`;

        fetch(`/admin/transactions/${transactionId}/edit`)
            .then(response => response.json())
            .then(transaction => {
                document.getElementById('editTransactionId').value = transaction.id;
                document.getElementById('editUserDisplay').textContent = 
                    (transaction.user?.name || 'Unknown') + ' (' + (transaction.user?.email || 'N/A') + ')';
                document.getElementById('editTypeDisplay').textContent = transaction.type || '—';
                document.getElementById('editCryptoDisplay').textContent = transaction.which_crypto || '—';

                const isWithdrawal = transaction.type === 'withdrawal';
                const isSwap = transaction.type === 'swap';
                const isEditableType = !isWithdrawal;

                // --- Amount In ---
                const amountInInput = document.getElementById('editAmountInInput');
                const amountInDisplay = document.getElementById('editAmountInDisplay');
                if (isEditableType) {
                    amountInInput.value = transaction.amount_in || '';
                    amountInInput.name = 'amount_in'; // ✅ Only add name if editable
                    amountInInput.classList.remove('hidden');
                    amountInDisplay.classList.add('hidden');
                } else {
                    amountInInput.removeAttribute('name'); // ❌ Remove name
                    amountInInput.classList.add('hidden');
                    amountInDisplay.textContent = transaction.amount_in || '0';
                    amountInDisplay.classList.remove('hidden');
                }

                // --- Amount Out ---
                const amountOutField = document.getElementById('editAmountOutField');
                const amountOutInput = document.getElementById('editAmountOutInput');
                const amountOutDisplay = document.getElementById('editAmountOutDisplay');
                if (isSwap || isWithdrawal) {
                    amountOutField.classList.remove('hidden');
                    if (isEditableType) { // swap
                        amountOutInput.value = transaction.amount_out || '';
                        amountOutInput.name = 'amount_out'; // ✅
                        amountOutInput.classList.remove('hidden');
                        amountOutDisplay.classList.add('hidden');
                    } else { // withdrawal
                        amountOutInput.removeAttribute('name'); // ❌
                        amountOutInput.classList.add('hidden');
                        amountOutDisplay.textContent = transaction.amount_out || '0';
                        amountOutDisplay.classList.remove('hidden');
                    }
                } else {
                    amountOutField.classList.add('hidden');
                }

                // --- Network Fee ---
                const networkFeeInput = document.getElementById('editNetworkFeeInput');
                const networkFeeDisplay = document.getElementById('editNetworkFeeDisplay');
                if (isEditableType) {
                    networkFeeInput.value = transaction.network_fee || '';
                    networkFeeInput.name = 'network_fee'; // ✅
                    networkFeeInput.classList.remove('hidden');
                    networkFeeDisplay.classList.add('hidden');
                } else {
                    networkFeeInput.removeAttribute('name'); // ❌
                    networkFeeInput.classList.add('hidden');
                    networkFeeDisplay.textContent = transaction.network_fee || '0';
                    networkFeeDisplay.classList.remove('hidden');
                }

                // --- Rate ---
                const rateInput = document.getElementById('editRateInput');
                const rateDisplay = document.getElementById('editRateDisplay');
                if (isEditableType) {
                    rateInput.value = transaction.rate || '';
                    rateInput.name = 'rate'; // ✅
                    rateInput.classList.remove('hidden');
                    rateDisplay.classList.add('hidden');
                } else {
                    rateInput.removeAttribute('name'); // ❌
                    rateInput.classList.add('hidden');
                    rateDisplay.textContent = transaction.rate ? parseFloat(transaction.rate).toFixed(6) : '—';
                    rateDisplay.classList.remove('hidden');
                }

                // --- Swap Fields ---
                if (isSwap) {
                    document.getElementById('editSwapFields').classList.remove('hidden');
                    document.querySelector('select[name="from_crypto"]').value = transaction.from_crypto || '';
                    document.querySelector('select[name="to_crypto"]').value = transaction.to_crypto || '';
                } else {
                    document.getElementById('editSwapFields').classList.add('hidden');
                }

                // --- Always editable ---
                document.getElementById('editTransactionHash').value = transaction.transaction_hash || '';
                document.querySelector('select[name="status"]').value = transaction.status || '';
                document.getElementById('editFromAddress').value = transaction.from_address || '';
                document.getElementById('editToAddress').value = transaction.to_address || '';

                document.getElementById('editTransactionModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load transaction.');
            });
    }

    function closeEditTransactionModal() {
        document.getElementById('editTransactionModal').classList.add('hidden');
    }
</script>