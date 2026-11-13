<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Polygon Transaction Hash: {{ substr($tx->hash ?? '', 0, 12) }}... | PolygonScan</title>
  <meta name="description" content="Transfer {{ $tx->amount ?? '0' }} {{ $tx->symbol ?? 'MATIC' }} to {{ substr($tx->to ?? '', 0, 6) }}...{{ substr($tx->to ?? '', -6) }} | {{ ucfirst($tx->status ?? 'pending') }} | {{ $tx->timestamp ?? 'N/A' }}">
  <link rel="icon" href="https://polygonscan.com/assets/generic/html/favicon.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'polygon-purple': '#8247e5',
            'polygon-light': '#f8f9fa',
            'polygon-dark': '#1a1a1a'
          }
        }
      }
    }
  </script>
  <style>
    .theme-light { background: #ffffff; color: #212529; }
    .theme-dim { background: #f8f9fa; color: #495057; }
    .theme-dark { background: #1a1a1a; color: #ffffff; }
    
    .nav-dropdown:hover .dropdown-content { display: block; }
    .dropdown-content { display: none; }
    
    @media (max-width: 768px) {
      .mobile-menu { display: none; }
      .mobile-menu.active { display: block; }
    }
  </style>
</head>
<body class="theme-light min-h-screen">
  <!-- Header -->
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <!-- Top Bar -->
    <div class="bg-gray-50 px-4 py-2 text-sm">
      <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between">
        <div class="flex items-center space-x-4">
          <span>{{ $tx->symbol ?? 'MATIC' }} Price: <a href="#" class="text-blue-600">${{ number_format($tx->rate ?? 0.195202, 6) }}</a> (-2.70%)</span>
          <span>Gas: <a href="#" class="text-blue-600">{{ $tx->gas_price_gwei ?? rand(30, 50) }} Gwei</a></span>
        </div>
        <div class="flex items-center space-x-2">
          <button class="p-1 rounded hover:bg-gray-200" title="Light Mode">☀️</button>
          <button class="p-1 rounded hover:bg-gray-200" title="Dim Mode">🌙</button>
          <button class="p-1 rounded hover:bg-gray-200" title="Dark Mode">🌚</button>
        </div>
      </div>
    </div>

    <!-- Main Navigation -->
    <nav class="px-4 py-3">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
          <img src="https://polygonscan.com/assets/poly/images/svg/logos/logo-light.svg" alt="PolygonScan" class="h-8">
          <div class="hidden md:flex items-center space-x-1 text-sm">
            <img src="https://polygonscan.com/assets/poly/images/svg/logos/chain-light.svg" alt="Polygon" class="h-4">
            <span>Polygon Mainnet</span>
          </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-6">
          <a href="#" class="text-gray-700 hover:text-polygon-purple">Home</a>
          <div class="nav-dropdown relative">
            <button class="text-gray-700 hover:text-polygon-purple flex items-center">
              Blockchain <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
            </button>
            <div class="dropdown-content absolute top-full left-0 bg-white border border-gray-200 rounded-lg shadow-lg py-2 w-48">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Transactions</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pending Transactions</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">View Blocks</a>
            </div>
          </div>
          <div class="nav-dropdown relative">
            <button class="text-gray-700 hover:text-polygon-purple flex items-center">
              Tokens <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
            </button>
            <div class="dropdown-content absolute top-full left-0 bg-white border border-gray-200 rounded-lg shadow-lg py-2 w-48">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Top Tokens (ERC-20)</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Token Transfers</a>
            </div>
          </div>
          <a href="#" class="text-gray-700 hover:text-polygon-purple">NFTs</a>
          <a href="#" class="text-gray-700 hover:text-polygon-purple">Resources</a>
          <a href="#" class="text-gray-700 hover:text-polygon-purple">Developers</a>
          <a href="#" class="text-gray-700 hover:text-polygon-purple">More</a>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 max-w-md mx-4">
          <div class="relative">
            <input type="text" placeholder="Search by Address / Txn Hash / Block / Token / Domain Name" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-polygon-purple" disabled>
            <button class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Sign In -->
        <div class="flex items-center space-x-4">
          <button class="bg-polygon-purple text-white px-4 py-2 rounded-lg hover:bg-purple-700">Sign In</button>
          <button class="lg:hidden p-2" onclick="toggleMobileMenu()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div id="mobileMenu" class="mobile-menu lg:hidden mt-4 pb-4 border-t border-gray-200">
        <div class="space-y-2 pt-4">
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Home</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Blockchain</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Tokens</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">NFTs</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Resources</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Developers</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">More</a>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
      <a href="#" class="hover:text-polygon-purple">Transaction Details</a>
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
      </svg>
    </div>

    <!-- Sponsored Banner -->
    <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-4 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <img src="https://polygonscan.com/images/gen/metawin2_20.png" alt="MetaWin" class="w-8 h-8">
          <div>
            <span class="font-semibold">MetaWin:</span>
            <span class="text-sm">Play & WIN $50k+ each Week!</span>
          </div>
        </div>
        <button class="bg-polygon-purple text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
          Claim Your FREE Entry Now
        </button>
      </div>
    </div>

    <!-- Transaction Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
      <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6">
          <button class="py-4 px-2 border-b-2 border-polygon-purple text-polygon-purple font-medium">
            Overview
          </button>
          <button class="py-4 px-2 text-gray-500 hover:text-gray-700">
            Logs (2)
          </button>
          <button class="py-4 px-2 text-gray-500 hover:text-gray-700">
            State
          </button>
        </nav>
      </div>

      <!-- Transaction Details -->
      <div class="p-6">
        <!-- Transaction Action -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
          <div class="flex items-center space-x-3">
            <div class="bg-blue-100 p-2 rounded-full">
              <img src="https://polygonscan.com/assets/poly/images/svg/logos/token-light.svg" alt="{{ $tx->symbol ?? 'MATIC' }}" class="w-6 h-6">
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">Transaction Action</h3>
              <p class="text-sm text-gray-600">
                Transfer <span class="font-medium">{{ number_format($tx->amount ?? 0, 6) }} {{ $tx->symbol ?? 'MATIC' }}</span> (${{ number_format(($tx->amount ?? 0) * ($tx->rate ?? 0.195202), 2) }}) to 
                <a href="#" class="text-blue-600 hover:underline">{{ $tx->to ?? 'N/A' }}</a>
              </p>
            </div>
          </div>
        </div>

        <!-- Transaction Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Transaction Hash:</span>
              <div class="flex items-center space-x-2">
                <code class="text-sm bg-gray-100 px-2 py-1 rounded break-all">
                  {{ $tx->hash ?? 'N/A' }}
                </code>
                <button class="text-gray-400 hover:text-gray-600" title="Copy">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Status:</span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                {{ $tx->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($tx->status ?? 'pending') }}
              </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Block:</span>
              <div class="flex items-center space-x-2">
                <a href="#" class="text-blue-600 hover:underline">{{ $tx->block_number ?? 'N/A' }}</a>
                <span class="text-sm text-gray-500">{{ $tx->confirmations ?? 0 }} Block Confirmations</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Timestamp:</span>
              <div class="text-sm">
                <span class="text-gray-900">{{ $tx->age ?? 'N/A' }}</span>
                <span class="text-gray-500">({{ $tx->timestamp ?? 'N/A' }})</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">From:</span>
              <a href="#" class="text-blue-600 hover:underline text-sm break-all">
                {{ $tx->from_address ?? 'N/A' }}
              </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">To:</span>
              <a href="#" class="text-blue-600 hover:underline text-sm break-all">
                {{ $tx->to ?? 'N/A' }}
              </a>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Value:</span>
              <div class="flex items-center space-x-2">
                <img src="https://polygonscan.com/assets/poly/images/svg/logos/token-light.svg" alt="{{ $tx->symbol ?? 'MATIC' }}" class="w-4 h-4">
                <span class="font-medium">{{ number_format($tx->amount ?? 0, 6) }} {{ $tx->symbol ?? 'MATIC' }}</span>
                <span class="text-gray-500">(${{ number_format(($tx->amount ?? 0) * ($tx->rate ?? 0.195202), 2) }})</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Transaction Fee:</span>
              <div class="text-sm">
                <span class="font-medium">{{ number_format($tx->fee_matic ?? 0, 12) }} {{ $tx->symbol ?? 'MATIC' }}</span>
                <span class="text-gray-500">(${{ number_format(($tx->fee_matic ?? 0) * ($tx->rate ?? 0.195202), 6) }})</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Gas Price:</span>
              <div class="text-sm">
                <span class="font-medium">{{ $tx->gas_price_gwei ?? 'N/A' }} Gwei</span>
                <span class="text-gray-500">({{ number_format($tx->network_fee ?? 0, 12) }} {{ $tx->symbol ?? 'MATIC' }})</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Gas Limit & Usage:</span>
              <div class="text-sm">
                <span class="font-medium">21,000 | 21,000</span>
                <span class="text-gray-500">(100%)</span>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Gas Fees:</span>
              <div class="text-sm space-y-1">
                <div>Base: <span class="font-medium">{{ rand(0, 1) ? '0.042019861' : '0.000000000' }} Gwei</span></div>
                <div>Max: <span class="font-medium">{{ $tx->gas_price_gwei ?? rand(30, 50) }} Gwei</span></div>
                <div>Max Priority: <span class="font-medium">{{ $tx->gas_price_gwei ? ($tx->gas_price_gwei - 0.042) : rand(29, 49) }} Gwei</span></div>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start justify-between py-3 border-b border-gray-100">
              <span class="text-gray-600 font-medium mb-1 sm:mb-0">Burnt & Txn Savings:</span>
              <div class="text-sm space-y-1">
                <div>🔥 Burnt: <span class="font-medium">{{ number_format(($tx->fee_matic ?? 0) * 0.1, 12) }} {{ $tx->symbol ?? 'MATIC' }}</span></div>
                <div>💸 Savings: <span class="font-medium">{{ number_format(($tx->fee_matic ?? 0) * 0.05, 12) }} {{ $tx->symbol ?? 'MATIC' }}</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <h4 class="font-semibold text-gray-900 mb-4">Other Attributes</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
              <span class="text-gray-600">Txn Type:</span>
              <span class="ml-2 font-medium">2 (EIP-1559)</span>
            </div>
            <div>
              <span class="text-gray-600">Nonce:</span>
              <span class="ml-2 font-medium">{{ rand(0, 1000) }}</span>
            </div>
            <div>
              <span class="text-gray-600">Position In Block:</span>
              <span class="ml-2 font-medium">{{ rand(1, 200) }}</span>
            </div>
          </div>
        </div>

        <!-- Input Data -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <h4 class="font-semibold text-gray-900 mb-4">Input Data</h4>
          <div class="bg-gray-50 rounded-lg p-4">
            <code class="text-sm text-gray-600">
              0x{{ str_pad(dechex((int)(($tx->amount ?? 0) * 1e18)), 64, '0', STR_PAD_LEFT) }}
            </code>
          </div>
        </div>

        <!-- Private Note -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <h4 class="font-semibold text-gray-900 mb-2">Private Note</h4>
          <p class="text-sm text-gray-600">
            To access the <strong>Private Note</strong> feature, you must be 
            <a href="#" class="text-blue-600 hover:underline">Logged In</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Transaction Receipt Event Logs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Transaction Receipt Event Logs</h3>
      </div>
      <div class="p-6">
        <!-- Log Entry 1 -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-900">515</span>
            <span class="text-xs text-gray-500">LogTransfer</span>
          </div>
          <div class="text-sm space-y-2">
            <div>
              <span class="text-gray-600">Address:</span>
              <a href="#" class="text-blue-600 hover:underline ml-2">
                0x0000000000000000000000000000000000001010
              </a>
            </div>
            <div>
              <span class="text-gray-600">Topics:</span>
              <div class="mt-1 space-y-1 text-xs font-mono bg-white p-2 rounded border">
                <div>0: e6497e3ee548a3372136af2fcb0696db31fc6cf20260707645068bd3fe97f3c4</div>
                <div>1: 0000000000000000000000000000000000001010</div>
                <div>2: {{ substr($tx->from_address ?? '', 2, 40) }}</div>
                <div>3: {{ substr($tx->to ?? '', 2, 40) }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Log Entry 2 -->
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-900">516</span>
            <span class="text-xs text-gray-500">LogFeeTransfer</span>
          </div>
          <div class="text-sm space-y-2">
            <div>
              <span class="text-gray-600">Address:</span>
              <a href="#" class="text-blue-600 hover:underline ml-2">
                0x0000000000000000000000000000000000001010
              </a>
            </div>
            <div>
              <span class="text-gray-600">Topics:</span>
              <div class="mt-1 space-y-1 text-xs font-mono bg-white p-2 rounded border">
                <div>0: 4dfe1bbbcf077ddc3e01291eea2d5c70c2b422b415d95645b9adcfd678cb1d63</div>
                <div>1: 0000000000000000000000000000000000001010</div>
                <div>2: {{ substr($tx->from_address ?? '', 2, 40) }}</div>
                <div>3: 7Ee41D8A25641000661B1EF5E6AE8A00400466B0</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Information Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
      <p class="text-sm text-blue-800">
        A transaction is a cryptographically signed instruction that changes the blockchain state. 
        Block explorers track the details of all transactions in the network. 
        <a href="#" class="text-blue-600 hover:underline">Learn more about transactions in our Knowledge Base</a>.
      </p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-50 border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Back to Top -->
      <div class="text-center mb-8">
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="text-blue-600 hover:text-blue-800 text-sm">
          Back to Top
        </button>
      </div>

      <!-- Footer Content -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Company -->
        <div>
          <h4 class="font-semibold text-gray-900 mb-4">Company</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Brand Assets</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Contact Us</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Terms & Privacy</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Bug Bounty</a></li>
          </ul>
        </div>

        <!-- Community -->
        <div>
          <h4 class="font-semibold text-gray-900 mb-4">Community</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="text-gray-600 hover:text-gray-900">API Documentation</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Knowledge Base</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Network Status</a></li>
          </ul>
        </div>

        <!-- Products & Services -->
        <div>
          <h4 class="font-semibold text-gray-900 mb-4">Products & Services</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Advertise</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Explorer as a Service</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">API Plans</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Priority Support</a></li>
            <li><a href="#" class="text-gray-600 hover:text-gray-900">Blockscan</a></li>
          </ul>
        </div>

        <!-- Powered By -->
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <img src="https://polygonscan.com/assets/poly/images/svg/logos/chain-light.svg" alt="Polygon" class="h-6">
            <span class="text-sm font-medium">Powered by Polygon PoS Chain</span>
          </div>
          <p class="text-xs text-gray-600 mb-4">
            PolygonScan is a Block Explorer and Analytics Platform for Polygon PoS Chain.
          </p>
          <button class="flex items-center space-x-2 text-sm text-blue-600 hover:text-blue-800">
            <img src="https://polygonscan.com/images/svg/brands/metamask.svg" alt="MetaMask" class="w-4 h-4">
            <span>Add Polygon Network</span>
          </button>
        </div>
      </div>

      <!-- Bottom Footer -->
      <div class="border-t border-gray-200 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
        <div class="mb-4 md:mb-0">
          <span>PolygonScan © 2025 (POLY-C1) | ⛏ Built by Team </span>
          <a href="#" class="text-blue-600 hover:underline font-semibold">Etherscan</a>
        </div>
        <div>
          <span>Donations: </span>
          <a href="#" class="text-blue-600 hover:underline">0x71c765...d8976f</a>
        </div>
      </div>

      <!-- Cookie Notice -->
      <div class="mt-4 text-xs text-gray-500 text-center">
        This website uses cookies to improve your experience. By continuing to use this website, you agree to its 
        <a href="#" class="text-blue-600 hover:underline">Terms</a> and 
        <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
      </div>
    </div>
  </footer>

  <script>
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
      const themeButtons = document.querySelectorAll('[title*="Mode"]');
      const body = document.body;
      
      themeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const title = this.getAttribute('title');
          body.className = body.className.replace(/theme-\w+/, '');
          
          if (title.includes('Light')) {
            body.classList.add('theme-light');
          } else if (title.includes('Dim')) {
            body.classList.add('theme-dim');
          } else if (title.includes('Dark')) {
            body.classList.add('theme-dark');
          }
        });
      });
    });

    document.addEventListener('click', function(e) {
      if (e.target.closest('[title="Copy"]')) {
        const codeElement = e.target.closest('.flex').querySelector('code');
        if (codeElement) {
          navigator.clipboard.writeText(codeElement.textContent);
          const button = e.target.closest('[title="Copy"]');
          const originalTitle = button.title;
          button.title = 'Copied!';
          setTimeout(() => {
            button.title = originalTitle;
          }, 2000);
        }
      }
    });
  </script>
</body>
</html>