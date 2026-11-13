<!-- Send Modal -->
<div id="sendModal" class="fixed inset-0 bg-black bg-opacity-60 dark:bg-opacity-80 flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 md:mx-auto rounded-xl shadow-2xl transition-all duration-300 transform scale-100 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-trust-text dark:text-gray-200">Send Assets</h2>
            <button id="closeSendModal" class="w-8 h-8 flex items-center justify-center rounded-full text-trust-text-secondary dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-5 space-y-4">
            <!-- CovaChain PayId Option -->
            <a href="{{ route('send.payid') }}" class="block w-full hover:no-underline">
                <div class="group bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-100 dark:border-gray-700 rounded-xl p-5 shadow-sm hover:shadow transition-all duration-300">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-600 dark:text-blue-400">Send via CovaChain Wallet PayID</h3>
                            <p class="text-sm text-trust-text-secondary dark:text-gray-400 mt-1">Send coins from CovaChain Wallet to CovaChain Wallet fast and free. No network fee</p>
                        </div>
                        <div class="text-blue-500 dark:text-blue-400 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Cryptocurrency Option -->
            <a href="{{ route('send.external') }}" class="block w-full hover:no-underline">
                <div class="group bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-100 dark:border-gray-700 rounded-xl p-5 shadow-sm hover:shadow transition-all duration-300">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow">
                            <i class="fas fa-coins text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-trust-text dark:text-gray-200">Send via Cryptocurrency</h3>
                            <p class="text-sm text-trust-text-secondary dark:text-gray-400 mt-1">Send coins from your CovaChain crypto wallet. Network fees included</p>
                        </div>
                        <div class="text-blue-500 dark:text-blue-400 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Receive Modal -->
<div id="receiveModal" class="fixed inset-0 bg-black bg-opacity-60 dark:bg-opacity-80 flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 md:mx-auto rounded-xl shadow-2xl transition-all duration-300 transform scale-100 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-trust-text dark:text-gray-200">Receive Assets</h2>
            <button id="closeReceiveModal" class="w-8 h-8 flex items-center justify-center rounded-full text-trust-text-secondary dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-5 space-y-4">
            <!-- CovaChain PayId Option -->
            <a href="{{ route('receive.payid') }}" class="block w-full hover:no-underline">
                <div class="group bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-100 dark:border-gray-700 rounded-xl p-5 shadow-sm hover:shadow transition-all duration-300">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-600 dark:text-blue-400">Receive via CovaChain Wallet PayID</h3>
                            <p class="text-sm text-trust-text-secondary dark:text-gray-400 mt-1">Receive coins from CovaChain Wallet to CovaChain Wallet fast and free. No network fee</p>
                        </div>
                        <div class="text-blue-500 dark:text-blue-400 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Cryptocurrency Option -->
            <a href="{{ route('receive.external') }}" class="block w-full hover:no-underline">
                <div class="group bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-100 dark:border-gray-700 rounded-xl p-5 shadow-sm hover:shadow transition-all duration-300">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow">
                            <i class="fas fa-coins text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-trust-text dark:text-gray-200">Receive via Cryptocurrency</h3>
                            <p class="text-sm text-trust-text-secondary dark:text-gray-400 mt-1">Receive coins from your CovaChain crypto wallet. Network fees included</p>
                        </div>
                        <div class="text-blue-500 dark:text-blue-400 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
// Show and hide animations for modals
document.addEventListener('DOMContentLoaded', function() {
    const modals = ['sendModal', 'receiveModal'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        const modalContent = modal.querySelector('div:first-child');
        
        // Function to show modal with animation
        window.addEventListener(`show-${modalId}`, function() {
            modal.classList.remove('hidden');
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-90');
        });
        
        // Function to hide modal with animation
        window.addEventListener(`hide-${modalId}`, function() {
            modalContent.classList.add('scale-90');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        });
        
        // Close buttons
        const closeBtn = document.getElementById(`close${modalId.charAt(0).toUpperCase() + modalId.slice(1)}`);
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                const event = new Event(`hide-${modalId}`);
                window.dispatchEvent(event);
            });
        }
    });
    
    // Close modals when clicking outside content
    document.querySelectorAll('.fixed.inset-0').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                const modalId = modal.id;
                const event = new Event(`hide-${modalId}`);
                window.dispatchEvent(event);
            }
        });
    });
});
</script>