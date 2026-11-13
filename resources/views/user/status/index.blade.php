@if(session('error_code') === 'SENDING_DISABLED')
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 mb-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0 mt-0.5">
                <i class="fas fa-exclamation-triangle text-blue-500 dark:text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ session('error') }}
                </p>
                <div class="mt-3">
                    <button type="button" onclick="showSupportModal()" 
                       class="text-sm font-medium text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 flex items-center group">
                        Contact Support 
                        <span class="ml-1 group-hover:translate-x-0.5 transition-transform" aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Support Modal -->
    <div id="supportModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center hidden z-50" onclick="hideSupportModal(event)">
        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6 mx-4 shadow-lg border border-gray-200 dark:border-gray-700" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-blue-500 dark:text-blue-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Contact Support</h2>
                <p class="text-gray-500 dark:text-gray-400">
                    To enable crypto sending, please contact our support team. They will assist you with the verification process.
                </p>
            </div>
            <div class="space-y-3">
                <a href="mailto:{{ setting('company_email', 'support@example.com') }}" 
                   class="w-full flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-colors group">
                    <i class="fas fa-envelope text-blue-500 dark:text-blue-400 mr-3 text-lg"></i>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-gray-100">Email Support</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ setting('company_email', 'support@example.com') }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors"></i>
                </a>
            </div>
            <button type="button" onclick="hideSupportModal()" 
                    class="w-full mt-6 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors border border-gray-200 dark:border-gray-600 font-medium">
                Close
            </button>
        </div> 
    </div>
@endif

@if(auth()->user()->require_fee)
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 mb-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0 mt-0.5">
                <i class="fas fa-info-circle text-blue-500 dark:text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Withdrawal pending: Gas fee required to process your transaction.
                </p>
                <div class="mt-3">
                    <button type="button" onclick="showGasFeeModal()" 
                       class="text-sm font-medium text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 flex items-center group">
                        Pay Gas Fee 
                        <span class="ml-1 group-hover:translate-x-0.5 transition-transform" aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gas Fee Modal -->
    <div id="gasFeeModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center hidden z-50" onclick="hideGasFeeModal(event)">
        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6 mx-4 shadow-lg border border-gray-200 dark:border-gray-700" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gas-pump text-blue-500 dark:text-blue-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Gas Fee Required</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    To process your {{ isset($asset) ? $asset['symbol'] : 'cryptocurrency' }} withdrawal, please transfer the gas fee to the address below.
                </p>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-4 border border-blue-100 dark:border-blue-900/30">
                    <p class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Required Gas Fee:</p>
                    <p class="text-blue-600 dark:text-blue-400 text-lg font-bold">
                        {{ format_fiat(isset($asset) && isset($asset['fee']) ? (float) $asset['fee'] * (float) $asset['price'] : 0.0) }} ({{ __('equivalent') }})
                    </p>
                </div>
                
                <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Send to this wallet address:</p>
                    @php
                        $walletAddress = trim((string) ($asset['system_wallet_address'] ?? ''));
                    @endphp
                    <div class="flex items-start justify-center flex-wrap gap-2">
                        <p id="walletAddress" class="text-sm font-mono bg-white dark:bg-gray-800 px-3 py-2 rounded-lg break-all border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 min-w-[200px]">
                            {{ $walletAddress !== '' ? $walletAddress : 'Address not available' }}
                        </p>
                        @if($walletAddress !== '')
                            <button type="button" onclick="copyToClipboard('walletAddress')"
                                    class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 w-8 h-8 flex items-center justify-center rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors flex-shrink-0"
                                    aria-label="Copy wallet address">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                        @endif
                    </div>
                </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                    Your withdrawal will be automatically processed after the gas fee is received.
                </p>
            </div>
            
            <button type="button" onclick="hideGasFeeModal()" 
                    class="w-full mt-4 px-4 py-3 bg-blue-500 dark:bg-blue-600 text-white rounded-xl hover:bg-blue-600 dark:hover:bg-blue-700 transition-colors shadow-sm font-medium">
                I Understand
            </button>
        </div>
    </div>
@endif

@push('scripts')
<script>
    // Unified modal handlers with event delegation
    function showSupportModal() {
        document.getElementById('supportModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function hideSupportModal(event) {
        if (!event || event.target.id === 'supportModal') {
            document.getElementById('supportModal')?.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
    
    function showGasFeeModal() {
        document.getElementById('gasFeeModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function hideGasFeeModal(event) {
        if (!event || event.target.id === 'gasFeeModal') {
            document.getElementById('gasFeeModal')?.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
    
    // Enhanced clipboard functionality with dark mode tooltip
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const text = element.innerText.trim();
        if (!text || text.includes('Address not found')) return;
        
        navigator.clipboard.writeText(text).then(() => {
            // Create tooltip with dark mode support
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed bg-green-500 dark:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium shadow-lg z-50 transition-opacity';
            tooltip.style.left = '50%';
            tooltip.style.top = '50%';
            tooltip.style.transform = 'translate(-50%, -50%)';
            tooltip.style.opacity = '0';
            tooltip.innerText = 'Copied to clipboard!';
            document.body.appendChild(tooltip);
            
            // Fade in
            setTimeout(() => {
                tooltip.style.transition = 'opacity 0.2s ease';
                tooltip.style.opacity = '1';
            }, 10);
            
            // Fade out and remove
            setTimeout(() => {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(tooltip);
                }, 200);
            }, 1500);
            
            // Visual feedback on address container
            element.classList.add('ring-2', 'ring-green-500', 'dark:ring-green-400');
            setTimeout(() => {
                element.classList.remove('ring-2', 'ring-green-500', 'dark:ring-green-400');
            }, 1000);
        }).catch(err => {
            console.error('Copy failed:', err);
            // Optional: Show error tooltip
        });
    }
    
    // Keyboard accessibility for modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            hideSupportModal();
            hideGasFeeModal();
        }
    });
    
    // Cleanup body overflow on navigation
    document.addEventListener('livewire:navigated', () => {
        document.body.style.overflow = '';
    });
</script>
@endpush