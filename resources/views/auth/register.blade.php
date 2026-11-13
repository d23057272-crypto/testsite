<x-guest-layout>
    <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-gray-50 dark:bg-black p-4 sm:p-6 md:p-8 flex items-center justify-center">
        
        <!-- Dark Mode Toggle Button - Top Right Corner -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-3 rounded-full bg-white dark:bg-gray-900 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-300 dark:border-gray-600"
            title="Toggle Dark Mode"
            aria-label="Toggle dark mode"
        >
            <!-- Sun icon for dark mode -->
            <svg x-show="darkMode" class="w-5 h-5 text-yellow-300 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <!-- Moon icon for light mode -->
            <svg x-show="!darkMode" class="w-5 h-5 text-blue-700 dark:text-blue-300 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Background elements - darker for dark mode -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-80 h-80 -top-20 -left-20 bg-blue-100 dark:bg-blue-950/40 rounded-full opacity-60"></div>
            <div class="absolute w-72 h-72 bottom-10 right-10 bg-blue-100 dark:bg-blue-950/40 rounded-full opacity-60"></div>
            <div class="absolute w-64 h-64 top-1/3 right-1/4 bg-blue-100 dark:bg-blue-950/40 rounded-full opacity-60"></div>
        </div>

        <div class="w-full max-w-xl animate-fadeIn">
            <!-- Card Container - Higher contrast dark background -->
            <div class="relative bg-white dark:bg-gray-950 rounded-xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
                <!-- Content -->
                <div class="relative">
                    <!-- Header with icon -->
                    <div class="text-center mb-8 animate-slideDown">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-blue-100 dark:bg-blue-900/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-700 dark:text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h1>
                        <p class="text-gray-700 dark:text-gray-200 font-medium">Join us today</p>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 100ms;">
                            <x-input-label for="name" :value="__('Name')" class="text-sm font-bold text-gray-900 dark:text-white" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input 
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-semibold transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-500 dark:group-hover:border-blue-400"
                                    placeholder="Full Name"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-700 dark:text-red-300 font-bold text-sm" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 200ms;">
                            <x-input-label for="email" :value="__('Email')" class="text-sm font-bold text-gray-900 dark:text-white" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-semibold transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-500 dark:group-hover:border-blue-400"
                                    placeholder="john@example.com"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-700 dark:text-red-300 font-bold text-sm" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 300ms;">
                            <x-input-label for="password" :value="__('Password')" class="text-sm font-bold text-gray-900 dark:text-white" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input 
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-semibold transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-500 dark:group-hover:border-blue-400"
                                    placeholder="Minimum 8 characters"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-700 dark:text-red-300 font-bold text-sm" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 400ms;">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-bold text-gray-900 dark:text-white" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input 
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-semibold transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-500 dark:group-hover:border-blue-400"
                                    placeholder="Re-type your password"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-700 dark:text-red-300 font-bold text-sm" />
                        </div>
                            
                        <!-- Referral Code -->
                        <div class="space-y-2 animate-slideUp" style="animation-delay: 450ms;">
                            <x-input-label for="referral_code" :value="__('Referral Code (Optional)')" class="text-sm font-bold text-gray-900 dark:text-white" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                @if(!empty($referrer))
                                    <input type="hidden" name="referral_code" value="{{ $referral_code }}" />
                                @endif
                                <input 
                                    id="referral_code"
                                    type="text"
                                    name="{{ empty($referrer) ? 'referral_code' : '' }}"
                                    value="{{ $referral_code }}"
                                    {{ !empty($referrer) ? 'disabled' : '' }}
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-semibold transition-all duration-300 placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-500 dark:group-hover:border-blue-400 disabled:opacity-80 disabled:cursor-not-allowed"
                                    placeholder="Enter a referral code if you were invited by an existing member"
                                />
                            </div>
                            @if(!empty($referrer))
                                <div class="relative group mt-2">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="text"
                                        value="✓ Referred by: {{ $referrer->name }}"
                                        disabled
                                        class="w-full pl-10 pr-4 py-3 bg-green-100 dark:bg-green-900/50 border-2 border-green-300 dark:border-green-600 rounded-xl text-sm font-bold text-green-800 dark:text-green-200 cursor-not-allowed"
                                    />
                                </div>
                            @endif
                            <div id="referral-status" class="mt-2 text-sm font-medium text-gray-800 dark:text-gray-200"></div>
                            <x-input-error :messages="$errors->get('referral_code')" class="mt-1 text-red-700 dark:text-red-300 font-bold text-sm" />
                        </div>

                        <!-- Register Button -->
                        <button
                            type="submit"
                            class="w-full px-4 py-3 mt-6 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 animate-slideUp group shadow-md"
                            style="animation-delay: 500ms;"
                        >
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Create Account') }}
                            </span>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-8 text-center animate-slideUp" style="animation-delay: 800ms;">
                        <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                            Already have an account? 
                            <a href="{{ route('login') }}" 
                               class="font-bold text-blue-800 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-200 transition-all duration-300 hover:underline ml-1">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out; }
        .animate-slideUp { opacity: 0; animation: slideUp 0.5s ease-out forwards; }
        .animate-slideDown { opacity: 0; animation: slideDown 0.5s ease-out forwards; }
        
        * { transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease; }
        [x-cloak] { display: none !important; }
        
        /* === AGGRESSIVE DARK MODE CONTRAST FIXES === */
        .dark {
            color-scheme: dark;
        }
        
        /* Force white text on all key elements in dark mode */
        .dark h1,
        .dark h2,
        .dark h3,
        .dark h4,
        .dark h5,
        .dark h6,
        .dark p,
        .dark label,
        .dark span,
        .dark a:not(.btn),
        .dark input,
        .dark input::placeholder,
        .dark .font-medium,
        .dark .font-semibold,
        .dark .font-bold {
            color: #ffffff !important;
        }
        
        /* Labels specifically */
        .dark label {
            color: #f9fafb !important; /* gray-50 */
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        /* Input fields */
        .dark input {
            background-color: #1f2937 !important; /* gray-800 */
            border-color: #4b5563 !important; /* gray-600 */
            color: #ffffff !important;
        }
        
        .dark input::placeholder {
            color: #9ca3af !important; /* gray-400 */
            opacity: 1 !important;
        }
        
        .dark input:focus {
            border-color: #3b82f6 !important; /* blue-500 */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4) !important;
        }
        
        /* Disabled inputs */
        .dark input:disabled {
            background-color: #374151 !important; /* gray-700 */
            color: #e5e7eb !important; /* gray-200 */
            opacity: 0.9;
        }
        
        /* Error messages */
        .dark .text-red-700 {
            color: #fca5a5 !important; /* red-300 */
            text-shadow: 0 1px 2px rgba(0,0,0,0.4);
        }
        
        /* Links */
        .dark a {
            color: #93c5fd !important; /* blue-300 */
        }
        .dark a:hover {
            color: #bfdbfe !important; /* blue-200 */
        }
        
        /* Card background */
        .dark .bg-white {
            background-color: #030712 !important; /* gray-950 */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75) !important;
        }
        
        /* Icons in dark mode */
        .dark svg {
            color: #d1d5db !important; /* gray-300 */
        }
        
        /* Success messages */
        .dark .text-green-800 {
            color: #86efac !important; /* green-300 */
        }
        
        /* Button text */
        .dark button {
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const referralInput = document.getElementById('referral_code');
    const referralStatus = document.getElementById('referral-status');
    let typingTimer;

    const urlParams = new URLSearchParams(window.location.search);
    const refCode = urlParams.get('ref');
    if (refCode) {
        referralInput.value = refCode;
        verifyReferralCode();
    }

    referralInput.addEventListener('input', function() {
        if (!this.disabled) {
            clearTimeout(typingTimer);
            if (this.value.length > 0) {
                typingTimer = setTimeout(verifyReferralCode, 500);
            } else {
                referralStatus.innerHTML = '';
            }
        }
    });

    function verifyReferralCode() {
        const code = referralInput.value;
        if (!code) return;

        fetch('{{ route('referral.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ referral_code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                let referredByHtml = `
                    <div class="relative group mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input 
                            type="text"
                            value="✓ Referred by: ${data.referrer}"
                            disabled
                            class="w-full pl-10 pr-4 py-3 bg-green-100 dark:bg-green-900/50 border-2 border-green-300 dark:border-green-600 rounded-xl text-sm font-bold text-green-800 dark:text-green-200 cursor-not-allowed"
                        />
                    </div>`;
                referralStatus.innerHTML = referredByHtml;
                referralInput.disabled = true;
            } else {
                referralStatus.innerHTML = '<span class="text-red-700 dark:text-red-300 font-bold">✗ Invalid referral code</span>';
                referralInput.disabled = false;
                referralInput.focus();
            }
        })
        .catch(error => {
            referralStatus.innerHTML = '';
            referralInput.disabled = false;
        });
    }
});
</script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>