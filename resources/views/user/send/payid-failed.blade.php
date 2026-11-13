<x-user-layout>
    <main class="max-w-3xl mx-auto min-h-screen bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300 flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
            <a href="{{ route('send.payid.details', ['symbol' => $symbol, 'network' => $network]) }}" 
               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Transaction Failed</h1>
            <div class="w-8"></div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col items-center justify-center p-4 space-y-6">
            <!-- Error Icon -->
            <div class="w-24 h-24 bg-red-500 dark:bg-red-600 rounded-full flex items-center justify-center shadow-md dark:shadow-red-900/30">
                <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
            </div>

            <!-- Error Message -->
            <h2 class="text-2xl font-bold text-center">Transaction Failed</h2>
            <p class="text-gray-500 dark:text-gray-400 text-center">{{ $error }}</p>

            <!-- Error Details -->
            <div class="w-full max-w-md bg-white dark:bg-gray-700 rounded-xl p-6 space-y-4 shadow-sm border border-gray-200 dark:border-gray-600">
                @if($error_code)
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">Error Code</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $error_code }}</span>
                    </div>
                @endif

                @if($amount)
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">Attempted Amount</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ rtrim(sprintf('%.8f', $amount), '0') }} {{ strtoupper($symbol) }}</span>
                    </div>
                @endif

                @if(isset($available_balance))
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">Available Balance</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ rtrim(sprintf('%.8f', $available_balance), '0') }} {{ strtoupper($symbol) }}</span>
                    </div>
                @endif

                @if($network !== 'native')
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-300">Network</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ strtoupper($network) }}</span>
                    </div>
                @endif
            </div>

            <!-- Error Description -->
            <div class="w-full max-w-md bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/50 rounded-xl p-4">
                <p class="text-red-600 dark:text-red-400 text-sm">
                    @switch($error_code)
                        @case('SELF_TRANSFER')
                            You cannot send funds to your own PayID. Please use a different recipient's PayID.
                            @break

                        @case('INSUFFICIENT_FUNDS')
                            The transaction could not be completed due to insufficient funds in your wallet. Please ensure you have enough balance to cover the transaction amount.
                            @break

                        @case('RECIPIENT_NOT_FOUND')
                            The recipient PayID could not be found. Please check the PayID and try again.
                            @break

                        @case('TRANSACTION_FAILED')
                            Your transaction could not be processed at this time. Please try again later.
                            @break

                        @default
                            An unexpected error occurred while processing your transaction. Please try again or contact support if the problem persists.
                    @endswitch
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-4 space-y-4">
            <a href="{{ route('send.payid.details', ['symbol' => $symbol, 'network' => $network]) }}" 
               class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors text-center block shadow-sm">
                Try Again
            </a>
            <a href="{{ route('dashboard') }}" 
               class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold py-3.5 px-4 rounded-xl transition-colors text-center block">
                Go to Dashboard
            </a>
        </div>
    </main>
</x-user-layout>