
<div class="h-full w-full mx-auto pb-6 md:pb-10 bg-white dark:bg-gray-900 shadow-sm rounded-t-xl">

    <!-- Balance display -->
    <div class="text-center py-6 border-b border-gray-100 dark:border-gray-700">

        <h2 class="text-gray-500 dark:text-gray-400 text-sm mb-2 font-medium">
            {{ $accountId ?? 'My Wallet' }}
        </h2>

        <div class="md:text-5xl text-4xl font-semibold mb-1 text-gray-900 dark:text-white">
            {{ $balanceShow ? format_fiat((float) $balance) : '***' }}
        </div>

        <div class="text-gray-500 dark:text-gray-400 text-sm">
            {{ $balanceShow ? format_fiat((float) $balance) : '***' }} (0.0%)
        </div>

        <!-- Functional balance visibility button -->
        <button
            type="button"
            wire:click="toggleBalance"
            wire:loading.attr="disabled"
            aria-label="{{ $balanceShow ? 'Hide balance' : 'Show balance' }}"
            title="{{ $balanceShow ? 'Hide balance' : 'Show balance' }}"
            class="mt-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors focus:outline-none disabled:opacity-50"
        >
            <i class="fas {{ $balanceShow ? 'fa-eye' : 'fa-eye-slash' }} text-md"></i>
        </button>

    </div>


    <!-- Main action buttons -->
    <div class="grid grid-cols-4 gap-4 px-6 my-6">

        <div class="flex flex-col items-center">
            <button
                type="button"
                onclick="toggleModal('sendModal')"
                class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center shadow-sm dark:shadow-lg transition-colors"
            >
                <i class="fas fa-arrow-up text-lg text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Send</span>
        </div>

        <div class="flex flex-col items-center">
            <button
                type="button"
                onclick="toggleModal('receiveModal')"
                class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center shadow-sm dark:shadow-lg transition-colors"
            >
                <i class="fas fa-qrcode text-lg text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Receive</span>
        </div>

        <div class="flex flex-col items-center">
            <a
                href="{{ route('buy.index') }}"
                class="h-16 w-16 rounded-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 flex items-center justify-center shadow-sm dark:shadow-lg transition-colors"
            >
                <i class="fas fa-bolt text-lg text-white"></i>
            </a>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Buy</span>
        </div>

        <div class="flex flex-col items-center">
            <a
                href="{{ route('swap') }}"
                class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center shadow-sm dark:shadow-lg transition-colors"
            >
                <i class="fas fa-landmark text-lg text-gray-700 dark:text-gray-300"></i>
            </a>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Swap</span>
        </div>

    </div>


    <!-- Tabs and Token List -->
    <div class="px-4">

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
            <div class="flex">

                <button
                    type="button"
                    class="px-4 py-2 border-b-2 border-blue-600 dark:border-blue-500 font-medium text-blue-600 dark:text-blue-400"
                >
                    Crypto
                </button>

                <div class="ml-auto">
                    <button
                        type="button"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                    >
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>

            </div>
        </div>


        <!-- Token List -->
        <div class="space-y-4" wire:poll.30s>

            @foreach($cryptoAssets as $asset)

                <a
                    wire:key="crypto-{{ $asset['symbol'] }}-{{ $asset['network'] ?? 'native' }}"
                    href="{{ route('crypto.details', [
                        'symbol' => strtolower($asset['symbol']),
                        'network' => $asset['network'] ?? 'native'
                    ]) }}"
                    class="block hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg transition-colors"
                >

                    <div class="flex items-center justify-between py-2">

                        <div class="flex items-center space-x-3">

                            <div class="relative inline-block">

                                @if(!empty($asset['icon_url']))
                                    <img
                                        src="{{ $asset['icon_url'] }}"
                                        alt="{{ $asset['name'] }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($asset['symbol'], 0, 3)) }}
                                    </div>
                                @endif

                                @if(!empty($asset['network_url']))
                                    <img
                                        src="{{ $asset['network_url'] }}"
                                        alt="Network"
                                        class="absolute border-2 border-white dark:border-gray-800"
                                        style="width: 15px; height: auto; bottom: 0; right: 0; background: white; border-radius: 50%;"
                                        loading="lazy"
                                    >
                                @endif

                            </div>


                            <div>

                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $asset['symbol'] }}
                                    </span>

                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                        {{ $asset['name'] }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ format_fiat((float) $asset['price']) }}

                                    <span class="{{ floatval($asset['change']) < 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                                        {{ $asset['change'] }}%.
                                    </span>
                                </div>

                                @if(!empty($asset['address']))
                                    <div
                                        class="mt-1 max-w-xs break-all text-xs font-mono text-gray-500 dark:text-gray-400"
                                        title="{{ $asset['address'] }}"
                                    >
                                        Deposit address: {{ $asset['address'] }}
                                    </div>
                                @endif

                            </div>

                        </div>


                        <!-- Individual crypto balance visibility -->
                        <div class="text-right">

                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $balanceShow ? $asset['balance'] : '***' }}
                            </div>

                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $balanceShow ? format_fiat((float) $asset['value']) : '***' }}
                            </div>

                        </div>

                    </div>

                </a>

            @endforeach


            <!-- Manage Crypto Link -->
            <div class="text-center py-4">
                <a
                    href="{{ route('crypto.manage') }}"
                    class="text-blue-600 dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300 transition-colors"
                >
                    Manage crypto
                </a>
            </div>

        </div>

    </div>


    <!-- Wallet Connection Modal -->
    <div
        id="walletModal"
        class="fixed inset-0 bg-black bg-opacity-60 dark:bg-opacity-80 hidden z-50 flex items-center justify-center"
    >

        <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 rounded-xl shadow-lg">

            <div class="p-6">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Connect Wallet
                    </h3>

                    <button
                        type="button"
                        onclick="hideWalletModal()"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                    >
                        <i class="fas fa-times"></i>
                    </button>

                </div>


                <form id="walletForm" class="space-y-4">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Please use a secure wallet connection method. Never enter or share your recovery phrase with a website.
                    </p>

                    <div class="flex justify-end space-x-3 mt-6">

                        <button
                            type="button"
                            onclick="hideWalletModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                        >
                            Close
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>
        function showWalletModal() {
            document.getElementById('walletModal')?.classList.remove('hidden');
        }

        function hideWalletModal() {
            document.getElementById('walletModal')?.classList.add('hidden');
        }
    </script>

</div>
