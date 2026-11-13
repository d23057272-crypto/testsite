
<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Wallet Management
                </div>

                <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-800">
                    {{ __('Edit Cryptocurrency') }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Manage pricing, wallet addresses, transaction limits, network details and availability.
                </p>
            </div>

            <a href="{{ route('admin.settings.wallets') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Wallet Management
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
            @if(session('status') || session('success'))
                <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold">Update successful</p>
                        <p class="mt-0.5 text-sm">{{ session('status') ?? session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-red-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold">Unable to complete the update</p>
                        <p class="mt-0.5 text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <p class="text-sm font-bold">Please correct the following errors:</p>
                    </div>

                    <ul class="mt-3 list-disc space-y-1 pl-7 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- CRYPTOCURRENCY IDENTITY CARD --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-400"></div>

                <div class="relative flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
                    <div class="flex min-w-0 items-center gap-4">
                        @if($crypto->icon_url)
                            <img
                                src="{{ $crypto->icon_url }}"
                                alt="{{ $crypto->name }}"
                                id="cryptoIconPreview"
                                class="h-16 w-16 flex-shrink-0 rounded-2xl border border-slate-200 bg-white object-cover p-1 shadow-sm"
                                onerror="this.style.display='none'"
                            >
                        @else
                            <div id="cryptoIconFallback" class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-100 text-lg font-extrabold text-blue-700">
                                {{ strtoupper(substr($crypto->symbol, 0, 3)) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                Cryptocurrency Configuration
                            </p>

                            <h3 class="mt-1 truncate text-xl font-extrabold tracking-tight text-slate-900">
                                {{ $crypto->name }}
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="rounded-lg border border-blue-100 bg-blue-50 px-2.5 py-1 font-mono text-xs font-bold text-blue-700">
                                    {{ strtoupper($crypto->symbol) }}
                                </span>

                                @if($crypto->network)
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        {{ $crypto->network }}
                                    </span>
                                @endif

                                @if($crypto->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 sm:min-w-[150px]">
                        <p class="text-xs font-medium text-slate-500">Asset Symbol</p>
                        <p class="mt-1 font-mono text-lg font-extrabold tracking-wide text-slate-800">
                            {{ strtoupper($crypto->symbol) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- EDIT FORM --}}
            <form method="POST"
                  action="{{ route('admin.cryptos.update', $crypto) }}"
                  id="cryptoEditForm"
                  class="space-y-6">
                @csrf
                @method('PUT')

                {{-- GENERAL INFORMATION --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-5 sm:px-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900">General Information</h3>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Manage the cryptocurrency identity, API information and system wallet address.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">

                        {{-- NAME --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Cryptocurrency Name <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $crypto->name) }}"
                                maxlength="100"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="e.g. Bitcoin"
                            >

                            @error('name')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SYMBOL AND FETCH DETAILS --}}
                        <div>
                            <label for="symbol" class="mb-2 block text-sm font-semibold text-slate-700">
                                Cryptocurrency Symbol
                            </label>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <input
                                    type="text"
                                    id="symbol"
                                    value="{{ strtoupper($crypto->symbol) }}"
                                    disabled
                                    class="block w-full min-w-0 flex-1 cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-500 shadow-sm"
                                >

                                <button
                                    type="button"
                                    id="fetchCryptoDetails"
                                    data-fetch-url="{{ route('admin.cryptos.fetch-details') }}"
                                    class="inline-flex flex-shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <svg id="fetchButtonIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>

                                    <svg id="fetchButtonSpinner" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>

                                    <span id="fetchButtonText">Fetch Details</span>
                                </button>
                            </div>

                            <p class="mt-1.5 text-xs text-slate-500">
                                The cryptocurrency symbol cannot be changed here. Fetch Details retrieves updated API information.
                            </p>
                        </div>

                        {{-- NETWORK --}}
                        <div>
                            <label for="network" class="mb-2 block text-sm font-semibold text-slate-700">
                                Blockchain Network
                            </label>

                            <input
                                type="text"
                                name="network"
                                id="network"
                                value="{{ old('network', $crypto->network) }}"
                                maxlength="100"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="e.g. TRC20, ERC20, BEP20"
                            >

                            <p class="mt-1.5 text-xs text-slate-500">
                                Specify the blockchain network supported by this cryptocurrency.
                            </p>

                            @error('network')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- COIN ID --}}
                        <div>
                            <label for="coin_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                Coin ID (API) <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="coin_id"
                                id="coin_id"
                                value="{{ old('coin_id', $crypto->coin_id) }}"
                                maxlength="150"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="e.g. bitcoin, ethereum, tether-trc20"
                            >

                            <p class="mt-1.5 text-xs text-slate-500">
                                The catalogue identifier used to identify this asset in your application.
                            </p>

                            @error('coin_id')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PRICE COIN ID --}}
                        <div>
                            <label for="price_coin_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                Price Provider Coin ID
                            </label>

                            <input
                                type="text"
                                name="price_coin_id"
                                id="price_coin_id"
                                value="{{ old('price_coin_id', $crypto->price_coin_id ?? '') }}"
                                maxlength="150"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="e.g. bitcoin, ethereum, tether"
                            >

                            <p class="mt-1.5 text-xs text-slate-500">
                                Optional provider lookup ID. For network-specific USDT assets, use <strong>tether</strong> if your pricing service uses the shared Tether market price.
                            </p>

                            @error('price_coin_id')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SYSTEM WALLET ADDRESS --}}
                        <div class="sm:col-span-2">
                            <label for="wallet_address" class="mb-2 block text-sm font-semibold text-slate-700">
                                System Wallet Address
                            </label>

                            <input
                                type="text"
                                name="wallet_address"
                                id="wallet_address"
                                value="{{ old('wallet_address', $crypto->wallet_address ?? '') }}"
                                maxlength="255"
                                autocomplete="off"
                                spellcheck="false"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="Enter the platform wallet address"
                            >

                            <div class="mt-2 flex items-start gap-2 rounded-xl border border-blue-100 bg-blue-50/70 p-3">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                                </svg>

                                <p class="text-xs leading-5 text-blue-800">
                                    This is the platform's receiving wallet address for this cryptocurrency. It can be displayed to users when they select Receive. Make sure the address belongs to the selected blockchain network.
                                </p>
                            </div>

                            @error('wallet_address')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- LIVE PRICE --}}
                        <div id="fetchedPriceContainer" class="hidden sm:col-span-2">
                            <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                                            Retrieved Live Market Price
                                        </p>

                                        <p id="fetchedLivePrice" class="mt-1 text-2xl font-extrabold tabular-nums text-blue-900">
                                            $0.00
                                        </p>
                                    </div>

                                    <span id="fetchedPriceProvider"
                                          class="inline-flex w-fit items-center rounded-lg border border-blue-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        Live provider
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- FETCH MESSAGE --}}
                        <div id="fetchDetailsMessage"
                             class="hidden sm:col-span-2 rounded-xl border p-3 text-sm"
                             role="status"
                             aria-live="polite">
                        </div>

                        {{-- ICON URL --}}
                        <div class="sm:col-span-2">
                            <label for="icon_url" class="mb-2 block text-sm font-semibold text-slate-700">
                                Cryptocurrency Icon URL
                            </label>

                            <input
                                type="url"
                                name="icon_url"
                                id="icon_url"
                                value="{{ old('icon_url', $crypto->icon_url) }}"
                                maxlength="2048"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                placeholder="https://example.com/crypto-icon.png"
                            >

                            <p class="mt-1.5 text-xs text-slate-500">
                                Provide a valid image URL for the cryptocurrency logo.
                            </p>

                            @error('icon_url')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="iconPreviewContainer"
                                 class="mt-4 {{ old('icon_url', $crypto->icon_url) ? '' : 'hidden' }}">
                                <div class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <img
                                        id="iconPreview"
                                        src="{{ old('icon_url', $crypto->icon_url) }}"
                                        alt="Cryptocurrency icon preview"
                                        class="h-12 w-12 rounded-xl border border-slate-200 bg-white object-cover p-1"
                                    >

                                    <div>
                                        <p class="text-xs font-bold text-slate-700">Icon Preview</p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            This is how the asset image will appear.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- PRICING CONFIGURATION --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-5 sm:px-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12V5m0 15v-2m9-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900">Pricing Configuration</h3>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Configure how the application determines this cryptocurrency's USD value.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">

                        {{-- MANUAL PRICE --}}
                        <div>
                            <label for="manual_price" class="mb-2 block text-sm font-semibold text-slate-700">
                                Manual Price Override (USD)
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-slate-500">$</span>

                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="0"
                                    name="manual_price"
                                    id="manual_price"
                                    value="{{ old('manual_price', $crypto->manual_price !== null ? number_format((float) $crypto->manual_price, 8, '.', '') : '') }}"
                                    class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-9 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Enter manual USD price"
                                >
                            </div>

                            <p class="mt-2 text-xs leading-5 text-slate-500">
                                Enter a value to override automatic market pricing. Leave empty and save to remove the manual override.
                            </p>

                            @error('manual_price')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CURRENT MANUAL PRICE --}}
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Current Manual Price
                            </label>

                            <div class="flex min-h-[48px] flex-wrap items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                @if($crypto->manual_price !== null && (float) $crypto->manual_price > 0)
                                    <span class="text-base font-extrabold tabular-nums text-blue-700">
                                        ${{ number_format((float) $crypto->manual_price, 8) }}
                                    </span>

                                    <span class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        Manual override active
                                    </span>
                                @else
                                    <span class="text-sm font-medium text-slate-500">
                                        No manual override configured
                                    </span>
                                @endif
                            </div>

                            <p class="mt-2 text-xs leading-5 text-slate-500">
                                This displays the currently saved manual override, not necessarily the latest market price.
                            </p>
                        </div>

                        <div class="sm:col-span-2 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                                </svg>

                                <div>
                                    <p class="text-sm font-bold text-blue-900">Automatic pricing priority</p>
                                    <p class="mt-1 text-xs leading-5 text-blue-800">
                                        The application uses Binance first, followed by CoinGecko, Twelve Data, Alpha Vantage and other configured fallback providers. If live providers are unavailable, the last valid cached market price may be used. A saved manual override takes priority over automatic pricing.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- TRANSACTION LIMITS --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-5 sm:px-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900">
                                    Transaction Limits &amp; Fees
                                </h3>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Set minimum transaction amounts and withdrawal charges.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-3 sm:p-7">

                        {{-- MINIMUM DEPOSIT --}}
                        <div>
                            <label for="min_deposit" class="mb-2 block text-sm font-semibold text-slate-700">
                                Minimum Deposit
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-slate-500">$</span>

                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="0"
                                    name="min_deposit"
                                    id="min_deposit"
                                    value="{{ old('min_deposit', $crypto->min_deposit) }}"
                                    class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-9 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="0.00000000"
                                >
                            </div>

                            <p class="mt-2 text-xs text-slate-500">Minimum deposit amount configured for this asset.</p>

                            @error('min_deposit')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- MINIMUM WITHDRAWAL --}}
                        <div>
                            <label for="min_withdrawal" class="mb-2 block text-sm font-semibold text-slate-700">
                                Minimum Withdrawal
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-slate-500">$</span>

                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="0"
                                    name="min_withdrawal"
                                    id="min_withdrawal"
                                    value="{{ old('min_withdrawal', $crypto->min_withdrawal) }}"
                                    class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-9 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="0.00000000"
                                >
                            </div>

                            <p class="mt-2 text-xs text-slate-500">Minimum withdrawal amount configured for this asset.</p>

                            @error('min_withdrawal')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- WITHDRAWAL FEE --}}
                        <div>
                            <label for="withdrawal_fee" class="mb-2 block text-sm font-semibold text-slate-700">
                                Withdrawal Fee
                            </label>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-slate-500">$</span>

                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="0"
                                    name="withdrawal_fee"
                                    id="withdrawal_fee"
                                    value="{{ old('withdrawal_fee', $crypto->withdrawal_fee) }}"
                                    class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-9 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="0.00000000"
                                >
                            </div>

                            <p class="mt-2 text-xs text-slate-500">Fee charged when a user withdraws this cryptocurrency.</p>

                            @error('withdrawal_fee')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mx-5 mb-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50/70 p-4 sm:mx-7 sm:mb-7">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                        </svg>

                        <p class="text-xs leading-5 text-blue-800">
                            These limits and fees must also be enforced by your backend transaction logic. Confirm whether your application interprets these values as USD amounts or cryptocurrency amounts before changing existing settings.
                        </p>
                    </div>
                </div>

                {{-- STATUS CONFIGURATION --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-5 sm:px-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900">
                                    Cryptocurrency Status
                                </h3>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Control the availability of this cryptocurrency and its transaction features.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-3 sm:p-7">

                        {{-- ACTIVE --}}
                        <label class="group flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-blue-300 hover:bg-blue-50/40">
                            <input type="hidden" name="is_active" value="0">

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $crypto->is_active) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            >

                            <span class="min-w-0">
                                <span class="block text-sm font-bold text-slate-800">Active</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">
                                    Make this cryptocurrency available in the application.
                                </span>
                            </span>
                        </label>

                        {{-- DEPOSITS --}}
                        <label class="group flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                            <input type="hidden" name="is_deposit_enabled" value="0">

                            <input
                                type="checkbox"
                                name="is_deposit_enabled"
                                value="1"
                                {{ old('is_deposit_enabled', $crypto->is_deposit_enabled) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                            >

                            <span class="min-w-0">
                                <span class="block text-sm font-bold text-slate-800">Deposits Enabled</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">
                                    Allow users to deposit this cryptocurrency.
                                </span>
                            </span>
                        </label>

                        {{-- WITHDRAWALS --}}
                        <label class="group flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-indigo-300 hover:bg-indigo-50/40">
                            <input type="hidden" name="is_withdrawal_enabled" value="0">

                            <input
                                type="checkbox"
                                name="is_withdrawal_enabled"
                                value="1"
                                {{ old('is_withdrawal_enabled', $crypto->is_withdrawal_enabled) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            >

                            <span class="min-w-0">
                                <span class="block text-sm font-bold text-slate-800">Withdrawals Enabled</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">
                                    Allow users to withdraw this cryptocurrency.
                                </span>
                            </span>
                        </label>

                    </div>

                    <div class="mx-5 mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 sm:mx-7 sm:mb-7">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.46 0z"/>
                        </svg>

                        <p class="text-xs leading-5 text-amber-800">
                            <strong>Important:</strong> Disabling deposits or withdrawals should prevent users from initiating the corresponding transaction. These settings must also be enforced by your backend transaction logic.
                        </p>
                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="flex flex-col-reverse gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <a href="{{ route('admin.settings.wallets') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/15 transition hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Cryptocurrency Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- FETCH CRYPTOCURRENCY DETAILS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fetchButton = document.getElementById('fetchCryptoDetails');

            if (!fetchButton) {
                return;
            }

            const fetchButtonText = document.getElementById('fetchButtonText');
            const fetchButtonIcon = document.getElementById('fetchButtonIcon');
            const fetchButtonSpinner = document.getElementById('fetchButtonSpinner');

            const nameInput = document.getElementById('name');
            const symbolInput = document.getElementById('symbol');
            const coinIdInput = document.getElementById('coin_id');
            const priceCoinIdInput = document.getElementById('price_coin_id');
            const iconUrlInput = document.getElementById('icon_url');

            const priceContainer = document.getElementById('fetchedPriceContainer');
            const livePriceElement = document.getElementById('fetchedLivePrice');
            const priceProviderElement = document.getElementById('fetchedPriceProvider');
            const messageElement = document.getElementById('fetchDetailsMessage');

            const previewContainer = document.getElementById('iconPreviewContainer');
            const previewImage = document.getElementById('iconPreview');
            const identityIconPreview = document.getElementById('cryptoIconPreview');

            let activeController = null;

            function showFetchMessage(message, type = 'info') {
                if (!messageElement) {
                    return;
                }

                messageElement.textContent = message;

                messageElement.classList.remove(
                    'hidden',
                    'border-emerald-200',
                    'bg-emerald-50',
                    'text-emerald-800',
                    'border-red-200',
                    'bg-red-50',
                    'text-red-800',
                    'border-blue-200',
                    'bg-blue-50',
                    'text-blue-800'
                );

                if (type === 'success') {
                    messageElement.classList.add(
                        'border-emerald-200',
                        'bg-emerald-50',
                        'text-emerald-800'
                    );
                } else if (type === 'error') {
                    messageElement.classList.add(
                        'border-red-200',
                        'bg-red-50',
                        'text-red-800'
                    );
                } else {
                    messageElement.classList.add(
                        'border-blue-200',
                        'bg-blue-50',
                        'text-blue-800'
                    );
                }
            }

            function setLoading(isLoading) {
                fetchButton.disabled = isLoading;

                if (fetchButtonText) {
                    fetchButtonText.textContent = isLoading ? 'Fetching...' : 'Fetch Details';
                }

                if (fetchButtonIcon) {
                    fetchButtonIcon.classList.toggle('hidden', isLoading);
                }

                if (fetchButtonSpinner) {
                    fetchButtonSpinner.classList.toggle('hidden', !isLoading);
                }
            }

            function formatUsdPrice(price) {
                const numericPrice = Number(price);

                if (!Number.isFinite(numericPrice) || numericPrice < 0) {
                    return 'Price unavailable';
                }

                return '$' + numericPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 8
                });
            }

            function updateIconPreview(url) {
                const cleanUrl = String(url || '').trim();

                if (!cleanUrl) {
                    if (previewContainer) {
                        previewContainer.classList.add('hidden');
                    }

                    if (previewImage) {
                        previewImage.removeAttribute('src');
                    }

                    if (identityIconPreview) {
                        identityIconPreview.style.display = 'none';
                    }

                    return;
                }

                try {
                    const parsedUrl = new URL(cleanUrl);

                    if (!['http:', 'https:'].includes(parsedUrl.protocol)) {
                        throw new Error('Invalid image URL');
                    }

                    if (previewImage && previewContainer) {
                        previewImage.src = parsedUrl.href;
                        previewContainer.classList.remove('hidden');
                    }

                    if (identityIconPreview) {
                        identityIconPreview.src = parsedUrl.href;
                        identityIconPreview.style.display = '';
                    }
                } catch (error) {
                    if (previewContainer) {
                        previewContainer.classList.add('hidden');
                    }
                }
            }

            function extractCryptoDetails(responseData) {
                if (
                    !responseData ||
                    typeof responseData !== 'object' ||
                    Array.isArray(responseData)
                ) {
                    throw new Error('The server returned an unexpected response.');
                }

                if (responseData.success === false || responseData.error) {
                    throw new Error(
                        responseData.message ||
                        responseData.error ||
                        'Unable to retrieve cryptocurrency details.'
                    );
                }

                const details =
                    responseData.data &&
                    typeof responseData.data === 'object' &&
                    !Array.isArray(responseData.data)
                        ? responseData.data
                        : responseData;

                if (
                    !details.name &&
                    !details.coin_id &&
                    !details.icon_url &&
                    details.live_price == null
                ) {
                    throw new Error(
                        responseData.message ||
                        'The API did not return usable cryptocurrency information.'
                    );
                }

                return details;
            }

            fetchButton.addEventListener('click', async function () {
                const fetchUrl = fetchButton.dataset.fetchUrl;
                const symbol = symbolInput ? symbolInput.value.trim() : '';

                if (!symbol) {
                    showFetchMessage('Cryptocurrency symbol is missing.', 'error');
                    return;
                }

                if (!fetchUrl) {
                    showFetchMessage('The cryptocurrency details endpoint is not configured.', 'error');
                    return;
                }

                if (activeController) {
                    activeController.abort();
                }

                const controller = new AbortController();
                activeController = controller;

                setLoading(true);

                if (priceContainer) {
                    priceContainer.classList.add('hidden');
                }

                if (messageElement) {
                    messageElement.classList.add('hidden');
                }

                showFetchMessage(
                    'Retrieving cryptocurrency information. Please wait...',
                    'info'
                );

                try {
                    const url = new URL(fetchUrl, window.location.origin);
                    url.searchParams.set('symbol', symbol);

                    const response = await fetch(url.toString(), {
                        method: 'GET',
                        signal: controller.signal,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        if (response.status === 401 || response.status === 403) {
                            throw new Error('You are not authorized to fetch cryptocurrency details. Please sign in again.');
                        }

                        if (response.status === 419) {
                            throw new Error('Your session has expired. Refresh the page and try again.');
                        }

                        throw new Error('The server did not return JSON. Check the Laravel route and application logs.');
                    }

                    const responseData = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            responseData.message ||
                            responseData.error ||
                            'The server could not retrieve cryptocurrency information. HTTP ' + response.status
                        );
                    }

                    const details = extractCryptoDetails(responseData);

                    if (details.name && nameInput) {
                        nameInput.value = details.name;
                    }

                    if (details.coin_id && coinIdInput) {
                        coinIdInput.value = details.coin_id;
                    }

                    if (details.price_coin_id && priceCoinIdInput) {
                        priceCoinIdInput.value = details.price_coin_id;
                    }

                    if (details.icon_url && iconUrlInput) {
                        iconUrlInput.value = details.icon_url;
                        updateIconPreview(details.icon_url);
                    }

                    if (
                        details.live_price !== undefined &&
                        details.live_price !== null &&
                        details.live_price !== ''
                    ) {
                        const numericPrice = Number(details.live_price);

                        if (!Number.isFinite(numericPrice) || numericPrice < 0) {
                            throw new Error('The API returned an invalid market price.');
                        }

                        if (livePriceElement) {
                            livePriceElement.textContent = formatUsdPrice(numericPrice);
                        }

                        if (priceProviderElement) {
                            priceProviderElement.textContent = details.provider
                                ? 'Provider: ' + details.provider
                                : 'Live market price';
                        }

                        if (priceContainer) {
                            priceContainer.classList.remove('hidden');
                        }

                        showFetchMessage(
                            'Cryptocurrency details and market price retrieved successfully. Review the information before saving.',
                            'success'
                        );
                    } else {
                        showFetchMessage(
                            'Cryptocurrency information retrieved, but a live market price is currently unavailable. Existing pricing settings have been preserved.',
                            'success'
                        );
                    }

                    /*
                     * IMPORTANT:
                     * The manual price override is deliberately NOT cleared.
                     * Fetching market information must not silently remove
                     * an administrator's existing manual price setting.
                     */

                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    console.error('Fetch Cryptocurrency Details Error:', error);

                    showFetchMessage(
                        error.message || 'An unexpected error occurred while fetching cryptocurrency details.',
                        'error'
                    );

                } finally {
                    if (activeController === controller) {
                        activeController = null;
                        setLoading(false);
                    }
                }
            });

            // Live cryptocurrency icon preview.
            if (iconUrlInput && previewContainer && previewImage) {
                iconUrlInput.addEventListener('input', function () {
                    updateIconPreview(iconUrlInput.value);
                });

                previewImage.addEventListener('error', function () {
                    previewContainer.classList.add('hidden');

                    if (identityIconPreview) {
                        identityIconPreview.style.display = 'none';
                    }
                });

                previewImage.addEventListener('load', function () {
                    previewContainer.classList.remove('hidden');

                    if (identityIconPreview) {
                        identityIconPreview.src = previewImage.src;
                        identityIconPreview.style.display = '';
                    }
                });
            }

            // Abort any active fetch when the form is submitted.
            const editForm = document.getElementById('cryptoEditForm');

            if (editForm) {
                editForm.addEventListener('submit', function () {
                    if (activeController) {
                        activeController.abort();
                    }
                });
            }
        });
    </script>
</x-admin-layout>
