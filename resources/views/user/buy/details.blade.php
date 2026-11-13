<x-user-layout>
    @php
        $fiatCurrency = auth()->user()->preferred_currency ?? 'USD';
        $fiatRate = app(\App\Services\CurrencyService::class)->rate($fiatCurrency);
        if ($fiatRate === null) {
            $fiatCurrency = 'USD';
            $fiatRate = 1;
        }
    @endphp
    <main class="w-full mx-auto h-auto bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Buy {{ $asset['name'] }}</h1>
            <div class="w-8"></div>
        </div>

        <!-- Main Content -->
        <div class="p-4 flex flex-col items-center justify-center min-h-[calc(100vh-70px)]">
            <!-- Amount Input -->
            <div class="text-center mb-8 bg-white dark:bg-gray-700 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-600 w-full max-w-md">
                <div class="flex items-center justify-center text-4xl mb-2">
                    <span class="mr-2 text-gray-800 dark:text-gray-100">{{ $fiatCurrency }}</span>
                    <input 
                        type="number" 
                        value="150" 
                        id="fiatAmount"
                        class="w-32 bg-transparent text-center focus:outline-none text-4xl text-gray-900 dark:text-gray-100"
                        min="0"
                    >
                </div>
                <p class="text-gray-500 dark:text-gray-400">
                    ≈ <span id="cryptoAmount">{{ number_format(150 / $asset['price'], 8) }}</span> {{ $asset['symbol'] }}
                </p>
            </div>

            <!-- Payment Options -->
            <div class="w-full max-w-md space-y-4">
                <button onclick="togglePaymentModal()" class="w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-xl p-4 shadow-sm transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <div class="text-left">
                                <p id="selectedProviderName" class="text-blue-500 dark:text-blue-400 font-medium">Choose Payment Method</p>
                                <p id="selectedProviderInstruction" class="text-sm text-gray-500 dark:text-gray-400">Select provider</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                    </div>
                </button>

                <!-- Dynamic "Buy Now" Button -->
                <button id="buyButton" onclick="redirectToProvider()" class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition-colors shadow-sm">
                    Buy {{ $asset['symbol'] }} Now
                </button>
            </div>
        </div>

        <!-- Payment Method Modal -->
        <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center hidden z-50" onclick="togglePaymentModal()">
            <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6 mx-4 shadow-lg border border-gray-200 dark:border-gray-700" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Payment Method</h2>
                    <button onclick="togglePaymentModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-4 mb-6">
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 dark:hover:border-blue-500 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="binance" checked class="form-radio text-blue-500 dark:text-blue-400 mr-3">
                        <span class="flex items-center">
                            <i class="fas fa-credit-card mr-2 text-blue-500 dark:text-blue-400"></i>
                            <span class="font-medium text-gray-900 dark:text-gray-100">Binance</span>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 dark:hover:border-blue-500 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="transak" class="form-radio text-blue-500 dark:text-blue-400 mr-3">
                        <span class="flex items-center">
                            <i class="fas fa-university mr-2 text-blue-500 dark:text-blue-400"></i>
                            <span class="font-medium text-gray-900 dark:text-gray-100">Transak</span>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 dark:hover:border-blue-500 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="moonpay" class="form-radio text-blue-500 dark:text-blue-400 mr-3">
                        <span class="flex items-center">
                            <i class="fas fa-mobile-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                            <span class="font-medium text-gray-900 dark:text-gray-100">MoonPay</span>
                        </span>
                    </label>
                </div>

                <!-- Confirm Payment Method Button -->
                <button onclick="updateSelectedProvider()" class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors">
                    Confirm Payment Method
                </button>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        function updateSelectedProvider() {
            // Get the selected radio button value
            const selectedRadio = document.querySelector('input[name="payment"]:checked');
            if (selectedRadio) {
                const selectedValue = selectedRadio.value;
                // Map the radio values to display names
                const providerMapping = {
                    binance: 'Binance',
                    transak: 'Transak',
                    moonpay: 'MoonPay'
                };
                // Update the text in the main button with the selected provider
                document.getElementById('selectedProviderName').textContent = providerMapping[selectedValue] || 'Choose Payment Method';
                // Optionally update the instruction line
                document.getElementById('selectedProviderInstruction').textContent = 'Selected';
            }
            // Close the modal after updating
            togglePaymentModal();
        }

        // Toggle the visibility of the payment modal
        function togglePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.toggle('hidden');
        }

        // Update crypto amount based on USD input value
        function updateCryptoAmount() {
            const fiatInput = document.getElementById('fiatAmount');
            const fiatAmount = parseFloat(fiatInput.value) || 0;
            const price = Number("{{ $asset['price'] }}");
            const cryptoAmount = (fiatAmount * {{ $fiatRate }} / price).toFixed(8);
            document.getElementById('cryptoAmount').textContent = cryptoAmount;
        }

        // Initialize the calculation and attach input listener
        document.addEventListener('DOMContentLoaded', function() {
            updateCryptoAmount();
            document.getElementById('fiatAmount').addEventListener('input', updateCryptoAmount);
        });

        // Redirect to the selected payment provider
        function redirectToProvider() {
            const selectedPayment = document.querySelector('input[name="payment"]:checked');
            if (!selectedPayment) {
                alert('Please select a payment provider');
                return;
            }
            const selectedProvider = selectedPayment.value;

            let providerUrl = '';

            switch (selectedProvider) {
                case 'binance':
                    providerUrl = 'https://www.binance.com/en/buy-crypto';
                    break;
                case 'transak':
                    providerUrl = 'https://transak.com/';
                    break;
                case 'moonpay':
                    providerUrl = "https://www.moonpay.com/buy/{{ strtolower($asset['symbol']) }}";
                    break;
                default:
                    alert('Invalid payment provider selected');
                    return;
            }

            window.location.href = providerUrl;
        }
    </script>
    @endpush
</x-user-layout>