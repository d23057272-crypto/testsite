<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction {{ substr($tx->hash, 0, 12) }}... | TRONSCAN</title>
  <meta name="description" content="TRONSCAN is the first blockchain browser in the tron community. It supports multiple login methods and provides a complete browsing and search experience.">
  <link rel="icon" href="https://tronscan.io/favicon.png?v=2" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-shadow {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .hover-scale {
      transition: transform 0.2s ease-in-out;
    }
    .hover-scale:hover {
      transform: scale(1.02);
    }
    .status-confirmed {
      background: linear-gradient(90deg, #10b981, #059669);
    }
    .trx-icon, .usdt-icon {
      width: 20px;
      height: 20px;
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
    }
    .trx-icon {
      background-image: url('https://static.tronscan.org/production/logo/trx.png');
    }
    .usdt-icon {
      background-image: url('https://static.tronscan.org/production/logo/usdt.png');
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <img src="https://tronscan.io/static-20251028140439/media/logo_left.75928ad1b469e9b7f18200c4605d3396.svg" alt="TRONSCAN" class="h-8">
        </div>
        <nav class="hidden md:flex items-center space-x-8">
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Blockchain</a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Tokens</a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Data</a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Governance</a>
        </nav>
        <div class="flex items-center space-x-4">
          <div class="hidden sm:flex items-center space-x-2 text-sm">
            <span class="text-gray-600">{{ $tx->symbol }}:</span>
            <span class="text-green-600 font-semibold">Loading...</span>
          </div>
          <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Connect Wallet
          </button>
        </div>
        <button class="md:hidden p-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="relative">
        <input type="text" placeholder="Search by Token / Account / Contract / Transaction / Block" 
               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
    </div>
  </div>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="mb-6">
      <div class="flex items-center space-x-2 text-sm text-gray-600">
        <a href="#" class="hover:text-blue-600">Home</a>
        <span>-</span>
        <span class="text-gray-900">Transaction Details</span>
      </div>
    </nav>

    <div class="bg-white rounded-xl card-shadow p-6 mb-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Transaction Details</h1>

      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
          <div class="flex-1">
            <div class="flex items-center space-x-2 mb-2">
              <a href="#" class="text-blue-600 hover:text-blue-800 font-mono text-sm break-all">
                {{ $tx->from_address ?? 'N/A' }}
              </a>
            </div>
            <div class="text-gray-600 text-sm mb-2">transferred</div>
            <div class="flex items-center space-x-2 text-xl font-bold text-gray-900">
              <span>{{ number_format($tx->amount ?? 0, 6, '.', ',') }}</span>
              @if($tx->symbol === 'USDT')
                <div class="usdt-icon"></div>
                <span class="text-green-600">USDT</span>
              @else
                <div class="trx-icon"></div>
                <span class="text-blue-600">TRX</span>
              @endif
            </div>
          </div>

          <div class="flex items-center justify-center px-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </div>

          <div class="flex-1 text-right lg:text-left">
            <div class="text-gray-600 text-sm mb-2">to</div>
            <a href="#" class="text-blue-600 hover:text-blue-800 font-mono text-sm break-all">
              {{ $tx->to_address ?? 'N/A' }}
            </a>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-200">
            <span class="text-gray-600 font-medium mb-1 sm:mb-0">Hash:</span>
            <span class="font-mono text-sm break-all text-gray-900">
              {{ $tx->hash ?? 'N/A' }}
            </span>
          </div>

         <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-200">
    <span class="text-gray-600 font-medium mb-1 sm:mb-0">Result:</span>
    @php
        $statusClass = match($tx->status) {
            'success' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reversed', 'cancelled', 'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };

        $statusText = match($tx->status) {
            'success' => 'SUCCESSFUL',
            'pending' => 'PENDING',
            'reversed' => 'REVERSED',
            'cancelled' => 'CANCELLED',
            'failed' => 'FAILED',
            default => strtoupper($tx->status ?? 'UNKNOWN'),
        };
    @endphp
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
        {{ $statusText }}
    </span>
</div>

          <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-200">
            <span class="text-gray-600 font-medium mb-1 sm:mb-0">Block & Time:</span>
            <div class="text-right">
              <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                {{ $tx->block_number ?? 'N/A' }}
              </a>
              <div class="text-sm text-gray-500">
                {{ $tx->created_at ? $tx->created_at->diffForHumans() : 'N/A' }}
              </div>
              <div class="text-sm text-gray-500">
                {{ $tx->created_at ? $tx->created_at->utc()->format('Y-m-d H:i:s') : 'N/A' }} (UTC)
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-200">
            <span class="text-gray-600 font-medium mb-1 sm:mb-0">Status:</span>
            <div class="text-right">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium status-confirmed text-white">
                CONFIRMED
              </span>
              <div class="text-sm text-gray-500 mt-1">Confirmed by over 200 blocks</div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-200">
            <span class="text-gray-600 font-medium mb-1 sm:mb-0">Confirmed SRs:</span>
            <div class="text-right">
              <div class="font-semibold text-gray-900 mb-2">{{ $randomSrs->count() }}</div>
              <div class="space-y-1 text-sm">
                @foreach($randomSrs as $sr)
                  <div class="text-blue-600 hover:text-blue-800 cursor-pointer">{{ $sr }}</div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-200">
            <span class="text-gray-600 font-medium mb-1 sm:mb-0">Resources Consumed & Fee:</span>
            <div class="text-right">
              <div class="text-sm text-gray-500 mb-1">
                Energy Fee Limit: {{ number_format($tx->fee_limit ?? 0.1, 1) }} {{ $tx->symbol }}
              </div>
              <div class="font-semibold text-gray-900">
                {{ $tx->bandwidth_used ?? 'N/A' }} Bandwidth
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl card-shadow p-6">
      <h2 class="text-xl font-bold text-gray-900 mb-6">Transfer Details</h2>
      <div class="overflow-x-auto">
        <table class="w-full">
          <tbody class="divide-y divide-gray-200">
            <tr>
              <td class="py-4 pr-4 text-gray-600 font-medium whitespace-nowrap">From:</td>
              <td class="py-4 font-mono text-sm break-all">
                <a href="#" class="text-blue-600 hover:text-blue-800">
                  {{ $tx->from_address ?? 'N/A' }}
                </a>
              </td>
            </tr>
            <tr>
              <td class="py-4 pr-4 text-gray-600 font-medium whitespace-nowrap">To:</td>
              <td class="py-4 font-mono text-sm break-all">
                <a href="#" class="text-blue-600 hover:text-blue-800">
                  {{ $tx->to_address ?? 'N/A' }}
                </a>
              </td>
            </tr>
            <tr>
              <td class="py-4 pr-4 text-gray-600 font-medium whitespace-nowrap">Amount:</td>
              <td class="py-4">
                <div class="flex items-center space-x-2">
                  <span class="font-semibold text-gray-900">
                    {{ number_format($tx->amount ?? 0, 6, '.', ',') }}
                  </span>
                  @if($tx->symbol === 'USDT')
                    <div class="usdt-icon"></div>
                    <span class="text-green-600 font-medium">USDT</span>
                  @else
                    <div class="trx-icon"></div>
                    <span class="text-blue-600 font-medium">TRX</span>
                  @endif
                </div>
              </td>
            </tr>
             <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-200">
    <span class="text-gray-600 font-medium mb-1 sm:mb-0">Resources Consumed & Fee:</span>
    <div class="text-right">
        <div class="text-sm text-gray-500 mb-1">
            Energy Fee Limit: {{ number_format($tx->network_fee ?? 0.1, 6) }} {{ $tx->symbol }}
        </div>
        <div class="font-semibold text-gray-900">
            {{ $tx->bandwidth_used ?? rand(200, 500) }} Bandwidth
        </div>
    </div>
</div>
<!-- Rate -->
<div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-200">
    <span class="text-gray-600 font-medium mb-1 sm:mb-0">Rate:</span>
    <div class="text-right">
        @if($tx->rate)
            ${{ number_format($tx->rate, 2) }} / {{ $tx->symbol }}
        @else
            <span class="text-gray-500">N/A</span>
        @endif
    </div>
</div>
          </tbody>
        </table>
      </div>

      <div class="mt-8 p-4 bg-gray-50 rounded-lg">
        <div class="flex items-center justify-between">
          <span class="text-gray-600 font-medium">Private Note:</span>
          <div class="text-sm">
            <span class="text-gray-500">To access this feature, please </span>
            <a href="#" class="text-blue-600 hover:text-blue-800">Log In</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
          <img src="https://tronscan.io/static-20251028140439/media/logo_left.75928ad1b469e9b7f18200c4605d3396.svg" alt="TRONSCAN" class="h-8 mb-4 filter brightness-0 invert">
          <p class="text-gray-400 text-sm">The best blockchain explorer of TRON</p>
          <div class="flex space-x-4 mt-6">
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
              </svg>
            </a>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">About Us</h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Services & Support</h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">API</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Advertise</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Resources</h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">TRON ETF</a></li>
            <li><a href="#" class="hover:text-white transition-colors">TRON Architecture</a></li>
            <li><a href="#" class="hover:text-white transition-colors">TRON Whitepaper V2.1</a></li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-400">
        <p>Copyright© 2017-2025 tronscan.io</p>
      </div>
    </div>
  </footer>

  <div id="cookieNotice" class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-4 z-50">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
      <p class="text-sm text-gray-600">
        This website uses cookies to enhance your experience. By continuing to browse, you agree to its 
        <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> and 
        <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>.
      </p>
      <button onclick="hideCookieNotice()" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition-colors whitespace-nowrap">
        Got It
      </button>
    </div>
  </div>

  <script>
    function hideCookieNotice() {
      document.getElementById('cookieNotice').style.display = 'none';
    }
    setTimeout(hideCookieNotice, 10000);

    let dots = 0;
    setInterval(() => {
      const el = document.querySelector('.text-green-600, .text-blue-600');
      if (el && el.textContent.includes('Loading')) {
        dots = (dots + 1) % 4;
        el.textContent = 'Loading' + '.'.repeat(dots);
      }
    }, 500);
  </script>
</body>
</html>