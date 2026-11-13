<!-- Progress Bar Modal -->
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 hidden z-50 flex items-center justify-center" aria-hidden="true" role="dialog">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700" role="document">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sync-alt text-blue-500 dark:text-blue-400 text-2xl animate-spin-slow"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Processing Transaction</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Please wait while we process your transaction...</p>
        </div>
        
        <div class="relative pt-1">
            <div class="flex mb-3 items-center justify-between">
                <div>
                    <span id="progressText" class="text-sm font-semibold inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-0.5 rounded-full transition-all duration-300">
                        0%
                    </span>
                </div>
            </div>
            <div class="overflow-hidden h-2.5 mb-4 text-xs flex rounded-full bg-blue-100 dark:bg-blue-900/20">
                <div id="progressBar" 
                     class="transition-all duration-500 ease-out shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 dark:bg-blue-600 rounded-full"
                     style="width: 0%">
                </div>
            </div>
            <p id="progressStatus" class="text-sm text-center text-gray-500 dark:text-gray-400 font-medium">Initializing transfer...</p>
            
            <!-- Optional: Estimated time remaining -->
            <div id="estimatedTime" class="mt-2 text-center text-xs text-gray-400 dark:text-gray-500 hidden">
                <i class="fas fa-clock mr-1"></i>
                <span>Estimated time: <span id="timeRemaining">30</span> seconds</span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 2s linear infinite;
    }
</style>

<script>
    // Enhanced progress modal functionality
    document.addEventListener('DOMContentLoaded', () => {
        const progressModal = document.getElementById('progressModal');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const progressStatus = document.getElementById('progressStatus');
        const estimatedTime = document.getElementById('estimatedTime');
        const timeRemaining = document.getElementById('timeRemaining');

        // Show modal with optional custom messages
        window.showProgressModal = function(options = {}) {
            if (progressModal) {
                // Reset progress
                if (progressBar) progressBar.style.width = '0%';
                if (progressText) progressText.textContent = '0%';
                if (progressStatus) progressStatus.textContent = options.initialStatus || 'Initializing transfer...';
                
                // Show estimated time if enabled
                if (estimatedTime && options.showTimer) {
                    estimatedTime.classList.remove('hidden');
                    let seconds = options.estimatedSeconds || 30;
                    if (timeRemaining) timeRemaining.textContent = seconds;
                    
                    const timer = setInterval(() => {
                        seconds--;
                        if (timeRemaining) timeRemaining.textContent = seconds;
                        if (seconds <= 0) {
                            clearInterval(timer);
                            if (estimatedTime) estimatedTime.classList.add('hidden');
                        }
                    }, 1000);
                    
                    // Store timer reference for cleanup
                    progressModal.dataset.timer = timer;
                }
                
                // Show modal
                progressModal.classList.remove('hidden');
                progressModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        };

        // Update progress with smooth animation
        window.updateProgress = function(percent, status) {
            if (progressBar) {
                // Ensure percent is within bounds
                const clampedPercent = Math.min(100, Math.max(0, percent));
                
                // Animate progress bar
                progressBar.style.width = `${clampedPercent}%`;
                
                // Update text with smooth transition
                if (progressText) {
                    progressText.textContent = `${Math.round(clampedPercent)}%`;
                }
                
                // Update status message
                if (progressStatus && status) {
                    progressStatus.textContent = status;
                }
            }
        };

        // Hide modal and cleanup
        window.hideProgressModal = function() {
            if (progressModal) {
                progressModal.classList.add('hidden');
                progressModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                
                // Cleanup timer if exists
                const timerId = progressModal.dataset.timer;
                if (timerId) {
                    clearInterval(parseInt(timerId));
                    delete progressModal.dataset.timer;
                }
            }
        };

        // Keyboard accessibility: Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !progressModal.classList.contains('hidden')) {
                // Note: In production, you might want to prevent closing during critical operations
                // hideProgressModal();
            }
        });

        // Prevent closing when clicking inside modal
        if (progressModal) {
            progressModal.addEventListener('click', (e) => {
                if (e.target === progressModal) {
                    // Don't close on overlay click during processing
                    // Uncomment below if you want to allow closing:
                    // hideProgressModal();
                }
            });
        }

        // Optional: Add progress steps helper
        window.setProgressSteps = function(steps = []) {
            // steps format: [{ percent: 25, message: 'Step 1' }, ...]
            if (!steps.length) return;
            
            let currentStep = 0;
            const interval = setInterval(() => {
                if (currentStep < steps.length) {
                    const step = steps[currentStep];
                    window.updateProgress(step.percent, step.message);
                    currentStep++;
                } else {
                    clearInterval(interval);
                }
            }, 800);
            
            return interval; // Return for manual cleanup if needed
        };
    });

    // Auto-hide modal on Livewire navigation
    document.addEventListener('livewire:navigated', () => {
        window.hideProgressModal?.();
    });
</script>