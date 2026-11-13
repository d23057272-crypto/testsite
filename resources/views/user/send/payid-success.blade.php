<x-user-layout>
    <main class="w-full mx-auto h-auto bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Transaction Sent</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col items-center justify-center p-4 space-y-6">
            <!-- Success Icon -->
            <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center shadow-md">
                <i class="fas fa-check text-4xl text-white"></i>
            </div>

            <!-- Success Message -->
            <h2 class="text-2xl font-bold text-center">Transaction Successful!</h2>
            <p class="text-gray-500 dark:text-gray-400 text-center">Your funds have been sent successfully.</p>

            <!-- Transaction Details -->
            <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl p-6 space-y-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Amount Sent</span>
                    <span class="font-semibold">{{ rtrim(sprintf('%.8f', $transaction->amount_out), '0') }} {{ strtoupper(explode('_', $transaction->which_crypto)[0]) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Recipient</span>
                    <span class="font-semibold">{{ $transaction->metadata['recipient_name'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Network Fee</span>
                    <span class="font-semibold">Free</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Total Amount</span>
                    <span class="font-semibold">{{ rtrim(sprintf('%.8f', $transaction->amount_out), '0') }} {{ strtoupper(explode('_', $transaction->which_crypto)[0]) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Date</span>
                    <span class="font-semibold">{{ $transaction->created_at->format('M d, Y H:i:s') }}</span>
                </div>
            </div>

            <!-- PayID Details -->
            <div class="w-full max-w-md">
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">Recipient PayID</p>
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 rounded-xl p-3 border border-gray-200 dark:border-gray-700">
                    <span class="text-sm font-mono truncate mr-2">{{ $transaction->metadata['recipient_payid'] }}</span>
                    <button onclick="copyToClipboard('{{ $transaction->metadata['recipient_payid'] }}')" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 w-8 h-8 flex items-center justify-center rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/30">
                        <i class="far fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Done Button -->
        <div class="p-4">
            <a href="{{ route('dashboard') }}" 
               class="block w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors text-center shadow-sm">
                Done
            </a>
        </div>
    </main>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show success message
                const message = document.createElement('div');
                message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg z-50';
                message.textContent = 'Copied to clipboard!';
                document.body.appendChild(message);
                
                // Remove message after 2 seconds
                setTimeout(() => {
                    message.remove();
                }, 2000);
            }).catch(() => {
                // Show error message
                const message = document.createElement('div');
                message.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg z-50';
                message.textContent = 'Failed to copy to clipboard';
                document.body.appendChild(message);
                
                // Remove message after 2 seconds
                setTimeout(() => {
                    message.remove();
                }, 2000);
            });
        }
    </script>
</x-user-layout>