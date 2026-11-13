<!-- resources/views/errors/404.blade.php -->
<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-6 flex items-center justify-center">
        <div class="max-w-2xl w-full">
            <!-- Main Error Container -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transform transition-all hover:scale-105">
                <!-- Top Decorative Bar -->
                <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                
                <!-- Content Section -->
                <div class="p-8">
                    <!-- Lost Page Animation -->
                    <div class="mb-8 flex justify-center">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <div class="animate-bounce">
                                    <svg class="w-16 h-16 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Decorative Elements -->
                            <div class="absolute -right-3 -bottom-3">
                                <div class="relative animate-pulse">
                                    <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center text-white font-bold text-sm">?</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div class="text-center space-y-4">
                        <h1 class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-500">404</h1>
                        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300">Page Lost in Space</h2>
                        <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            Houston, we have a problem! The page you're looking for has floated away into the digital cosmos.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                        <a href="{{ url('/') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform transition-all hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Return Home
                        </a>
                        <button onclick="window.history.back()" 
                                class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transform transition-all hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Go Back
                        </button>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>Error Code: PAGE_WENT_EXPLORING</span>
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
</x-guest-layout>