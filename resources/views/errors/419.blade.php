<!-- resources/views/errors/419.blade.php -->
<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-6 flex items-center justify-center">
        <div class="max-w-2xl w-full">
            <!-- Main Error Container -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transform transition-all hover:scale-105">
                <!-- Top Decorative Bar -->
                <div class="h-2 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>
                
                <!-- Content Section -->
                <div class="p-8">
                    <!-- Timer Animation -->
                    <div class="mb-8 flex justify-center">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center relative overflow-hidden">
                                <!-- Circular Progress Animation -->
                                <svg class="w-full h-full absolute animate-spin-slow" viewBox="0 0 100 100">
                                    <circle 
                                        cx="50" cy="50" r="45" 
                                        stroke-width="8"
                                        stroke="currentColor"
                                        fill="none"
                                        class="text-emerald-200 dark:text-emerald-800"
                                    />
                                    <path
                                        d="M 50,50 m -45,0 a 45,45 0 1,1 90,0"
                                        stroke-width="8"
                                        stroke="currentColor"
                                        fill="none"
                                        class="text-emerald-500 dark:text-emerald-400"
                                    />
                                </svg>
                                <!-- Lock Icon -->
                                <div class="z-10 animate-pulse">
                                    <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Key Element -->
                            <div class="absolute -right-4 -bottom-4 transform rotate-45 animate-bounce">
                                <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div class="text-center space-y-4">
                        <h1 class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-teal-500">500</h1>
                        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300">Session Timed Out</h2>
                        <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            Your security token has expired. Don't worry - we're just keeping things safe!
                        </p>
                    </div>

                    <!-- Session Timer -->
                    <div class="mt-8 max-w-md mx-auto">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Session Duration</span>
                                <span class="font-mono">00:00:00</span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="w-0 h-full bg-gradient-to-r from-green-500 to-teal-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                        <button onclick="window.location.reload()" 
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh Session
                        </button>
                        <a href="{{ url('/') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transform transition-all hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Return Home
                        </a>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>Status: SESSION_HIBERNATING</span>
                    <button x-on:click="darkMode = !darkMode" 
                            class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <template x-if="!darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </template>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <style>
        .animate-spin-slow {
            animation: spin 3s linear infinite;
        }
    </style>
</x-guest-layout>