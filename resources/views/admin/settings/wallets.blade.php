<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">
                    {{ __('Wallet & Crypto Management') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage digital assets, deposit addresses and transaction availability.
                </p>
            </div>

            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                Financial Administration
            </div>
        </div>
    </x-slot>

    @php
        $cryptos = $cryptos ?? collect();
        $livePrices = $livePrices ?? [];
        $settings = $settings ?? [];
    @endphp

    <div class="py-8 sm:py-10 bg-slate-50/70">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- PAGE INTRODUCTION --}}
            <div class="mb-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-700 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 3v18m9-9H3"/>
                            </svg>
                        </span>
                        Wallet Administration
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Wallet & Crypto Management
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Manage supported cryptocurrencies, wallet addresses, market prices and transaction settings.
                    </p>
                </div>

                <a
                    href="{{ route('admin.cryptos.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-800 to-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/15 transition hover:from-blue-900 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Cryptocurrency
                </a>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-7">

                {{-- TOTAL ASSETS --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Configured Assets
                            </p>
                            <p class="text-3xl font-bold text-slate-900 mt-2 tabular-nums">
                                {{ $cryptos->count() }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Registered cryptocurrencies
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 3v18m9-9H3m15.364-6.364L6.364 17.364m0-10.728l12.728 12.728"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- ACTIVE ASSETS --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Active Assets
                            </p>
                            <p class="text-3xl font-bold text-emerald-700 mt-2 tabular-nums">
                                {{ $cryptos->where('is_active', true)->count() }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Currently enabled
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- DISABLED ASSETS --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Disabled Assets
                            </p>
                            <p class="text-3xl font-bold text-slate-700 mt-2 tabular-nums">
                                {{ $cryptos->where('is_active', false)->count() }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Currently unavailable
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M18.364 5.636L5.636 18.364m12.728 0L5.636 5.636"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(session('status'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold text-emerald-900">Operation successful</p>
                        <p class="text-sm text-emerald-700 mt-0.5 break-words">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold text-red-900">Operation unsuccessful</p>
                        <p class="text-sm text-red-700 mt-0.5 break-words">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.46 0z"/>
                        </svg>

                        <p class="text-sm font-bold text-red-800">
                            Please correct the following errors:
                        </p>
                    </div>

                    <ul class="list-disc pl-5 text-sm text-red-700 space-y-1 break-words">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- =====================================================
                SECTION 1: SYSTEM WALLET ADDRESSES
            ====================================================== --}}

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-7">

                <div class="h-1 bg-gradient-to-r from-blue-800 via-blue-600 to-sky-400"></div>

                <div class="p-5 sm:p-7">

                    {{-- SECTION HEADER --}}
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-7">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 10h18M7 15h2m4 0h4M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    System Wallet Addresses
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Manage the deposit wallet addresses displayed to your users.
                                </p>
                            </div>
                        </div>

                        <span class="inline-flex items-center self-start sm:self-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            {{ $cryptos->count() }} Configured Assets
                        </span>
                    </div>

                    {{-- WALLET ADDRESS FORM --}}
                    <form
                        method="POST"
                        action="{{ route('admin.settings.wallets.update') }}"
                        id="walletAddressesForm"
                    >
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">

                            @forelse($cryptos as $crypto)

                                @php
                                    $addressKey = $crypto->wallet_address_setting_key;

                                    $currentAddress = old(
                                        $addressKey,
                                        data_get($settings, $addressKey, '')
                                    );

                                    $toggleFormId = 'toggle-status-' . $crypto->id;
                                @endphp

                                <div class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5 transition duration-200 hover:border-blue-200 hover:bg-white hover:shadow-md">

                                    {{-- ASSET HEADER --}}
                                    <div class="flex items-center justify-between gap-3 mb-4">

                                        <div class="flex items-center gap-3 min-w-0 flex-1">

                                            @if($crypto->icon_url)
                                                <img
                                                    src="{{ $crypto->icon_url }}"
                                                    alt="{{ $crypto->name }}"
                                                    class="w-11 h-11 rounded-xl border border-slate-200 bg-white object-cover flex-shrink-0 p-1"
                                                    onerror="this.style.display='none'"
                                                >
                                            @else
                                                <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                            d="M12 3v18m9-9H3"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <label
                                                    for="{{ $addressKey }}"
                                                    class="block text-sm font-bold text-slate-900 break-words"
                                                >
                                                    {{ $crypto->name }}
                                                </label>

                                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                                    <span class="text-[10px] font-bold tracking-wider text-blue-800 bg-blue-100 px-2 py-1 rounded-md">
                                                        {{ strtoupper($crypto->symbol) }}
                                                    </span>

                                                    @if($crypto->network)
                                                        <span class="text-xs text-slate-500 break-words">
                                                            {{ $crypto->network }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ACTIVE STATUS --}}
                                        <button
                                            type="submit"
                                            form="{{ $toggleFormId }}"
                                            aria-label="Toggle {{ $crypto->name }} active status"
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-2 rounded-lg font-bold flex-shrink-0 transition-colors {{ $crypto->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full {{ $crypto->is_active ? 'bg-emerald-600' : 'bg-red-600' }}"></span>
                                            {{ $crypto->is_active ? 'Active' : 'Disabled' }}
                                        </button>

                                    </div>

                                    {{-- ADDRESS FIELD --}}
                                    <label
                                        for="{{ $addressKey }}"
                                        class="block text-xs font-semibold text-slate-600 mb-2"
                                    >
                                        Deposit Wallet Address
                                    </label>

                                    <input
                                        type="text"
                                        name="{{ $addressKey }}"
                                        id="{{ $addressKey }}"
                                        value="{{ $currentAddress }}"
                                        placeholder="Enter system wallet address..."
                                        autocomplete="off"
                                        spellcheck="false"
                                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 font-mono placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                    >

                                    <div class="flex items-center justify-between gap-2 mt-3">
                                        <p class="text-xs text-slate-400 break-words">
                                            Deposit address for {{ strtoupper($crypto->symbol) }}.
                                        </p>

                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 11c-1.105 0-2 .672-2 1.5S10.895 14 12 14s2 .672 2 1.5S13.105 17 12 17m0-6V9m0 8v-1"/>
                                            <circle cx="12" cy="12" r="9"/>
                                        </svg>
                                    </div>

                                </div>

                            @empty

                                <div class="md:col-span-2 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-white border border-slate-200 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12V5m0 14v-2m8-5a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                        </svg>
                                    </div>

                                    <h4 class="text-base font-bold text-slate-800">
                                        No Cryptocurrencies Configured
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-2">
                                        Add your first cryptocurrency to begin managing system wallet addresses.
                                    </p>

                                    <a
                                        href="{{ route('admin.cryptos.create') }}"
                                        class="inline-flex items-center gap-2 mt-5 rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Cryptocurrency
                                    </a>
                                </div>

                            @endforelse

                        </div>

                        {{-- SAVE WALLET ADDRESSES --}}
                        @if($cryptos->count() > 0)
                            <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                                    </svg>

                                    <p class="text-xs text-slate-500">
                                        Make sure each wallet address matches its corresponding blockchain network.
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-800 to-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/15 transition hover:from-blue-900 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Wallet Addresses
                                </button>

                            </div>
                        @endif

                    </form>

                    {{-- SEPARATE ACTIVE STATUS FORMS --}}
                    @foreach($cryptos as $crypto)
                        <form
                            id="toggle-status-{{ $crypto->id }}"
                            action="{{ route('admin.cryptos.toggle', $crypto) }}"
                            method="POST"
                            class="hidden"
                        >
                            @csrf
                        </form>
                    @endforeach

                </div>
            </div>

            {{-- =====================================================
                SECTION 2: CRYPTO ASSET CONFIGURATION
            ====================================================== --}}

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="h-1 bg-gradient-to-r from-indigo-800 via-blue-600 to-sky-400"></div>

                <div class="p-5 sm:p-7">

                    {{-- TABLE HEADER --}}
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 3v18h18M7 14l4-4 4 4 6-7"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    Crypto Asset Configuration
                                </h3>

                                <p class="text-sm text-slate-500 mt-1">
                                    View effective market prices and manage deposit and withdrawal availability.
                                    Manual prices can only be changed from each asset's edit page.
                                </p>
                            </div>
                        </div>

                        <span class="inline-flex items-center self-start sm:self-center gap-2 rounded-lg bg-blue-50 border border-blue-100 px-3 py-2 text-xs font-bold text-blue-800">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            {{ $cryptos->count() }} Configured Assets
                        </span>

                    </div>

                    {{-- TABLE --}}
                    <div class="w-full overflow-x-auto rounded-xl border border-slate-200">

                        <table class="w-full min-w-[900px] divide-y divide-slate-200">

                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        Asset
                                    </th>

                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        Effective Price
                                    </th>

                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        Deposit
                                    </th>

                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        Withdraw
                                    </th>

                                    <th class="px-5 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-slate-100">

                                @forelse($cryptos as $crypto)

                                    @php
                                        $effectivePrice = $crypto->getEffectivePrice($livePrices);

                                        $hasEffectivePrice = is_numeric($effectivePrice)
                                            && (float) $effectivePrice > 0;

                                        $manualPriceActive = $crypto->manual_price !== null
                                            && is_numeric($crypto->manual_price)
                                            && (float) $crypto->manual_price > 0;
                                    @endphp

                                    <tr class="transition hover:bg-slate-50/80">

                                        {{-- ASSET --}}
                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">

                                                @if($crypto->icon_url)
                                                    <img
                                                        src="{{ $crypto->icon_url }}"
                                                        alt="{{ $crypto->name }}"
                                                        class="w-10 h-10 rounded-xl border border-slate-200 bg-white object-cover flex-shrink-0 p-1"
                                                        onerror="this.style.display='none'"
                                                    >
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                                d="M12 3v18m9-9H3"/>
                                                        </svg>
                                                    </div>
                                                @endif

                                                <div>
                                                    <p class="text-sm font-bold text-slate-900">
                                                        {{ $crypto->name }}
                                                    </p>

                                                    <p class="text-xs text-slate-500 mt-1">
                                                        {{ strtoupper($crypto->symbol) }}

                                                        @if($crypto->network)
                                                            <span class="mx-1 text-slate-300">•</span>
                                                            {{ $crypto->network }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- EFFECTIVE PRICE --}}
                                        <td class="px-5 py-5 whitespace-nowrap">

                                            @if($hasEffectivePrice)
                                                <p class="text-sm font-bold text-slate-900">
                                                    ${{ number_format((float) $effectivePrice, 8) }}
                                                </p>
                                            @else
                                                <p class="text-sm font-bold text-red-600">
                                                    Unavailable
                                                </p>

                                                <p class="text-xs text-slate-400 mt-1">
                                                    No valid price
                                                </p>
                                            @endif

                                            @if($manualPriceActive)
                                                <span class="inline-flex items-center gap-1.5 mt-2 text-[10px] font-bold uppercase tracking-wide text-blue-700 bg-blue-50 px-2 py-1 rounded-md">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                                    Manual Override
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 mt-2 text-[10px] font-bold uppercase tracking-wide text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Market Price
                                                </span>
                                            @endif

                                        </td>

                                        {{-- DEPOSIT STATUS --}}
                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <form
                                                action="{{ route('admin.cryptos.toggle-deposit', $crypto) }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    aria-label="Toggle {{ $crypto->name }} deposits"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ $crypto->is_deposit_enabled ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                                                >
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $crypto->is_deposit_enabled ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                    {{ $crypto->is_deposit_enabled ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- WITHDRAWAL STATUS --}}
                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <form
                                                action="{{ route('admin.cryptos.toggle-withdrawal', $crypto) }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    aria-label="Toggle {{ $crypto->name }} withdrawals"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ $crypto->is_withdrawal_enabled ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                                                >
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $crypto->is_withdrawal_enabled ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                    {{ $crypto->is_withdrawal_enabled ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-5 py-5 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-3">

                                                <a
                                                    href="{{ route('admin.cryptos.edit', $crypto) }}"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('admin.cryptos.destroy', $crypto) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure? This will delete the cryptocurrency configuration.')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 hover:text-red-800 transition"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="px-6 py-14 text-center">

                                            <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M12 3v18m9-9H3"/>
                                                </svg>
                                            </div>

                                            <h4 class="text-sm font-bold text-slate-800">
                                                No Cryptocurrency Assets Found
                                            </h4>

                                            <p class="text-sm text-slate-500 mt-2">
                                                Start by adding your first cryptocurrency asset.
                                            </p>

                                            <a
                                                href="{{ route('admin.cryptos.create') }}"
                                                class="inline-flex items-center gap-2 mt-5 rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Add Cryptocurrency
                                            </a>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>

                    </div>

                    {{-- TABLE FOOTER --}}
                    @if($cryptos->count() > 0)
                        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                            <p class="text-xs text-slate-500">
                                Showing {{ $cryptos->count() }} configured cryptocurrency assets.
                            </p>

                            <p class="text-xs text-slate-400">
                                Market prices are displayed in USD. Manual prices are managed from the Edit page.
                            </p>

                        </div>
                    @endif

                </div>
            </div>

            {{-- BOTTOM SECURITY NOTE --}}
            <div class="mt-5 flex items-center justify-center gap-2 text-xs text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                </svg>
                Wallet and cryptocurrency settings are managed through the administration panel.
            </div>

        </div>
    </div>
</x-admin-layout>