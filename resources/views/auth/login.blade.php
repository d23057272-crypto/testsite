<x-guest-layout>
    <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-gradient-to-br from-blue-50 to-white dark:from-gray-950 dark:to-gray-900 p-4 sm:p-6 md:p-8 flex items-center justify-center overflow-hidden">
        
        <!-- Dark Mode Toggle Button - Top Right Corner -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode"
        >
            <!-- Sun icon for dark mode -->
            <svg x-show="darkMode" class="w-5 h-5 text-yellow-300 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <!-- Moon icon for light mode -->
            <svg x-show="!darkMode" class="w-5 h-5 text-gray-800 dark:text-gray-200 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Animated background elements - Reduced opacity for better text contrast -->
        <div class="absolute w-64 h-64 rounded-full bg-blue-300/5 dark:bg-blue-900/10 animate-blob"></div>
        <div class="absolute w-72 h-72 rounded-full bg-blue-400/5 dark:bg-blue-800/10 animate-blob animation-delay-2000"></div>
        <div class="absolute w-80 h-80 rounded-full bg-blue-500/5 dark:bg-blue-700/10 animate-blob animation-delay-4000"></div>
        
        <!-- Floating cryptocurrency icons -->
        <div class="absolute top-1/4 left-1/6 animate-float animation-delay-1000">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center shadow-md">
                <i class="fab fa-bitcoin text-blue-600 dark:text-blue-300"></i>
            </div>
        </div>
        <div class="absolute bottom-1/4 right-1/6 animate-float animation-delay-3000">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center shadow-md">
                <i class="fab fa-ethereum text-blue-600 dark:text-blue-300"></i>
            </div>
        </div>
        <div class="absolute top-1/3 right-1/4 animate-float animation-delay-5000">
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center shadow-md">
                <i class="fas fa-coins text-blue-600 dark:text-blue-300"></i>
            </div>
        </div>

        <div class="relative w-full max-w-md animate-fadeIn z-10">
            <!-- Card Container -->
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-blue-100 dark:hover:shadow-blue-900/30">
                <!-- App Logo and Name -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-6 mx-auto">
                        <div class="relative w-full h-full">
                            <div class="absolute inset-0 bg-blue-500 dark:bg-blue-600 rounded-full animate-pulse opacity-20"></div>
                            <div class="absolute inset-2 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-700 dark:text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 animate-slideDown">Secure Wallet Login</h1>
                    <p class="text-gray-700 dark:text-gray-200 animate-slideDown animation-delay-200 font-medium">Access your cryptocurrency dashboard</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Login Field -->
                    <div class="space-y-2 animate-slideUp animation-delay-400">
                        <x-input-label 
                            for="login" 
                            :value="__('UUID or Email')" 
                            class="text-sm font-semibold text-gray-800 dark:text-gray-100" 
                        />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input 
                                id="login"
                                type="text"
                                name="login"
                                value="{{ old('login') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white font-medium group-hover:border-blue-400 dark:group-hover:border-blue-500"
                                placeholder="Enter your UUID or email"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('login')" class="mt-1" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2 animate-slideUp animation-delay-600">
                        <x-input-label 
                            for="password" 
                            :value="__('Password')" 
                            class="text-sm font-semibold text-gray-800 dark:text-gray-100"
                        />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white font-medium group-hover:border-blue-400 dark:group-hover:border-blue-500"
                                placeholder="••••••••"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-2 animate-slideUp animation-delay-800">
                        <label class="flex items-center group cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-blue-600 dark:text-blue-400 focus:ring-blue-500 transition-all duration-300"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-200 font-medium group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors duration-300">
                                {{ __('Remember me') }}
                            </span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                                class="text-sm font-semibold text-blue-700 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-200 transition-all duration-300 hover:underline">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        class="w-full px-4 py-3 mt-6 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-blue-500 animate-slideUp animation-delay-1000 group relative overflow-hidden"
                    >
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-500 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <span class="relative flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            {{ __('Sign in') }}
                        </span>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-8 text-center animate-slideUp animation-delay-1200">
                    <p class="text-sm text-gray-700 dark:text-gray-200 font-medium">
                        Don't have an account?
                        <a href="{{ route('register') }}" 
                            class="font-bold text-blue-700 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-200 transition-all duration-300 hover:underline ml-1">
                            {{ __('Create account') }}
                        </a>
                    </p>
                </div>
                
                <!-- Security Badge -->
                <div class="mt-8 text-center animate-slideUp animation-delay-1400">
                    <div class="inline-flex items-center px-3 py-1 bg-blue-50 dark:bg-blue-900/30 rounded-full text-xs text-blue-600 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Secure Connection
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
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
        .animate-slideUp { opacity: 0; animation: slideUp 0.5s ease-out forwards; }
        .animate-slideDown { opacity: 0; animation: slideDown 0.5s ease-out forwards; }
        .animate-blob { animation: blob 7s infinite; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-400 { animation-delay: 0.4s; }
        .animation-delay-600 { animation-delay: 0.6s; }
        .animation-delay-800 { animation-delay: 0.8s; }
        .animation-delay-1000 { animation-delay: 1s; }
        .animation-delay-1200 { animation-delay: 1.2s; }
        .animation-delay-1400 { animation-delay: 1.4s; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-3000 { animation-delay: 3s; }
        .animation-delay-4000 { animation-delay: 4s; }
        .animation-delay-5000 { animation-delay: 5s; }

        /* === 🌙 ULTRA-HIGH CONTRAST DARK MODE FIXES === */
        
        /* Force proper color scheme */
        .dark { color-scheme: dark; }
        
        /* PRIMARY TEXT - Maximum contrast in both modes */
        .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6,
        .dark .font-bold, .dark .font-semibold, .dark .font-medium {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Body/paragraph text */
        .dark p, .dark span, .dark label, .dark li {
            color: #e5e7eb !important; /* gray-200 */
        }
        
        /* Secondary/muted text - still readable */
        .dark .text-gray-400, .dark .text-gray-500, .dark .text-gray-600 {
            color: #d1d5db !important; /* gray-300 */
        }
        
        /* Input placeholder text */
        .dark ::placeholder {
            color: #9ca3af !important; /* gray-400 */
            opacity: 1;
        }
        
        /* Links - Bright and hover-visible */
        .dark a:not(button):not([class*="bg-"]) {
            color: #93c5fd !important; /* blue-300 */
        }
        .dark a:not(button):not([class*="bg-"]):hover {
            color: #bfdbfe !important; /* blue-200 */
        }
        
        /* Badge/pill text on colored backgrounds */
        .dark [class*="bg-"].rounded-full,
        .dark .badge, .dark .pill {
            color: #ffffff !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.4);
        }
        
        /* Input fields - ensure text visibility */
        .dark input, .dark textarea, .dark select {
            color: #ffffff !important;
            background-color: #1f2937 !important; /* gray-800 */
        }
        
        /* Card background - solid enough for contrast */
        .dark .bg-white, .dark .bg-gray-50 {
            background-color: #111827 !important; /* gray-900 */
        }
        
        /* Icons - ensure visibility */
        .dark svg, .dark i {
            color: #f3f4f6 !important; /* gray-100 */
        }
        
        /* Error messages - high visibility */
        .dark .text-red-600, .dark .text-red-500 {
            color: #fca5a5 !important; /* red-300 */
            font-weight: 600 !important;
        }
        
        /* Focus states for accessibility */
        .dark :focus-visible {
            outline: 2px solid #60a5fa !important; /* blue-400 */
            outline-offset: 2px;
        }
        
        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Prevent flash of unstyled content */
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>