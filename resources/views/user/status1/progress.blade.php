<!-- Progress Bar Modal -->
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-md mx-4 p-6 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sync-alt text-blue-500 text-2xl animate-spin"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Processing Transaction</h3>
            <p class="text-sm text-gray-500">Please wait while we process your transaction...</p>
        </div>
        
        <div class="relative pt-1">
            <div class="flex mb-2 items-center justify-between">
                <div>
                    <span id="progressText" class="text-sm font-medium inline-block text-blue-500">
                        0%
                    </span>
                </div>
            </div>
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-blue-100">
                <div id="progressBar" 
                     class="transition-all duration-500 ease-out shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 rounded-full"
                     style="width: 0%">
                </div>
            </div>
            <p id="progressStatus" class="text-sm text-center text-gray-500">Initializing transfer...</p>
        </div>
    </div>
</div>