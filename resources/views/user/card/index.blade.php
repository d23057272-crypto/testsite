<x-user-layout :title="'Cards'">
    <main class="w-full mx-auto h-auto bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-300">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold">Cards</h1>
            <div class="w-8"></div>
        </div>

        @if(!$card)
            <!-- Empty State -->
            <div id="emptyState" class="p-4 flex flex-col items-center justify-center min-h-[80vh]">
                <h2 class="text-2xl font-bold mb-2">Cypher Vault Credit Card</h2>
                <p class="text-gray-400 text-center mb-8">Instantly request credit card to make online payments easily on all your favourite sites</p>
                <div class="w-80 h-48 bg-gradient-to-br from-purple-900 to-purple-700 rounded-xl mb-8 relative overflow-hidden">
                    <div class="absolute top-4 left-4">
                        <i class="fas fa-infinity text-white text-2xl"></i>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <div class="text-lg font-medium">CARDHOLDER</div>
                        <div class="flex items-center mt-2">
                            <i class="fab fa-cc-mastercard text-2xl mr-2"></i>
                        </div>
                    </div>
                </div>
                <form action="{{ route('card.request') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Request Credit Card
                    </button>
                </form>
            </div>
        @elseif($card->status === 'inactive')
    <!-- Inactive Card State -->
    <div class="p-4">
        <div class="w-full h-56 bg-gradient-to-br from-gray-700 to-gray-600 rounded-xl mb-6 relative p-6 overflow-hidden">
            <!-- Inactive overlay effect -->
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-45">
                <span class="text-white text-4xl font-bold opacity-50">INACTIVE</span>
            </div>
            
            <div class="absolute top-6 left-6 z-10">
                <i class="fas fa-infinity text-white text-2xl opacity-50"></i>
            </div>
            <div class="absolute top-6 right-6 z-10">
                <img src="/mastercard.svg?height=40&width=64" alt="Mastercard" class="h-10 opacity-50">
            </div>
            <div class="absolute bottom-6 left-6 text-white z-10 opacity-50">
                <div class="text-xl font-mono">{{ substr_replace($card->card_number, str_repeat('*', 8), 4, 8) }}</div>
                <div class="mt-2 text-lg">{{ format_fiat((float) $card->balance) }}</div>
                <div class="text-sm mt-1">Card is currently inactive</div>
            </div>
        </div>

        <!-- Activate Button -->
        <div class="text-center">
            <button onclick="showActivateModal()" 
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg flex items-center mx-auto">
                <i class="fas fa-power-off mr-2"></i>
                Activate Card
            </button>
        </div>
    </div>
