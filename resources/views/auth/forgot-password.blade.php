<x-guest-layout>
    <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-white dark:bg-gray-900 p-4 sm:p-6 md:p-8 flex items-center justify-center">
        
        <!-- Dark Mode Toggle Button - Top Right Corner -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode"
        >
            <!-- Sun icon for dark mode -->
            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <!-- Moon icon for light mode -->
            <svg x-show="!darkMode" class="w-5 h-5 text-blue-500 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Background elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-80 h-80 -top-20 -left-20 bg-blue-50 dark:bg-blue-900/20 rounded-full opacity-60"></div>
            <div class="absolute w-72 h-72 bottom-10 right-10 bg-blue-50 dark:bg-blue-900/20 rounded-full opacity-60"></div>
            <div class="absolute w-64 h-64 top-1/3 right-1/4 bg-blue-50 dark:bg-blue-900/20 rounded-full opacity-60"></div>
        </div>

        <div class="w-full max-w-md animate-fadeIn">
            <!-- Card Container -->
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                <!-- Content -->
                <div class="relative">
                    <!-- Header with icon -->
                    <div class="text-center mb-8 animate-slideDown">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Reset Password') }}</h1>
                    </div>

                    <!-- Description -->
                    <div class="mb-8 text-center animate-slideUp" style="animation-delay: 100ms;">
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('Forgot your password? No problem. Just let us know your UUID and we will send you a password reset link.') }}
                        </p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-8 animate-slideUp" style="animation-delay: 150ms;">
                            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            {{ session('status') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6 animate-slideUp" style="animation-delay: 200ms;">
                        @csrf

                        <!-- UUID Field -->
                        <div class="space-y-2">
                            <x-input-label 
                                for="uuid" 
                                :value="__('UUID')" 
                                class="text-sm font-medium text-gray-700 dark:text-gray-300" 
                            />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input 
                                    id="uuid"
                                    type="text"
                                    name="uuid"
                                    value="{{ old('uuid') }}"
                                    required
                                    autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-gray-100 text-sm transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500 group-hover:border-blue-300 dark:group-hover:border-blue-400"
                                    placeholder="Enter your UUID"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center transition-opacity duration-300 opacity-0 group-focus-within:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('uuid')" class="mt-1" />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full px-4 py-3 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 group"
                        >
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                {{ __('Send Reset Link') }}
                            </span>
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <div class="mt-8 text-center animate-slideUp" style="animation-delay: 300ms;">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-300 hover:underline group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to login') }}
                        </a>
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
        
        /* Dark mode transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Fix for Alpine.js x-show initial flash */
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>