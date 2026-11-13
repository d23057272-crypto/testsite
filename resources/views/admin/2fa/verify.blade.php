<x-guest-layout>
    <div class="min-h-screen w-full bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4 sm:p-6 md:p-8 flex items-center justify-center">
        <!-- Additional floating elements with animations -->
        <div class="fixed top-20 left-20 w-24 h-24 bg-yellow-400/20 rounded-full animate-pulse"></div>
        <div class="fixed bottom-20 right-20 w-16 h-16 bg-yellow-500/20 rounded-full animate-bounce delay-300"></div>
        <div class="fixed top-1/3 right-1/4 w-12 h-12 bg-yellow-300/20 rounded-full animate-ping"></div>

        <div class="w-full max-w-md animate-fadeIn">
            <!-- Card Container -->
            <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
                <!-- Decorative Elements -->
                <div class="absolute -top-8 -right-8 w-32 h-32 bg-yellow-400/20 dark:bg-yellow-600/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-yellow-500/20 dark:bg-yellow-500/10 rounded-full blur-3xl animate-pulse delay-150"></div>
                
                <!-- Content -->
                <div class="relative">
                    <!-- Header with icon -->
                    <div class="text-center mb-8 animate-slideDown">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Two-Factor Authentication</h1>
                        <p class="text-gray-600 dark:text-gray-400">Enter the code from your authenticator app</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- 2FA Verification Form -->
                    <form method="POST" action="{{ route('admin.2fa.validate') }}" class="space-y-5">
                        @csrf

                        <!-- Code Field -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 100ms;">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    id="code"
                                    type="text"
                                    name="code"
                                    required
                                    autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-600 focus:border-transparent dark:text-gray-200 text-sm transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500 group-hover:border-yellow-500 dark:group-hover:border-yellow-600"
                                    placeholder="Enter your 6-digit code"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('code')" class="mt-1" />
                        </div>

                        <!-- Verify Button -->
                        <button
                            type="submit"
                            class="w-full px-4 py-3 mt-6 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800 animate-slideUp group"
                            style="animation-delay: 200ms;"
                        >
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ __('Verify Code') }}
                            </span>
                        </button>
                    </form>

                    <!-- Recovery Code Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 animate-slideUp" style="animation-delay: 300ms;">
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-4">
                            Lost access to your authenticator app?
                        </p>
                        <form method="POST" action="{{ route('admin.2fa.recovery') }}" class="space-y-5">
                            @csrf
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <input
                                    id="recovery_code"
                                    type="text"
                                    name="recovery_code"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-600 focus:border-transparent dark:text-gray-200 text-sm transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500 group-hover:border-yellow-500 dark:group-hover:border-yellow-600"
                                    placeholder="Enter recovery code"
                                />
                                <x-input-error :messages="$errors->get('recovery_code')" class="mt-1" />
                            </div>
                            <button
                                type="submit"
                                class="w-full px-4 py-3 text-sm font-medium text-yellow-500 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 dark:text-yellow-400 rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800"
                            >
                                {{ __('Use Recovery Code') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
        .animate-slideUp {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }
        .animate-slideDown {
            opacity: 0;
            animation: slideDown 0.5s ease-out forwards;
        }
    </style>
</x-guest-layout>