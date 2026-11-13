
<x-admin-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="container px-4 py-6 mx-auto sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div class="flex flex-col gap-4 mb-7 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span class="text-xs font-semibold tracking-widest text-slate-500 uppercase">
                            Administration / User Management
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Users Management
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Manage customer accounts, verification, wallet balances and account settings.
                    </p>
                </div>

                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white transition bg-indigo-700 rounded-lg shadow-sm hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="ri-user-add-line text-lg"></i>
                    Create New User
                </a>
            </div>

            {{-- FLASH MESSAGES --}}
            <x-session-status class="mb-5" :status="session('status')" :errors="$errors" />

            {{-- FILTER PANEL --}}
            <div class="mb-6 overflow-hidden bg-white border rounded-xl border-slate-200 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Search & Filters</h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Find customer accounts using their details or verification status.
                        </p>
                    </div>
                    <i class="text-xl ri-filter-3-line text-slate-400"></i>
                </div>

                <form action="{{ route('admin.users') }}" method="GET"
                      class="grid grid-cols-1 gap-4 p-5 md:grid-cols-4">

                    <div>
                        <label class="block mb-2 text-xs font-semibold tracking-wide uppercase text-slate-600">
                            KYC Status
                        </label>

                        <select name="kyc_status"
                                class="w-full px-3 py-2.5 text-sm bg-white border rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">All Statuses</option>
                            <option value="approved" {{ request('kyc_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('kyc_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('kyc_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="not_submitted" {{ request('kyc_status') === 'not_submitted' ? 'selected' : '' }}>Not Submitted</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-xs font-semibold tracking-wide uppercase text-slate-600">
                            Search Customers
                        </label>

                        <div class="relative">
                            <i class="absolute text-lg -translate-y-1/2 ri-search-line left-3 top-1/2 text-slate-400"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, email or ID..."
                                   class="w-full py-2.5 pl-10 pr-3 text-sm border rounded-lg border-slate-300 text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center flex-1 gap-2 px-4 py-2.5 text-sm font-semibold text-white transition bg-slate-800 rounded-lg hover:bg-slate-900">
                            <i class="ri-search-line"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.users') }}"
                           class="inline-flex items-center justify-center px-3 py-2.5 text-sm font-medium border rounded-lg border-slate-300 text-slate-600 hover:bg-slate-50"
                           title="Reset filters">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </form>
            </div>

            {{-- USERS TABLE --}}
            <div class="overflow-visible bg-white border rounded-xl border-slate-200 shadow-sm">

                <div class="flex flex-col gap-2 px-5 py-4 border-b border-slate-100 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Customer Accounts</h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Account records and administrative controls.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 self-start px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                        <i class="ri-group-line"></i>
                        {{ method_exists($users, 'total') ? number_format($users->total()) : $users->count() }} Accounts
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left">
                        <thead>
                            <tr class="text-[11px] font-bold tracking-wider uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                                <th class="px-5 py-4">Customer</th>
                                <th class="px-5 py-4">Verification</th>
                                <th class="px-5 py-4">Portfolio Balance</th>
                                <th class="px-5 py-4">Registration</th>
                                <th class="px-5 py-4 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse($users as $user)

                                @php
                                    $kycStatus = $user->kyc_status ?? 'not_submitted';

                                    $kycStyles = [
                                        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
                                        'not_submitted' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                    ];

                                    $kycIcons = [
                                        'approved' => 'ri-checkbox-circle-fill',
                                        'pending' => 'ri-time-line',
                                        'rejected' => 'ri-close-circle-fill',
                                        'not_submitted' => 'ri-information-line',
                                    ];

                                    $statusStyle = $kycStyles[$kycStatus] ?? $kycStyles['not_submitted'];
                                    $statusIcon = $kycIcons[$kycStatus] ?? $kycIcons['not_submitted'];

                                    $totalBalance = $user->getTotalBalanceUSD();

                                    $activeAssets = $user->hasCryptoAssets()
                                        ? count($user->cryptoAssets->getActiveCryptos())
                                        : 0;
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">

                                    {{-- CUSTOMER --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 text-sm font-bold text-indigo-700 border rounded-full bg-indigo-50 border-indigo-100">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">
                                                <div class="text-sm font-bold text-slate-800">
                                                    {{ $user->name }}
                                                </div>

                                                <div class="mt-0.5 text-xs text-slate-500">
                                                    {{ $user->email }}
                                                </div>

                                                <div class="mt-1 font-mono text-[10px] text-slate-400">
                                                    ID: {{ $user->uuid ?? $user->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KYC STATUS --}}
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-bold rounded-full ring-1 {{ $statusStyle }}">
                                            <i class="{{ $statusIcon }}"></i>
                                            {{ ucwords(str_replace('_', ' ', $kycStatus)) }}
                                        </span>
                                    </td>

                                    {{-- BALANCE --}}
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-bold text-slate-900">
                                            ${{ number_format((float) $totalBalance, 2) }}
                                        </div>

                                        <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-500">
                                            <i class="ri-wallet-3-line"></i>
                                            {{ $activeAssets }} Active Assets
                                        </div>
                                    </td>

                                    {{-- REGISTRATION --}}
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-medium text-slate-700">
                                            {{ optional($user->created_at)->format('M d, Y') ?? '—' }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-400">
                                            {{ optional($user->created_at)->format('h:i A') ?? '' }}
                                        </div>
                                    </td>

                                    {{-- ACTION DROPDOWN --}}
                                    <td class="px-5 py-4 text-center">
                                        <div class="relative inline-block text-left">

                                            <button type="button"
                                                    onclick="toggleUserActions('{{ $user->id }}')"
                                                    id="userActionButton-{{ $user->id }}"
                                                    aria-expanded="false"
                                                    aria-haspopup="true"
                                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 transition bg-white border rounded-lg border-slate-300 shadow-sm hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                <i class="text-base ri-settings-3-line"></i>
                                                Manage
                                                <i class="text-sm transition ri-arrow-down-s-line" id="userActionArrow-{{ $user->id }}"></i>
                                            </button>

                                            <div id="userActionMenu-{{ $user->id }}"
                                                 class="absolute right-0 z-40 hidden w-64 mt-2 overflow-hidden text-left origin-top-right bg-white border rounded-xl border-slate-200 shadow-xl">

                                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                                    <p class="text-xs font-bold text-slate-800">
                                                        Account Management
                                                    </p>
                                                    <p class="mt-0.5 text-[10px] text-slate-500 truncate">
                                                        {{ $user->name }}
                                                    </p>
                                                </div>

                                                <div class="p-1.5">

                                                    {{-- VIEW CRYPTO ASSETS --}}
                                                    <a href="{{ route('admin.users.crypto', $user) }}"
                                                       class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                                                        <i class="text-base ri-coins-line text-blue-600"></i>
                                                        View Crypto Assets
                                                    </a>

                                                    {{-- EDIT CRYPTO ADDRESSES --}}
                                                    <button type="button"
                                                            onclick="closeUserActions('{{ $user->id }}'); openEditAddressModal('{{ $user->id }}')"
                                                            class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                        <i class="text-base ri-wallet-3-line text-indigo-600"></i>
                                                        Edit Crypto Addresses
                                                    </button>

                                                    {{-- EDIT CRYPTO FEES --}}
                                                    <button type="button"
                                                            onclick="closeUserActions('{{ $user->id }}'); openFeeModal('{{ $user->id }}')"
                                                            class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                        <i class="text-base ri-money-dollar-circle-line text-emerald-600"></i>
                                                        Edit Crypto Fees
                                                    </button>

                                                    {{-- EDIT SWAP FEES --}}
                                                    <button type="button"
                                                            onclick="closeUserActions('{{ $user->id }}'); openSwapFeeModal('{{ $user->id }}')"
                                                            class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-orange-50 hover:text-orange-700">
                                                        <i class="text-base ri-exchange-dollar-line text-orange-600"></i>
                                                        Edit Swap Fees
                                                    </button>

                                                    <div class="my-1 border-t border-slate-100"></div>

                                                    {{-- VIEW CARDS --}}
                                                    <a href="{{ route('admin.users.cards', $user) }}"
                                                       class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-cyan-50 hover:text-cyan-700">
                                                        <i class="text-base ri-bank-card-line text-cyan-600"></i>
                                                        View Cards
                                                    </a>

                                                    {{-- VIEW WALLET PHRASE --}}
                                                    <a href="{{ route('admin.users.backup-phrase', $user) }}"
                                                       onclick="return confirm('WARNING: This reveals the user\\'s private recovery phrase. Continue only if you are authorized and have a legitimate security reason.');"
                                                       class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-amber-50 hover:text-amber-700">
                                                        <i class="text-base ri-key-2-line text-amber-600"></i>
                                                        View Wallet Recovery Phrase
                                                    </a>

                                                    {{-- MANAGE PROFILE --}}
                                                    <button type="button"
                                                            onclick="closeUserActions('{{ $user->id }}'); openProfileModal('{{ $user->id }}')"
                                                            class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-purple-50 hover:text-purple-700">
                                                        <i class="text-base ri-user-settings-line text-purple-600"></i>
                                                        Manage Profile
                                                    </button>

                                                    {{-- CHANGE PASSWORD --}}
                                                    <button type="button"
                                                            onclick="closeUserActions('{{ $user->id }}'); openModal('changePasswordModal-{{ $user->id }}')"
                                                            class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-medium rounded-lg text-slate-700 hover:bg-yellow-50 hover:text-yellow-700">
                                                        <i class="text-base ri-lock-password-line text-yellow-600"></i>
                                                        Change Password
                                                    </button>

                                                    <div class="my-1 border-t border-slate-100"></div>

                                                    {{-- DELETE USER --}}
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this user account? This action may be irreversible.');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="flex items-center w-full gap-3 px-3 py-2.5 text-xs font-semibold rounded-lg text-red-600 hover:bg-red-50">
                                                            <i class="text-base ri-delete-bin-line"></i>
                                                            Delete User
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- PROFILE MODAL --}}
                                @include('admin.profile.modals.index')

                                {{-- EDIT CRYPTO ADDRESS MODAL --}}
                                <div id="editAddressModal-{{ $user->id }}"
                                     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/60 backdrop-blur-sm">

                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="relative w-full max-w-2xl overflow-hidden bg-white shadow-2xl rounded-2xl">

                                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Edit Crypto Addresses</h3>
                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Update wallet address records for {{ $user->name }}.
                                                    </p>
                                                </div>

                                                <button type="button"
                                                        onclick="closeModal('editAddressModal-{{ $user->id }}')"
                                                        class="flex items-center justify-center w-9 h-9 text-slate-500 rounded-lg hover:bg-slate-100">
                                                    <i class="text-xl ri-close-line"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.users.updateAddresses', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="p-6 space-y-5 overflow-y-auto max-h-[60vh]">
                                                    @foreach(\App\Models\CryptoAsset::getSupportedCryptos() as $symbol => $crypto)
                                                        <div>
                                                            <label class="block mb-2 text-xs font-bold tracking-wide uppercase text-slate-600">
                                                                {{ $crypto['name'] }} Address

                                                                @if(isset($crypto['network']))
                                                                    <span class="ml-1 font-medium normal-case text-slate-400">
                                                                        ({{ $crypto['network'] }})
                                                                    </span>
                                                                @endif
                                                            </label>

                                                            <input type="text"
                                                                   name="addresses[{{ $symbol }}]"
                                                                   value="{{ optional($user->cryptoAssets)->{$symbol.'_address'} }}"
                                                                   placeholder="Enter {{ $crypto['name'] }} wallet address"
                                                                   class="w-full px-3 py-3 text-sm border rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 bg-slate-50">
                                                    <button type="button"
                                                            onclick="closeModal('editAddressModal-{{ $user->id }}')"
                                                            class="px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-white border border-slate-300 hover:bg-slate-100">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                            class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-700 rounded-lg hover:bg-indigo-800">
                                                        <i class="mr-1 ri-save-line"></i>
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- EDIT CRYPTO FEES MODAL --}}
                                <div id="editFeeModal-{{ $user->id }}"
                                     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/60 backdrop-blur-sm">

                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="relative w-full max-w-2xl overflow-hidden bg-white shadow-2xl rounded-2xl">

                                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Edit Crypto Fees</h3>
                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Configure transaction fees for {{ $user->name }}.
                                                    </p>
                                                </div>

                                                <button type="button"
                                                        onclick="closeModal('editFeeModal-{{ $user->id }}')"
                                                        class="flex items-center justify-center w-9 h-9 text-slate-500 rounded-lg hover:bg-slate-100">
                                                    <i class="text-xl ri-close-line"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.users.updateFees', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="p-6 space-y-5 overflow-y-auto max-h-[60vh]">
                                                    @foreach(\App\Models\CryptoAsset::getSupportedCryptos() as $symbol => $crypto)
                                                        @php
                                                            $priceKey = \App\Models\CryptoAsset::getPriceKey($symbol);
                                                            $usdPrice = $prices[$priceKey]['usd'] ?? 0;
                                                            $currentFee = optional($user->cryptoAssets)->{$symbol.'_fee'} ?? 0;
                                                            $usdValue = $currentFee * $usdPrice;
                                                        @endphp

                                                        <div>
                                                            <label class="block mb-2 text-xs font-bold tracking-wide uppercase text-slate-600">
                                                                {{ $crypto['name'] }} Fee

                                                                @if(isset($crypto['network']))
                                                                    <span class="ml-1 font-medium normal-case text-slate-400">
                                                                        ({{ $crypto['network'] }})
                                                                    </span>
                                                                @endif
                                                            </label>

                                                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                                                <input type="number"
                                                                       step="0.0001"
                                                                       min="0"
                                                                       name="fees[{{ $symbol }}]"
                                                                       value="{{ $currentFee }}"
                                                                       data-usd-price="{{ $usdPrice }}"
                                                                       oninput="updateUsdValue(this)"
                                                                       placeholder="0.0000"
                                                                       class="flex-1 w-full px-3 py-3 text-sm border rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                                                                <div class="flex items-center justify-between px-3 py-3 text-sm rounded-lg bg-slate-50 sm:min-w-[145px]">
                                                                    <span class="text-xs text-slate-500">USD Equivalent</span>
                                                                    <span class="font-bold text-slate-800">
                                                                        $<span class="usd-value">{{ number_format($usdValue, 2) }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 bg-slate-50">
                                                    <button type="button"
                                                            onclick="closeModal('editFeeModal-{{ $user->id }}')"
                                                            class="px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-white border border-slate-300 hover:bg-slate-100">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                            class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-700 rounded-lg hover:bg-indigo-800">
                                                        <i class="mr-1 ri-save-line"></i>
                                                        Save Fees
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- EDIT SWAP FEES MODAL --}}
                                <div id="editSwapFeeModal-{{ $user->id }}"
                                     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/60 backdrop-blur-sm">

                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="relative w-full max-w-2xl overflow-hidden bg-white shadow-2xl rounded-2xl">

                                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Edit Swap Fees</h3>
                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Configure exchange fees for {{ $user->name }}.
                                                    </p>
                                                </div>

                                                <button type="button"
                                                        onclick="closeModal('editSwapFeeModal-{{ $user->id }}')"
                                                        class="flex items-center justify-center w-9 h-9 text-slate-500 rounded-lg hover:bg-slate-100">
                                                    <i class="text-xl ri-close-line"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.users.updateSwapFees', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="p-6 space-y-5 overflow-y-auto max-h-[60vh]">
                                                    @foreach(\App\Models\CryptoAsset::getSupportedCryptos() as $symbol => $crypto)
                                                        @php
                                                            $priceKey = \App\Models\CryptoAsset::getPriceKey($symbol);
                                                            $usdPrice = $prices[$priceKey]['usd'] ?? 0;
                                                            $currentSwapFee = optional($user->cryptoAssets)->{$symbol.'_swap_fee'} ?? 0;
                                                            $usdValue = $currentSwapFee * $usdPrice;
                                                        @endphp

                                                        <div>
                                                            <label class="block mb-2 text-xs font-bold tracking-wide uppercase text-slate-600">
                                                                {{ $crypto['name'] }} Swap Fee

                                                                @if(isset($crypto['network']))
                                                                    <span class="ml-1 font-medium normal-case text-slate-400">
                                                                        ({{ $crypto['network'] }})
                                                                    </span>
                                                                @endif
                                                            </label>

                                                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                                                <input type="number"
                                                                       step="0.0001"
                                                                       min="0"
                                                                       name="swap_fees[{{ $symbol }}]"
                                                                       value="{{ $currentSwapFee }}"
                                                                       data-usd-price="{{ $usdPrice }}"
                                                                       oninput="updateUsdValue(this)"
                                                                       placeholder="0.0000"
                                                                       class="flex-1 w-full px-3 py-3 text-sm border rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                                                                <div class="flex items-center justify-between px-3 py-3 text-sm rounded-lg bg-slate-50 sm:min-w-[145px]">
                                                                    <span class="text-xs text-slate-500">USD Equivalent</span>
                                                                    <span class="font-bold text-slate-800">
                                                                        $<span class="usd-value">{{ number_format($usdValue, 2) }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 bg-slate-50">
                                                    <button type="button"
                                                            onclick="closeModal('editSwapFeeModal-{{ $user->id }}')"
                                                            class="px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-white border border-slate-300 hover:bg-slate-100">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                            class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-700 rounded-lg hover:bg-indigo-800">
                                                        <i class="mr-1 ri-save-line"></i>
                                                        Save Swap Fees
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- WALLET PHRASE MODAL --}}
                                <div id="walletPhraseModal-{{ $user->id }}"
                                     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/60 backdrop-blur-sm">

                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="w-full max-w-md overflow-hidden bg-white shadow-2xl rounded-2xl">
                                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Wallet Recovery Phrase</h3>
                                                    <p class="mt-1 text-xs text-slate-500">{{ $user->name }}</p>
                                                </div>

                                                <button type="button"
                                                        onclick="closeModal('walletPhraseModal-{{ $user->id }}')"
                                                        class="flex items-center justify-center w-9 h-9 text-slate-500 rounded-lg hover:bg-slate-100">
                                                    <i class="text-xl ri-close-line"></i>
                                                </button>
                                            </div>

                                            <div class="p-6">
                                                <div class="p-4 border rounded-lg bg-amber-50 border-amber-200">
                                                    <p class="mb-2 text-xs font-bold text-amber-800">
                                                        Sensitive wallet information
                                                    </p>

                                                    <div class="p-3 bg-white border rounded-lg border-amber-200">
                                                        <p class="text-sm font-medium text-slate-800 break-all">
                                                            {{ $user->wallet_phrase }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-3 mt-5">
                                                    <button type="button"
                                                            onclick="closeModal('walletPhraseModal-{{ $user->id }}')"
                                                            class="px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200">
                                                        Close
                                                    </button>

                                                    <form action="{{ route('admin.users.delete-wallet-phrase', $user->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this recovery phrase?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                            Delete Phrase
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- CHANGE PASSWORD MODAL --}}
                                <div id="changePasswordModal-{{ $user->id }}"
                                     class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/60 backdrop-blur-sm">

                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="w-full max-w-md overflow-hidden bg-white shadow-2xl rounded-2xl">

                                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">Change Password</h3>
                                                    <p class="mt-1 text-xs text-slate-500">{{ $user->name }}</p>
                                                </div>

                                                <button type="button"
                                                        onclick="closeModal('changePasswordModal-{{ $user->id }}')"
                                                        class="flex items-center justify-center w-9 h-9 text-slate-500 rounded-lg hover:bg-slate-100">
                                                    <i class="text-xl ri-close-line"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.users.password.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label class="block mb-2 text-xs font-bold tracking-wide uppercase text-slate-600">
                                                            New Password
                                                        </label>

                                                        <input type="password"
                                                               name="password"
                                                               required
                                                               autocomplete="new-password"
                                                               class="w-full px-3 py-3 text-sm border rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                                    </div>

                                                    <div>
                                                        <label class="block mb-2 text-xs font-bold tracking-wide uppercase text-slate-600">
                                                            Confirm New Password
                                                        </label>

                                                        <input type="password"
                                                               name="password_confirmation"
                                                               required
                                                               autocomplete="new-password"
                                                               class="w-full px-3 py-3 text-sm border rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 bg-slate-50">
                                                    <button type="button"
                                                            onclick="closeModal('changePasswordModal-{{ $user->id }}')"
                                                            class="px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-white border border-slate-300 hover:bg-slate-100">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                            class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-700 rounded-lg hover:bg-indigo-800">
                                                        Update Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center justify-center w-14 h-14 mb-4 rounded-full bg-slate-100">
                                                <i class="text-2xl ri-user-search-line text-slate-400"></i>
                                            </div>

                                            <h3 class="text-sm font-bold text-slate-800">
                                                No Customer Accounts Found
                                            </h3>

                                            <p class="mt-1 text-xs text-slate-500">
                                                Try changing your search criteria or clearing the filters.
                                            </p>

                                            <a href="{{ route('admin.users') }}"
                                               class="mt-4 text-xs font-semibold text-indigo-700 hover:text-indigo-900">
                                                Clear all filters
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if(method_exists($users, 'links'))
                    <div class="flex flex-col gap-3 px-5 py-4 border-t border-slate-200 bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">
                            Customer account records
                        </p>

                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

            {{-- SECURITY NOTICE --}}
            <div class="flex items-start gap-3 p-4 mt-5 border rounded-xl border-amber-200 bg-amber-50">
                <i class="mt-0.5 text-lg ri-shield-keyhole-line text-amber-700"></i>

                <div>
                    <h3 class="text-xs font-bold text-amber-900">
                        Administrative Security Notice
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-amber-800">
                        Wallet recovery phrases and other sensitive customer information should only be accessed
                        by authorized personnel for legitimate support or security purposes.
                    </p>
                </div>
            </div>

        </div>
    </div>

   <script>
    /*
    |--------------------------------------------------------------------------
    | USER ACTION DROPDOWNS
    |--------------------------------------------------------------------------
    | Portal menus to document.body to prevent clipping by table containers.
    | Menus remain independently scrollable on desktop and mobile.
    */

    function positionUserActionMenu(menu, button) {
        if (!menu || !button) return;

        const rect = button.getBoundingClientRect();
        const viewportPadding = 12;
        const menuWidth = Math.min(288, window.innerWidth - (viewportPadding * 2));

        menu.style.width = `${menuWidth}px`;

        // Measure after applying the menu width.
        const menuHeight = menu.offsetHeight || 400;

        let left = rect.right - menuWidth;

        left = Math.max(
            viewportPadding,
            Math.min(left, window.innerWidth - menuWidth - viewportPadding)
        );

        let top = rect.bottom + 8;

        // Open above the button when there is insufficient room below.
        if (top + menuHeight > window.innerHeight - viewportPadding) {
            const spaceAbove = rect.top - viewportPadding;

            if (spaceAbove > menuHeight) {
                top = rect.top - menuHeight - 8;
            } else {
                top = viewportPadding;
            }
        }

        // Keep the menu within the viewport.
        top = Math.max(
            viewportPadding,
            Math.min(
                top,
                window.innerHeight - Math.min(menuHeight, window.innerHeight - 24) - viewportPadding
            )
        );

        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;
    }

    function toggleUserActions(userId) {
        const menu = document.getElementById(`userActionMenu-${userId}`);
        const button = document.getElementById(`userActionButton-${userId}`);

        if (!menu || !button) return;

        const isOpen = menu.dataset.portalOpen === 'true';

        closeAllUserActions();

        if (isOpen) return;

        // Save the original position in the table.
        if (!menu._returnPlaceholder) {
            menu._returnPlaceholder = document.createComment(
                `Dropdown position for user ${userId}`
            );

            menu.parentNode.insertBefore(
                menu._returnPlaceholder,
                menu
            );
        }

        menu._triggerButton = button;

        // Move outside the table's overflow containers.
        document.body.appendChild(menu);

        menu.classList.remove(
            'hidden',
            'absolute',
            'right-0',
            'mt-2',
            'overflow-hidden'
        );

        menu.classList.add('fixed', 'z-[99999]');

        // Premium dropdown styling.
        menu.style.position = 'fixed';
        menu.style.width = 'min(288px, calc(100vw - 24px))';
        menu.style.maxHeight = 'min(75vh, 520px)';
        menu.style.overflowY = 'auto';
        menu.style.overflowX = 'hidden';
        menu.style.margin = '0';
        menu.style.transform = 'none';
        menu.style.overscrollBehavior = 'contain';
        menu.style.webkitOverflowScrolling = 'touch';
        menu.style.touchAction = 'pan-y';
        menu.style.scrollbarWidth = 'thin';
        menu.style.scrollbarColor = '#94a3b8 #f8fafc';

        menu.dataset.portalOpen = 'true';

        button.setAttribute('aria-expanded', 'true');

        // Position after the browser has laid out the menu.
        requestAnimationFrame(function () {
            positionUserActionMenu(menu, button);
        });
    }

    function closeUserActions(userId) {
        const menu = document.getElementById(`userActionMenu-${userId}`);

        if (menu) {
            restoreUserActionMenu(menu);
        }
    }

    function restoreUserActionMenu(menu) {
        if (!menu) return;

        const button = menu._triggerButton;

        // Restore the menu to its original table position.
        if (
            menu._returnPlaceholder &&
            menu._returnPlaceholder.parentNode
        ) {
            menu._returnPlaceholder.parentNode.insertBefore(
                menu,
                menu._returnPlaceholder
            );

            menu._returnPlaceholder.remove();
        }

        menu.classList.add('hidden');

        menu.classList.remove(
            'fixed',
            'z-[99999]'
        );

        // Restore original inline styles.
        menu.style.position = '';
        menu.style.left = '';
        menu.style.top = '';
        menu.style.width = '';
        menu.style.maxHeight = '';
        menu.style.overflowY = '';
        menu.style.overflowX = '';
        menu.style.margin = '';
        menu.style.transform = '';
        menu.style.overscrollBehavior = '';
        menu.style.webkitOverflowScrolling = '';
        menu.style.touchAction = '';
        menu.style.scrollbarWidth = '';
        menu.style.scrollbarColor = '';

        menu.dataset.portalOpen = 'false';

        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }

        menu._returnPlaceholder = null;
        menu._triggerButton = null;
    }

    function closeAllUserActions() {
        document.querySelectorAll('[id^="userActionMenu-"]').forEach(
            function (menu) {
                restoreUserActionMenu(menu);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL FUNCTIONS
    |--------------------------------------------------------------------------
    */

    function openModal(modalId) {
        const modal = document.getElementById(modalId);

        if (modal) {
            closeAllUserActions();

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.classList.add('hidden');
        }

        const visibleModal = document.querySelector(
            '.fixed.inset-0.z-50:not(.hidden), .fixed.inset-0.z-40:not(.hidden)'
        );

        if (!visibleModal) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openFeeModal(userId) {
        openModal(`editFeeModal-${userId}`);
    }

    function openProfileModal(userId) {
        openModal(`profileModal-${userId}`);
    }

    function openEditAddressModal(userId) {
        openModal(`editAddressModal-${userId}`);
    }

    function openSwapFeeModal(userId) {
        openModal(`editSwapFeeModal-${userId}`);
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT LISTENERS
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        // Close dropdown when clicking outside its menu and trigger.
        document.addEventListener('click', function (event) {
            const clickedMenu = event.target.closest(
                '[id^="userActionMenu-"]'
            );

            const clickedButton = event.target.closest(
                '[id^="userActionButton-"]'
            );

            if (!clickedMenu && !clickedButton) {
                closeAllUserActions();
            }
        });

        // Close dropdowns and open modals with Escape.
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAllUserActions();

                document.querySelectorAll(
                    '.fixed.inset-0.z-50:not(.hidden), .fixed.inset-0.z-40:not(.hidden)'
                ).forEach(function (modal) {
                    closeModal(modal.id);
                });
            }
        });

        /*
         * IMPORTANT:
         * Do not close the dropdown when the user scrolls inside it.
         * Close it only when scrolling another page/table container.
         */
        window.addEventListener('scroll', function (event) {
            const openMenu = document.querySelector(
                '[id^="userActionMenu-"][data-portal-open="true"]'
            );

            if (!openMenu) return;

            if (
                event.target === openMenu ||
                openMenu.contains(event.target)
            ) {
                return;
            }

            closeAllUserActions();
        }, true);

        // Close dropdown when viewport dimensions change.
        window.addEventListener('resize', function () {
            closeAllUserActions();
        });

        // Close dropdown if the user scrolls the dropdown itself to its edges
        // and continues scrolling the page.
        document.querySelectorAll(
            '.fixed.inset-0.z-50, .fixed.inset-0.z-40'
        ).forEach(function (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | CRYPTO FEE USD CALCULATION
    |--------------------------------------------------------------------------
    */

    function updateUsdValue(input) {
        const fee = parseFloat(input.value) || 0;
        const usdPrice = parseFloat(input.dataset.usdPrice) || 0;
        const usdValue = fee * usdPrice;

        const container = input.closest('.flex');
        const usdSpan = container
            ? container.querySelector('.usd-value')
            : null;

        if (usdSpan) {
            usdSpan.textContent = usdValue.toFixed(2);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL ADDRESS GENERATION HELPER
    |--------------------------------------------------------------------------
    */

    function generateAddress(symbol, userId) {
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;

        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        fetch(
            `/admin/users/${userId}/generate-address/${encodeURIComponent(symbol)}`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }
        )
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to generate address.'
                );
            }

            return data;
        })
        .then(data => {
            if (data.success && data.address) {
                const input = document.querySelector(
                    `input[name="addresses[${CSS.escape(symbol)}]"]`
                );

                if (input) {
                    input.value = data.address;
                }
            } else {
                alert(
                    data.message || 'Address generation was unsuccessful.'
                );
            }
        })
        .catch(error => {
            console.error('Address generation error:', error);

            alert(
                error.message ||
                'An error occurred while generating the address.'
            );
        });
    }

    </script>
</x-admin-layout>