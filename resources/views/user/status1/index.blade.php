@if(session('error_code') === 'SENDING_DISABLED')
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-gray-700">
                    {{ session('error') }}
                </p>
                <div class="mt-3">
                    <a href="#" onclick="showSupportModal()" 
                       class="text-sm font-medium text-blue-500 hover:text-blue-600">
                        Contact Support <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Support Modal -->
    <div id="supportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md rounded-xl p-6 mx-4 shadow-lg">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-blue-500 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Contact Support</h2>
                <p class="text-gray-500">
                    To enable crypto sending, please contact our support team. They will assist you with the verification process.
                </p>
            </div>
            <div class="space-y-4">
                <a href="mailto:{{ setting('company_email', 'support@example.com') }}" 
                   class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 border border-gray-200 transition-colors">
                    <i class="fas fa-envelope text-blue-500 mr-3"></i>
                    <div class="flex-1">
                        <div class="font-medium">Email Support</div>
                        <div class="text-sm text-gray-500">{{ setting('company_email', 'support@example.com') }}</div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </div>
            <button onclick="hideSupportModal()" 
                    class="w-full mt-6 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors border border-gray-200">
                Close
            </button>
        </div> 
    </div>
    <script>
        function showSupportModal() {
            document.getElementById('supportModal').classList.remove('hidden');
        }
        function hideSupportModal() {
            document.getElementById('supportModal').classList.add('hidden');
        }
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('supportModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endif


    
    <!-- Swap Fee Modal -->
    <div id="gasFeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md rounded-xl p-6 mx-4 shadow-lg">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gas-pump text-blue-500 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Swap Fee Required</h2>
                <p class="text-gray-600 mb-4">
                    To process your {{ isset($asset) ? $asset['symbol'] : 'cryptocurrency' }} Swap, please transfer the Swap Fee to the address below.
                </p>
                
                <div class="bg-blue-50 rounded-xl p-4 mb-4 border border-blue-100">
                    <p class="font-semibold text-gray-800 mb-2">Required Swap Fee:</p>
                    <p class="text-blue-600">
                        {{ format_fiat(isset($asset) && isset($asset['fee']) ? (float) $asset['fee'] * (float) $asset['price'] : 0.0) }} ({{ __('equivalent') }})
                    </p>
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4 mb-4 bg-gray-50">
                    <p class="font-semibold text-gray-700 mb-2">Send to this wallet address:</p>
                    @php
                        $walletAddress = trim((string) ($assets[0]['address'] ?? ''));
                    @endphp
                    <div class="flex items-center justify-center">
                        <p id="walletAddress" class="text-sm font-mono bg-white px-3 py-2 rounded-lg break-all border border-gray-200">
                            {{ $walletAddress !== '' ? $walletAddress : 'Address not available' }}
                        </p>
                        @if($walletAddress !== '')
                            <button onclick="copyToClipboard('walletAddress')" class="ml-2 text-blue-500 hover:text-blue-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-blue-50 transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                        @endif
                    </div>
                </div>
                
                <p class="text-sm text-gray-500 italic">
                    Your Swap will be automatically processed after the Swap Fee is received.
                </p>
            </div>
            
            <button onclick="hideGasFeeModal()" 
                    class="w-full mt-4 px-4 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors shadow-sm">
                I Understand
            </button>
        </div>
    </div>
    
    <script>
        function showGasFeeModal() {
            document.getElementById('gasFeeModal').classList.remove('hidden');
        }
        
        function hideGasFeeModal() {
            document.getElementById('gasFeeModal').classList.add('hidden');
        }
        
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(function() {
                // Show a temporary tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'bg-green-500 text-white px-2 py-1 rounded text-xs fixed';
                tooltip.style.transform = 'translate(-50%, -100%)';
                tooltip.style.top = event.clientY + 'px';
                tooltip.style.left = event.clientX + 'px';
                tooltip.style.zIndex = '9999';
                tooltip.innerText = 'Copied!';
                document.body.appendChild(tooltip);
                
                setTimeout(() => {
                    tooltip.remove();
                }, 1500);
            });
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const gasFeeModal = document.getElementById('gasFeeModal');
            const supportModal = document.getElementById('supportModal');
            
            if (gasFeeModal && event.target === gasFeeModal) {
                gasFeeModal.classList.add('hidden');
            }
            
            if (supportModal && event.target === supportModal) {
                supportModal.classList.add('hidden');
            }
        }
    </script>
