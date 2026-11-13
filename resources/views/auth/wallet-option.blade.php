<x-guest-layout>
    <div x-data="{ 
            darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
            hoveredOption: null
         }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-[#0a0f1a] dark:via-[#0d1117] dark:to-[#0a0f1a] p-4 sm:p-6 flex items-center justify-center relative transition-colors duration-500">

        <!-- Professional Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-[600px] h-[600px] -top-40 -left-40 bg-blue-500/8 dark:bg-blue-400/5 rounded-full blur-[100px] transition-opacity duration-700"></div>
            <div class="absolute w-[500px] h-[500px] bottom-0 right-0 bg-indigo-500/8 dark:bg-indigo-400/5 rounded-full blur-[100px] transition-opacity duration-700"></div>
            <div class="absolute w-[300px] h-[300px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-violet-500/5 dark:bg-violet-400/3 rounded-full blur-[80px] transition-opacity duration-700"></div>
        </div>

        <!-- Dark Mode Toggle -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-2.5 rounded-xl bg-white/90 dark:bg-[#161b22]/90 backdrop-blur-md shadow-lg border border-slate-200/80 dark:border-[#30363d]/80 hover:shadow-xl hover:border-slate-300 dark:hover:border-[#484f58] transition-all duration-300 group"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode">
            <svg x-show="darkMode" class="w-5 h-5 text-amber-400 transition-transform duration-300 group-hover:rotate-45 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <svg x-show="!darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Compact Professional Container -->
        <div class="w-full max-w-2xl animate-fadeIn z-10 px-2">

            <!-- Elegant Card with Button-like Options -->
            <div class="relative bg-white dark:bg-[#161b22] rounded-2xl shadow-2xl shadow-slate-200/60 dark:shadow-black/60 border border-slate-200/80 dark:border-[#30363d]/80 overflow-hidden transition-all duration-500">

                <!-- Premium Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 dark:from-blue-400 dark:via-indigo-400 dark:to-violet-400"></div>

                <div class="p-6 sm:p-8">

                    <!-- Header -->
                    <div class="text-center mb-7">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-2xl bg-blue-100 dark:bg-[#1c2d4d] shadow-lg shadow-blue-500/20 dark:shadow-blue-400/10 transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                            </svg>
                        </div>

                        <h1 class="text-2xl font-bold text-slate-900 dark:text-[#e6edf3] mb-2 tracking-tight transition-colors duration-500">
                            Set Up Wallet
                        </h1>
                        <p class="text-slate-600 dark:text-[#8b949e] text-sm max-w-sm mx-auto leading-relaxed transition-colors duration-500">
                            Select your setup method. Both provide secure, non-custodial access to your assets.
                        </p>
                    </div>

                    <!-- Button-Style Options - Clear Click Affordance -->
                    <div class="space-y-3 mb-6">

                        <!-- Create New Wallet Button -->
                        <a href="{{ route('recovery.phrase') }}" 
                           @mouseenter="hoveredOption = 'create'"
                           @mouseleave="hoveredOption = null"
                           class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all duration-300 cursor-pointer select-none focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 dark:focus:ring-offset-[#161b22]"
                           :class="hoveredOption === 'create' 
                                ? 'border-blue-500 bg-blue-50/80 dark:bg-[#1c2d4d] shadow-lg shadow-blue-500/15 dark:shadow-blue-400/10' 
                                : 'border-slate-200 dark:border-[#30363d] hover:border-blue-400 dark:hover:border-blue-500 bg-white dark:bg-[#0d1117] hover:shadow-md dark:hover:shadow-black/40'">

                            <!-- Button Icon -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 dark:bg-[#1c2d4d] flex items-center justify-center shadow-sm transition-all duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="font-semibold text-slate-900 dark:text-[#e6edf3] transition-colors duration-500">
                                        Create New Wallet
                                    </h3>

                                </div>
                                <p class="text-xs text-slate-600 dark:text-[#8b949e] transition-colors duration-500">
                                    Generate a new wallet with secure 12-word recovery phrase
                                </p>
                            </div>

                            <!-- Action Arrow - Clear Button Indicator -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 dark:text-[#6e7681] group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <!-- Import Existing Wallet Button -->
                        <a href="{{ route('import.wallet') }}" 
                           @mouseenter="hoveredOption = 'import'"
                           @mouseleave="hoveredOption = null"
                           class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all duration-300 cursor-pointer select-none focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 dark:focus:ring-offset-[#161b22]"
                           :class="hoveredOption === 'import' 
                                ? 'border-indigo-500 bg-indigo-50/80 dark:bg-[#1c1d4d] shadow-lg shadow-indigo-500/15 dark:shadow-indigo-400/10' 
                                : 'border-slate-200 dark:border-[#30363d] hover:border-indigo-400 dark:hover:border-indigo-500 bg-white dark:bg-[#0d1117] hover:shadow-md dark:hover:shadow-black/40'">

                            <!-- Button Icon -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-100 dark:bg-[#1c1d4d] flex items-center justify-center shadow-sm transition-all duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-slate-900 dark:text-[#e6edf3] mb-0.5 transition-colors duration-500">
                                    Import Existing Wallet
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-[#8b949e] transition-colors duration-500">
                                    Restore access using your 12 or 24-word recovery phrase
                                </p>
                            </div>

                            <!-- Action Arrow - Clear Button Indicator -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 dark:text-[#6e7681] group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <!-- Security Notice - High Visibility -->
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-[#2d1b00] border border-amber-200 dark:border-[#d4a72c]/30 transition-colors duration-500">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-amber-100 dark:bg-[#4a3600] flex items-center justify-center transition-colors duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-800 dark:text-amber-300 transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <p class="text-xs text-amber-900 dark:text-amber-100 leading-relaxed transition-colors duration-500">
                                <strong class="font-semibold">Critical:</strong> Your recovery phrase is the only key to your wallet. Store it offline and <strong class="font-semibold">never share it</strong>. Legitimate support will never request it.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Professional Footer -->
            <p class="text-center text-xs text-slate-600 dark:text-slate-300 mt-5 font-medium">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    AES-256 Encrypted
                </span><br>
                <span class="mx-2 text-slate-400 dark:text-slate-500">•</span>
                <span>Non-custodial</span>
                <span class="mx-2 text-slate-400 dark:text-slate-500">•</span>
                <span>Your keys, your crypto</span>
            </p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn { 
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; 
        }

        /* Smooth, performant transitions */
        * {
            transition-property: color, background-color, border-color, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Respect reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* =========================
           LIGHT MODE (default)
           ========================= */
        html {
            color-scheme: light;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Main text hierarchy - light mode */
        .text-slate-900 {
            color: #0f172a !important;
        }

        .text-slate-700 {
            color: #334155 !important;
        }

        .text-slate-600 {
            color: #475569 !important;
        }

        .text-slate-500 {
            color: #64748b !important;
        }

        .text-slate-400 {
            color: #94a3b8 !important;
        }

        .text-white {
            color: #ffffff !important;
        }

        /* Card backgrounds - light mode */
        .bg-white {
            background-color: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        /* Icon container backgrounds - light mode */
        .bg-blue-100 {
            background-color: #dbeafe !important;
        }

        .bg-indigo-100 {
            background-color: #e0e7ff !important;
        }

        .bg-amber-100 {
            background-color: #fef3c7 !important;
        }

        /* Button backgrounds - light mode */
        .bg-slate-800\/60 {
            background-color: rgba(248, 250, 252, 0.9) !important;
        }

        .bg-blue-50\/70 {
            background-color: rgba(239, 246, 255, 0.85) !important;
        }

        .bg-indigo-50\/70 {
            background-color: rgba(238, 242, 255, 0.85) !important;
        }

        .bg-amber-50 {
            background-color: rgba(255, 251, 235, 0.9) !important;
        }

        /* Borders - light mode */
        .border-slate-200 {
            border-color: rgba(226, 232, 240, 0.9) !important;
        }

        .border-slate-700 {
            border-color: rgba(51, 65, 85, 0.5) !important;
        }

        .border-blue-200 {
            border-color: rgba(191, 219, 254, 0.9) !important;
        }

        .border-indigo-200 {
            border-color: rgba(199, 210, 254, 0.9) !important;
        }

        .border-amber-200 {
            border-color: rgba(253, 230, 138, 0.9) !important;
        }

        /* Shadows - light mode */
        .shadow-xl {
            box-shadow:
                0 8px 30px rgba(15, 23, 42, 0.06),
                0 2px 8px rgba(15, 23, 42, 0.04) !important;
        }

        /* Accent text - light mode */
        .text-blue-600 {
            color: #2563eb !important;
        }

        .text-blue-800 {
            color: #1e40af !important;
        }

        .text-indigo-600 {
            color: #4f46e5 !important;
        }

        .text-indigo-800 {
            color: #3730a3 !important;
        }

        .text-amber-800 {
            color: #92400e !important;
        }

        .text-amber-900 {
            color: #78350f !important;
        }

        /* Hover effects - light mode */
        .hover\:border-blue-400:hover {
            border-color: #60a5fa !important;
        }

        .hover\:border-indigo-400:hover {
            border-color: #818cf8 !important;
        }

        /* Button hover micro-interaction */
        .cursor-pointer:hover {
            transform: translateY(-2px);
        }

        /* =========================
           DARK MODE
           ========================= */
        .dark {
            color-scheme: dark;
        }

        .dark body,
        .dark {
            background-color: #020617 !important;
            color: #f8fafc !important;
        }

        /* Background gradient - dark mode */
        .dark .dark\:from-slate-950 {
            background-image: linear-gradient(
                135deg,
                #0a0f1a 0%,
                #0d1117 40%,
                #111827 70%,
                #0a0f1a 100%
            ) !important;
        }

        /* Main card - dark mode */
        .dark .bg-white,
        .dark .bg-slate-900 {
            background-color: rgba(22, 27, 34, 0.95) !important;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-color: rgba(48, 54, 61, 0.9) !important;
            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.03) !important;
        }

        /* Icon container backgrounds - dark mode */
        .dark .bg-blue-100 {
            background-color: rgba(28, 45, 77, 0.9) !important;
        }

        .dark .bg-indigo-100 {
            background-color: rgba(28, 29, 77, 0.9) !important;
        }

        .dark .bg-amber-100 {
            background-color: rgba(74, 54, 0, 0.9) !important;
        }

        /* Button backgrounds - dark mode */
        .dark .bg-slate-800\/60 {
            background-color: rgba(13, 17, 23, 0.9) !important;
        }

        .dark .bg-blue-50\/70 {
            background-color: rgba(28, 45, 77, 0.9) !important;
        }

        .dark .bg-indigo-50\/70 {
            background-color: rgba(28, 29, 77, 0.9) !important;
        }

        .dark .bg-amber-50 {
            background-color: rgba(45, 27, 0, 0.9) !important;
        }

        /* Text hierarchy - dark mode (HIGH VISIBILITY) */
        .dark .text-slate-900,
        .dark .text-white {
            color: #e6edf3 !important;
        }

        .dark .text-slate-700 {
            color: #c9d1d9 !important;
        }

        .dark .text-slate-600 {
            color: #8b949e !important;
        }

        .dark .text-slate-500,
        .dark .text-slate-400 {
            color: #6e7681 !important;
        }

        /* Accent text - dark mode */
        .dark .text-blue-600 {
            color: #58a6ff !important;
        }

        .dark .text-blue-800 {
            color: #79c0ff !important;
        }

        .dark .text-indigo-600 {
            color: #818cf8 !important;
        }

        .dark .text-indigo-800 {
            color: #a5b4fc !important;
        }

        .dark .text-amber-800,
        .dark .text-amber-900 {
            color: #f2cc60 !important;
        }

        /* Borders - dark mode */
        .dark .border-slate-200,
        .dark .border-slate-700 {
            border-color: rgba(48, 54, 61, 0.9) !important;
        }

        .dark .border-blue-200 {
            border-color: rgba(31, 111, 235, 0.5) !important;
        }

        .dark .border-indigo-200 {
            border-color: rgba(99, 102, 241, 0.5) !important;
        }

        .dark .border-amber-200 {
            border-color: rgba(212, 167, 44, 0.4) !important;
        }

        /* Hover borders - dark mode */
        .dark .hover\:border-blue-400:hover {
            border-color: #58a6ff !important;
            box-shadow: 0 0 0 1px rgba(88, 166, 255, 0.15);
        }

        .dark .hover\:border-indigo-400:hover {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.15);
        }

        /* Focus ring offset - dark mode */
        .dark .focus\:ring-offset-slate-900:focus {
            --tw-ring-offset-color: #161b22;
        }

        /* Gradient accent line - dark mode */
        .dark .from-blue-600,
        .dark .via-indigo-600,
        .dark .to-violet-600 {
            --tw-gradient-from: #58a6ff;
            --tw-gradient-to: #818cf8;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }

        /* Prevent Alpine flash */
        [x-cloak] {
            display: none !important;
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