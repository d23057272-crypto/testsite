<x-user-layout :title="'Manage Crypto'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Manage Crypto</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>
    
        <!-- Crypto List -->
        <div class="space-y-4 mb-4">
            @foreach($cryptos as $crypto)
                <div class="flex items-center justify-between p-4 crypto-item bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700" data-crypto="{{ strtolower($crypto['symbol']) }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-4">
                            @if(strtolower($crypto['symbol']) === 'usdt')
                                <!-- USDT Icon as Text -->
                                <span class="text-blue-500 dark:text-blue-400 font-bold text-xl">₮</span>
                            @else
                                <!-- Cryptocurrency Icon as Image -->
                                <img 
                                    src="{{ asset('images/' . strtolower($crypto['symbol']) . '-icon.png') }}" 
                                    alt="{{ $crypto['name'] }} Icon" 
                                    class="w-8 h-8 object-contain"
                                    onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-blue-500 dark:text-blue-400 font-bold text-xl\'>{{ strtoupper(substr($crypto['symbol'], 0, 1)) }}</span>'"
                                >
                            @endif
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $crypto['symbol'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $crypto['name'] }}</p>
                        </div>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            class="sr-only peer crypto-toggle" 
                            {{ $crypto['status'] === true ? 'checked' : '' }}
                            data-crypto="{{ strtolower($crypto['symbol']) }}"
                            id="crypto-toggle-{{ strtolower($crypto['symbol']) }}"
                        >
                        <label 
                            for="crypto-toggle-{{ strtolower($crypto['symbol']) }}" 
                            class="w-12 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 dark:after:border-gray-600 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500 dark:peer-checked:bg-blue-600 cursor-pointer"
                        ></label>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Info Section -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-sm text-gray-600 dark:text-gray-300">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2 mt-0.5"></i>
                <p>Toggle cryptocurrencies you want to enable in your wallet. Disabled cryptocurrencies won't be shown in your dashboard.</p>
            </div>
        </div>
    </main>

    <!-- Success Toast -->
    <div id="crypto-success-message" class="fixed top-4 right-4 bg-blue-500 dark:bg-blue-600 text-white px-4 py-3 rounded-xl shadow-lg z-50 flex items-center opacity-0 pointer-events-none transition-all duration-300">
        <i class="fas fa-check-circle mr-2"></i>
        <span>Crypto status updated</span>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize toast position
    const successMsg = document.getElementById('crypto-success-message');
    successMsg.style.transform = 'translateX(calc(100% + 1rem))';
    
    document.querySelectorAll('.crypto-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cryptoSymbol = this.getAttribute('data-crypto');
            const isChecked = this.checked;
            const toggle = this;
            
            // Add loading state to the toggle
            const label = document.querySelector(`label[for="crypto-toggle-${cryptoSymbol}"]`);
            label.classList.add('opacity-50');

            fetch('{{ route('crypto.manage.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    crypto: cryptoSymbol,
                    status: isChecked ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading state
                label.classList.remove('opacity-50');
                
                if (data.success) {
                    // Show success toast
                    const successMsg = document.getElementById('crypto-success-message');
                    successMsg.style.transform = 'translateX(0)';
                    successMsg.classList.remove('opacity-0');
                    successMsg.classList.add('opacity-90');
                    successMsg.classList.remove('pointer-events-none');
                    
                    // Add subtle animation to the crypto item
                    const cryptoItem = toggle.closest('.crypto-item');
                    cryptoItem.style.transition = 'transform 0.3s ease';
                    cryptoItem.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        cryptoItem.style.transform = 'scale(1)';
                    }, 300);
                    
                    // Hide toast after 3 seconds
                    setTimeout(() => {
                        successMsg.style.transform = 'translateX(calc(100% + 1rem))';
                        successMsg.classList.remove('opacity-90');
                        successMsg.classList.add('opacity-0');
                        successMsg.classList.add('pointer-events-none');
                    }, 3000);
                } else {
                    // Revert the checkbox if update fails
                    toggle.checked = !isChecked;
                    
                    // Show error message
                    alert(data.message || 'Failed to update crypto status');
                }
            })
            .catch(error => {
                // Remove loading state
                label.classList.remove('opacity-50');
                
                // Revert the checkbox
                toggle.checked = !isChecked;
                
                alert('An error occurred while updating crypto status');
            });
        });
    });
});
</script>
@endpush
</x-user-layout>