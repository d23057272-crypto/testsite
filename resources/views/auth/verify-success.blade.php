<x-guest-layout>
    <div class="min-h-screen w-full bg-white p-4 sm:p-6 md:p-8 flex items-center justify-center">
        <!-- Background elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-80 h-80 -top-20 -left-20 bg-blue-50 rounded-full opacity-60"></div>
            <div class="absolute w-72 h-72 bottom-10 right-10 bg-blue-50 rounded-full opacity-60"></div>
            <div class="absolute w-64 h-64 top-1/3 right-1/4 bg-blue-50 rounded-full opacity-60"></div>
        </div>

        <div class="w-full max-w-md animate-fadeIn">
            <!-- Card Container -->
            <div class="relative bg-white rounded-xl shadow-lg p-8 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                <!-- Content -->
                <div class="relative">
                    <!-- Success Icon -->
                    <div class="text-center mb-8 animate-slideDown">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-green-100">
                            <svg class="w-8 h-8 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Email Verified!') }}</h1>
                    </div>

                    <!-- Success Message -->
                    <div class="mb-8 text-center animate-slideUp" style="animation-delay: 100ms;">
                        <p class="text-gray-600">
                            {{ __('Thank you for verifying your email address. Your account is now fully activated.') }}
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="animate-slideUp" style="animation-delay: 300ms;">
                        @auth
                            <a href="{{ route('dashboard') }}" 
                                class="block w-full px-4 py-3 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                                {{ __('Proceed to Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                                class="block w-full px-4 py-3 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                                {{ __('Proceed to Login') }}
                            </a>
                        @endauth
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