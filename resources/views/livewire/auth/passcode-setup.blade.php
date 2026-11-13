{{--
    Passcode Setup Component
    Optimized for zero-lag input using Alpine.js local state
    Only syncs to Livewire after all 6 digits are entered

    UPDATED: Fixed mobile/desktop responsive layout
--}}

<div
    x-data="passcodeSetup()"
    x-init="init()"
    class="transition-colors duration-75"
    wire:ignore.self
>
    {{-- Dark Mode Toggle --}}
    <button
        @click="darkMode = !darkMode"
        class="fixed top-4 right-4 z-50 p-2.5 rounded-xl bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm shadow-lg border-2 border-slate-300 dark:border-slate-600 hover:shadow-xl transition-all duration-75 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
        title="Toggle Dark Mode"
        aria-label="Toggle dark mode">
        {{-- Sun Icon (Dark Mode Active) --}}
        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-amber-500 transition-transform hover:rotate-12 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" stroke-width="2">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
        </svg>
        {{-- Moon Icon (Light Mode Active) --}}
        <svg x-show="!darkMode" x-cloak class="w-5 h-5 text-blue-700 transition-transform hover:rotate-12 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" stroke-width="2">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
    </button>

    {{-- ==================== DESKTOP/TABLET VERSION ==================== --}}
    <div class="hidden md:block min-h-screen w-full bg-white dark:bg-slate-950 p-6 transition-colors duration-75">
        {{-- Background Blobs --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-96 h-96 rounded-full bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 -top-48 -left-48 opacity-70 animate-float-slow"></div>
            <div class="absolute w-96 h-96 rounded-full bg-gradient-to-l from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 -bottom-48 -right-48 opacity-70 animate-float-slow-reverse"></div>
            <div class="absolute top-1/4 right-20 w-72 h-72 rounded-full bg-gradient-to-t from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 opacity-70 animate-float-medium"></div>
        </div>

        <div class="max-w-md mx-auto relative z-10 pt-10">
            {{-- Steps Indicator --}}
            <div class="flex justify-center mb-12">
                <div class="flex items-center relative">
                    {{-- Step 1 --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center relative shadow-soft-blue z-10 transition-all duration-75 border-2"
                        :class="step >= 1 ? 'bg-blue-500 border-blue-600 text-white' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300'">
                        <svg x-show="step > 1" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 drop-shadow-sm" viewBox="0 0 20 20" fill="currentColor" stroke-width="2.5">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span x-show="step === 1" class="text-sm font-semibold drop-shadow-sm">1</span>
                        <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-blue-700 dark:text-blue-300 font-bold whitespace-nowrap">Create</span>
                    </div>
                    <div class="w-20 h-1 transition-all duration-75 rounded-full" :class="step >= 2 ? 'bg-gradient-to-r from-blue-500 to-blue-400 dark:from-blue-400 dark:to-blue-500' : 'bg-gradient-to-r from-blue-400 to-blue-200 dark:from-blue-600 dark:to-blue-800'"></div>
                    {{-- Step 2 --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center relative shadow-soft-blue z-10 transition-all duration-75 border-2"
                        :class="step >= 2 ? 'bg-blue-500 border-blue-600 text-white' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300'">
                        <svg x-show="step > 2" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 drop-shadow-sm" viewBox="0 0 20 20" fill="currentColor" stroke-width="2.5">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span x-show="step < 3" class="text-sm font-semibold drop-shadow-sm">2</span>
                        <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-blue-700 dark:text-blue-300 font-bold whitespace-nowrap">Confirm</span>
                    </div>
                    <div class="w-20 h-1 transition-all duration-75 rounded-full" :class="step >= 3 ? 'bg-gradient-to-r from-blue-400 to-blue-200 dark:from-blue-500 dark:to-blue-600' : 'bg-gradient-to-r from-blue-200 to-blue-100 dark:from-blue-800 dark:to-blue-900'"></div>
                    {{-- Step 3 --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10 transition-all duration-75 border-2"
                        :class="step >= 3 ? 'bg-blue-500 border-blue-600 text-white shadow-soft-blue' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300 shadow-soft'">
                        <span class="text-sm font-semibold drop-shadow-sm">3</span>
                        <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-slate-700 dark:text-slate-300 font-bold whitespace-nowrap">Complete</span>
                    </div>
                </div>
            </div>

            {{-- Card Container --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-soft dark:shadow-slate-900/50 p-8 transition-all duration-75 relative border-2 border-slate-200 dark:border-slate-700">

                {{-- Success State (Step 3) --}}
                <div x-show="step === 3" x-cloak class="text-center py-8 animate-fadeIn">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-soft-md border-2 border-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 drop-shadow-sm">Passcode Set!</h1>
                    <p class="text-slate-700 dark:text-slate-200 mb-6 font-medium">Your secure 6-digit passcode has been created successfully.</p>
                    <button @click="$wire.redirectToVerify()" class="w-full py-3 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all duration-75 active:scale-95 shadow-soft-blue border-2 border-blue-700">Verify Passcode</button>
                </div>

                {{-- Form State (Steps 1 & 2) --}}
                <div x-show="step < 3" class="relative">
                    <div class="text-center mb-10 animate-fadeIn">
                        <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 shadow-soft-md border-2 border-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 drop-shadow-sm" x-text="step === 1 ? 'Create Passcode' : 'Confirm Passcode'"></h1>
                        <p class="text-slate-700 dark:text-slate-200 font-medium" x-text="step === 1 ? 'Set up a secure 6-digit passcode' : 'Enter your passcode again to confirm'"></p>
                    </div>

                    <div class="space-y-10">
                        {{-- Dots --}}
                        <div class="flex justify-center space-x-6 mt-4 mb-12">
                            <template x-for="i in 6" :key="i">
                                <div class="w-4 h-4 rounded-full border-2 transition-all duration-75 transform"
                                    :class="(step === 1 ? localPasscode.length : localConfirmPasscode.length) >= i ? 'scale-110 bg-blue-500 border-blue-600 shadow-soft-blue' : 'border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800'"></div>
                            </template>
                        </div>

                        {{-- Error Message --}}
                        <div x-show="error" x-cloak x-transition class="mb-8 text-center text-red-600 dark:text-red-400 text-sm font-medium animate-shake" :class="darkMode ? 'text-red-400' : 'text-red-600'" x-text="error"></div>

                        {{-- Keypad --}}
                        <div class="grid grid-cols-3 gap-6">
                            <template x-for="number in [1,2,3,4,5,6,7,8,9]" :key="number">
                                <button type="button" @click="addDigit(number)"
                                    class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xl font-bold shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 focus:outline-none active:scale-95">
                                    <span class="relative z-10 drop-shadow-sm" x-text="number"></span>
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                                    <div class="absolute inset-0 rounded-full group-active:bg-slate-100 dark:group-active:bg-slate-700 transition-colors duration-75"></div>
                                </button>
                            </template>

                            {{-- Clear (X) Button --}}
                            <button type="button" @click="clearPasscode"
                                class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 focus:outline-none active:scale-95"
                                aria-label="Clear all digits">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-red-500 dark:text-red-400 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-red-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                            </button>

                            {{-- Zero Button --}}
                            <button type="button" @click="addDigit(0)"
                                class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xl font-bold shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 focus:outline-none active:scale-95">
                                <span class="relative z-10 drop-shadow-sm">0</span>
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                            </button>

                            {{-- Delete (Backspace) Button --}}
                            <button type="button" @click="deleteDigit"
                                class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 focus:outline-none active:scale-95"
                                aria-label="Delete last digit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-blue-700 dark:text-blue-300 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/>
                                </svg>
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- END Desktop Card Container --}}
        </div>
        {{-- END Desktop max-w-md container --}}
    </div>
    {{-- END Desktop wrapper --}}

    {{-- ==================== MOBILE VERSION ==================== --}}
    <div class="md:hidden min-h-screen bg-white dark:bg-slate-950 transition-colors duration-75">
        {{-- Background Blobs --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-72 h-72 rounded-full bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 -top-36 -left-36 opacity-70 animate-float-slow"></div>
            <div class="absolute w-72 h-72 rounded-full bg-gradient-to-l from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 -bottom-36 -right-36 opacity-70 animate-float-slow-reverse"></div>
        </div>

        {{-- Mobile Header with Back Button --}}
        <div class="p-6 relative z-10">
            <a href="/" class="group inline-flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 transition-all duration-75 hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-700 dark:text-blue-300 transition-transform duration-75 group-hover:-translate-x-0.5 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </a>
        </div>

        {{-- Mobile Content Container --}}
        <div class="px-6 pb-6 relative z-10">

            {{-- Mobile Steps Indicator --}}
            <div class="flex justify-center mb-8">
                <div class="flex items-center relative">
                    {{-- Mobile Step 1 --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center relative shadow-soft-blue z-10 transition-all duration-75 border-2"
                        :class="step >= 1 ? 'bg-blue-500 border-blue-600 text-white' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300'">
                        <svg x-show="step > 1" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 drop-shadow-sm" viewBox="0 0 20 20" fill="currentColor" stroke-width="2.5">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span x-show="step === 1" class="text-xs font-semibold drop-shadow-sm">1</span>
                        <span class="absolute -top-10 left-1/2 transform -translate-x-1/2 text-xs text-blue-700 dark:text-blue-300 font-bold whitespace-nowrap">Create</span>
                    </div>
                    <div class="w-16 h-1 transition-all duration-75 rounded-full" :class="step >= 2 ? 'bg-gradient-to-r from-blue-500 to-blue-400 dark:from-blue-400 dark:to-blue-500' : 'bg-gradient-to-r from-blue-400 to-blue-200 dark:from-blue-600 dark:to-blue-800'"></div>
                    {{-- Mobile Step 2 --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center relative shadow-soft-blue z-10 transition-all duration-75 border-2"
                        :class="step >= 2 ? 'bg-blue-500 border-blue-600 text-white' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300'">
                        <svg x-show="step > 2" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 drop-shadow-sm" viewBox="0 0 20 20" fill="currentColor" stroke-width="2.5">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span x-show="step < 3" class="text-xs font-semibold drop-shadow-sm">2</span>
                        <span class="absolute -top-10 left-1/2 transform -translate-x-1/2 text-xs text-blue-700 dark:text-blue-300 font-bold whitespace-nowrap">Confirm</span>
                    </div>
                    <div class="w-16 h-1 transition-all duration-75 rounded-full" :class="step >= 3 ? 'bg-gradient-to-r from-blue-400 to-blue-200 dark:from-blue-500 dark:to-blue-600' : 'bg-gradient-to-r from-blue-200 to-blue-100 dark:from-blue-800 dark:to-blue-900'"></div>
                    {{-- Mobile Step 3 --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center relative z-10 transition-all duration-75 border-2"
                        :class="step >= 3 ? 'bg-blue-500 border-blue-600 text-white shadow-soft-blue' : 'bg-blue-100 dark:bg-slate-700 border-blue-300 dark:border-slate-500 text-blue-500 dark:text-blue-300 shadow-soft'">
                        <span class="text-xs font-semibold drop-shadow-sm">3</span>
                        <span class="absolute -top-10 left-1/2 transform -translate-x-1/2 text-xs text-slate-700 dark:text-slate-300 font-bold whitespace-nowrap">Complete</span>
                    </div>
                </div>
            </div>

            {{-- Mobile Success State (Step 3) --}}
            <div x-show="step === 3" x-cloak class="text-center py-8 animate-fadeIn">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-soft-md border-2 border-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 drop-shadow-sm">Passcode Set!</h1>
                <p class="text-slate-700 dark:text-slate-200 mb-6 font-medium">Your secure 6-digit passcode has been created successfully.</p>
                <button @click="$wire.redirectToVerify()" class="w-full py-3 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all duration-75 active:scale-95 shadow-soft-blue border-2 border-blue-700">Verify Passcode</button>
            </div>

            {{-- Mobile Form State (Steps 1 & 2) --}}
            <div x-show="step < 3" class="w-full max-w-xs mx-auto">
                <div class="text-center mb-10 animate-fadeIn">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 shadow-soft-md border-2 border-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 drop-shadow-sm" x-text="step === 1 ? 'Create Passcode' : 'Confirm Passcode'"></h1>
                    <p class="text-sm text-slate-700 dark:text-slate-200 font-medium" x-text="step === 1 ? 'Set up a secure 6-digit passcode' : 'Enter your passcode again to confirm'"></p>
                </div>

                {{-- Mobile Dots --}}
                <div class="flex space-x-6 mt-4 mb-12 animate-fadeIn justify-center">
                    <template x-for="i in 6" :key="i">
                        <div class="w-4 h-4 rounded-full border-2 transition-all duration-75 transform"
                            :class="(step === 1 ? localPasscode.length : localConfirmPasscode.length) >= i ? 'scale-110 bg-blue-500 border-blue-600 shadow-soft-blue' : 'border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800'"></div>
                    </template>
                </div>

                {{-- Mobile Error --}}
                <div x-show="error" x-cloak x-transition class="mb-8 text-center text-red-600 dark:text-red-400 text-sm font-medium animate-shake" :class="darkMode ? 'text-red-400' : 'text-red-600'" x-text="error"></div>

                {{-- Mobile Keypad --}}
                <div class="grid grid-cols-3 gap-6">
                    <template x-for="number in [1,2,3,4,5,6,7,8,9]" :key="number">
                        <button type="button" @click="addDigit(number)"
                            class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xl font-bold shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 active:scale-95 focus:outline-none">
                            <span class="relative z-10 drop-shadow-sm" x-text="number"></span>
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                            <div class="absolute inset-0 rounded-full group-active:bg-slate-100 dark:group-active:bg-slate-700 transition-colors duration-75"></div>
                        </button>
                    </template>

                    {{-- Mobile Clear (X) --}}
                    <button type="button" @click="clearPasscode"
                        class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 active:scale-95 focus:outline-none"
                        aria-label="Clear all digits">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-red-500 dark:text-red-400 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-red-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                    </button>

                    {{-- Mobile Zero --}}
                    <button type="button" @click="addDigit(0)"
                        class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xl font-bold shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 active:scale-95 focus:outline-none">
                        <span class="relative z-10 drop-shadow-sm">0</span>
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                    </button>

                    {{-- Mobile Delete (Backspace) --}}
                    <button type="button" @click="deleteDigit"
                        class="group relative flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-soft dark:shadow-slate-900/30 border-2 border-slate-300 dark:border-slate-600 overflow-hidden transition-all duration-75 transform hover:scale-105 active:scale-95 focus:outline-none"
                        aria-label="Delete last digit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-blue-700 dark:text-blue-300 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-15 group-active:opacity-25 transition-opacity duration-75 rounded-full"></div>
                    </button>
                </div>
            </div>
        </div>
        {{-- END Mobile Content Container --}}
    </div>
    {{-- END Mobile wrapper --}}

    {{-- ==================== ALPINE.JS LOGIC ==================== --}}
    <script>
        function passcodeSetup() {
            return {
                step: @js($step),
                localPasscode: '',
                localConfirmPasscode: '',
                error: @js($error),
                darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
                isProcessing: false,

                init() {
                    this.$watch('darkMode', val => {
                        document.documentElement.classList.toggle('dark', val);
                        localStorage.setItem('darkMode', val);
                    });

                    Livewire.on('passcode-step-changed', (event) => {
                        this.step = event.step;
                        this.localConfirmPasscode = '';
                        this.isProcessing = false;
                    });

                    Livewire.on('passcode-mismatch', () => {
                        this.step = 1;
                        this.localPasscode = '';
                        this.localConfirmPasscode = '';
                        this.error = 'Passcodes do not match. Please try again.';
                        this.isProcessing = false;
                    });

                    Livewire.on('passcode-saved', () => {
                        this.step = 3;
                        this.isProcessing = false;
                    });

                    Livewire.on('passcode-error', (event) => {
                        this.error = event.message;
                        this.isProcessing = false;
                    });

                    Livewire.on('passcode-reset', () => {
                        this.step = 1;
                        this.localPasscode = '';
                        this.localConfirmPasscode = '';
                        this.error = null;
                        this.isProcessing = false;
                    });
                },

                addDigit(num) {
                    if (this.isProcessing) return;

                    if (this.step === 1) {
                        if (this.localPasscode.length >= 6) return;
                        this.localPasscode += num.toString();
                        if (this.localPasscode.length === 6) this.submitPasscode();
                    } else if (this.step === 2) {
                        if (this.localConfirmPasscode.length >= 6) return;
                        this.localConfirmPasscode += num.toString();
                        if (this.localConfirmPasscode.length === 6) this.submitConfirmPasscode();
                    }
                },

                deleteDigit() {
                    if (this.isProcessing) return;
                    if (this.step === 1) this.localPasscode = this.localPasscode.slice(0, -1);
                    else if (this.step === 2) this.localConfirmPasscode = this.localConfirmPasscode.slice(0, -1);
                    if (this.error) { this.error = null; this.$wire.error = null; }
                },

                clearPasscode() {
                    if (this.isProcessing) return;
                    if (this.step === 1) this.localPasscode = '';
                    else if (this.step === 2) this.localConfirmPasscode = '';
                    if (this.error) { this.error = null; this.$wire.error = null; }
                },

                submitPasscode() {
                    if (this.isProcessing) return;
                    this.isProcessing = true;
                    this.$wire.passcode = this.localPasscode;
                    this.$wire.verifyAndSave();
                },

                submitConfirmPasscode() {
                    if (this.isProcessing) return;
                    this.isProcessing = true;
                    this.$wire.confirmPasscode = this.localConfirmPasscode;
                    this.$wire.verifyAndSave();
                },

                reset() {
                    this.step = 1;
                    this.localPasscode = '';
                    this.localConfirmPasscode = '';
                    this.error = null;
                    this.isProcessing = false;
                    this.$wire.resetSetup();
                }
            }
        }
    </script>

    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        @keyframes float-slow { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 20px); } }
        @keyframes float-slow-reverse { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-20px, -20px); } }
        @keyframes float-medium { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-15px, 15px); } }

        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        .animate-shake { animation: shake 0.4s ease-in-out; }
        .animate-float-slow { animation: float-slow 10s ease-in-out infinite; }
        .animate-float-slow-reverse { animation: float-slow-reverse 10s ease-in-out infinite; }
        .animate-float-medium { animation: float-medium 7s ease-in-out infinite; }

        .shadow-soft { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); }
        .shadow-soft-md { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12); }
        .shadow-soft-blue { box-shadow: 0 0 12px rgba(59, 130, 246, 0.4), 0 2px 8px rgba(59, 130, 246, 0.2); }

        [x-cloak] { display: none !important; }

        .text-slate-900 { color: #0f172a !important; }
        .text-slate-600, .text-slate-700 { color: #334155 !important; }
        .text-blue-600 { color: #2563eb !important; }
        .text-blue-700 { color: #1d4ed8 !important; }
        .text-red-600 { color: #dc2626 !important; }
        .text-amber-500 { color: #f59e0b !important; }

        .dark .text-slate-900, .dark .text-white, .dark h1, .dark h2, .dark h3, .dark h4, .dark .font-semibold, .dark .font-bold { color: #ffffff !important; }
        .dark .text-slate-600, .dark .text-slate-700 { color: #e2e8f0 !important; }
        .dark .text-blue-400 { color: #60a5fa !important; }
        .dark .text-blue-300 { color: #93c5fd !important; }
        .dark .text-blue-500 { color: #3b82f6 !important; }
        .dark .text-red-400 { color: #f87171 !important; }
        .dark .text-red-500 { color: #ef4444 !important; }
        .dark .text-amber-500 { color: #fbbf24 !important; }

        .dark .bg-white { background-color: #0f172a !important; }
        .dark .bg-slate-800 { background-color: #1e293b !important; }
        .dark .bg-slate-900 { background-color: #020617 !important; }
        .dark .shadow-soft { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4); }
        .dark .shadow-soft-md { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); }

        svg { filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1)); }
        .dark svg { filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3)); }
        svg[stroke-width] { vector-effect: non-scaling-stroke; }

        button:focus-visible, a:focus-visible { outline: 3px solid #3b82f6; outline-offset: 3px; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }

        @media (pointer: coarse) {
            button { touch-action: manipulation; -webkit-tap-highlight-color: transparent; }
        }

        body { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    </style>
</div>