<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">
                    {{ __('Add New Cryptocurrency') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Configure digital assets, pricing and transaction settings.
                </p>
            </div>

            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                Asset Management
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/70">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- PAGE INTRODUCTION --}}
            <div class="mb-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-700 mb-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                        Asset Administration
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Create Cryptocurrency
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Add a new digital asset and configure its operational settings.
                    </p>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"/>
                    </svg>
                    Secure configuration
                </div>
            </div>

            {{-- MAIN FORM CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-blue-800 via-blue-600 to-sky-400"></div>

                <div class="p-5 sm:p-8 lg:p-9">

                    {{-- FORM INTRO --}}
                    <div class="flex items-start gap-4 pb-7 mb-8 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 3v18m9-9H3m15.364-6.364L5.636 18.364M5.636 5.636l12.728 12.728"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                Cryptocurrency Configuration
                            </h3>

                            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                Complete the information below to register a cryptocurrency,
                                configure its market price, define transaction limits and control availability.
                            </p>
                        </div>
                    </div>

                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-emerald-900">Operation successful</p>
                                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- ERROR MESSAGE --}}
                    @if (session('error'))
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-red-900">Unable to complete operation</p>
                                <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.cryptos.store') }}">
                        @csrf

                        {{-- SECTION 1: GENERAL INFORMATION --}}
                        <section class="mb-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 110-16 8 8 0 010 16z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-slate-900">General Information</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Basic cryptocurrency identification and wallet information.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                                {{-- SYMBOL --}}
                                <div>
                                    <label for="symbolInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Cryptocurrency Symbol <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input
                                            type="text"
                                            name="symbol"
                                            id="symbolInput"
                                            value="{{ old('symbol') }}"
                                            class="block w-full min-w-0 rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                            required
                                            maxlength="20"
                                            autocomplete="off"
                                            placeholder="e.g. usdt"
                                        >

                                        <button
                                            type="button"
                                            id="fetchBtn"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg id="fetchIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-5.2-5.2m2.2-5.3a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                                            </svg>
                                            <span id="fetchBtnText">Fetch Details</span>
                                        </button>
                                    </div>

                                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                        Must match your application's supported balance column where applicable
                                        (e.g. usdt for usdt_balance).
                                    </p>

                                    <p id="fetchStatus" class="text-xs font-medium mt-2 hidden" role="status" aria-live="polite"></p>

                                    @error('symbol')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NAME --}}
                                <div>
                                    <label for="nameInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Cryptocurrency Name <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        id="nameInput"
                                        value="{{ old('name') }}"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                        required
                                        maxlength="100"
                                        placeholder="e.g. Tether"
                                    >

                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NETWORK --}}
                                <div>
                                    <label for="networkInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Blockchain Network
                                    </label>

                                    <input
                                        type="text"
                                        name="network"
                                        id="networkInput"
                                        value="{{ old('network') }}"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                        maxlength="100"
                                        placeholder="e.g. ERC20, TRC20, BEP20"
                                    >

                                    <p class="text-xs text-slate-500 mt-2">
                                        Optional. Specify the supported blockchain network.
                                    </p>

                                    @error('network')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- COIN ID --}}
                                <div>
                                    <label for="coinIdInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Coin ID (API) <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="coin_id"
                                        id="coinIdInput"
                                        value="{{ old('coin_id') }}"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                        required
                                        maxlength="150"
                                        placeholder="e.g. tether"
                                    >

                                    <p class="text-xs text-slate-500 mt-2">
                                        Automatically populated when cryptocurrency details are fetched.
                                    </p>

                                    @error('coin_id')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- ICON URL --}}
                                <div>
                                    <label for="iconUrlInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Cryptocurrency Icon URL
                                    </label>

                                    <input
                                        type="url"
                                        name="icon_url"
                                        id="iconUrlInput"
                                        value="{{ old('icon_url') }}"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                        maxlength="2048"
                                        placeholder="https://..."
                                    >

                                    <div
                                        id="iconPreview"
                                        class="mt-3 inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 {{ old('icon_url') ? '' : 'hidden' }}"
                                    >
                                        <img
                                            src="{{ old('icon_url') }}"
                                            alt="Cryptocurrency icon preview"
                                            class="w-12 h-12 rounded-full border border-slate-200 object-cover bg-white"
                                        >

                                        <div>
                                            <p class="text-xs font-semibold text-slate-700">Icon Preview</p>
                                            <p class="text-xs text-slate-500 mt-0.5">Cryptocurrency image</p>
                                        </div>
                                    </div>

                                    @error('icon_url')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- WALLET ADDRESS --}}
                                <div>
                                    <label for="walletAddressInput" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        System Wallet Address
                                    </label>

                                    <input
                                        type="text"
                                        name="wallet_address"
                                        id="walletAddressInput"
                                        value="{{ old('wallet_address') }}"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 font-mono placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                        maxlength="255"
                                        placeholder="Enter the admin wallet address"
                                    >

                                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                        This address will be displayed to users when they select Receive.
                                    </p>

                                    @error('wallet_address')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </section>

                        {{-- SECTION 2: PRICING CONFIGURATION --}}
                        <section class="mb-10 pt-8 border-t border-slate-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 2v10m0 0v2m0-14a8 8 0 100 16 8 8 0 000-16z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-slate-900">Pricing Configuration</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Configure the cryptocurrency's USD market price.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- MANUAL PRICE --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <label for="priceInput" class="text-sm font-semibold text-slate-700">
                                            Manual Price Override
                                        </label>

                                        <span class="text-[10px] font-bold uppercase tracking-wider rounded-md bg-amber-50 text-amber-700 px-2 py-1">
                                            Optional
                                        </span>
                                    </div>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold">$</span>

                                        <input
                                            type="number"
                                            step="0.00000001"
                                            min="0"
                                            name="manual_price"
                                            id="priceInput"
                                            value="{{ old('manual_price') }}"
                                            class="block w-full rounded-xl border-slate-300 bg-slate-50 pl-9 pr-4 py-3 text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                            placeholder="0.00000000"
                                        >
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                        Enter a price manually to override automatic market pricing.
                                        Leave empty to use live market pricing.
                                    </p>

                                    @error('manual_price')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- LIVE PRICE --}}
                                <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/80 to-white p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <label for="livePriceInput" class="text-sm font-semibold text-slate-700">
                                            Live Market Price
                                        </label>

                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-emerald-50 text-emerald-700 px-2 py-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Market lookup
                                        </span>
                                    </div>

                                    <input
                                        type="text"
                                        id="livePriceInput"
                                        value=""
                                        readonly
                                        placeholder="Awaiting market data..."
                                        class="block w-full rounded-xl border-blue-100 bg-white px-4 py-3 text-lg font-bold text-blue-900 shadow-sm placeholder-slate-400"
                                    >

                                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                        Checks Binance, CoinGecko, Twelve Data, Alpha Vantage and Finnhub in configured priority order.
                                    </p>

                                    <p id="priceProvider" class="text-xs font-semibold text-blue-700 mt-2 hidden"></p>
                                </div>

                            </div>
                        </section>

                        {{-- SECTION 3: TRANSACTION LIMITS & FEES --}}
                        <section class="mb-10 pt-8 border-t border-slate-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-slate-900">Transaction Limits &amp; Fees</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Define minimum transaction amounts and withdrawal charges.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- MINIMUM DEPOSIT --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mb-4">
                                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>

                                    <label for="minDepositInput" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Minimum Deposit
                                    </label>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-semibold">$</span>

                                        <input
                                            type="number"
                                            name="min_deposit"
                                            id="minDepositInput"
                                            value="{{ old('min_deposit', '0.00') }}"
                                            min="0"
                                            step="0.01"
                                            class="block w-full rounded-lg border-slate-300 bg-slate-50 pl-8 pr-3 py-3 text-sm font-semibold text-slate-800 shadow-sm transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                            placeholder="0.00"
                                        >
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3">Minimum USD value users must deposit.</p>

                                    @error('min_deposit')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- MINIMUM WITHDRAWAL --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mb-4">
                                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 20V4m-7 7l7-7 7 7"/>
                                        </svg>
                                    </div>

                                    <label for="minWithdrawalInput" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Minimum Withdrawal
                                    </label>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-semibold">$</span>

                                        <input
                                            type="number"
                                            name="min_withdrawal"
                                            id="minWithdrawalInput"
                                            value="{{ old('min_withdrawal', '0.00') }}"
                                            min="0"
                                            step="0.01"
                                            class="block w-full rounded-lg border-slate-300 bg-slate-50 pl-8 pr-3 py-3 text-sm font-semibold text-slate-800 shadow-sm transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                            placeholder="0.00"
                                        >
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3">Minimum USD value users can withdraw.</p>

                                    @error('min_withdrawal')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- WITHDRAWAL FEE --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                                        <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 2v10m0 0v2"/>
                                        </svg>
                                    </div>

                                    <label for="withdrawalFeeInput" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Withdrawal Fee
                                    </label>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-semibold">$</span>

                                        <input
                                            type="number"
                                            name="withdrawal_fee"
                                            id="withdrawalFeeInput"
                                            value="{{ old('withdrawal_fee', '0.00') }}"
                                            min="0"
                                            step="0.01"
                                            class="block w-full rounded-lg border-slate-300 bg-slate-50 pl-8 pr-3 py-3 text-sm font-semibold text-slate-800 shadow-sm transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                            placeholder="0.00"
                                        >
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3">Fixed fee charged for each withdrawal.</p>

                                    @error('withdrawal_fee')
                                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 110-16 8 8 0 010 16z"/>
                                    </svg>
                                </div>

                                <p class="text-xs text-blue-800 leading-relaxed pt-1">
                                    Transaction limits and fees are configured in USD. Ensure your withdrawal
                                    processing logic applies the configured fee and minimum limits correctly.
                                </p>
                            </div>
                        </section>

                        {{-- SECTION 4: CRYPTOCURRENCY STATUS --}}
                        <section class="mb-10 pt-8 border-t border-slate-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-slate-900">Cryptocurrency Status</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Control availability and transaction permissions for this asset.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- ACTIVE --}}
                                <label class="group flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-5 cursor-pointer transition hover:border-blue-300 hover:bg-blue-50/30">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                    >

                                    <span class="min-w-0">
                                        <span class="flex items-center gap-2 text-sm font-bold text-slate-800">
                                            Cryptocurrency Active
                                        </span>
                                        <span class="block text-xs text-slate-500 mt-1.5 leading-relaxed">
                                            Make this cryptocurrency available on the platform.
                                        </span>
                                    </span>
                                </label>

                                {{-- DEPOSITS --}}
                                <label class="group flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-5 cursor-pointer transition hover:border-emerald-300 hover:bg-emerald-50/30">
                                    <input
                                        type="checkbox"
                                        name="is_deposit_enabled"
                                        value="1"
                                        {{ old('is_deposit_enabled', '1') == '1' ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                    >

                                    <span class="min-w-0">
                                        <span class="text-sm font-bold text-slate-800">Deposits Enabled</span>
                                        <span class="block text-xs text-slate-500 mt-1.5 leading-relaxed">
                                            Allow users to deposit this cryptocurrency.
                                        </span>
                                    </span>
                                </label>

                                {{-- WITHDRAWALS --}}
                                <label class="group flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-5 cursor-pointer transition hover:border-violet-300 hover:bg-violet-50/30">
                                    <input
                                        type="checkbox"
                                        name="is_withdrawal_enabled"
                                        value="1"
                                        {{ old('is_withdrawal_enabled', '1') == '1' ? 'checked' : '' }}
                                        class="mt-1 w-4 h-4 rounded border-slate-300 text-violet-600 shadow-sm focus:ring-violet-500"
                                    >

                                    <span class="min-w-0">
                                        <span class="text-sm font-bold text-slate-800">Withdrawals Enabled</span>
                                        <span class="block text-xs text-slate-500 mt-1.5 leading-relaxed">
                                            Allow users to withdraw this cryptocurrency.
                                        </span>
                                    </span>
                                </label>

                            </div>

                            <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                                <svg class="w-5 h-5 text-amber-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.46 0z"/>
                                </svg>

                                <p class="text-xs text-amber-800 leading-relaxed">
                                    <strong>Important:</strong>
                                    Disabling deposits or withdrawals should prevent the corresponding transaction
                                    from being initiated by users. These settings must also be enforced by your
                                    backend transaction logic.
                                </p>
                            </div>
                        </section>

                        {{-- VALIDATION ERRORS --}}
                        @if ($errors->any())
                            <div class="mb-7 rounded-xl border border-red-200 bg-red-50 p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.46 0z"/>
                                    </svg>

                                    <p class="text-sm font-bold text-red-800">
                                        Please correct the following errors:
                                    </p>
                                </div>

                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM ACTIONS --}}
                        <div class="pt-7 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-4">
                            <p class="text-xs text-slate-500">
                                <span class="text-red-500">*</span>
                                Required fields must be completed.
                            </p>

                            <div class="flex flex-col-reverse sm:flex-row gap-3">
                                <a
                                    href="{{ route('admin.settings.wallets') }}"
                                    class="inline-flex justify-center items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-400"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-blue-800 to-blue-600 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/15 transition hover:from-blue-900 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Add Crypto &amp; Save Address
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- SECURITY NOTE --}}
            <div class="mt-5 flex items-center justify-center gap-2 text-xs text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"/>
                </svg>
                Cryptocurrency configuration is managed through the administration panel.
            </div>

        </div>
    </div>

    {{-- CRYPTOCURRENCY FETCHING SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const symbolInput = document.getElementById('symbolInput');
            const fetchBtn = document.getElementById('fetchBtn');
            const fetchBtnText = document.getElementById('fetchBtnText');
            const fetchIcon = document.getElementById('fetchIcon');
            const statusEl = document.getElementById('fetchStatus');

            const nameInput = document.getElementById('nameInput');
            const coinIdInput = document.getElementById('coinIdInput');
            const iconUrlInput = document.getElementById('iconUrlInput');

            const livePriceInput = document.getElementById('livePriceInput');
            const manualPriceInput = document.getElementById('priceInput');

            const iconPreview = document.getElementById('iconPreview');
            const iconImage = iconPreview.querySelector('img');
            const priceProvider = document.getElementById('priceProvider');

            const fetchDetailsUrl = @json(route('admin.cryptos.fetch-details'));

            let activeController = null;

            const providerNames = {
                binance: 'Binance',
                coingecko: 'CoinGecko',
                'twelve data': 'Twelve Data',
                twelvedata: 'Twelve Data',
                twelve_data: 'Twelve Data',
                alphavantage: 'Alpha Vantage',
                alpha_vantage: 'Alpha Vantage',
                'alpha vantage': 'Alpha Vantage',
                finnhub: 'Finnhub',
                cache: 'Last-known cached price',
                cached: 'Last-known cached price',
                last_good: 'Last-known cached price',
                lastgood: 'Last-known cached price',
                manual: 'Manual price override'
            };

            function showStatus(message, type = 'info') {
                statusEl.textContent = message;

                statusEl.className = 'text-xs font-medium mt-2';

                const colors = {
                    success: 'text-emerald-600',
                    warning: 'text-amber-600',
                    error: 'text-red-600',
                    info: 'text-blue-600'
                };

                statusEl.classList.add(colors[type] || colors.info);
                statusEl.classList.remove('hidden');
            }

            function setLoading(isLoading) {
                fetchBtn.disabled = isLoading;
                fetchBtnText.textContent = isLoading ? 'Fetching...' : 'Fetch Details';

                if (isLoading) {
                    fetchIcon.classList.add('animate-spin');
                } else {
                    fetchIcon.classList.remove('animate-spin');
                }
            }

            function formatUsdPrice(value) {
                const number = Number(value);

                if (!Number.isFinite(number) || number <= 0) {
                    return '';
                }

                return '$' + number.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 8
                });
            }

            function getValidPrice(value) {
                if (value === null || value === undefined || value === '') {
                    return null;
                }

                const number = Number(value);

                return Number.isFinite(number) && number > 0
                    ? number
                    : null;
            }

            function displayProvider(provider) {
                if (!provider) {
                    priceProvider.textContent = '';
                    priceProvider.classList.add('hidden');
                    return;
                }

                const key = String(provider).trim().toLowerCase();
                const providerName = providerNames[key] || provider;

                priceProvider.textContent = 'Price source: ' + providerName;
                priceProvider.classList.remove('hidden');
            }

            function updateIconPreview(url) {
                const cleanUrl = String(url || '').trim();

                if (!cleanUrl) {
                    iconImage.removeAttribute('src');
                    iconPreview.classList.add('hidden');
                    return;
                }

                try {
                    const parsedUrl = new URL(cleanUrl);

                    if (!['http:', 'https:'].includes(parsedUrl.protocol)) {
                        iconPreview.classList.add('hidden');
                        return;
                    }

                    iconImage.src = parsedUrl.href;
                    iconPreview.classList.remove('hidden');

                } catch (error) {
                    iconPreview.classList.add('hidden');
                }
            }

            function applyCoinDetails(data) {
                if (data.name) {
                    nameInput.value = data.name;
                }

                if (data.coin_id) {
                    coinIdInput.value = data.coin_id;
                }

                if (data.icon_url) {
                    iconUrlInput.value = data.icon_url;
                    updateIconPreview(data.icon_url);
                }
            }

            function resetPriceDisplay() {
                livePriceInput.value = '';
                priceProvider.textContent = '';
                priceProvider.classList.add('hidden');
            }

            fetchBtn.addEventListener('click', async function () {

                const symbol = symbolInput.value.trim().toLowerCase();

                if (!symbol) {
                    showStatus(
                        'Please enter a cryptocurrency symbol first.',
                        'error'
                    );

                    symbolInput.focus();
                    return;
                }

                if (activeController) {
                    activeController.abort();
                }

                const controller = new AbortController();
                activeController = controller;

                setLoading(true);
                resetPriceDisplay();

                showStatus(
                    'Searching cryptocurrency information and available USD price providers...',
                    'info'
                );

                try {

                    const url = new URL(fetchDetailsUrl, window.location.origin);

                    url.searchParams.set('symbol', symbol);

                    const response = await fetch(url.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        signal: controller.signal,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        if (response.status === 401 || response.status === 403) {
                            throw new Error(
                                'Your admin session may have expired or you do not have permission to fetch cryptocurrency details. Please sign in again.'
                            );
                        }

                        if (response.redirected) {
                            throw new Error(
                                'The request was redirected. Please refresh the page and sign in again.'
                            );
                        }

                        throw new Error(
                            'The server did not return JSON. Check the Laravel route and application logs.'
                        );
                    }

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            result.error ||
                            result.message ||
                            'The server could not retrieve cryptocurrency details.'
                        );
                    }

                    const data = result && result.data
                        ? result.data
                        : {};

                    const hasCoinDetails =
                        Boolean(data.name) &&
                        Boolean(data.coin_id);

                    if (!hasCoinDetails) {
                        throw new Error(
                            result.error ||
                            result.message ||
                            'Cryptocurrency details could not be identified. You can enter the information manually.'
                        );
                    }

                    applyCoinDetails(data);

                    const livePrice = getValidPrice(data.live_price);

                    if (livePrice !== null) {

                        livePriceInput.value = formatUsdPrice(livePrice);

                        displayProvider(data.provider);

                        showStatus(
                            'Cryptocurrency identified and USD market price retrieved successfully.',
                            'success'
                        );

                    } else {

                        livePriceInput.value = 'Price unavailable';

                        displayProvider(data.provider || '');

                        showStatus(
                            'Cryptocurrency identified successfully, but a live USD price is currently unavailable. You can enter a manual price override if required.',
                            'warning'
                        );
                    }

                    /*
                     * IMPORTANT:
                     * Do not automatically clear manualPriceInput here.
                     * A manually entered price is an intentional admin override.
                     */

                } catch (error) {

                    if (error.name === 'AbortError') {
                        return;
                    }

                    console.error('Cryptocurrency details fetch failed:', error);

                    showStatus(
                        error.message || 'A network error occurred. Please try again.',
                        'error'
                    );

                } finally {

                    if (activeController === controller) {
                        activeController = null;
                        setLoading(false);
                    }
                }
            });

            // Update icon preview when URL is manually edited.
            iconUrlInput.addEventListener('input', function () {
                updateIconPreview(iconUrlInput.value);
            });

            // Handle invalid or unavailable icon images.
            iconImage.addEventListener('error', function () {
                iconPreview.classList.add('hidden');
            });

            iconImage.addEventListener('load', function () {
                iconPreview.classList.remove('hidden');
            });

            // Normalize the symbol.
            symbolInput.addEventListener('blur', function () {
                symbolInput.value = symbolInput.value.trim().toLowerCase();
            });

            // Cancel an active request if the page is being submitted.
            const form = symbolInput.closest('form');

            if (form) {
                form.addEventListener('submit', function () {
                    if (activeController) {
                        activeController.abort();
                    }
                });
            }

        });
    </script>

</x-admin-layout>