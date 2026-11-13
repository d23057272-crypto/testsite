<x-admin-layout>
    @php
        // 🔁 SYMBOL TO COINGECKO ID MAPPING (VERIFIED)
        $symbolToId = [
            'btc' => 'bitcoin',
            'eth' => 'ethereum',
            'usdt' => 'tether',
            'usdt_erc20' => 'tether',
            'usdt_trc20' => 'tether',
            'usdt_bep20' => 'tether',
            'usdt_polygon' => 'tether',
            'bnb' => 'binancecoin',
            'sol' => 'solana',
            'xrp' => 'ripple',
            'doge' => 'dogecoin',
            'ltc' => 'litecoin',
            'bch' => 'bitcoin-cash',
            'dash' => 'dash',
            'xlm' => 'stellar',
            'trx' => 'tron',
            'xdc' => 'xdc-network',
            'dot' => 'polkadot',
            'matic' => 'matic-network',
            'ada' => 'cardano',
            'avax' => 'avalanche-2',
            'shib' => 'shiba-inu',
        ];

        $supportedCryptos = \App\Models\CryptoAsset::getSupportedCryptos();
        $symbols = array_keys($supportedCryptos);
        
        // Get unique CoinGecko IDs
        $ids = [];
        foreach ($symbols as $symbol) {
            $baseSymbol = str_contains($symbol, '_') ? explode('_', $symbol, 2)[0] : $symbol;
            $id = $symbolToId[$baseSymbol] ?? null;
            if ($id && !in_array($id, $ids)) {
                $ids[] = $id;
            }
        }

        $cryptoPrices = [];
        $priceFetchError = null;
        $lastUpdated = null;
        
        if (!empty($ids)) {
            $idsList = implode(',', $ids);
            $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids={$idsList}&order=market_cap_desc&per_page=250&page=1&sparkline=false";
            
            $context = stream_context_create([
                "http" => [
                    "timeout" => 10,
                    "ignore_errors" => true,
                    "method" => "GET",
                    "header" => implode("\r\n", [
                        "User-Agent: Mozilla/5.0 (compatible; CryptoTracker/1.0; +https://yourdomain.com)",
                        "Accept: application/json",
                        "Accept-Language: en-US,en;q=0.9",
                        "Connection: close"
                    ])
                ]
            ]);
            
            $json = @file_get_contents($url, false, $context);
            $httpStatus = null;
            
            if (isset($http_response_header)) {
                foreach ($http_response_header as $header) {
                    if (preg_match('/^HTTP\/\d\.\d (\d+)/', $header, $matches)) {
                        $httpStatus = $matches[1];
                        break;
                    }
                }
            }

            if ($httpStatus === '200' && $json !== false) {
                $markets = json_decode($json, true);
                if (is_array($markets)) {
                    $lastUpdated = now()->format('Y-m-d H:i:s');
                    $priceLookup = [];
                    foreach ($markets as $coin) {
                        $priceLookup[$coin['id']] = $coin['current_price'] ?? 0;
                    }
                    
                    foreach ($symbols as $symbol) {
                        $baseSymbol = str_contains($symbol, '_') ? explode('_', $symbol, 2)[0] : $symbol;
                        $id = $symbolToId[$baseSymbol] ?? null;
                        $cryptoPrices[$symbol] = ($id && isset($priceLookup[$id])) ? (float) $priceLookup[$id] : 0;
                    }
                } else {
                    $priceFetchError = 'Invalid response format';
                }
            } else {
                $priceFetchError = "API failed (HTTP {$httpStatus})";
                \Log::warning('CoinGecko API failure', ['status' => $httpStatus]);
            }
        }

        foreach ($symbols as $symbol) {
            $cryptoPrices[$symbol] = $cryptoPrices[$symbol] ?? 0;
        }
        
        // Prepare asset data for JavaScript
        $assetData = [];
        foreach ($symbols as $symbol) {
            $baseSymbol = str_contains($symbol, '_') ? explode('_', $symbol, 2)[0] : $symbol;
            $balance = $cryptoAssets->{$symbol.'_balance'} ?? 0;
            $address = $cryptoAssets->{$symbol.'_address'} ?? '';
            $status = $cryptoAssets->{$symbol.'_status'} ?? false;
            
            // Determine if stablecoin (price always ~$1)
            $isStable = in_array(strtoupper($baseSymbol), ['USDT', 'USDC', 'DAI', 'BUSD', 'USDP', 'TUSD']);
            
            $assetData[$symbol] = [
                'symbol' => $symbol,
                'baseSymbol' => $baseSymbol,
                'balance' => (float) $balance,
                'address' => $address,
                'status' => $status,
                'isStable' => $isStable,
                'initialPrice' => $cryptoPrices[$symbol] ?? 0,
                'network' => $supportedCryptos[$symbol]['network'] ?? null,
                'name' => $supportedCryptos[$symbol]['name'] ?? strtoupper($baseSymbol),
            ];
        }
    @endphp

    <div class="container px-6 mx-auto">
        <div class="flex justify-between items-center my-6">
            <h2 class="text-2xl font-semibold text-gray-700">
                Wallets for {{ $user->name ?? 'Unknown User' }}
                <span class="text-sm text-gray-500 ml-2">(ID: {{ $user->uuid ?? 'N/A' }})</span>
            </h2>
            
            <div class="text-right">
                <p class="text-sm text-gray-600 flex items-center justify-end">
                    Total Balance 
                    <span id="realtime-indicator" class="ml-2 w-2 h-2 bg-yellow-400 rounded-full animate-pulse" title="Prices updating in real-time"></span>
                </p>
                <p id="total-balance-display" class="text-2xl font-bold text-purple-600">
                    ${{ number_format($user->getTotalBalanceUSD() ?? 0, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    <span id="price-source">Initial prices from CoinGecko</span>
                    <span id="last-update-time" class="ml-2">{{ $lastUpdated ? 'Updated: ' . now()->parse($lastUpdated)->diffForHumans() : '' }}</span>
                </p>
            </div>
        </div>

        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />
        
        @if($priceFetchError)
            <div class="mb-4 p-3 bg-yellow-50 text-yellow-700 rounded-lg text-sm flex items-center">
                <i class="ri-alert-line mr-2"></i>
                <span>{{ $priceFetchError }}. Real-time prices will activate shortly.</span>
            </div>
        @endif

        <!-- Crypto Assets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="crypto-assets-grid">
            @foreach($supportedCryptos as $symbol => $crypto)
                @php
                    $price = $cryptoPrices[$symbol] ?? 0;
                    $balance = $cryptoAssets->{$symbol.'_balance'} ?? 0;
                    $usdValue = $price > 0 ? $balance * $price : 0;
                    $baseSymbol = str_contains($symbol, '_') ? explode('_', $symbol, 2)[0] : $symbol;
                    $isStable = in_array(strtoupper($baseSymbol), ['USDT', 'USDC', 'DAI', 'BUSD']);
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow"
                     data-symbol="{{ $symbol }}"
                     data-base-symbol="{{ $baseSymbol }}"
                     data-balance="{{ $balance }}"
                     data-is-stable="{{ $isStable ? 'true' : 'false' }}"
                     data-initial-price="{{ $price }}">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-50">
                                    <img 
                                        src="{{ asset('images/' . ($crypto['name'] === 'USDT' && isset($crypto['network']) ? strtolower('usdt_' . $crypto['network']) : strtolower($crypto['name'])) . '-icon.png') }}" 
                                        onerror="this.src='{{ asset('images/default-crypto-icon.png') }}'"
                                        alt="{{ $crypto['name'] }} icon" 
                                        class="w-5 h-5 object-contain"
                                    >
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $crypto['name'] }}</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ strtoupper($symbol) }}
                                        @if(isset($crypto['network']))
                                            <span class="ml-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded-full text-[10px] font-medium">
                                                {{ $crypto['network'] }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin.users.crypto.update', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_update" value="1">
                                <input type="hidden" name="crypto" value="{{ $symbol }}">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer"
                                           name="{{ $symbol }}_status"
                                           value="1"
                                           {{ $cryptoAssets->{$symbol.'_status'} ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </form>
                        </div>
                        
                        {{-- Real-time USD value display --}}
                        <div class="mt-2">
                            <p class="text-sm font-bold text-gray-800">
                                ≈ $<span class="asset-usd-value" data-symbol="{{ $symbol }}">{{ number_format($usdValue, 2) }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                ≈$<span class="asset-price" data-symbol="{{ $symbol }}">{{ number_format($price, $price < 0.01 ? 8 : 2) }}</span> 
                                per {{ $crypto['name'] }}
                                <span class="price-change inline-block ml-1 text-[10px] font-medium" data-symbol="{{ $symbol }}"></span>
                            </p>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Balance</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ number_format($balance, $balance < 0.01 ? 8 : 4) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Wallet Address</p>
                            <div class="flex items-center space-x-2">
                                <input type="text" 
                                       value="{{ $cryptoAssets->{$symbol.'_address'} ?? 'Not generated' }}" 
                                       class="text-xs bg-gray-50 border border-gray-300 rounded px-2 py-1 w-full font-mono truncate"
                                       readonly>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ $cryptoAssets->{$symbol.'_address'} ?? '' }}', this)"
                                        class="p-1.5 text-gray-400 hover:text-purple-600 transition-colors rounded hover:bg-gray-100"
                                        title="Copy address">
                                    <i class="ri-file-copy-line text-base"></i>
                                </button>
                            </div>
                        </div>

                        <form id="update-crypto-{{ $symbol }}" 
                              action="{{ route('admin.users.crypto.update', $user) }}" 
                              method="POST" 
                              class="space-y-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="crypto" value="{{ $symbol }}">
                            
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="amount" 
                                    step="0.00000001"
                                    min="0"
                                    id="amount-{{ $symbol }}"
                                    class="block w-full pl-3 pr-20 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                    placeholder="0.00"
                                    data-price="{{ $price }}"
                                    data-symbol="{{ $symbol }}"
                                    oninput="updateUsdInline('{{ $symbol }}')"
                                    onwheel="this.blur()">
                                
                                <div id="usd-inline-{{ $symbol }}" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500 text-sm">
                                    $0.00
                                </div>
                            </div>

                            <div class="flex space-x-2 pt-1">
                                <button type="submit" 
                                        name="action" 
                                        value="add"
                                        class="flex-1 px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                    <i class="ri-add-line mr-1 align-middle"></i> Add
                                </button>
                                <button type="submit" 
                                        name="action" 
                                        value="subtract"
                                        class="flex-1 px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    <i class="ri-subtract-line mr-1 align-middle"></i> Subtract
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Real-Time Price Engine --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Asset configuration from PHP
            const assets = @json($assetData);
            const binanceSymbolMap = {
                'btc': 'btcusdt',
                'eth': 'ethusdt',
                'bnb': 'bnbusdt',
                'sol': 'solusdt',
                'xrp': 'xrpusdt',
                'doge': 'dogeusdt',
                'ltc': 'ltcusdt',
                'bch': 'bchusdt',
                'dash': 'dashusdt',
                'xlm': 'xlmusdt',
                'trx': 'trxusdt',
                'dot': 'dotusdt',
                'matic': 'maticusdt',
                'ada': 'adausdt',
                'avax': 'avaxusdt',
                'shib': 'shibusdt',
                // Add more as needed
            };
            
            // Current prices storage
            const currentPrices = {};
            let ws = null;
            let reconnectTimeout = null;
            let lastPing = Date.now();
            const PING_INTERVAL = 60000; // 60 seconds
            
            // Initialize with CoinGecko prices
            Object.entries(assets).forEach(([symbol, data]) => {
                if (data.isStable) {
                    currentPrices[symbol] = 1.0;
                    updateAssetDisplay(symbol, 1.0, 0);
                } else if (data.initialPrice > 0) {
                    currentPrices[symbol] = data.initialPrice;
                    updateAssetDisplay(symbol, data.initialPrice, 0);
                }
            });
            
            // Calculate initial total balance
            updateTotalBalance();
            document.getElementById('price-source').textContent = 'Real-time prices via Binance WebSocket';
            document.getElementById('realtime-indicator').classList.replace('bg-yellow-400', 'bg-green-500');
            
            // Build Binance streams
            const streams = Object.values(assets)
                .filter(asset => !asset.isStable && binanceSymbolMap[asset.baseSymbol])
                .map(asset => `${binanceSymbolMap[asset.baseSymbol]}@ticker`)
                .filter((value, index, self) => self.indexOf(value) === index); // Unique streams
            
            if (streams.length > 0) {
                connectWebSocket(streams);
            } else {
                console.warn('No tradable symbols found for WebSocket connection');
                document.getElementById('realtime-indicator').classList.replace('bg-green-500', 'bg-yellow-400');
            }
            
            // WebSocket connection handler
            function connectWebSocket(streams) {
                if (ws) ws.close();
                
                const streamUrl = `wss://stream.binance.com:9443/stream?streams=${streams.join('/')}`;
                ws = new WebSocket(streamUrl);
                
                ws.onopen = () => {
                    console.log('✓ Binance WebSocket connected');
                    document.getElementById('realtime-indicator').classList.replace('bg-yellow-400', 'bg-green-500');
                    document.getElementById('last-update-time').textContent = 'Live: Updating in real-time';
                    lastPing = Date.now();
                    
                    // Start ping interval to keep connection alive
                    setInterval(() => {
                        if (ws && ws.readyState === WebSocket.OPEN && Date.now() - lastPing > PING_INTERVAL) {
                            ws.ping(); // Binance doesn't require ping, but we track activity
                        }
                    }, PING_INTERVAL);
                };
                
                ws.onmessage = (event) => {
                    lastPing = Date.now();
                    try {
                        const data = JSON.parse(event.data);
                        if (!data.data || !data.data.s || !data.data.c) return;
                        
                        // Find matching asset symbol
                        const binanceSymbol = data.data.s.toLowerCase(); // e.g., "btcusdt"
                        const price = parseFloat(data.data.c); // Current price
                        const priceChangePercent = parseFloat(data.data.P) || 0; // 24h change %
                        
                        // Find our symbol that matches this Binance stream
                        for (const [symbol, asset] of Object.entries(assets)) {
                            if (asset.isStable) continue;
                            
                            const mappedSymbol = binanceSymbolMap[asset.baseSymbol];
                            if (mappedSymbol && mappedSymbol === binanceSymbol) {
                                currentPrices[symbol] = price;
                                updateAssetDisplay(symbol, price, priceChangePercent);
                                break;
                            }
                        }
                        
                        updateTotalBalance();
                    } catch (e) {
                        console.error('WebSocket message error:', e);
                    }
                };
                
                ws.onerror = (error) => {
                    console.error('WebSocket error:', error);
                    document.getElementById('realtime-indicator').classList.replace('bg-green-500', 'bg-red-500');
                };
                
                ws.onclose = () => {
                    console.warn('WebSocket disconnected. Reconnecting in 5s...');
                    document.getElementById('realtime-indicator').classList.replace('bg-green-500', 'bg-yellow-400');
                    document.getElementById('last-update-time').textContent = 'Reconnecting...';
                    
                    if (reconnectTimeout) clearTimeout(reconnectTimeout);
                    reconnectTimeout = setTimeout(() => {
                        connectWebSocket(streams);
                    }, 5000);
                };
            }
            
            // Update individual asset display
            function updateAssetDisplay(symbol, price, changePercent) {
                // Update price display
                const priceEl = document.querySelector(`.asset-price[data-symbol="${symbol}"]`);
                if (priceEl) {
                    priceEl.textContent = price < 0.01 ? price.toFixed(8) : price.toFixed(2);
                }
                
                // Update USD value display
                const balance = assets[symbol].balance || 0;
                const usdValue = balance * price;
                const usdEl = document.querySelector(`.asset-usd-value[data-symbol="${symbol}"]`);
                if (usdEl) {
                    usdEl.textContent = usdValue.toFixed(2);
                }
                
                // Update price change indicator
                const changeEl = document.querySelector(`.price-change[data-symbol="${symbol}"]`);
                if (changeEl) {
                    if (Math.abs(changePercent) > 0.01) {
                        const isPositive = changePercent >= 0;
                        changeEl.textContent = `${isPositive ? '+' : ''}${changePercent.toFixed(2)}%`;
                        changeEl.className = `price-change inline-block ml-1 text-[10px] font-medium ${
                            isPositive ? 'text-green-600' : 'text-red-600'
                        }`;
                    } else {
                        changeEl.textContent = '';
                        changeEl.className = 'price-change inline-block ml-1 text-[10px] font-medium';
                    }
                }
                
                // Update adjustment form
                const input = document.getElementById(`amount-${symbol}`);
                if (input) {
                    input.dataset.price = price;
                    if (input.value) {
                        updateUsdInline(symbol);
                    }
                }
            }
            
            // Recalculate total balance across all assets
            function updateTotalBalance() {
                let total = 0;
                
                Object.entries(assets).forEach(([symbol, asset]) => {
                    const price = currentPrices[symbol] || asset.initialPrice || 0;
                    total += (asset.balance || 0) * price;
                });
                
                const totalEl = document.getElementById('total-balance-display');
                if (totalEl) {
                    totalEl.textContent = '$' + total.toFixed(2);
                }
            }
            
            // Initialize USD previews
            document.querySelectorAll('[id^="amount-"]').forEach(input => {
                const symbol = input.dataset.symbol;
                if (symbol && currentPrices[symbol]) {
                    input.dataset.price = currentPrices[symbol];
                    updateUsdInline(symbol);
                }
            });
        });

        // Existing helper functions (enhanced)
        function updateUsdInline(symbol) {
            const input = document.getElementById('amount-' + symbol);
            const display = document.getElementById('usd-inline-' + symbol);
            if (!input || !display) return;

            const amount = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            const usd = amount * price;

            if (price <= 0) {
                display.innerHTML = '<span class="text-red-500 text-[10px]">No price</span>';
                return;
            }

            display.textContent = '$' + (usd > 0 ? usd.toFixed(2) : '0.00');
            display.className = 'absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-sm ' + 
                (usd > 0 ? 'text-green-600 font-medium' : 'text-gray-400');
        }

        function copyToClipboard(text, btn) {
            if (!text || text === 'Not generated') {
                alert('No valid address to copy');
                return;
            }
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => showCopyFeedback(btn))
                    .catch(err => console.error('Copy failed:', err));
            } else {
                const el = document.createElement('textarea');
                el.value = text;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                try {
                    document.execCommand('copy');
                    showCopyFeedback(btn);
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                }
                document.body.removeChild(el);
            }
        }

        function showCopyFeedback(btn) {
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ri-check-line text-green-500"></i>';
            btn.title = 'Copied!';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.title = 'Copy address';
            }, 1500);
        }
    </script>
</x-admin-layout>