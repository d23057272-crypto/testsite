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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Verify Your Email') }}</h1>
                    </div>

                    <!-- Verification Message -->
                    <div class="mb-8 text-center animate-slideUp" style="animation-delay: 100ms;">
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </p>
                    </div>

                    <!-- Success Message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-8 animate-slideUp" style="animation-delay: 200ms;">
                            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-4 animate-slideUp" style="animation-delay: 300ms;">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-3 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 group">
                                <span class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ __('Resend Verification Email') }}
                                </span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl transition-all duration-300 hover:border-blue-300 dark:hover:border-blue-400 hover:shadow-md group">
                                <span class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </span>
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
        
        /* Dark mode transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Fix for Alpine.js x-show initial flash */
        [x-cloak] { display: none !important; }
    </style>

    <!-- Perfectly Centered Popup -->
    <div 
        id="emailWarningPopup" 
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 p-4"
    >
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="bg-yellow-400 dark:bg-yellow-600 px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white text-center">Important Notice</h3>
            </div>
            <div class="p-6 text-gray-700 dark:text-gray-300 text-center">
                Please check your inbox for the verification email.<br>
                If it's not there, kindly check your spam or junk folder.<br>
                If you find it there, mark it as "Not Spam" so you don't miss future important emails.<br>
                Thank you.
            </div>
            <div class="px-6 pb-6 text-center">
                <button 
                    id="gotItButton" 
                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition"
                >
                    Got it
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('emailWarningPopup');
            const gotItBtn = document.getElementById('gotItButton');

            if (popup && gotItBtn) {
                // Show popup after 1 second
                setTimeout(() => {
                    popup.classList.remove('hidden');
                }, 1000);

                // Close popup when "Got it" is clicked
                gotItBtn.addEventListener('click', function () {
                    popup.classList.add('hidden');
                });

                // Optional: Close if user clicks outside the popup box
                popup.addEventListener('click', function (e) {
                    if (e.target === popup) {
                        popup.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>