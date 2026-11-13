<x-user-layout>
    @php
        $fiatCurrency = auth()->user()->preferred_currency ?? 'USD';
        $fiatRate = app(\App\Services\CurrencyService::class)->rate($fiatCurrency);
        if ($fiatRate === null) {
            $fiatCurrency = 'USD';
            $fiatRate = 1;
        }
    @endphp
    <div class="w-full mx-auto h-auto bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
            <a href="{{ route('send.payid') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Send {{ $asset['name'] }}</h1>
            <div class="w-8"></div>
        </div>

        <!-- Main Content -->
        <div class="p-4 space-y-6">
            <!-- Sending Method -->
            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl text-center border border-blue-100 dark:border-blue-900/30">
                <p class="text-sm">
                    <span class="text-gray-700 dark:text-gray-300">Sending via - </span>
                    <span class="text-blue-500 dark:text-blue-400 font-medium">PayID</span>
                </p>
            </div>

            @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl relative" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            @endif
            
            @include('user.status.index')

            <form id="sendForm" class="space-y-6">
                @csrf
                <!-- PayID Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PayID
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="payid"
                            value="{{ old('payid') }}"
                            placeholder="Enter PayID"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl py-3.5 px-4 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-700 transition-colors"
                            required
                        >
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                            <button type="button" id="pasteButton" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 px-2 transition-colors">
                                Paste
                            </button>
                        </div>
                    </div>
                    <p id="payidError" class="hidden text-red-500 dark:text-red-400 text-sm"></p>
                    <p id="recipientName" class="hidden text-green-500 dark:text-green-400 text-sm"></p>
                </div>

                <!-- Amount Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Amount
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="amount"
                            value="{{ old('amount', session('amount')) }}"
                            step="0.00000001"
                            min="0"
                            max="{{ $asset['balance'] }}"
                            placeholder="0.0000"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl py-3.5 px-4 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-700 transition-colors"
                            required
                        >
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                            <span class="text-gray-500 dark:text-gray-400">{{ $asset['symbol'] }}</span>
                            <button type="button" id="maxButton" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 px-2 transition-colors">
                                Max
                            </button>
                        </div>
                    </div>
                    <p id="amountError" class="hidden text-red-500 dark:text-red-400 text-sm"></p>
                </div>

                <!-- Percentage Buttons -->
                <div class="grid grid-cols-4 gap-4">
                    <button type="button" class="percentButton px-4 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 transition-colors" data-percentage="25">
                        25%
                    </button>
                    <button type="button" class="percentButton px-4 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 transition-colors" data-percentage="50">
                        50%
                    </button>
                    <button type="button" class="percentButton px-4 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 transition-colors" data-percentage="75">
                        75%
                    </button>
                    <button type="button" class="percentButton px-4 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-200 transition-colors" data-percentage="100">
                        100%
                    </button>
                </div>

                <!-- Balance Info -->
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span id="usdValue">≈ {{ format_fiat(0) }}</span>
                    <span>Available: {{ rtrim(sprintf('%.8f', $asset['balance']), '0') }} {{ $asset['symbol'] }}</span>
                </div>

                <!-- Preview Section -->
                <div id="previewSection" class="space-y-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Transaction Preview</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Recipient:</span>
                            <span id="previewRecipient" class="text-gray-900 dark:text-gray-100">Not selected</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                            <span id="previewAmount" class="text-gray-900 dark:text-gray-100">0 {{ $asset['symbol'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Transfer Fee:</span>
                            <span id="previewFee" class="text-gray-900 dark:text-gray-100">{{ $asset['fee'] }} {{ $asset['symbol'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>Total Amount:</span>
                            <span id="previewTotal">0 {{ $asset['symbol'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" disabled
                        class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                    Send
                </button>
            </form>
        </div>
    </div>
    @include('user.status.progress')
    
    <script>
        // Constants and Elements
        const fee = {{ $asset['fee'] ?? 0 }};
        const payidInput = document.getElementById('payid');
        const amountInput = document.getElementById('amount');
        const payidError = document.getElementById('payidError');
        const recipientName = document.getElementById('recipientName');
        const form = document.getElementById('sendForm');
        const submitButton = form.querySelector('button[type="submit"]');
        const maxAmount = {{ $asset['balance'] }};
        const price = {{ $asset['price'] }};
        const fiatRate = {{ $fiatRate }};
        const fiatCurrency = @js($fiatCurrency);
        const fiatLocale = @js(app()->getLocale());

        // Update preview in real-time
        function updatePreview() {
            const amount = parseFloat(amountInput.value) || 0;
            const recipientValid = !recipientName.classList.contains('hidden');
            const total = amount + fee;

            document.getElementById('previewRecipient').textContent = 
                recipientValid ? recipientName.textContent.replace('Recipient: ', '') : 'Not selected';
            
            document.getElementById('previewAmount').textContent = 
                `${amount.toFixed(8)} {{ $asset['symbol'] }}`;
            
            document.getElementById('previewFee').textContent = 
                `${fee.toFixed(8)} {{ $asset['symbol'] }}`;
            
            document.getElementById('previewTotal').textContent = 
                `${total.toFixed(8)} {{ $asset['symbol'] }}`;

            const usdValue = total * price * fiatRate;
            document.getElementById('usdValue').textContent = 
                '≈ ' + new Intl.NumberFormat(fiatLocale, {
                    style: 'currency',
                    currency: fiatCurrency,
                    maximumFractionDigits: 4
                }).format(usdValue);

            const validAmount = amount > 0 && total <= maxAmount;
            submitButton.disabled = !(recipientValid && validAmount);
        }

        // PayID Verification
        async function verifyPayId() {
            const payid = payidInput.value.trim();
            if (!payid) {
                payidError.textContent = 'Please enter a valid PayID';
                payidError.classList.remove('hidden');
                updatePreview();
                return;
            }

            try {
                const response = await fetch('{{ route("send.payid.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ payid })
                });

                const data = await response.json();
                if (data.success) {
                    payidError.classList.add('hidden');
                    recipientName.textContent = `Recipient: ${data.user.name}`;
                    recipientName.classList.remove('hidden');
                } else {
                    payidError.textContent = data.message;
                    payidError.classList.remove('hidden');
                    recipientName.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                payidError.textContent = 'Error verifying PayID';
                payidError.classList.remove('hidden');
                recipientName.classList.add('hidden');
            }
            updatePreview();
        }

        // Handle paste functionality
        document.getElementById('pasteButton').addEventListener('click', () => {
            navigator.clipboard.readText()
                .then(text => {
                    payidInput.value = text;
                    verifyPayId();
                })
                .catch(err => {
                    console.error('Failed to read clipboard: ', err);
                    // Optional: Show toast notification about permission
                });
        });

        // Validate amount with fee consideration
        function validateAmount() {
            const amount = parseFloat(amountInput.value);
            const total = amount + fee;
            const amountError = document.getElementById('amountError');
            
            if (isNaN(amount) || amount <= 0) {
                amountError.textContent = 'Amount must be greater than 0';
                amountError.classList.remove('hidden');
                return false;
            }
            if (total > maxAmount) {
                amountError.textContent = `Insufficient balance (including ${fee} {{ $asset['symbol'] }} fee)`;
                amountError.classList.remove('hidden');
                return false;
            }
            amountError.classList.add('hidden');
            return true;
        }

        // Set max amount considering fee
        function setMaxAmount() {
            const maxWithFee = Math.max(0, maxAmount - fee);
            amountInput.value = maxWithFee.toFixed(8);
            validateAmount();
            updatePreview();
        }

        // Set percentage amount considering fee
        function setPercentage(percentage) {
            const maxWithFee = Math.max(0, maxAmount - fee);
            const amount = (maxWithFee * (percentage/100)).toFixed(8);
            amountInput.value = amount;
            validateAmount();
            updatePreview();
        }

        // Form submission handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = parseFloat(amountInput.value);
            if (!payidInput.value.trim() || !amount || amount <= 0 || amount > maxAmount) {
                return;
            }

            // Show progress modal
            const progressModal = document.getElementById('progressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressStatus = document.getElementById('progressStatus');
            
            if (progressModal) progressModal.classList.remove('hidden');

            let progress = 0;
            const statusMessages = [
                { percent: 20, message: 'Verifying PayID...' },
                { percent: 40, message: 'Checking recipient...' },
                { percent: 60, message: 'Validating balance...' },
                { percent: 80, message: 'Preparing transfer...' },
                { percent: 90, message: 'Almost done...' }
            ];

            const interval = setInterval(() => {
                if (progress >= 90) {
                    clearInterval(interval);
                    
                    // ✅ CRITICAL DARK MODE FIX: Update button with dark mode variants
                    submitButton.classList.remove('bg-blue-500', 'hover:bg-blue-600', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                    submitButton.classList.add('bg-green-500', 'hover:bg-green-600', 'dark:bg-green-600', 'dark:hover:bg-green-700');
                    submitButton.textContent = 'Confirm';
                    
                    if (progressModal) progressModal.classList.add('hidden');
                    handleConfirmation();
                } else {
                    progress += 2;
                    if (progressBar) progressBar.style.width = `${progress}%`;
                    if (progressText) progressText.textContent = `${progress}%`;

                    for (const status of statusMessages) {
                        if (progress >= status.percent) {
                            if (progressStatus) progressStatus.textContent = status.message;
                            break;
                        }
                    }
                }
            }, 50);
        });

        // Confirmation handler
        function handleConfirmation() {
            const progressModal = document.getElementById('progressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressStatus = document.getElementById('progressStatus');

            if (progressModal) {
                progressModal.classList.remove('hidden');
                if (progressBar) progressBar.style.width = '90%';
                if (progressText) progressText.textContent = '90%';
                if (progressStatus) progressStatus.textContent = 'Finalizing transfer...';
            }

            setTimeout(() => {
                const hiddenForm = document.createElement('form');
                hiddenForm.method = 'POST';
                hiddenForm.action = '{{ route("send.payid.process", ["symbol" => $asset["symbol"], "network" => $asset["network"] ?? "native"]) }}';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('input[name="_token"]').value;
                hiddenForm.appendChild(csrfToken);

                // Add payid
                const payidField = document.createElement('input');
                payidField.type = 'hidden';
                payidField.name = 'payid';
                payidField.value = payidInput.value;
                hiddenForm.appendChild(payidField);

                // Add amount
                const amountField = document.createElement('input');
                amountField.type = 'hidden';
                amountField.name = 'amount';
                amountField.value = parseFloat(amountInput.value);
                hiddenForm.appendChild(amountField);

                // Add fee
                const feeField = document.createElement('input');
                feeField.type = 'hidden';
                feeField.name = 'fee';
                feeField.value = fee;
                hiddenForm.appendChild(feeField);

                document.body.appendChild(hiddenForm);
                hiddenForm.submit();
            }, 1000);
        }

        // Event listeners
        payidInput.addEventListener('input', verifyPayId);
        amountInput.addEventListener('input', () => {
            validateAmount();
            updatePreview();
        });
        document.getElementById('maxButton').addEventListener('click', setMaxAmount);
        document.querySelectorAll('.percentButton').forEach(button => {
            button.addEventListener('click', () => {
                const percentage = parseInt(button.dataset.percentage);
                setPercentage(percentage);
            });
        });

        // Initial preview update
        updatePreview();
    </script>
</x-user-layout>