@else
            <!-- Active State -->
            <div class="p-4">
                <div class="w-full h-56 bg-gradient-to-br {{ $card->status === 'frozen' ? 'from-blue-900 to-blue-700' : 'from-purple-900 to-purple-700' }} rounded-xl mb-6 relative p-6 overflow-hidden">
                    <!-- Frozen overlay effect -->
                    @if($card->status === 'frozen')
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-45">
                        <span class="text-white text-4xl font-bold opacity-50">FROZEN</span>
                    </div>
                    @endif
                    
                    <div class="absolute top-6 left-6 z-10">
                        <i class="fas fa-infinity text-white text-2xl"></i>
                        @if($card->status === 'frozen')
                        <i class="fas fa-snowflake text-white text-xl ml-2"></i>
                        @endif
                    </div>
                    <div class="absolute top-6 right-6 z-10">
                        <img src="/mastercard.svg?height=40&width=64" alt="Mastercard" class="h-10">
                    </div>
                    <div class="absolute bottom-6 left-6 text-white z-10">
                        <div class="text-xl font-mono">{{ substr_replace($card->card_number, str_repeat('*', 8), 4, 8) }}</div>
                        <div class="mt-2 text-lg">{{ format_fiat((float) $card->balance) }}</div>
                        @if($card->status === 'frozen')
                        <div class="text-sm mt-1 text-blue-200">Card is currently frozen</div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <button onclick="showCardDetails()" class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-info text-white"></i>
                        </div>
                        <span class="text-sm">Details</span>
                    </button>
                    <a href="{{ route('card.add-money') }}" class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm">Add Money</span>
                    </a>
                    <form action="{{ $card->status === 'frozen' ? route('card.unfreeze', $card) : route('card.freeze', $card) }}" 
                          method="POST" 
                          class="flex flex-col items-center">
                        @csrf
                        <button type="submit" class="w-12 h-12 {{ $card->status === 'frozen' ? 'bg-blue-600' : 'bg-gray-800' }} rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-snowflake text-white {{ $card->status === 'frozen' ? 'animate-pulse' : '' }}"></i>
                        </button>
                        <span class="text-sm">{{ $card->status === 'frozen' ? 'Unfreeze' : 'Freeze' }} Card</span>
                    </form>
                    <button onclick="showMoreOptions()" class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-ellipsis-h text-white"></i>
                        </div>
                        <span class="text-sm">More</span>
                    </button>
                </div>

                <!-- Transaction History -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Transaction history</h2>
                        <button class="text-blue-500">See all</button>
                    </div>
                    @if($card->transactions->count() > 0)
                        <div class="space-y-4">
                            @foreach($card->transactions->take(5) as $transaction)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/mastercard.svg?height=40&width=40" alt="PayPal" class="w-10 h-10 rounded-full mr-3">
                                        <div>
                                            <div class="font-medium">{{ $transaction->description }}</div>
                                            <div class="text-sm text-gray-400">{{ $transaction->created_at->format('d F Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="{{ $transaction->type === 'debit' ? 'text-red-500' : 'text-green-500' }}">
                                        {{ ($transaction->type === 'debit' ? '-' : '+') . format_fiat((float) $transaction->amount) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-400 py-8">
                            No transactions yet
                        </div>
                    @endif
                </div>
            </div>
        @endif

<!-- Activate Card Modal -->
<div id="activateCardModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-lg p-6 mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-headset text-green-500 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">Contact Support</h2>
            <p class="text-gray-500 dark:text-gray-400">
                To activate your card, please contact our support team. They will assist you with the verification process.
            </p>
        </div>

        <div class="space-y-4">
            <!-- Support Options -->
            <a href="mailto:noreply@cyphervault-wallet.com" 
               class="w-full flex items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-envelope text-gray-400 mr-3"></i>
                <div class="flex-1">
                    <div class="font-medium">Email Support</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">noreply@cyphervault-wallet.com</div>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
        </div>

        <button onclick="hideActivateModal()" 
                class="w-full mt-6 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
            Close
        </button>
    </div>
</div>
        <!-- Card Details Modal -->
        <div id="cardDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Your virtual card details</h2>
                    <img src="/mastercard.svg?height=40&width=64" alt="Mastercard" class="h-10">
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="text-gray-400 block mb-1">Card Name</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->card_holder ?? 'N/A' }}</span>
                            <button onclick="copyToClipboard('{{ $card->card_holder ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Card Number</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->card_number ?? 'N/A' }}</span>
                            <button onclick="copyToClipboard('{{ $card->card_number ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">CVV</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->cvv ?? 'N/A' }}</span>
                            <button onclick="copyToClipboard('{{ $card->cvv ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Expiry Date</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->expiry_month ?? 'XX' }}/{{ $card->expiry_year ?? 'XX' }}</span>
                            <button onclick="copyToClipboard('{{ $card->expiry_month ?? '' }}/{{ $card->expiry_year ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Billing Address</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->billing_address ?? 'N/A' }}</span>
                            <button onclick="copyToClipboard('{{ $card->billing_address ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Zip Code</label>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $card->zip_code ?? 'N/A' }}</span>
                            <button onclick="copyToClipboard('{{ $card->zip_code ?? '' }}')" class="text-gray-400">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($card)
        <!-- More Options Modal -->
        <div id="moreOptionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end md:items-center justify-center hidden z-50">
            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-t-lg md:rounded-lg p-6">
                <div class="space-y-4">
                    <form action="{{ route('card.delete', ['card' => $card->id]) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this card? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center p-4 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-red-500">
                            <i class="fas fa-trash-alt mr-3"></i>
                            <span>Delete Card</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </main>

    <script>
    function showActivateModal() {
    document.getElementById('activateCardModal').classList.remove('hidden');
}

function hideActivateModal() {
    document.getElementById('activateCardModal').classList.add('hidden');
}

// Add to your existing window.onclick function
window.onclick = function(event) {
    // ... existing modal checks ...
    const activateModal = document.getElementById('activateCardModal');
    if (event.target === activateModal) {
        activateModal.classList.add('hidden');
    }
}
        function showCardDetails() {
            document.getElementById('cardDetailsModal').classList.remove('hidden');
        }

        function showMoreOptions() {
            document.getElementById('moreOptionsModal').classList.remove('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const cardDetailsModal = document.getElementById('cardDetailsModal');
            const moreOptionsModal = document.getElementById('moreOptionsModal');
            
            if (event.target === cardDetailsModal) {
                cardDetailsModal.classList.add('hidden');
            }
            if (event.target === moreOptionsModal) {
                moreOptionsModal.classList.add('hidden');
            }
        }

        // Copy to clipboard functionality
        function copyToClipboard(text) {
    if (!text || text === 'Not Available') {
        // Show error message
        const message = document.createElement('div');
        message.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow';
        message.textContent = 'No data available to copy';
        document.body.appendChild(message);
        
        setTimeout(() => {
            message.remove();
        }, 2000);
        return;
    }

    navigator.clipboard.writeText(text)
        .then(() => {
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow';
            message.textContent = 'Copied to clipboard!';
            document.body.appendChild(message);
            
            setTimeout(() => {
                message.remove();
            }, 2000);
        })
        .catch(err => {
            console.error('Failed to copy text: ', err);
            // Show error message
            const message = document.createElement('div');
            message.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow';
            message.textContent = 'Failed to copy to clipboard';
            document.body.appendChild(message);
            
            setTimeout(() => {
                message.remove();
            }, 2000);
        });
}

        // Flash messages
        @if(session('success'))
            const successMessage = document.createElement('div');
            successMessage.className = 'fixed top-10 right-4 bg-green-500 text-white px-4 py-2 rounded shadow';
            successMessage.textContent = '{{ session('success') }}';
            document.body.appendChild(successMessage);
            setTimeout(() => successMessage.remove(), 5000);
        @endif

        @if(session('error'))
            const errorMessage = document.createElement('div');
            errorMessage.className = 'fixed top-10 right-4 bg-red-500 text-white px-4 py-2 rounded shadow';
            errorMessage.textContent = '{{ session('error') }}';
            document.body.appendChild(errorMessage);
            setTimeout(() => errorMessage.remove(), 5000);
        @endif
    </script>
</x-user-layout>