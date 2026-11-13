
<x-user-layout :title="'Wallet Addresses'">

    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}"
               class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
               aria-label="Go back">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">
                Your Wallet Addresses
            </h1>

            <a href="{{ route('crypto.manage') }}"
               class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
               aria-label="Manage cryptocurrencies">
                <i class="fas fa-plus text-xl"></i>
            </a>
        </div>

        {{-- Search --}}
        <div class="relative mb-5">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <i class="fas fa-search"></i>
            </div>

            <input
                type="text"
                placeholder="Search coin name, symbol or network"
                id="search-input"
                autocomplete="off"
                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-500 transition-all"
            >
        </div>

        {{-- Network Filters --}}
        <div class="mb-6 flex flex-wrap gap-2" id="network-filters">

            <button
                type="button"
                class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors bg-blue-500 dark:bg-blue-600 text-white"
                data-network="all"
                aria-pressed="true">
                All Networks
            </button>

            @foreach($activeCryptos->pluck('network')->filter()->unique()->sort() as $network)
                <button
                    type="button"
                    class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-colors bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600"
                    data-network="{{ strtolower($network) }}"
                    aria-pressed="false">
                    {{ $network }}
                </button>
            @endforeach

        </div>

        {{-- Results Count --}}
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Deposit wallet addresses
            </p>

            <span id="visible-count" class="text-xs text-gray-400 dark:text-gray-500">
                {{ count($activeCryptos) }} {{ count($activeCryptos) === 1 ? 'address' : 'addresses' }}
            </span>
        </div>

        {{-- Crypto Address List --}}
        <div class="space-y-3" id="crypto-list">

            @forelse($activeCryptos as $crypto)

                @php
                    $symbol = strtoupper(trim((string) ($crypto['symbol'] ?? '')));
                    $name = (string) ($crypto['name'] ?? $symbol ?: 'Cryptocurrency');
                    $network = strtoupper(trim((string) ($crypto['network'] ?? 'native')));
                    $address = trim((string) ($crypto['address'] ?? ''));
                    $iconUrl = trim((string) ($crypto['icon_url'] ?? ''));

                    $searchText = strtolower(
                        $name . ' ' . $symbol . ' ' . $network
                    );

                    $isUsdt = $symbol === 'USDT'
                        || str_starts_with($symbol, 'USDT_')
                        || str_starts_with($symbol, 'USDT-')
                        || strtolower($name) === 'usdt';
                @endphp

                <div
                    class="crypto-item flex items-center justify-between gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all"
                    data-network="{{ strtolower($network) }}"
                    data-search="{{ $searchText }}"
                >

                    {{-- Asset Information --}}
                    <div class="flex items-center flex-1 min-w-0">

                        {{-- Cryptocurrency Icon --}}
                        <div class="w-11 h-11 flex-shrink-0 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3 overflow-hidden">

                            @if($iconUrl !== '')
                                <img
                                    src="{{ $iconUrl }}"
                                    alt="{{ $name }} icon"
                                    class="w-8 h-8 object-contain"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';"
                                >
                            @else
                                <span class="hidden"></span>
                            @endif

                            <span
                                class="{{ $iconUrl !== '' ? 'hidden' : 'flex' }} w-full h-full items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm"
                            >
                                {{ $isUsdt ? '₮' : strtoupper(substr($symbol ?: $name, 0, 1)) }}
                            </span>

                        </div>

                        {{-- Name, Symbol and Network --}}
                        <div class="flex-1 min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $name }}
                                </h3>

                                @if($symbol !== '')
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        {{ $symbol }}
                                    </span>
                                @endif

                            </div>

                            @if($network !== '' && $network !== 'NATIVE')
                                <span class="inline-flex mt-1 text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full text-gray-600 dark:text-gray-300">
                                    {{ $network }}
                                </span>
                            @else
                                <span class="inline-flex mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Native Network
                                </span>
                            @endif

                            {{-- Wallet Address --}}
                            @if($address !== '')
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400 font-mono mt-1 break-all"
                                    title="{{ $address }}"
                                >
                                    {{ strlen($address) > 18 ? substr($address, 0, 18) . '...' : $address }}
                                </p>
                            @else
                                <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">
                                    No address configured
                                </p>
                            @endif

                        </div>
                    </div>

                    {{-- Copy Address --}}
                    @if($address !== '')
                        <button
                            type="button"
                            class="copy-btn flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                            data-address="{{ $address }}"
                            aria-label="Copy {{ $name }} address"
                            title="Copy address"
                        >
                            <i class="far fa-copy"></i>
                        </button>
                    @else
                        <span class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                    @endif

                </div>

            @empty

                {{-- Empty State --}}
                <div class="text-center py-10 px-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">

                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-wallet text-gray-400 dark:text-gray-500 text-2xl"></i>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 font-semibold">
                        No active cryptocurrency addresses found
                    </p>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6">
                        Deposit addresses are configured by your administrator.
                    </p>

                    <a
                        href="{{ route('crypto.manage') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-xl font-medium transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Add Cryptocurrencies
                    </a>

                </div>

            @endforelse

            {{-- No Search Results --}}
            <div id="no-results"
                 class="hidden text-center py-10 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">

                <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-gray-400 text-xl"></i>
                </div>

                <p class="font-semibold text-gray-700 dark:text-gray-300">
                    No matching addresses found
                </p>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Try another search term or network.
                </p>

            </div>

        </div>

    </main>

    {{-- Copy Notification Toast --}}
    <div
        id="copy-success"
        role="status"
        aria-live="polite"
        class="fixed top-4 right-4 max-w-[calc(100%-2rem)] bg-blue-600 dark:bg-blue-500 text-white px-4 py-3 rounded-xl shadow-lg z-50 flex items-center opacity-0 pointer-events-none translate-x-10 transition-all duration-300"
    >
        <i class="fas fa-check-circle mr-2"></i>
        <span id="copy-message">Address copied to clipboard!</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const searchInput = document.getElementById('search-input');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const cryptoItems = document.querySelectorAll('.crypto-item');
            const noResults = document.getElementById('no-results');
            const visibleCount = document.getElementById('visible-count');

            const toast = document.getElementById('copy-success');
            const toastMessage = document.getElementById('copy-message');

            let activeNetwork = 'all';
            let toastTimer = null;

            /*
            |--------------------------------------------------------------------------
            | Toast Notification
            |--------------------------------------------------------------------------
            */

            function showToast(message, success = true) {

                if (!toast) return;

                toastMessage.textContent = message;

                toast.classList.remove(
                    'bg-blue-600',
                    'dark:bg-blue-500',
                    'bg-red-600',
                    'dark:bg-red-500',
                    'opacity-0',
                    'pointer-events-none',
                    'translate-x-10'
                );

                if (success) {
                    toast.classList.add('bg-blue-600', 'dark:bg-blue-500');
                } else {
                    toast.classList.add('bg-red-600', 'dark:bg-red-500');
                }

                if (toastTimer) {
                    clearTimeout(toastTimer);
                }

                toastTimer = setTimeout(function () {
                    toast.classList.add(
                        'opacity-0',
                        'pointer-events-none',
                        'translate-x-10'
                    );
                }, 3000);
            }

            /*
            |--------------------------------------------------------------------------
            | Search and Network Filtering
            |--------------------------------------------------------------------------
            */

            function filterCryptos() {

                const searchTerm = searchInput
                    ? searchInput.value.trim().toLowerCase()
                    : '';

                let visible = 0;

                cryptoItems.forEach(function (item) {

                    const searchText = (
                        item.getAttribute('data-search') || ''
                    ).toLowerCase();

                    const network = (
                        item.getAttribute('data-network') || ''
                    ).toLowerCase();

                    const matchesSearch = searchText.includes(searchTerm);

                    const matchesNetwork =
                        activeNetwork === 'all' ||
                        network === activeNetwork ||
                        (
                            activeNetwork === 'bnb' &&
                            (network === 'bep20' || network === 'bnb smart chain')
                        );

                    const shouldShow = matchesSearch && matchesNetwork;

                    item.style.display = shouldShow ? 'flex' : 'none';

                    if (shouldShow) {
                        visible++;
                    }

                });

                if (noResults) {
                    noResults.classList.toggle(
                        'hidden',
                        visible !== 0
                    );
                }

                if (visibleCount) {
                    visibleCount.textContent =
                        visible + (visible === 1 ? ' address' : ' addresses');
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterCryptos);
            }

            /*
            |--------------------------------------------------------------------------
            | Network Filter Buttons
            |--------------------------------------------------------------------------
            */

            filterButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    filterButtons.forEach(function (btn) {

                        btn.classList.remove(
                            'bg-blue-500',
                            'dark:bg-blue-600',
                            'text-white'
                        );

                        btn.classList.add(
                            'bg-gray-100',
                            'dark:bg-gray-700',
                            'text-gray-700',
                            'dark:text-gray-300'
                        );

                        btn.setAttribute('aria-pressed', 'false');
                    });

                    this.classList.remove(
                        'bg-gray-100',
                        'dark:bg-gray-700',
                        'text-gray-700',
                        'dark:text-gray-300'
                    );

                    this.classList.add(
                        'bg-blue-500',
                        'dark:bg-blue-600',
                        'text-white'
                    );

                    this.setAttribute('aria-pressed', 'true');

                    activeNetwork = this.getAttribute('data-network') || 'all';

                    filterCryptos();
                });

            });

            /*
            |--------------------------------------------------------------------------
            | Clipboard Fallback
            |--------------------------------------------------------------------------
            */

            async function copyAddress(address) {

                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {
                    await navigator.clipboard.writeText(address);
                    return true;
                }

                const textArea = document.createElement('textarea');

                textArea.value = address;
                textArea.style.position = 'fixed';
                textArea.style.left = '-9999px';
                textArea.style.top = '0';

                document.body.appendChild(textArea);

                textArea.focus();
                textArea.select();

                let copied = false;

                try {
                    copied = document.execCommand('copy');
                } catch (error) {
                    copied = false;
                }

                document.body.removeChild(textArea);

                if (!copied) {
                    throw new Error('Clipboard access unavailable');
                }

                return true;
            }

            /*
            |--------------------------------------------------------------------------
            | Copy Address Buttons
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.copy-btn').forEach(function (button) {

                button.addEventListener('click', async function () {

                    const address = this.getAttribute('data-address');

                    if (!address) {
                        showToast('No wallet address available.', false);
                        return;
                    }

                    const originalContent = this.innerHTML;

                    try {

                        await copyAddress(address);

                        this.innerHTML = '<i class="fas fa-check"></i>';

                        this.classList.remove(
                            'bg-blue-50',
                            'dark:bg-blue-900/30',
                            'text-blue-500',
                            'dark:text-blue-400'
                        );

                        this.classList.add(
                            'bg-green-100',
                            'dark:bg-green-900/30',
                            'text-green-600',
                            'dark:text-green-400'
                        );

                        showToast('Address copied to clipboard!');

                        const currentButton = this;

                        setTimeout(function () {

                            currentButton.innerHTML = originalContent;

                            currentButton.classList.remove(
                                'bg-green-100',
                                'dark:bg-green-900/30',
                                'text-green-600',
                                'dark:text-green-400'
                            );

                            currentButton.classList.add(
                                'bg-blue-50',
                                'dark:bg-blue-900/30',
                                'text-blue-500',
                                'dark:text-blue-400'
                            );

                        }, 2000);

                    } catch (error) {

                        console.error('Clipboard error:', error);

                        showToast(
                            'Unable to copy address. Please copy it manually.',
                            false
                        );
                    }

                });

            });

            /*
            |--------------------------------------------------------------------------
            | Initial Rendering
            |--------------------------------------------------------------------------
            */

            filterCryptos();

        });
    </script>

</x-user-layout>
