<x-user-layout>
    <!-- Search Bar -->
    <div class="relative">
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
        <input type="text" 
               placeholder="Search" 
               id="cryptoSearch"
               class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
    </div>

    <!-- Account ID with Dropdown -->
    <div class="mt-6">
        <div class="flex items-center justify-between relative">
            <!-- Account ID and Dropdown Trigger -->
            <div class="flex items-center space-x-2">
                <button onclick="toggleAccountDropdown()" class="flex items-center space-x-2 hover:text-gray-700 dark:hover:text-gray-300">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white" id="accountId">{{ $user->uuid }}</span>
                    <i class="fas fa-chevron-down text-sm text-gray-500 dark:text-gray-400 transition-transform duration-200" id="accountDropdownIcon"></i>
                </button>
            </div>

            <!-- Copy Button -->
            <div class="relative">
                <button onclick="copyAccountId()" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <i class="fas fa-copy text-xl"></i>
                </button>
                <!-- Copy Success Message -->
                <div id="copyMessage" class="hidden absolute right-0 -top-10 bg-purple-600 text-white text-sm px-2 py-1 rounded shadow-lg whitespace-nowrap">
                    Copied!
                </div>
            </div>

            <!-- Dropdown Menu -->
            <div id="accountDropdown" class="hidden absolute top-full left-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 z-50">
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150">
                            <i class="fas fa-sign-out-alt mr-2 text-gray-500 dark:text-gray-400"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Display -->
    <div class="mt-6 flex items-center justify-between">
        <div id="balance" class="text-4xl font-bold text-gray-900 dark:text-white">{{ format_fiat((float) $totalBalanceUSD) }}</div>
        <button id="toggleBalance" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i id="balanceIcon" class="fas fa-eye text-xl"></i>
        </button>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 grid grid-cols-4 gap-4">
        <div class="flex flex-col items-center">
            <button class="h-16 w-16 rounded-full bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center shadow-md dark:shadow-lg">
                <i class="fas fa-arrow-up text-xl text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Send</span>
        </div>
        <div class="flex flex-col items-center">
            <button class="h-16 w-16 rounded-full bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center shadow-md dark:shadow-lg">
                <i class="fas fa-arrow-down text-xl text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Receive</span>
        </div>
        <div class="flex flex-col items-center">
            <button class="h-16 w-16 rounded-full bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center shadow-md dark:shadow-lg">
                <i class="fas fa-credit-card text-xl text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Buy</span>
        </div>
        <div class="flex flex-col items-center">
            <button class="h-16 w-16 rounded-full bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center shadow-md dark:shadow-lg">
                <i class="fas fa-exchange-alt text-xl text-gray-700 dark:text-gray-300"></i>
            </button>
            <span class="mt-2 text-sm text-gray-700 dark:text-gray-300">Swap</span>
        </div>
    </div>

    <!-- Help Text -->
    <div class="mt-6 text-center text-gray-600 dark:text-gray-400">
        Access, secure and withdraw assets
    </div>

    <!-- Connect Wallet Button -->
    <button class="mt-6 w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 rounded-md">
        🔗 Connect CovaChain Wallet to External Wallet
    </button>

    <!-- Crypto Assets - Now Dynamic -->
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Crypto</h2>
        <div class="space-y-4" id="cryptoList">
            @foreach($supportedCryptos as $symbol => $crypto)
                @if($cryptoAssets->{$symbol.'_status'})
                    <div class="crypto-item flex items-center justify-between p-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer shadow-sm dark:shadow-md" 
                         data-name="{{ strtolower($crypto['name']) }}" 
                         data-symbol="{{ strtolower($symbol) }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 {{ $crypto['color'] }} rounded-full flex items-center justify-center">
                                <i class="{{ $crypto['icon'] }} text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ strtoupper($symbol) }}</span>
                                    @if(strpos($symbol, 'usdt_') === 0)
                                        <span class="text-xs bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded text-gray-700 dark:text-gray-300">
                                            {{ strtoupper(str_replace('usdt_', '', $symbol)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="crypto-price" data-symbol="{{ $symbol }}">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ number_format($cryptoAssets->{$symbol.'_balance'}, 8) }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 crypto-value" data-symbol="{{ $symbol }}">{{ format_fiat(0) }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-user-layout>

<script>
    // Account dropdown and copy functionality
    function toggleAccountDropdown() {
        const dropdown = document.getElementById('accountDropdown');
        const icon = document.getElementById('accountDropdownIcon');
        
        dropdown.classList.toggle('hidden');
        icon.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    async function copyAccountId() {
        const accountId = document.getElementById('accountId').textContent;
        const messageElement = document.getElementById('copyMessage');

        try {
            await navigator.clipboard.writeText(accountId);
            
            // Show success message
            messageElement.classList.remove('hidden');
            
            // Hide message after 2 seconds
            setTimeout(() => {
                messageElement.classList.add('hidden');
            }, 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('accountDropdown');
        const button = event.target.closest('button');
        const triggerButton = event.target.closest('[onclick="toggleAccountDropdown()"]');
        
        if (button !== triggerButton && !triggerButton) {
            dropdown.classList.add('hidden');
            document.getElementById('accountDropdownIcon').style.transform = 'rotate(0deg)';
        }
    });

    // Balance toggle functionality
    let balanceVisible = true;
    const toggleButton = document.getElementById('toggleBalance');
    const balanceElement = document.getElementById('balance');
    const balanceIcon = document.getElementById('balanceIcon');
    const originalBalance = balanceElement.textContent;

    toggleButton.addEventListener('click', function() {
        if (balanceVisible) {
            balanceElement.textContent = '****';
            balanceIcon.className = 'fas fa-eye-slash text-xl';
        } else {
            balanceElement.textContent = originalBalance;
            balanceIcon.className = 'fas fa-eye text-xl';
        }
        balanceVisible = !balanceVisible;
    });

    // Search functionality
    const searchInput = document.getElementById('cryptoSearch');
    const cryptoItems = document.querySelectorAll('.crypto-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        cryptoItems.forEach(item => {
            const name = item.getAttribute('data-name');
            const symbol = item.getAttribute('data-symbol');
            
            if (name.includes(searchTerm) || symbol.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // TODO: Add price fetching functionality here
    // This would typically fetch real-time prices from your API
    document.addEventListener('DOMContentLoaded', function() {
        // Placeholder for price updates
        const priceElements = document.querySelectorAll('.crypto-price');
        const valueElements = document.querySelectorAll('.crypto-value');
        
        // You can add price fetching logic here
        priceElements.forEach(element => {
            element.textContent = 'Price loading...';
        });
    });
</script>