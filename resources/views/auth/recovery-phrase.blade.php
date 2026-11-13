<x-guest-layout>
    <div x-data="{ 
            darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches), 
            copied: false, 
            confirmed: false,
            showPhrase: false,
            words: @js($words)
         }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-gray-50 dark:bg-[#0a0f1a] p-4 sm:p-6 lg:p-8 flex items-center justify-center relative overflow-hidden transition-colors duration-500">

        <!-- Animated Background - Subtle for contrast -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-[500px] h-[500px] -top-40 -left-40 bg-blue-300/10 dark:bg-blue-500/5 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute w-[400px] h-[400px] bottom-0 right-0 bg-purple-300/10 dark:bg-purple-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute w-[300px] h-[300px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-cyan-300/5 dark:bg-cyan-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Dark Mode Toggle - Professional -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-2.5 rounded-xl bg-white/90 dark:bg-[#161b22]/90 backdrop-blur-md shadow-lg border border-gray-200/80 dark:border-[#30363d]/80 hover:shadow-xl transition-all duration-300 group"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode">
            <svg x-show="darkMode" class="w-5 h-5 text-amber-400 transition-transform duration-300 group-hover:rotate-45 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <svg x-show="!darkMode" class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Main Container -->
        <div class="w-full max-w-3xl animate-fadeIn z-10 px-2 sm:px-4">

            <!-- Professional Card -->
            <div class="relative bg-white dark:bg-[#161b22] rounded-2xl shadow-2xl shadow-gray-200/60 dark:shadow-black/60 border border-gray-200/80 dark:border-[#30363d]/80 overflow-hidden transition-all duration-500">

                <!-- Top Gradient Line -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 dark:from-blue-400 dark:via-indigo-400 dark:to-violet-400"></div>

                <div class="p-6 sm:p-8 lg:p-10">

                    <!-- Back Button -->
                    <a href="{{ route('wallet.option') }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-[#8b949e] hover:text-blue-600 dark:hover:text-blue-400 transition-all hover:translate-x-[-4px] mb-6 group">
                        <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-[#21262d] flex items-center justify-center mr-3 group-hover:bg-blue-100 dark:group-hover:bg-[#1c2d4d] transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 dark:text-[#8b949e] group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </span>
                        Back to Options
                    </a>

                    <!-- Header -->
                    <div class="text-center mb-8 sm:mb-10">
                        <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 mb-6 rounded-2xl bg-blue-100 dark:bg-[#1c2d4d] shadow-lg shadow-blue-500/20 dark:shadow-blue-400/10 transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 sm:w-12 sm:h-12 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-[#e6edf3] mb-3 tracking-tight transition-colors duration-500">
                            Secure Your Recovery Phrase
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-[#8b949e] font-medium max-w-lg mx-auto leading-relaxed transition-colors duration-500">
                            This <span class="text-blue-600 dark:text-blue-400 font-bold transition-colors duration-500">12-word phrase</span> is the master key to your wallet. 
                            Write it down and store it offline.
                        </p>
                    </div>

                    <!-- Security Warning - High Visibility -->
                    <div class="mb-6 p-4 bg-amber-50 dark:bg-[#2d1b00] border border-amber-200 dark:border-[#d4a72c]/30 rounded-xl flex items-start gap-3 transition-colors duration-500">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 dark:bg-[#4a3600] flex items-center justify-center border border-amber-200 dark:border-[#d4a72c]/30 transition-colors duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-800 dark:text-amber-300 transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900 dark:text-amber-100 mb-1 transition-colors duration-500">⚠️ Critical Security Notice</h3>
                            <p class="text-xs sm:text-sm text-amber-800 dark:text-amber-100 font-medium leading-relaxed transition-colors duration-500">
                                Never screenshot, email, or store this phrase digitally. Anyone with these words can access your funds permanently.
                            </p>
                        </div>
                    </div>

                    <!-- Recovery Phrase Card -->
                    <div class="relative mb-6">
                        <!-- Blur Overlay (Hidden after reveal) -->
                        <div x-show="!showPhrase" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-100"
                             x-transition:enter-end="opacity-0"
                             class="absolute inset-0 z-10 bg-gray-100/95 dark:bg-[#0d1117]/95 rounded-xl flex flex-col items-center justify-center p-6 backdrop-blur-sm">
                            <div class="w-16 h-16 rounded-xl bg-white dark:bg-[#161b22] shadow-md flex items-center justify-center mb-4 border border-gray-200 dark:border-[#30363d] transition-colors duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-500 dark:text-[#8b949e] transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-[#8b949e] font-medium mb-4 text-center transition-colors duration-500">Tap to reveal your recovery phrase</p>
                            <button @click="showPhrase = true" 
                                    class="px-6 py-2.5 bg-blue-600 dark:bg-blue-500 text-white rounded-lg font-semibold shadow hover:shadow-md transition-all hover:scale-105 active:scale-95 border border-blue-700 dark:border-blue-400">
                                Reveal Phrase
                            </button>
                        </div>

                        <!-- Phrase Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3 p-4 sm:p-6 bg-gray-50 dark:bg-[#0d1117] rounded-xl border-2 border-gray-200 dark:border-[#30363d] relative overflow-hidden transition-colors duration-500"
                             :class="!showPhrase ? 'blur-sm select-none' : ''">

                            @foreach($words as $index => $word)
                                <div class="relative group">
                                    <div class="relative bg-white dark:bg-[#161b22] p-3 sm:p-4 rounded-lg border border-gray-200 dark:border-[#30363d] shadow-sm hover:shadow-md transition-all duration-300">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <span class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-blue-100 dark:bg-[#1c2d4d] text-blue-800 dark:text-blue-300 text-xs font-bold flex items-center justify-center border border-blue-200 dark:border-blue-500/30 transition-colors duration-500">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="font-bold text-gray-900 dark:text-[#e6edf3] text-sm sm:text-base tracking-wide font-mono transition-colors duration-500">{{ $word }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 mb-6" x-show="showPhrase" x-transition>
                        <!-- Copy Button -->
                        <button type="button" 
                                @click="navigator.clipboard.writeText(words.join(' ')); copied = true; setTimeout(() => copied = false, 2000)"
                                class="flex-1 flex items-center justify-center gap-2 py-3 px-6 border-2 border-blue-200 dark:border-blue-500/30 text-sm font-semibold rounded-lg text-blue-800 dark:text-blue-300 bg-blue-50 dark:bg-[#1c2d4d] hover:bg-blue-100 dark:hover:bg-[#233557] transition-all active:scale-[0.98] duration-300">
                            <svg x-show="!copied" class="w-5 h-5 text-blue-600 dark:text-blue-400 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg>
                            <svg x-show="copied" class="w-5 h-5 text-green-600 dark:text-green-400 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy Phrase'" class="transition-colors duration-500"></span>
                        </button>

                        <!-- Download Button -->
                        <button type="button" 
                                @click="const blob = new Blob([words.join(' ')], {type: 'text/plain'}); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'recovery-phrase.txt'; a.click(); URL.revokeObjectURL(url);"
                                class="flex-1 flex items-center justify-center gap-2 py-3 px-6 border-2 border-gray-200 dark:border-[#30363d] text-sm font-semibold rounded-lg text-gray-700 dark:text-[#c9d1d9] bg-gray-50 dark:bg-[#161b22] hover:bg-gray-100 dark:hover:bg-[#21262d] transition-all active:scale-[0.98] duration-300">
                            <svg class="w-5 h-5 text-gray-500 dark:text-[#8b949e] transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span class="transition-colors duration-500">Download</span>
                        </button>
                    </div>

                    <!-- Confirmation Form -->
                    <form method="POST" action="{{ route('recovery.phrase.store') }}" class="mt-6 pt-6 border-t border-gray-200 dark:border-[#30363d] transition-colors duration-500">
                        @csrf

                        <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 mb-6"
                               :class="confirmed ? 'bg-green-50 dark:bg-[#0d2818] border-green-400 dark:border-green-600' : 'bg-gray-50 dark:bg-[#0d1117] border-gray-200 dark:border-[#30363d] hover:border-blue-400 dark:hover:border-blue-500'">
                            <div class="relative flex items-center">
                                <input type="checkbox" id="confirmation" name="confirmation" x-model="confirmed" class="peer sr-only">
                                <div class="w-6 h-6 border-2 rounded-md transition-all duration-200 flex items-center justify-center"
                                     :class="confirmed ? 'bg-green-600 border-green-600' : 'border-gray-300 dark:border-[#484f58] bg-white dark:bg-[#161b22]'">
                                    <svg x-show="confirmed" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="block font-bold text-gray-900 dark:text-[#e6edf3] mb-1 transition-colors duration-500">I have secured my recovery phrase</span>
                                <span class="text-xs sm:text-sm text-gray-600 dark:text-[#8b949e] font-medium transition-colors duration-500">I understand that losing this phrase means losing access to my wallet permanently</span>
                            </div>
                        </label>

                        <button type="submit"
                                x-bind:disabled="!confirmed || !showPhrase"
                                class="w-full py-4 px-6 text-sm sm:text-base font-bold text-white rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 dark:focus:ring-offset-[#161b22] relative overflow-hidden group"
                                :class="confirmed && showPhrase ? 'bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 shadow-lg hover:shadow-xl' : 'bg-gray-300 dark:bg-[#30363d] cursor-not-allowed'">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <span>Continue to Passcode Setup</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer Note -->
            <p class="text-center text-xs text-gray-500 dark:text-[#6e7681] font-medium mt-6 transition-colors duration-500">
                Secured with industry-standard encryption • Never share your phrase
            </p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Professional, fast transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        [x-cloak] { display: none !important; }

        /* =========================
           LIGHT MODE (default)
           ========================= */
        html {
            color-scheme: light;
        }

        body {
            background-color: #f9fafb;
            color: #111827;
        }

        /* Text hierarchy - light mode */
        .text-gray-900 {
            color: #111827 !important;
        }

        .text-gray-700 {
            color: #374151 !important;
        }

        .text-gray-600 {
            color: #4b5563 !important;
        }

        .text-gray-500 {
            color: #6b7280 !important;
        }

        .text-gray-400 {
            color: #9ca3af !important;
        }

        /* Accent text - light mode */
        .text-blue-600 {
            color: #2563eb !important;
        }

        .text-blue-700 {
            color: #1d4ed8 !important;
        }

        .text-blue-800 {
            color: #1e40af !important;
        }

        .text-indigo-600 {
            color: #4f46e5 !important;
        }

        .text-green-600 {
            color: #16a34a !important;
        }

        .text-amber-800 {
            color: #92400e !important;
        }

        .text-amber-900 {
            color: #78350f !important;
        }

        /* Card backgrounds - light mode */
        .bg-white {
            background-color: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .bg-gray-50 {
            background-color: #f9fafb !important;
        }

        .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }

        /* Icon containers - light mode */
        .bg-blue-100 {
            background-color: #dbeafe !important;
        }

        .bg-amber-100 {
            background-color: #fef3c7 !important;
        }

        .bg-green-50 {
            background-color: #f0fdf4 !important;
        }

        /* Borders - light mode */
        .border-gray-200 {
            border-color: #e5e7eb !important;
        }

        .border-gray-300 {
            border-color: #d1d5db !important;
        }

        .border-blue-200 {
            border-color: #bfdbfe !important;
        }

        .border-blue-300 {
            border-color: #93c5fd !important;
        }

        .border-amber-200 {
            border-color: #fde68a !important;
        }

        .border-amber-300 {
            border-color: #fcd34d !important;
        }

        .border-green-300 {
            border-color: #86efac !important;
        }

        .border-green-400 {
            border-color: #4ade80 !important;
        }

        /* Shadows - light mode */
        .shadow-xl {
            box-shadow: 0 8px 30px rgba(17, 24, 39, 0.06), 0 2px 8px rgba(17, 24, 39, 0.04) !important;
        }

        .shadow-lg {
            box-shadow: 0 6px 20px rgba(17, 24, 39, 0.08) !important;
        }

        /* =========================
           DARK MODE
           ========================= */
        .dark {
            color-scheme: dark;
        }

        .dark body,
        .dark {
            background-color: #0a0f1a !important;
            color: #e6edf3 !important;
        }

        /* Background gradient - dark mode */
        .dark .dark\:bg-black {
            background-color: #0a0f1a !important;
        }

        /* Main card - dark mode */
        .dark .bg-white,
        .dark .bg-gray-950 {
            background-color: rgba(22, 27, 34, 0.95) !important;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-color: rgba(48, 54, 61, 0.9) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.03) !important;
        }

        /* Secondary backgrounds - dark mode */
        .dark .bg-gray-50 {
            background-color: #0d1117 !important;
        }

        .dark .bg-gray-100 {
            background-color: #161b22 !important;
        }

        .dark .bg-gray-800 {
            background-color: #21262d !important;
        }

        .dark .bg-gray-900 {
            background-color: #0d1117 !important;
        }

        /* Icon containers - dark mode */
        .dark .bg-blue-100 {
            background-color: #1c2d4d !important;
        }

        .dark .bg-amber-100 {
            background-color: #4a3600 !important;
        }

        .dark .bg-green-50 {
            background-color: #0d2818 !important;
        }

        /* Text hierarchy - dark mode (HIGH VISIBILITY) */
        .dark .text-gray-900,
        .dark .text-white {
            color: #e6edf3 !important;
        }

        .dark .text-gray-700 {
            color: #c9d1d9 !important;
        }

        .dark .text-gray-600 {
            color: #8b949e !important;
        }

        .dark .text-gray-500,
        .dark .text-gray-400 {
            color: #6e7681 !important;
        }

        /* Accent text - dark mode */
        .dark .text-blue-600 {
            color: #58a6ff !important;
        }

        .dark .text-blue-700 {
            color: #79c0ff !important;
        }

        .dark .text-blue-800 {
            color: #93c5fd !important;
        }

        .dark .text-indigo-600 {
            color: #818cf8 !important;
        }

        .dark .text-green-600 {
            color: #4ade80 !important;
        }

        .dark .text-amber-800,
        .dark .text-amber-900 {
            color: #f2cc60 !important;
        }

        .dark .text-amber-600 {
            color: #fbbf24 !important;
        }

        /* Borders - dark mode */
        .dark .border-gray-200,
        .dark .border-gray-700 {
            border-color: rgba(48, 54, 61, 0.9) !important;
        }

        .dark .border-gray-300 {
            border-color: #484f58 !important;
        }

        .dark .border-gray-800 {
            border-color: #30363d !important;
        }

        .dark .border-blue-200,
        .dark .border-blue-300 {
            border-color: rgba(31, 111, 235, 0.4) !important;
        }

        .dark .border-amber-200,
        .dark .border-amber-300 {
            border-color: rgba(212, 167, 44, 0.3) !important;
        }

        .dark .border-green-300,
        .dark .border-green-400 {
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        /* Recovery phrase words - maximum contrast */
        .dark .font-mono {
            color: #e6edf3 !important;
            font-weight: 700 !important;
        }

        /* Badge numbers */
        .dark .bg-blue-900\/50 {
            background-color: rgba(28, 45, 77, 0.8) !important;
        }

        .dark .text-blue-200 {
            color: #93c5fd !important;
        }

        /* Hover effects - dark mode */
        .dark .hover\:bg-blue-100:hover {
            background-color: #1c2d4d !important;
        }

        .dark .hover\:bg-gray-100:hover {
            background-color: #21262d !important;
        }

        .dark .hover\:text-blue-600:hover {
            color: #58a6ff !important;
        }

        .dark .hover\:border-blue-400:hover {
            border-color: #58a6ff !important;
        }

        /* Focus ring offset - dark mode */
        .dark .focus\:ring-offset-gray-950:focus {
            --tw-ring-offset-color: #161b22;
        }

        /* Gradient accent line - dark mode */
        .dark .from-blue-500,
        .dark .via-purple-500,
        .dark .to-pink-500 {
            --tw-gradient-from: #58a6ff;
            --tw-gradient-to: #a78bfa;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }

        /* Professional scrollbar - dark mode */
        .dark ::-webkit-scrollbar {
            width: 8px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #0d1117;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #30363d;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #484f58;
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>