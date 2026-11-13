@php
    $transactionCryptos = collect(App\Models\CryptoAsset::getSupportedCryptos())
        ->mapWithKeys(fn ($crypto, $symbol) => [$symbol => $crypto['name']]);
    $catalogCryptos = App\Models\Cryptocurrency::orderBy('name')
        ->orderBy('network')
        ->get();
    $catalogPriceKeys = $catalogCryptos->mapWithKeys(fn ($currency) => [
        strtolower($currency->symbol) => $currency->price_coin_id ?: $currency->coin_id,
    ]);

    foreach ($catalogCryptos as $currency) {
        $label = $currency->name;
        if ($currency->network) {
            $label .= ' (' . strtoupper($currency->network) . ')';
        }
        $transactionCryptos[strtolower($currency->symbol)] = $label;
    }
@endphp

<!-- Create Transaction Modal -->
<div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen p-4 pt-10 sm:p-6">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Create Transaction</h3>
                <button type="button" onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.transactions.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <!-- User Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id" required
                            class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select User</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <!-- Transaction Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <select name="type" required onchange="handleTransactionTypeChange(this.value)"
                                class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Type</option>
                            @foreach(App\Models\Transaction::getValidTypes() as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Crypto Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cryptocurrency</label>
                        <select name="which_crypto" required
                                class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                onchange="updateCryptoFields(this.value)">
                            <option value="">Select Cryptocurrency</option>
                            @foreach($transactionCryptos as $symbol => $name)
                                @php
                                    $priceKey = $catalogPriceKeys[$symbol] ?? App\Models\CryptoAsset::getPriceKey($symbol);
                                @endphp
                                <option value="{{ $symbol }}"
                                        data-price="{{ $prices[$priceKey]['usd'] ?? 0 }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Swap Fields (Hidden by default) -->
                <div id="swapFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Crypto</label>
                        <select name="from_crypto"
                                class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Cryptocurrency</option>
                            @foreach($transactionCryptos as $symbol => $name)
                                <option value="{{ $symbol }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Crypto</label>
                        <select name="to_crypto"
                                class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Cryptocurrency</option>
                            @foreach($transactionCryptos as $symbol => $name)
                                <option value="{{ $symbol }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Amount Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount In</label>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   name="amount_in" 
                                   id="amountInInput"
                                   step="0.00000001" 
                                   required
                                   oninput="updateUsdValueFromAmount()"
                                   class="flex-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00000000">
                            <span class="text-sm text-gray-600 min-w-[80px]">
                                ≈ $<span id="amountInUsd">0.00</span>
                            </span>
                        </div>
                    </div>
                    <div id="amountOutField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount Out</label>
                        <input type="number" name="amount_out" id="amountOutInput" step="0.00000001"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="0.00000000">
                    </div>
                </div>

                <!-- Network Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Network Fee</label>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   name="network_fee" 
                                   id="networkFeeInput"
                                   step="0.00000001"
                                   oninput="updateUsdValueFromFee()"
                                   class="flex-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00000000">
                            <span class="text-sm text-gray-600 min-w-[80px]">
                                ≈ $<span id="networkFeeUsd">0.00</span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rate (USD per 1 unit)</label>
                        <input type="number" 
                               name="rate" 
                               id="rateInput"
                               step="0.000001"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Auto-filled from market">
                    </div>
                </div>

                <!-- Transaction Hash & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Hash</label>
                        <input type="text" name="transaction_hash"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Txn hash id">
                    </div> 
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" required
                                class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
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
                        <input type="text" name="from_address" id="fromAddress"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="From address">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Address</label>
                        <input type="text" name="to_address" id="toAddress"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="To address">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" onclick="closeTransactionModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Create Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentUsdPrice = 0;

    function openTransactionModal() {
        document.getElementById('transactionModal').classList.remove('hidden');
    }

    function closeTransactionModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }

    function handleTransactionTypeChange(value) {
        const swapFields = document.getElementById('swapFields');
        const amountOutField = document.getElementById('amountOutField');
        const amountOutInput = document.getElementById('amountOutInput');

        // Reset amount_out when changing type (optional but clean)
        if (amountOutInput) amountOutInput.value = '';

        if (value === 'swap') {
            swapFields.classList.remove('hidden');
            amountOutField.classList.remove('hidden');
        } else {
            swapFields.classList.add('hidden');
            if (value === 'withdrawal') {
                amountOutField.classList.remove('hidden');
                // Sync if amount_in already has a value
                updateUsdValueFromAmount(); // This will trigger sync
            } else {
                amountOutField.classList.add('hidden');
            }
        }
    }

    function updateCryptoFields(symbol) {
        const select = document.querySelector(`select[name="which_crypto"]`);
        const option = select.querySelector(`option[value="${symbol}"]`);
        currentUsdPrice = parseFloat(option?.dataset.price) || 0;

        document.getElementById('rateInput').value = currentUsdPrice > 0 ? currentUsdPrice.toFixed(6) : '';

        updateUsdValueFromAmount();
        updateUsdValueFromFee();
    }

    function syncAmountInOutForWithdrawal() {
        const typeSelect = document.querySelector('select[name="type"]');
        const amountInInput = document.getElementById('amountInInput');
        const amountOutInput = document.getElementById('amountOutInput');

        // Only proceed if all elements exist and type is withdrawal
        if (!typeSelect || !amountInInput || !amountOutInput) return;

        if (typeSelect.value === 'withdrawal') {
            amountOutInput.value = amountInInput.value || '';
        }
    }

    function updateUsdValueFromAmount() {
        const amount = parseFloat(document.getElementById('amountInInput').value) || 0;
        const usd = amount * currentUsdPrice;
        document.getElementById('amountInUsd').textContent = usd.toFixed(2);

        // Auto-sync amount_out for withdrawals
        syncAmountInOutForWithdrawal();
    }

    function updateUsdValueFromFee() {
        const fee = parseFloat(document.getElementById('networkFeeInput').value) || 0;
        const usd = fee * currentUsdPrice;
        document.getElementById('networkFeeUsd').textContent = usd.toFixed(2);
    }

    // Optional: fetch user address (kept as-is)
    async function updateAddressFields(crypto) {
        const user_id = document.querySelector('select[name="user_id"]').value;
        if (!user_id || !crypto) return;

        try {
            const response = await fetch(`/api/users/${user_id}/crypto-address/${crypto}`);
            const data = await response.json();
            if (data.address) {
                document.getElementById('fromAddress').value = data.address;
            }
        } catch (error) {
            console.error('Failed to fetch crypto address:', error);
        }
    }

    // Initialize if a crypto is pre-selected
    document.addEventListener('DOMContentLoaded', function () {
        const cryptoSelect = document.querySelector('select[name="which_crypto"]');
        if (cryptoSelect && cryptoSelect.value) {
            updateCryptoFields(cryptoSelect.value);
        }
    });
</script>