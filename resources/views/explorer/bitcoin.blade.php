<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction: {{ $tx->hash }} | Blockchain.com</title>
  <meta name="description" content="Bitcoin transaction details from your receipt.">
  <link rel="icon" href="https://www.blockchain.com/explorer/_next/static/media/favicon.50daffc0.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .crypto-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }
    @media (min-width: 768px) { .crypto-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .crypto-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1280px) { .crypto-grid { grid-template-columns: repeat(4, 1fr); } }
    .nav-items {
      display: flex;
      flex-wrap: nowrap;
      white-space: nowrap;
      overflow-x: auto;
      gap: 24px;
    }
    @media (max-width: 768px) {
      .nav-items { display: none; }
      .mobile-menu { display: block; }
    }
    @media (min-width: 769px) {
      .mobile-menu { display: none; }
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <header class="bg-white border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <div class="flex items-center">
        <a href="/" class="flex items-center">
  <img 
    src="https://covachain.org/public/images/blockchain.png"
    alt="Blockchain.com"
    class="w-56 sm:w-64 lg:w-80 h-auto object-contain"
  >
</a>
      </div>
      
      <nav class="hidden md:flex nav-items">
        <a href="/explorer" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
        <a href="/explorer/prices" class="text-gray-700 hover:text-blue-600 font-medium">Prices</a>
        <a href="/explorer/nfts" class="text-gray-700 hover:text-blue-600 font-medium">NFTs</a>
        <a href="/wallet" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">Buy</a>
      </nav>

      <button class="mobile-menu md:hidden p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>

      <div class="hidden md:flex items-center space-x-4">
        <select class="text-sm border border-gray-300 rounded px-2 py-1">
          <option>English</option>
        </select>
        <a href="/signin" class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 font-medium">Sign In</a>
      </div>
    </div>
  </div>
</header>
 <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Transaction Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="flex items-center mb-4">
        <div class="bg-orange-100 p-2 rounded-lg mr-3">
          <span class="text-orange-600 font-bold text-sm">TX</span>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Bitcoin Transaction</h1>
          <p class="text-gray-600 text-sm">{{ $tx->timestamp }}</p>
        </div>
      </div>

      <!-- Transaction Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-sm font-medium text-gray-500 mb-1">Hash ID</h3>
          <p class="text-sm font-mono text-gray-900 break-all">{{ $tx->hash }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-sm font-medium text-gray-500 mb-1">Amount</h3>
          <p class="text-lg font-semibold text-gray-900">{{ number_format($tx->amount_btc, 8) }} BTC</p>
          <p class="text-sm text-gray-600">${{ number_format($tx->amount_usd, 2) }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-sm font-medium text-gray-500 mb-1">Fee</h3>
          <p class="text-lg font-semibold text-gray-900">{{ number_format($tx->network_fee ?? 0.1, 6) }} {{ $tx->symbol }}</p>
          <p class="text-sm text-gray-600">${{ number_format($tx->fee_usd, 2) }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-sm font-medium text-gray-500 mb-1">From/To</h3>
          <p class="text-sm text-gray-900 truncate">{{ substr($tx->from, 0, 10) }}...</p>
          <p class="text-sm text-gray-900 truncate">{{ substr($tx->to, 0, 10) }}...</p>
        </div>
      </div>

     <!-- Confirmation Status -->
@php
    $status = $tx->status ?? 'pending';
    $isCompleted = in_array($status, ['success', 'completed']);
    $isProcessing = in_array($status, ['pending', 'processing']);
    $isFailed = in_array($status, ['failed', 'cancelled', 'reversed']);
@endphp

@if($isCompleted)
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-medium text-green-800">Confirmed</p>
                <p class="text-sm text-green-700">This transaction has been confirmed on the Bitcoin network.</p>
            </div>
        </div>
    </div>
@elseif($isProcessing)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-medium text-yellow-800">Processing</p>
                <p class="text-sm text-yellow-700">This transaction is still pending confirmation.</p>
            </div>
        </div>
    </div>
@elseif($isFailed)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
                @if($status === 'failed')
                    <p class="font-medium text-red-800">Failed</p>
                    <p class="text-sm text-red-700">This transaction failed and was not confirmed.</p>
                @elseif($status === 'cancelled')
                    <p class="font-medium text-red-800">Cancelled</p>
                    <p class="text-sm text-red-700">This transaction was cancelled by the user.</p>
                @elseif($status === 'reversed')
                    <p class="font-medium text-red-800">Reversed</p>
                    <p class="text-sm text-red-700">This transaction was reversed due to an issue.</p>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Only show efficiency message for confirmed transactions --}}
@if($isCompleted)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-blue-800 font-medium">This transaction is efficient, no issues detected.</p>
    </div>
@endif

    <!-- Transaction Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Summary</h2>
        <p class="text-gray-700 leading-relaxed">
          This transaction was broadcasted on {{ $tx->timestamp }}.
          @if($tx->status === 'completed')
            It has been confirmed on the Bitcoin network.
          @else
            It is currently pending confirmation.
          @endif
          The total value is ${{ number_format($tx->amount_usd, 2) }}.
        </p>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Advanced Details</h2>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600">Hash</span>
            <span class="text-gray-900 font-mono text-sm">{{ substr($tx->hash, 0, 6) }}...{{ substr($tx->hash, -4) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Time</span>
            <span class="text-gray-900">{{ $tx->timestamp }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Age</span>
            <span class="text-gray-900">{{ $tx->age }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">From</span>
            <span class="text-gray-900 font-mono text-sm truncate">{{ $tx->from }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">To</span>
            <span class="text-gray-900 font-mono text-sm truncate">{{ $tx->to }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Amount</span>
            <span class="text-gray-900">{{ number_format($tx->amount_btc, 8) }} BTC</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Network Fee</span>
            <span class="text-gray-900">{{ number_format($tx->network_fee ?? 0.1, 6) }} {{ $tx->symbol }}</span>
            </div>
            <div class="flex justify-between">
            <span class="text-gray-600">Energy Fee Limit</span>
            <span class="text-gray-900">{{ number_format(($tx->network_fee ?? 0) * 1e8, 0) }} SATS</span>
             </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Rate (USD/BTC)</span>
            <span class="text-gray-900">${{ number_format($tx->rate, 2) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Transaction Flow -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Transaction Flow</h2>
        <div class="flex space-x-2">
          <button class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm">Overview</button>
          <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">JSON</button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- From -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3">From</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">1</p>
            <a href="/addresses/btc/{{ $tx->from }}" class="text-blue-600 hover:underline text-sm font-mono break-all">
              {{ $tx->from }}
            </a>
            <div class="mt-2">
              <p class="text-lg font-semibold">{{ number_format($tx->amount_btc + $tx->fee_btc, 8) }} BTC</p>
              <p class="text-sm text-gray-600">${{ number_format($tx->amount_usd + $tx->fee_usd, 2) }}</p>
            </div>
          </div>
        </div>

        <!-- Arrow -->
        <div class="flex items-center justify-center">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
        </div>

        <!-- To -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3">To</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">1</p>
            <a href="/addresses/btc/{{ $tx->to }}" class="text-blue-600 hover:underline text-sm font-mono break-all">
              {{ $tx->to }}
            </a>
            <div class="mt-2">
              <p class="text-lg font-semibold">{{ number_format($tx->amount_btc, 8) }} BTC</p>
              <p class="text-sm text-gray-600">${{ number_format($tx->amount_usd, 2) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Crypto Assets Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Explore top crypto assets.</h2>
      <div class="crypto-grid">
        @php
        $cryptoAssets = [
          ['name' => 'Bitcoin', 'symbol' => 'BTC'],
          ['name' => 'Ethereum', 'symbol' => 'ETH'],
          ['name' => 'Litecoin', 'symbol' => 'LTC'],
          ['name' => 'Bitcoin Cash', 'symbol' => 'BCH'],
          ['name' => 'Ripple', 'symbol' => 'XRP'],
          ['name' => 'Cardano', 'symbol' => 'ADA'],
          ['name' => 'Polkadot', 'symbol' => 'DOT'],
          ['name' => 'Chainlink', 'symbol' => 'LINK'],
          ['name' => 'Stellar', 'symbol' => 'XLM'],
          ['name' => 'Dogecoin', 'symbol' => 'DOGE'],
          ['name' => 'Uniswap', 'symbol' => 'UNI'],
          ['name' => 'Solana', 'symbol' => 'SOL'],
          ['name' => 'Polygon', 'symbol' => 'MATIC'],
          ['name' => 'Avalanche', 'symbol' => 'AVAX'],
          ['name' => 'Cosmos', 'symbol' => 'ATOM'],
          ['name' => 'Tezos', 'symbol' => 'XTZ']
        ];
        @endphp

        @foreach($cryptoAssets as $asset)
          <a href="/explorer/assets/{{ strtolower($asset['symbol']) }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
              <span class="text-white text-xs font-bold">{{ substr($asset['symbol'], 0, 2) }}</span>
            </div>
            <div>
              <p class="font-medium text-gray-900">{{ $asset['name'] }}</p>
              <p class="text-sm text-gray-600">{{ $asset['symbol'] }}</p>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center mb-4">
            <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="none">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z" fill="white"/>
            </svg>
            <span class="text-xl font-bold">Blockchain.com</span>
          </div>
          <p class="text-gray-400 text-sm">The easiest and most trusted transaction search engine and block explorer.</p>
        </div>
        <div><h3 class="font-semibold mb-4">Products</h3><ul class="space-y-2 text-sm text-gray-400"><li><a href="#" class="hover:text-white">Wallet</a></li><li><a href="#" class="hover:text-white">Exchange</a></li><li><a href="#" class="hover:text-white">Explorer</a></li><li><a href="#" class="hover:text-white">Institutional</a></li></ul></div>
        <div><h3 class="font-semibold mb-4">Learn</h3><ul class="space-y-2 text-sm text-gray-400"><li><a href="#" class="hover:text-white">Academy</a></li><li><a href="#" class="hover:text-white">Research</a></li><li><a href="#" class="hover:text-white">News</a></li><li><a href="#" class="hover:text-white">Charts</a></li></ul></div>
        <div><h3 class="font-semibold mb-4">Support</h3><ul class="space-y-2 text-sm text-gray-400"><li><a href="#" class="hover:text-white">Help Center</a></li><li><a href="#" class="hover:text-white">Contact</a></li><li><a href="#" class="hover:text-white">API</a></li><li><a href="#" class="hover:text-white">Status</a></li></ul></div>
      </div>
      <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
        <p>&copy; 2025 Blockchain.com. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    document.querySelector('.mobile-menu')?.addEventListener('click', function() {
      const nav = document.querySelector('.nav-items');
      nav?.classList.toggle('hidden');
    });

    function copyToClipboard(text) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
      }
    }

    document.querySelectorAll('.font-mono').forEach(el => {
      el.style.cursor = 'pointer';
      el.title = 'Click to copy';
      el.addEventListener('click', () => copyToClipboard(el.textContent.trim()));
    });
  </script>
</body>
</html>