<x-user-layout>
    <main class="w-full mx-auto min-h-screen bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition-all duration-300 flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Transaction Details</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col items-center justify-center p-4 space-y-6">
            <!-- Status Icon and Message -->
            @if($transaction->status === App\Models\Transaction::STATUS_PENDING)
                <!-- Pending Icon -->
                <div class="w-24 h-24 bg-yellow-400 dark:bg-yellow-500 rounded-full flex items-center justify-center shadow-md dark:shadow-yellow-900/30">
                    <i class="fas fa-hourglass-half text-4xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100">Transaction Pending</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">Your transaction is being processed.</p>
            @elseif($transaction->status === App\Models\Transaction::STATUS_COMPLETED)
                <!-- Success Icon -->
                <div class="w-24 h-24 bg-green-500 dark:bg-green-600 rounded-full flex items-center justify-center shadow-md dark:shadow-green-900/30">
                    <i class="fas fa-check text-4xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100">Transaction Successful!</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">Your funds have been sent successfully.</p>
            @elseif($transaction->status === App\Models\Transaction::STATUS_FAILED)
                <!-- Failed Icon -->
                <div class="w-24 h-24 bg-red-500 dark:bg-red-600 rounded-full flex items-center justify-center shadow-md dark:shadow-red-900/30">
                    <i class="fas fa-times text-4xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100">Transaction Failed</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">There was an issue processing your transaction.</p>
            @else
                <!-- Default Icon -->
                <div class="w-24 h-24 bg-gray-400 dark:bg-gray-600 rounded-full flex items-center justify-center shadow-md dark:shadow-gray-900/30">
                    <i class="fas fa-question text-4xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100">Transaction Status Unknown</h2>
                <p class="text-gray-500 dark:text-gray-400 text-center">Please contact support for more information.</p>
            @endif

            <!-- Transaction Details -->
            <div class="w-full max-w-md bg-white dark:bg-gray-700 rounded-xl p-6 space-y-4 shadow-sm border border-gray-200 dark:border-gray-600">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Amount Sent</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ rtrim(sprintf('%.8f', $transaction->amount_out), '0') }} 
                        {{ strtoupper(explode('_', $transaction->which_crypto)[0]) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Recipient Address</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100 break-all">
                        @php
                            $toAddress = $transaction->to_address ?? 'N/A';
                            if ($transaction->type === \App\Models\Transaction::TYPE_WITHDRAWAL && $toAddress !== 'N/A' && strlen($toAddress) > 11) {
                                $toAddress = substr($toAddress, 0, 6) . '...' . substr($toAddress, -4);
                            }
                        @endphp
                        {{ $toAddress }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Network Fee</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                        @php $fee = $transaction->network_fee ?? 0; @endphp
                        @if($fee > 0)
                            {{ rtrim(sprintf('%.8f', $fee), '0') }} {{ strtoupper(explode('_', $transaction->which_crypto)[0]) }}
                        @else
                            Free
                        @endif
                    </span>
                </div>
                <div class="flex justify-between font-semibold text-gray-900 dark:text-gray-100">
                    <span>Total Amount</span>
                    <span>
                        @php $total = ($transaction->amount_out ?? 0) + ($transaction->network_fee ?? 0); @endphp
                        {{ rtrim(sprintf('%.8f', $total), '0') }} {{ strtoupper(explode('_', $transaction->which_crypto)[0]) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Date</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-gray-500 dark:text-gray-400">Transaction Hash</span>
                    <button 
                        onclick="copyToClipboard('{{ $transaction->transaction_hash ?? 'N/A' }}')" 
                        class="font-semibold text-blue-500 dark:text-blue-400 hover:underline flex items-center space-x-1 group">
                        <span>{{ shorten_string($transaction->transaction_hash ?? 'N/A') }}</span>
                        <i class="fas fa-copy text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>

            <!-- Done Button -->
            <div class="p-4 w-full max-w-md">
                <a href="{{ route('dashboard') }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors text-center shadow-sm">
                    Done
                </a>
            </div>
        </div>
    </main>

    <script>
        function copyToClipboard(text) {
            if (text === 'N/A') return;
            
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Copied to clipboard!', 'success');
            }).catch(() => {
                showNotification('Failed to copy to clipboard', 'error');
            });
        }

        function showNotification(message, type) {
            // Remove existing notifications
            const existing = document.querySelector('.toast-notification');
            if (existing) existing.remove();
            
            const notification = document.createElement('div');
            notification.className = `toast-notification fixed bottom-4 right-4 px-4 py-3 rounded-xl shadow-lg z-50 transition-all transform ${
                type === 'success' 
                    ? 'bg-green-500 dark:bg-green-600 text-white' 
                    : 'bg-red-500 dark:bg-red-600 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Animation
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(20px)';
            setTimeout(() => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
                setTimeout(() => notification.remove(), 300);
            }, 2500);
        }

        // Add click handler for hash copying on page load
        document.addEventListener('DOMContentLoaded', () => {
            const hashElement = document.querySelector('[onclick^="copyToClipboard"]');
            if (hashElement) {
                hashElement.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
        });
    </script>
</x-user-layout>