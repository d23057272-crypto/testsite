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
            <a href="{{ route('send.external') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Send {{ $asset['name'] }}</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>

        <!-- Main Content -->
        <div class="p-4 space-y-6">
            <!-- Sending Method -->
            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl text-center border border-blue-100 dark:border-blue-900/30">
                <p class="text-sm">
                    <span class="text-gray-700 dark:text-gray-300">Sending via - </span>
                    <span class="text-blue-500 dark:text-blue-400 font-medium">Cryptocurrency</span>
                </p>
            </div>

            <!-- Error Message -->
            @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl relative" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            @endif
            
            @include('user.status.index')

            <!-- External Send Form -->
            <form id="sendForm" class="space-y-6" action="{{ route('send.external.process', ['symbol' => $asset['symbol'], 'network' => $asset['network'] ?? 'native']) }}" method="POST">
                @csrf
                <!-- Address Input -->
                <div class="space-y-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $asset['symbol'] }} Address {{ $asset['network'] ? '(' . $asset['network'] . ')' : '' }}
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Enter {{ $asset['symbol'] }} address"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl py-3.5 px-4 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-700 transition-colors"
                            required
                        >
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                            <button type="button" id="pasteButton" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 px-2 transition-colors" title="Paste from clipboard">
                                <i class="fas fa-paste"></i>
                            </button>
                            <button type="button" id="scanButton" class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Scan QR Code">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>
                    <p id="addressError" class="hidden text-red-500 dark:text-red-400 text-sm"></p>
                </div>

                <!-- Amount Input -->
                <div class="space-y-2">
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Amount
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="amount"
                            name="amount"
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
                            <button type="button" id="maxButton" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 px-2 transition-colors" title="Set to Max">
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

                <!-- Network Fee -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Network Fee</span>
                        <span id="networkFee" class="text-gray-900 dark:text-gray-100">{{ number_format($asset['fee'], 4) }} {{ $asset['symbol'] }}</span>
                    </div>
                </div>

                <!-- Balance Info -->
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span id="usdValue">≈ {{ format_fiat(0) }}</span>
                    <span>Available: {{ number_format($asset['balance'], 8) }} {{ $asset['symbol'] }}</span>
                </div>

                <!-- Preview Section -->
                <div id="previewSection" class="space-y-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Transaction Preview</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Address:</span>
                            <span id="previewAddress" class="text-gray-900 dark:text-gray-100">Not provided</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                            <span id="previewAmount" class="text-gray-900 dark:text-gray-100">0 {{ $asset['symbol'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Network Fee:</span>
                            <span id="previewFee" class="text-gray-900 dark:text-gray-100">{{ number_format($asset['fee'], 4) }} {{ $asset['symbol'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>Total:</span>
                            <span id="previewTotal">0 {{ $asset['symbol'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitButton" disabled
                        class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                    Send
                </button>
            </form>
        </div>
    </div>
    
    @include('user.status.progress')
    
    <!-- QR Code Scanner Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 w-full max-w-md mx-4 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Scan QR Code</h3>
                <button type="button" id="closeQrModal" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="qr-reader" class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"></div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center">Point your camera at a QR code</p>
        </div>
    </div>

    <!-- Include HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Elements
        const form = document.getElementById('sendForm');
        const addressInput = document.getElementById('address');
        const amountInput = document.getElementById('amount');
        const addressError = document.getElementById('addressError');
        const amountError = document.getElementById('amountError');
        const previewSection = document.getElementById('previewSection');
        const submitButton = document.getElementById('submitButton');
        const qrModal = document.getElementById('qrModal');
        const qrReader = document.getElementById('qr-reader');
        const closeQrModalBtn = document.getElementById('closeQrModal');

        // Constants
        const maxAmount = parseFloat("{{ $asset['balance'] }}");
        const price = parseFloat("{{ $asset['price'] }}");
        const fiatRate = {{ $fiatRate }};
        const fiatCurrency = @js($fiatCurrency);
        const fiatLocale = @js(app()->getLocale());
        const networkFeeAmount = parseFloat("{{ $asset['fee'] }}");

        // Initialize QR Code Scanner
        let html5QrcodeScanner = null;

        function initializeQRScanner() {
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
        }

        function startQRScanner() {
            qrModal.classList.remove('hidden');
            if (!html5QrcodeScanner) {
                initializeQRScanner();
            }

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText) => {
                    addressInput.value = decodedText;
                    validateAddress();
                    updatePreview();
                    closeQRScanner();
                },
                (errorMessage) => {
                    console.warn(`QR Code scan error: ${errorMessage}`);
                }
            ).catch(err => {
                console.error('Unable to start QR scanner:', err);
                // Optional: Show error toast notification
            });
        }

        function closeQRScanner() {
            qrModal.classList.add('hidden');
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    // Scanner stopped successfully
                }).catch(err => {
                    console.error('Failed to stop QR scanner:', err);
                });
            }
        }

        // Close modal on button click
        if (closeQrModalBtn) {
            closeQrModalBtn.addEventListener('click', closeQRScanner);
        }

        // Handle paste functionality
        document.getElementById('pasteButton').addEventListener('click', () => {
            navigator.clipboard.readText()
                .then(text => {
                    addressInput.value = text.trim();
                    validateAddress();
                    updatePreview();
                })
                .catch(err => {
                    console.error('Failed to read clipboard: ', err);
                    // Optional: Show toast notification
                });
        });

        // Handle QR scanner button
        document.getElementById('scanButton').addEventListener('click', startQRScanner);

        // Handle percentage buttons
        document.querySelectorAll('.percentButton').forEach(button => {
            button.addEventListener('click', () => {
                const percentage = parseInt(button.dataset.percentage);
                setPercentage(percentage);
            });
        });

        // Handle Max button
        document.getElementById('maxButton').addEventListener('click', setMaxAmount);

        function setPercentage(percentage) {
            const maxWithFee = maxAmount - networkFeeAmount;
            const amount = (maxWithFee * (percentage / 100)).toFixed(8);
            amountInput.value = amount > 0 ? amount : '0.00000000';
            validateAmount();
            updatePreview();
        }

        function setMaxAmount() {
            const maxWithFee = maxAmount - networkFeeAmount;
            const amount = maxWithFee.toFixed(8);
            amountInput.value = amount > 0 ? amount : '0.00000000';
            validateAmount();
            updatePreview();
        }

        function updatePreview() {
            const currentAmount = parseFloat(amountInput.value) || 0;
            const recipient = addressInput.value.trim() || 'Not provided';
            const shortenedRecipient = recipient.length > 18 ? recipient.substring(0, 18) + '.....' : recipient;
            const total = (currentAmount + networkFeeAmount).toFixed(8);

            if (!previewSection.classList.contains('hidden')) {
                document.getElementById('previewAddress').textContent = shortenedRecipient;
                document.getElementById('previewAmount').textContent = `${currentAmount.toFixed(8)} {{ $asset['symbol'] }}`;
                document.getElementById('previewFee').textContent = `${networkFeeAmount.toFixed(4)} {{ $asset['symbol'] }}`;
                document.getElementById('previewTotal').textContent = `${total} {{ $asset['symbol'] }}`;
            }

            const totalUsdValue = (currentAmount + networkFeeAmount) * price;
            document.getElementById('usdValue').textContent = '≈ ' + new Intl.NumberFormat(fiatLocale, {
                style: 'currency',
                currency: fiatCurrency,
                maximumFractionDigits: 4
            }).format(totalUsdValue * fiatRate);
            toggleSubmitButton();
        }

        function validateAmount() {
            const amount = parseFloat(amountInput.value);
            const total = amount + networkFeeAmount;

            if (isNaN(amount) || amount <= 0) {
                amountError.textContent = 'Amount must be greater than 0';
                amountError.classList.remove('hidden');
                toggleSubmitButton();
                return false;
            }

            if (total > maxAmount) {
                amountError.textContent = `Insufficient balance (including ${networkFeeAmount} {{ $asset['symbol'] }} fee)`;
                amountError.classList.remove('hidden');
                toggleSubmitButton();
                return false;
            }

            amountError.classList.add('hidden');
            toggleSubmitButton();
            return true;
        }

        function validateAddress() {
            const address = addressInput.value.trim();
            const symbol = "{{ strtoupper($asset['symbol']) }}";
            const network = "{{ strtoupper($asset['network'] ?? 'native') }}";

            if (!address) {
                addressError.textContent = 'Address is required.';
                addressError.classList.remove('hidden');
                toggleSubmitButton();
                return false;
            }

            fetch('{{ route("send.external.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ address, symbol, network }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addressError.classList.add('hidden');
                } else {
                    addressError.textContent = data.message;
                    addressError.classList.remove('hidden');
                }
                toggleSubmitButton();
            })
            .catch(error => {
                console.error('Error:', error);
                addressError.textContent = 'An error occurred during address verification.';
                addressError.classList.remove('hidden');
                toggleSubmitButton();
            });

            return true;
        }

        function toggleSubmitButton() {
            const isAddressValid = addressError.classList.contains('hidden');
            const amountValid = amountError.classList.contains('hidden') && (parseFloat(amountInput.value) + networkFeeAmount) <= maxAmount;
            submitButton.disabled = !(isAddressValid && amountValid);
        }

        // Form submission handling
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const isAddressValid = validateAddress();
            const isAmountValid = validateAmount();
            if (!isAddressValid || !isAmountValid) return;

            // Show progress modal logic
            const progressModal = document.getElementById('progressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressStatus = document.getElementById('progressStatus');
            if (!progressModal) {
                form.submit();
                return;
            }

            progressModal.classList.remove('hidden');
            let progress = 0;
            const statusMessages = [
                { percent: 20, message: 'Validating address...' },
                { percent: 40, message: 'Checking balance...' },
                { percent: 60, message: 'Calculating network fees...' },
                { percent: 80, message: 'Preparing transaction...' },
                { percent: 90, message: 'Almost done...' }
            ];

            const interval = setInterval(() => {
                if (progress >= 90) {
                    clearInterval(interval);
                    previewSection.classList.remove('hidden');
                    updatePreview();
                    
                    // ✅ CRITICAL DARK MODE FIX: Update button with dark mode variants
                    submitButton.textContent = 'Confirm';
                    submitButton.classList.remove('bg-blue-500', 'hover:bg-blue-600', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                    submitButton.classList.add('bg-green-500', 'hover:bg-green-600', 'dark:bg-green-600', 'dark:hover:bg-green-700');
                    
                    progressModal.classList.add('hidden');
                    submitButton.addEventListener('click', handleConfirm, { once: true });
                    submitButton.disabled = false;
                } else {
                    progress += 2;
                    progressBar.style.width = `${progress}%`;
                    progressText.textContent = `${progress}%`;
                    for (const status of statusMessages) {
                        if (progress <= status.percent) {
                            progressStatus.textContent = status.message;
                            break;
                        }
                    }
                }
            }, 50);
        });

        function handleConfirm(e) {
            const progressModal = document.getElementById('progressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressStatus = document.getElementById('progressStatus');

            if (!progressModal) {
                form.submit();
                return;
            }

            progressModal.classList.remove('hidden');
            progressBar.style.width = '90%';
            progressText.textContent = '90%';
            progressStatus.textContent = 'Finalizing transaction...';

            setTimeout(() => {
                form.submit();
            }, 1000);
        }

        // Input listeners
        amountInput.addEventListener('input', () => {
            validateAmount();
            updatePreview();
        });

        addressInput.addEventListener('input', () => {
            validateAddress();
            updatePreview();
        });

        updatePreview();
      });
    </script>
</x-user-layout>