<x-user-layout :title="__('Settings')">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">{{ __('Settings') }}</h1>
            <div class="w-8"></div>
        </div>

        <!-- Main Settings -->
        <div class="rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
            <a href="{{ route('crypto.manage') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-layer-group text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ __('Manage Crypto') }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
            </a>
            <a href="{{ route('crypto.address') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-asterisk text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ __('Crypto Address') }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
            </a>
            <a href="{{ route('referral.index') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-users text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ __('Referrals') }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
            </a>
            <a href="{{ route('notifications') }}" class="relative flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-bell text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ __('Notifications') }}</span>
                </div>
                <div class="flex items-center">
                    <span id="settings-notification-badge" class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center mr-2 hidden">
                        0
                    </span>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                </div>
            </a>
            {{-- Language Settings --}}
            <a href="{{ route('language.settings') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-language text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ __('Language') }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
            </a>
        </div>

        <form method="POST" action="{{ route('currency.update') }}" class="mt-6 rounded-xl border border-gray-100 p-4 dark:border-gray-700">
            @csrf
            @method('PUT')
            <label for="preferredCurrency" class="mb-2 block font-medium text-gray-900 dark:text-white">
                {{ __('Preferred currency') }}
            </label>
            <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Fiat values are display estimates. Crypto transfers remain denominated in the selected cryptocurrency.') }}
            </p>
            <div class="flex flex-col gap-3 sm:flex-row">
                <select id="preferredCurrency" name="currency" class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    @foreach(config('currencies', []) as $code => $name)
                        <option value="{{ $code }}" {{ (Auth::user()->preferred_currency ?? 'USD') === $code ? 'selected' : '' }}>
                            {{ $code }} — {{ $name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                    {{ __('Save') }}
                </button>
            </div>
            @error('currency')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </form>
        @if(session('status'))
            <p role="status" class="mt-3 text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </p>
        @endif

        <!-- Security Section -->
        <div class="mt-6">
            <h2 class="text-sm text-gray-500 dark:text-gray-400 px-1 mb-3">{{ __('SECURITY') }}</h2>
            <div class="rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
                <a href="{{ route('settings') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-user-check text-blue-500 dark:text-blue-400"></i>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ __('Account Verification') }}</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                </a>
                <a href="{{ route('backup.phrase') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-key text-blue-500 dark:text-blue-400"></i>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ __('Backup Phrase') }}</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-lock text-blue-500 dark:text-blue-400"></i>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ __('Password Settings') }}</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500"></i>
                </a>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl font-medium text-red-500 dark:text-red-400 flex items-center justify-center transition-colors">
                    <i class="fas fa-power-off mr-2"></i>
                    <span>{{ __('Logout') }}</span>
                </button>
            </form>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateSettingsNotificationBadge() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('settings-notification-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }

        // Initial load
        updateSettingsNotificationBadge();

        // Periodic updates every 5 minutes
        setInterval(updateSettingsNotificationBadge, 5 * 60 * 1000);
    });
    </script>
</x-user-layout>