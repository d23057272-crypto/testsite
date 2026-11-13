<x-user-layout>
    <main class="w-full mx-auto h-auto bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <a href="{{ route('card.index') }}" class="text-gray-400 hover:text-gray-300">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Add Money</h1>
            <div class="w-8"></div>
        </div>

        <!-- Card Balance -->
        <div class="p-4">
            <div class="w-full bg-gradient-to-br from-purple-900 to-purple-700 rounded-xl p-6 mb-6">
                <div class="text-white">
                    <div class="text-sm">Current Balance</div>
                    <div class="text-3xl font-bold">${{ number_format($card->balance, 2) }}</div>
                </div>
            </div>

            <!-- Fund Form -->
            <form action="{{ route('card.fund') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Amount Input -->
                <div>
                    <label class="block text-sm font-medium mb-2">Amount to Add</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">$</span>
                        <input type="number" 
                               name="amount" 
                               step="0.01" 
                               min="1" 
                               class="w-full bg-white dark:bg-gray-800 border border-gray-700 rounded-lg p-3 pl-8" 
                               placeholder="0.00"
                               required>
                        <div id="conversionInfo" class="mt-2 text-sm text-gray-400"></div>
                    </div>
                </div>

                <!-- Asset Selection -->
                <div>
                    <label class="block text-sm font-medium mb-2">Select Asset</label>
                    <div class="space-y-4">
                       @foreach(['btc', 'usdt_trc20', 'usdt_bep20', 'usdt_erc20', 'eth', 'trx', 'bnb', 'dot', 'bch', 'ltc', 'xlm', 'dash', 'sol'] as $crypto)
    @if($cryptoAsset->{$crypto . '_status'})
        @php
            $balance = $cryptoAsset->{$crypto . '_balance'};
            
            // Handle USDT networks and other cryptos differently
            if (str_starts_with($crypto, 'usdt_')) {
                $cryptoName = 'tether';
            } else {
                $cryptoMap = [
                    'btc' => 'bitcoin',
                    'eth' => 'ethereum',
                    'trx' => 'tron',
                    'bnb' => 'binancecoin',
                    'dot' => 'polkadot',
                    'bch' => 'bitcoin-cash',
                    'ltc' => 'litecoin',
                    'xlm' => 'stellar',
                    'dash' => 'dash',
                    'sol' => 'solana'
                ];
                $cryptoName = $cryptoMap[$crypto] ?? $crypto;
            }
            
            $price = $prices[$cryptoName]['usd'] ?? 0;
            $usdValue = $balance * $price;
            $cryptoSymbol = strtoupper(explode('_', $crypto)[0]);
        @endphp
        <label class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-750">
            <div class="flex items-center flex-1">
                <img src="{{ asset($crypto . '-icon.png') }}" alt="{{ $cryptoSymbol }}" class="w-8 h-8 mr-3">
                <div class="min-w-0">
                    <div class="font-medium">{{ strtoupper(ucfirst(str_replace('_', ' ', $crypto))) }}</div>
                    <div class="text-sm text-gray-400 truncate">
                        Available: {{ number_format($balance, 4) }} {{ $cryptoSymbol }}
                        <span class="text-gray-500">(≈ {{ format_fiat((float) $usdValue) }})</span>
                    </div>
                </div>
            </div>
            <input type="radio" 
                   name="crypto" 
                   value="{{ $crypto }}" 
                   class="form-radio text-blue-500 ml-4"
                   data-price="{{ $price }}"
                   data-balance="{{ $balance }}"
                   data-symbol="{{ $cryptoSymbol }}">
        </label>
    @endif
@endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-4 rounded-lg transition duration-200">
                    Add Money
                </button>
            </form>
        </div>

        <!-- Information Section -->
        <div class="p-4 mt-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                <h3 class="font-medium mb-2">Important Information</h3>
                <ul class="text-sm text-gray-400 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-info-circle mt-1 mr-2"></i>
                        <span>The conversion rate is based on the current market price</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-clock mt-1 mr-2"></i>
                        <span>Processing time: Instant</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-shield-alt mt-1 mr-2"></i>
                        <span>Your transaction is secure and encrypted</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="fixed top-10 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="successMessage">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="fixed top-10 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg" id="errorMessage">
            {{ session('error') }}
        </div>
        @endif
    </main>

    <script>
        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('#successMessage, #errorMessage');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(() => message.remove(), 500);
                }, 3000);
            });

            // Real-time conversion handling
            const amountInput = document.querySelector('input[name="amount"]');
            const cryptoRadios = document.querySelectorAll('input[name="crypto"]');
            const conversionInfo = document.getElementById('conversionInfo');
            
            function updateConversion() {
                const amount = parseFloat(amountInput.value) || 0;
                const selectedCrypto = document.querySelector('input[name="crypto"]:checked');
                
                if (selectedCrypto && amount > 0) {
                    const price = parseFloat(selectedCrypto.dataset.price);
                    const balance = parseFloat(selectedCrypto.dataset.balance);
                    const symbol = selectedCrypto.dataset.symbol;
                    const requiredCrypto = amount / price;
                    
                    conversionInfo.textContent = `≈ ${requiredCrypto.toFixed(8)} ${symbol}`;
                    
                    if (requiredCrypto > balance) {
                        conversionInfo.className = 'mt-2 text-sm text-red-500';
                        conversionInfo.textContent += ' (Insufficient balance)';
                    } else {
                        conversionInfo.className = 'mt-2 text-sm text-gray-400';
                    }
                } else {
                    conversionInfo.textContent = '';
                }
            }
            
            amountInput.addEventListener('input', updateConversion);
            cryptoRadios.forEach(radio => radio.addEventListener('change', updateConversion));

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const amount = parseFloat(amountInput.value) || 0;
                const selectedCrypto = document.querySelector('input[name="crypto"]:checked');
                
                if (!selectedCrypto) {
                    e.preventDefault();
                    alert('Please select a cryptocurrency');
                    return;
                }
                
                if (amount <= 0) {
                    e.preventDefault();
                    alert('Please enter a valid amount');
                    return;
                }

                const price = parseFloat(selectedCrypto.dataset.price);
                const balance = parseFloat(selectedCrypto.dataset.balance);
                const requiredCrypto = amount / price;
                
                if (requiredCrypto > balance) {
                    e.preventDefault();
                    alert('Insufficient balance');
                    return;
                }
            });
        });
    </script>
</x-user-layout>