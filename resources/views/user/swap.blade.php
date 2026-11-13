<x-user-layout :title="'Swap'">
    @php
        $fiatCurrency = auth()->user()->preferred_currency ?? 'USD';
        $fiatRate = app(\App\Services\CurrencyService::class)->rate($fiatCurrency);
        if ($fiatRate === null) {
            $fiatCurrency = 'USD';
            $fiatRate = 1;
        }
    @endphp
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}"
               class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">
                Swap
            </h1>

            <div class="w-8"></div>
        </div>

        @if(empty($assets))
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-800 p-4 mb-5">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                            No swap assets available
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                            There are currently no active cryptocurrencies available for swapping.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form id="swapForm" class="space-y-6">
            @csrf

            <!-- FROM -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">

                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">
                        From
                    </span>

                    <div id="fromBalance" class="text-sm text-gray-500 dark:text-gray-400"></div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <button type="button"
                            class="flex items-center text-left"
                            onclick="openCryptoSelector('from')">

                        <div id="fromCryptoIcon"
                             class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-blue-50 dark:bg-blue-900/30 overflow-hidden">
                            <i class="fas fa-coins text-blue-500 dark:text-blue-400"></i>
                        </div>

                        <div class="flex items-center">
                            <span id="fromCryptoLabel"
                                  class="font-medium text-gray-900 dark:text-white mr-2">
                                Select
                            </span>

                            <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                        </div>
                    </button>

                    <input type="number"
                           id="fromAmount"
                           name="amount"
                           placeholder="0.00"
                           min="0"
                           step="0.00000001"
                           inputmode="decimal"
                           class="bg-transparent text-right text-2xl font-medium w-32 focus:outline-none text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                           oninput="calculateSwap()">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                onclick="setAmount(25)"
                                class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50">
                            25%
                        </button>

                        <button type="button"
                                onclick="setAmount(50)"
                                class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50">
                            50%
                        </button>

                        <button type="button"
                                onclick="setAmount(75)"
                                class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50">
                            75%
                        </button>

                        <button type="button"
                                onclick="setAmount(100)"
                                class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50">
                            100%
                        </button>
                    </div>

                    <span id="fromUsdValue" class="text-gray-500 dark:text-gray-400 text-sm">
                        ≈ {{ format_fiat(0) }}
                    </span>
                </div>
            </div>

            <!-- SWAP DIRECTION -->
            <div class="flex justify-center -my-4 relative z-10">
                <button type="button"
                        onclick="swapDirections()"
                        class="w-12 h-12 rounded-full bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 flex items-center justify-center transform transition-transform hover:scale-110">
                    <i class="fas fa-arrow-down text-blue-500 dark:text-blue-400"></i>
                </button>
            </div>

            <!-- TO -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">

                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">
                        To
                    </span>

                    <div id="toBalance" class="text-sm text-gray-500 dark:text-gray-400"></div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <button type="button"
                            class="flex items-center text-left"
                            onclick="openCryptoSelector('to')">

                        <div id="toCryptoIcon"
                             class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-blue-50 dark:bg-blue-900/30 overflow-hidden">
                            <i class="fas fa-coins text-blue-500 dark:text-blue-400"></i>
                        </div>

                        <div class="flex items-center">
                            <span id="toCryptoLabel"
                                  class="font-medium text-gray-900 dark:text-white mr-2">
                                Select
                            </span>

                            <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                        </div>
                    </button>

                    <input type="number"
                           id="toAmount"
                           readonly
                           placeholder="0.00"
                           class="bg-transparent text-right text-2xl font-medium w-32 focus:outline-none text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <div class="flex justify-between">
                    <span id="rate" class="text-sm text-gray-500 dark:text-gray-400"></span>

                    <span id="toUsdValue" class="text-gray-500 dark:text-gray-400 text-sm">
                        ≈ {{ format_fiat(0) }}
                    </span>
                </div>
            </div>

            <!-- SWAP FEE INFORMATION -->
            <div id="swapFeeSection"
                 class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-2 hidden">

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-300">
                        Required Swap Fee
                    </span>

                    <span id="swapFeeDisplay"
                          class="font-medium text-gray-900 dark:text-white text-right">
                    </span>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    The fee shown is based on the selected receiving asset.
                </p>
            </div>

            <!-- SWAP BUTTON -->
            <button type="submit"
                    id="swapButton"
                    disabled
                    class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-4 rounded-xl transition-colors mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                Select currencies
            </button>
        </form>

        <!-- PROGRESS MODAL -->
        @include('user.status.progress')

        <!-- EXISTING SWAP FEE MODAL -->
        @include('user.status1.index')

        <!-- CRYPTO SELECTOR MODAL -->
        <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-80 hidden z-50"
             id="cryptoSelectorModal">

            <div class="bg-white dark:bg-gray-800 rounded-t-xl fixed bottom-0 left-0 right-0 max-h-[80vh] overflow-y-auto animate-slide-up">

                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Select Asset
                        </h3>

                        <button type="button"
                                onclick="closeCryptoSelector()"
                                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4" id="cryptoList"></div>
            </div>
        </div>

        <!-- RESULT MODAL -->
        <div id="resultModal"
             class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">

            <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6 mx-4 shadow-lg">

                <div id="successState" class="hidden text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-500 text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Swap Successful
                    </h3>

                    <p id="successMessage" class="text-sm text-gray-500 dark:text-gray-400"></p>
                </div>

                <div id="errorState" class="text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Swap Not Completed
                    </h3>

                    <p id="errorMessage" class="text-sm text-gray-500 dark:text-gray-400">
                        An error occurred.
                    </p>
                </div>

                <button type="button"
                        onclick="closeResultModal()"
                        class="w-full mt-6 px-4 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </main>

    <style>
        .animate-slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }
    </style>

    <script>
        /*
         * All selectable assets come from the cryptocurrencies table.
         * No fixed cryptocurrency list or balance-column mapping is used.
         */
        const assets = @json($assets ?? []);
        const swapFees = @json($swapFees ?? []);
        const swapFeeRequired = @json($swapFeeRequired ?? false);
        const fiatCurrency = @js($fiatCurrency);
        const fiatLocale = @js(app()->getLocale());
        const fiatRate = {{ $fiatRate }};

        const assetBySymbol = {};

        assets.forEach(asset => {
            assetBySymbol[asset.symbol] = asset;
        });

        let currentSelector = '';

        let cryptoData = {
            from: null,
            to: null
        };

        function formatNumber(value, decimals = 8) {
            const number = Number(value || 0);

            if (!Number.isFinite(number)) {
                return '0';
            }

            return number.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: decimals
            });
        }

        function formatFiat(value) {
            return Number(value || 0).toLocaleString(fiatLocale, {
                style: 'currency',
                currency: fiatCurrency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function getAssetDisplayName(asset) {
            if (!asset) {
                return 'Select';
            }

            const name = asset.name || asset.symbol.toUpperCase();
            const network = asset.network;

            if (network) {
                return `${name} (${network.toUpperCase()})`;
            }

            return `${name} (${asset.symbol.toUpperCase()})`;
        }

        function getAssetShortName(asset) {
            if (!asset) {
                return '';
            }

            if (asset.network) {
                return `${asset.name} ${asset.network.toUpperCase()}`;
            }

            return asset.name || asset.symbol.toUpperCase();
        }

        function createAssetIcon(asset, size = 'w-10 h-10') {
            const container = document.createElement('div');

            container.className =
                `${size} rounded-full flex items-center justify-center mr-3 ` +
                'bg-blue-50 dark:bg-blue-900/30 overflow-hidden flex-shrink-0';

            if (asset.icon_url) {
                const image = document.createElement('img');

                image.src = asset.icon_url;
                image.alt = getAssetDisplayName(asset);
                image.className = 'w-full h-full object-contain';
                image.onerror = function () {
                    this.remove();
                    container.textContent = asset.symbol.substring(0, 3).toUpperCase();
                    container.classList.add('text-blue-600', 'font-bold');
                };

                container.appendChild(image);
            } else {
                container.textContent = asset.symbol.substring(0, 3).toUpperCase();
                container.classList.add('text-blue-600', 'dark:text-blue-300', 'font-bold');
            }

            return container;
        }

        function updateSelectionDisplay(type) {
            const asset = cryptoData[type];

            const iconContainer = document.getElementById(`${type}CryptoIcon`);
            const label = document.getElementById(`${type}CryptoLabel`);
            const balanceElement = document.getElementById(`${type}Balance`);

            iconContainer.innerHTML = '';

            if (!asset) {
                iconContainer.innerHTML =
                    '<i class="fas fa-coins text-blue-500 dark:text-blue-400"></i>';

                label.textContent = 'Select';
                balanceElement.textContent = '';

                return;
            }

            const icon = createAssetIcon(asset, 'w-10 h-10');

            icon.classList.remove('mr-3');
            iconContainer.appendChild(icon);

            label.textContent = getAssetDisplayName(asset);

            balanceElement.textContent =
                `Available: ${formatNumber(asset.balance)} ${asset.symbol.toUpperCase()}`;
        }

        function updateUI() {
            const swapButton = document.getElementById('swapButton');

            const canSwap =
                cryptoData.from &&
                cryptoData.to &&
                cryptoData.from.symbol !== cryptoData.to.symbol;

            if (canSwap) {
                swapButton.disabled = false;
                swapButton.textContent = 'Swap';
            } else {
                swapButton.disabled = true;
                swapButton.textContent = 'Select currencies';
            }

            calculateSwap();
        }

        function calculateSwap() {
            const fromAmount =
                parseFloat(document.getElementById('fromAmount').value) || 0;

            const feeSection = document.getElementById('swapFeeSection');
            const feeDisplay = document.getElementById('swapFeeDisplay');

            feeSection.classList.add('hidden');
            feeDisplay.textContent = '';

            if (
                !cryptoData.from ||
                !cryptoData.to ||
                fromAmount <= 0 ||
                !cryptoData.from.price ||
                !cryptoData.to.price
            ) {
                document.getElementById('toAmount').value = '';
                document.getElementById('fromUsdValue').textContent = `≈ ${formatFiat(0)}`;
                document.getElementById('toUsdValue').textContent = `≈ ${formatFiat(0)}`;
                document.getElementById('rate').textContent = '';
                return;
            }

            const usdValue = fromAmount * cryptoData.from.price;
            const toAmount = usdValue / cryptoData.to.price;

            document.getElementById('toAmount').value =
                Number(toAmount).toFixed(8);

            document.getElementById('fromUsdValue').textContent =
                `≈ ${formatFiat(usdValue * fiatRate)}`;

            document.getElementById('toUsdValue').textContent =
                `≈ ${formatFiat(toAmount * cryptoData.to.price * fiatRate)}`;

            const rate = cryptoData.from.price / cryptoData.to.price;

            document.getElementById('rate').textContent =
                `1 ${cryptoData.from.symbol.toUpperCase()} = ${formatNumber(rate)} ${cryptoData.to.symbol.toUpperCase()}`;

            /*
             * The fee modal is shown only when the account's existing
             * swap_fee flag says a fee is required.
             */
            const feeInfo = swapFees[cryptoData.to.symbol];

            if (swapFeeRequired && feeInfo && Number(feeInfo.fee) > 0) {
                feeDisplay.textContent =
                    `${formatNumber(feeInfo.fee)} ${cryptoData.to.symbol.toUpperCase()} ≈ ${formatFiat(feeInfo.usd_equivalent * fiatRate)}`;

                feeSection.classList.remove('hidden');
            }
        }

        function openCryptoSelector(type) {
            currentSelector = type;

            const modal = document.getElementById('cryptoSelectorModal');
            const cryptoList = document.getElementById('cryptoList');

            cryptoList.innerHTML = '';

            const oppositeType = type === 'from' ? 'to' : 'from';
            const oppositeAsset = cryptoData[oppositeType];

            if (!assets.length) {
                const emptyMessage = document.createElement('p');

                emptyMessage.className =
                    'text-center text-sm text-gray-500 dark:text-gray-400 py-8';

                emptyMessage.textContent =
                    'No active cryptocurrencies are currently available.';

                cryptoList.appendChild(emptyMessage);
            }

            assets.forEach(asset => {
                if (
                    oppositeAsset &&
                    oppositeAsset.symbol === asset.symbol
                ) {
                    return;
                }

                const item = document.createElement('button');

                item.type = 'button';
                item.className =
                    'w-full flex items-center justify-between p-4 border-b ' +
                    'border-gray-100 dark:border-gray-700 hover:bg-gray-50 ' +
                    'dark:hover:bg-gray-700/50 text-left transition-colors';

                const left = document.createElement('div');
                left.className = 'flex items-center min-w-0';

                left.appendChild(createAssetIcon(asset));

                const details = document.createElement('div');
                details.className = 'min-w-0';

                const name = document.createElement('div');
                name.className = 'font-medium text-gray-900 dark:text-white';
                name.textContent = getAssetDisplayName(asset);

                const balance = document.createElement('div');
                balance.className = 'text-sm text-gray-500 dark:text-gray-400';

                balance.textContent =
                    `Balance: ${formatNumber(asset.balance)} ${asset.symbol.toUpperCase()}`;

                details.appendChild(name);
                details.appendChild(balance);

                left.appendChild(details);

                const arrow = document.createElement('i');
                arrow.className =
                    'fas fa-chevron-right text-gray-400 dark:text-gray-500 ml-3';

                item.appendChild(left);
                item.appendChild(arrow);

                item.addEventListener('click', function () {
                    selectCrypto(asset.symbol);
                });

                cryptoList.appendChild(item);
            });

            modal.classList.remove('hidden');
        }

        function closeCryptoSelector() {
            document.getElementById('cryptoSelectorModal').classList.add('hidden');
            currentSelector = '';
        }

        function selectCrypto(symbol) {
            const asset = assetBySymbol[symbol];

            if (!asset || !currentSelector) {
                return;
            }

            cryptoData[currentSelector] = asset;

            updateSelectionDisplay(currentSelector);

            closeCryptoSelector();
            updateUI();
        }

        function swapDirections() {
            const previousFrom = cryptoData.from;

            cryptoData.from = cryptoData.to;
            cryptoData.to = previousFrom;

            updateSelectionDisplay('from');
            updateSelectionDisplay('to');

            updateUI();
        }

        function setAmount(percentage) {
            if (!cryptoData.from) {
                return;
            }

            const maxAmount = Number(cryptoData.from.balance || 0);
            const amount = (maxAmount * percentage) / 100;

            document.getElementById('fromAmount').value =
                amount > 0 ? amount.toFixed(8) : '';

            calculateSwap();
        }

        function closeResultModal() {
            document.getElementById('resultModal').classList.add('hidden');
        }

        function showError(message) {
            const modal = document.getElementById('resultModal');

            document.getElementById('successState').classList.add('hidden');
            document.getElementById('errorState').classList.remove('hidden');

            document.getElementById('errorMessage').textContent =
                message || 'An unexpected error occurred.';

            modal.classList.remove('hidden');
        }

        function showSuccess(message) {
            const modal = document.getElementById('resultModal');

            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('successState').classList.remove('hidden');

            document.getElementById('successMessage').textContent =
                message || 'Your swap was completed successfully.';

            modal.classList.remove('hidden');
        }

        function updateFeeModal() {
            if (!cryptoData.to) {
                return;
            }

            const asset = cryptoData.to;
            const feeInfo = swapFees[asset.symbol] || {};

            const modal = document.getElementById('gasFeeModal');

            if (!modal) {
                return;
            }

            const heading = modal.querySelector('h2');

            if (heading) {
                heading.textContent =
                    `${getAssetDisplayName(asset)} Swap Fee Required`;
            }

            const description = modal.querySelector('h2 + p');

            if (description) {
                description.textContent =
                    `To process your ${getAssetDisplayName(asset)} swap, please transfer the required swap fee to the displayed address.`;
            }

            const feeDisplay =
                modal.querySelector('.bg-blue-50 p.text-blue-600');

            if (feeDisplay) {
                const feeAmount = Number(feeInfo.fee || 0);
                const usdAmount = Number(feeInfo.usd_equivalent || 0);

                feeDisplay.textContent =
                    `${formatNumber(feeAmount)} ${asset.symbol.toUpperCase()} (${formatFiat(usdAmount * fiatRate)})`;
            }

            const addressElement = document.getElementById('walletAddress');

            if (addressElement) {
                addressElement.textContent =
                    asset.address || 'Address not available';

                addressElement.title = asset.address || '';
            }
        }

        function resetProgress() {
            const bar = document.getElementById('progressBar');
            const text = document.getElementById('progressText');
            const status = document.getElementById('progressStatus');

            if (bar) {
                bar.style.width = '0%';
            }

            if (text) {
                text.textContent = '0%';
            }

            if (status) {
                status.textContent = 'Initializing transaction...';
            }
        }

        function setProgress(progress, message) {
            const bar = document.getElementById('progressBar');
            const text = document.getElementById('progressText');
            const status = document.getElementById('progressStatus');

            if (bar) {
                bar.style.width = `${progress}%`;
            }

            if (text) {
                text.textContent = `${progress}%`;
            }

            if (status && message) {
                status.textContent = message;
            }
        }

        async function proceedWithSwap() {
            const swapButton = document.getElementById('swapButton');

            if (!cryptoData.from || !cryptoData.to) {
                showError('Please select both cryptocurrencies.');
                return;
            }

            const amount =
                parseFloat(document.getElementById('fromAmount').value);

            swapButton.disabled = true;
            swapButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

            try {
                const response = await fetch(
                    '{{ route("swap.process") }}',
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN':
                                document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            from_crypto: cryptoData.from.symbol,
                            to_crypto: cryptoData.to.symbol,
                            amount: amount
                        })
                    }
                );

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(
                        result.message || 'The swap could not be completed.'
                    );
                }

                showSuccess(result.message || 'Swap completed successfully.');

                setTimeout(function () {
                    window.location.reload();
                }, 1200);

            } catch (error) {
                console.error('Swap error:', error);
                showError(error.message || 'An error occurred while processing your swap.');

            } finally {
                swapButton.disabled = false;
                swapButton.textContent = 'Swap';
            }
        }

        function runProgress(target, callback) {
            const progressModal = document.getElementById('progressModal');

            if (!progressModal) {
                showError('The progress modal could not be loaded.');
                return;
            }

            resetProgress();
            progressModal.classList.remove('hidden');

            let progress = 0;

            const interval = setInterval(function () {
                progress += 2;

                if (progress >= target) {
                    progress = target;
                }

                setProgress(
                    progress,
                    progress < target
                        ? 'Preparing your swap...'
                        : 'Please wait...'
                );

                if (progress >= target) {
                    clearInterval(interval);

                    setTimeout(function () {
                        progressModal.classList.add('hidden');
                        callback();
                    }, 250);
                }
            }, 40);
        }

        // Close modals when the user clicks their backdrop.
        window.addEventListener('click', function (event) {
            const modalIds = [
                'cryptoSelectorModal',
                'gasFeeModal',
                'progressModal',
                'resultModal'
            ];

            modalIds.forEach(function (id) {
                const modal = document.getElementById(id);

                if (modal && event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Form submission.
        document.getElementById('swapForm').addEventListener('submit', function (event) {
            event.preventDefault();

            if (!cryptoData.from || !cryptoData.to) {
                showError('Please select both cryptocurrencies.');
                return;
            }

            if (cryptoData.from.symbol === cryptoData.to.symbol) {
                showError('Please select two different cryptocurrencies.');
                return;
            }

            const amount =
                parseFloat(document.getElementById('fromAmount').value);

            if (!Number.isFinite(amount) || amount <= 0) {
                showError('Please enter a valid amount.');
                return;
            }

            if (amount > Number(cryptoData.from.balance || 0)) {
                showError('Insufficient balance.');
                return;
            }

            if (!cryptoData.from.price || !cryptoData.to.price) {
                showError('A current price is unavailable for one or both selected assets.');
                return;
            }

            /*
             * A required fee must be paid and verified separately.
             * The modal does not itself complete payment or unlock swaps.
             */
            if (swapFeeRequired) {
                runProgress(30, function () {
                    updateFeeModal();

                    const gasFeeModal = document.getElementById('gasFeeModal');

                    if (gasFeeModal) {
                        gasFeeModal.classList.remove('hidden');
                    } else {
                        showError('The swap fee information could not be loaded.');
                    }
                });

                return;
            }

            runProgress(100, function () {
                proceedWithSwap();
            });
        });

        // Initialize.
        document.addEventListener('DOMContentLoaded', function () {
            updateSelectionDisplay('from');
            updateSelectionDisplay('to');
            updateUI();
        });
    </script>
</x-user-layout>