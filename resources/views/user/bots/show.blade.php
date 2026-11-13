<x-user-layout :title="'Launch Bots'">
    <!-- Outer Container: Light => bg-gray-100, Dark => bg-gray-900 -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="space-y-4">

            {{-- =========================
                 TOP STATS BAR
            ========================== --}}
            <!-- For light mode: bg-gray-100, border-gray-200
                 For dark mode:  bg-gray-800, border-gray-700 -->
            <div class="w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-2 p-2 text-sm">

                    <!-- Pair Selection & Icon -->
                    <div class="relative flex items-center gap-2">
                        <!-- Dropdown Trigger -->
                        <button type="button"
                                class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded flex items-center justify-between gap-2"
                                onclick="toggleDropdown('pairDropdown')">
                            <img id="pairIcon" 
                                 src="/btc-icon.png" 
                                 alt="BTC" 
                                 class="w-6 h-6">
                            <span id="selectedPairText">BTC/USDT</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="pairDropdown" 
                             class="hidden absolute z-10 mt-[230px] bg-gray-100 dark:bg-gray-700 rounded shadow-lg">
                            <div class="py-1 max-h-48 overflow-y-auto">
                                @php
                                    $cryptos = auth()->user()->cryptoAssets
                                        ? auth()->user()->cryptoAssets->getActiveCryptos()
                                        : [];
                                @endphp
                                @foreach($cryptos as $crypto)
                                    @continue(
                                        strtoupper($crypto['symbol']) === 'USDT_TRC20' || 
                                        strtoupper($crypto['symbol']) === 'USDT_ERC20' || 
                                        strtoupper($crypto['symbol']) === 'USDT_BEP20'
                                    )
                                    <a href="#"
                                       class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center gap-2"
                                       data-symbol="{{ strtoupper($crypto['symbol']) }}"
                                       data-icon="{{ strtolower($crypto['symbol']) }}-icon.png">
                                        <img src="/bot/{{ strtolower($crypto['symbol']) }}-icon.png" 
                                             alt="{{ $crypto['symbol'] }}" 
                                             class="w-5 h-5">
                                        {{ strtoupper($crypto['symbol']) }}/USDT
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Last Price / Volume Label -->
                    <div>
                        <span class="text-gray-600 dark:text-gray-400" id="baseVolumeLabel">BTC Volume:</span><br>
                        <span id="baseVolume" class="text-gray-800 dark:text-gray-100 ml-1">0.00</span>
                    </div>

                    <!-- 24h Change -->
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">24h Change:</span><br>
                        <span id="24hChange" class="text-red-500 ml-1">0.00%</span>
                    </div>

                    <!-- 24h High -->
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">24h High:</span><br>
                        <span id="24hHigh" class="text-gray-800 dark:text-gray-100 ml-1">0.00</span>
                    </div>

                    <!-- 24h Low -->
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">24h Low:</span><br>
                        <span id="24hLow" class="text-gray-800 dark:text-gray-100 ml-1">0.00</span>
                    </div>
                </div>
            </div>

            {{-- =========================
                 MAIN TRADING AREA
            ========================== --}}
            <div class="grid md:grid-cols-3 grid-cols-1 gap-4">

                <!-- Chart Area -->
                <!-- Light: bg-gray-100, Dark: bg-gray-800 -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded">
                    <div class="flex items-center gap-2 p-3 border-b border-gray-200 dark:border-gray-600">
                        <!-- Interval Buttons -->
                        <button data-interval="1"  class="chart-interval text-gray-600 dark:text-gray-300 px-3 py-1 text-sm hover:bg-gray-100 hover:dark:bg-gray-700 rounded">1m</button>
                        <button data-interval="30" class="chart-interval text-gray-600 dark:text-gray-300 px-3 py-1 text-sm hover:bg-gray-100 hover:dark:bg-gray-700 rounded">30m</button>
                        <button data-interval="60" class="chart-interval text-gray-600 dark:text-gray-300 px-3 py-1 text-sm hover:bg-gray-100 hover:dark:bg-gray-700 rounded">1h</button>
                    </div>
                    <div class="h-[300px] md:h-[600px]">
                        <div id="chart" class="w-full h-full"></div>
                    </div>
                </div>

                <!-- Order Book (Desktop) -->
                <div class="hidden md:block bg-white dark:bg-gray-800 rounded">
                    <div class="grid grid-cols-3 text-sm text-gray-600 dark:text-gray-300 p-3 border-b border-gray-200 dark:border-gray-600">
                        <div>Price</div>
                        <div>Amount</div>
                        <div>Total</div>
                    </div>
                    <div id="orderbook" class="overflow-y-auto pl-2 max-h-[600px]">
                        <!-- Filled by JS -->
                    </div>
                </div>
            </div>

            {{-- =========================
                 BOT SETTINGS FORM
            ========================== --}}
            <form id="subscribeForm" class="bg-white dark:bg-gray-800 rounded p-4">
                @csrf
                <h3 class="text-gray-600 dark:text-gray-200 mb-4 text-sm">Bot Settings</h3>

                <!-- Bot Selection and Duration -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- Bot Selection: from BotSetting -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-200 text-sm mb-1 inline-block">Select Bot</label>
                        <select id="botType" name="bot_type"
                                class="w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded border-0 focus:ring-0">
                            <option value="" disabled selected>Select Bot</option>
                            @php
                                $activeBots = \App\Models\BotSetting::getActiveBotTypes();
                            @endphp
                            @foreach($activeBots as $botType => $params)
                                <option value="{{ $botType }}">{{ ucfirst($botType) }} Bot</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Duration Selection: Populated via JS -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-200 text-sm mb-1 inline-block">Select Duration</label>
                        <select id="botDuration" name="duration"
                                class="w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded border-0 focus:ring-0">
                            <option value="" disabled selected>Select Duration</option>
                        </select>
                    </div>

                    <!-- Amount Input -->
                    <div class="relative">
                        <label class="text-gray-600 dark:text-gray-200 text-sm mb-1 inline-block">Amount</label>
                        <input type="number" name="amount"
                               step="0.00000001"
                               class="w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded border-0 focus:ring-0"
                               placeholder="Amount">
                        <span class="absolute right-4 top-10 text-gray-500 dark:text-gray-400">USDT</span>
                    </div>
                </div>

                <!-- Wallet Balance (USDT_TRC20) -->
                <div class="mb-4">
                    <label class="text-gray-600 dark:text-gray-200 text-sm">Wallet Balance (USDT-TRC20)</label>
                    <div class="mt-2 bg-gray-100 dark:bg-gray-700 px-4 py-2.5 rounded">
                        <div class="flex justify-between items-center">
                            @php
                                $walletBalance = auth()->user()->cryptoAssets
                                    ? auth()->user()->cryptoAssets->usdt_trc20_balance
                                    : 0.0;
                            @endphp
                            <span id="walletBalance" class="text-gray-700 dark:text-white">
                                {{ number_format($walletBalance, 4) }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400">USDT</span>
                        </div>
                    </div>
                </div>

                <!-- (Optional) Hidden Input to Track Pair -->
                <input type="hidden" id="tradingPair" name="trading_pair" value="BTC/USDT">

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white rounded py-2.5 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    </svg>
                    Start Bot
                </button>
            </form>
            <!-- Custom Popup -->
<div id="customPopup" 
     class="fixed inset-0 z-50 hidden 
            bg-black bg-opacity-40 
            flex items-center justify-center">
  <div id="popupContent" 
       class="max-w-sm w-full 
              bg-white dark:bg-gray-800 
              rounded-lg shadow-lg 
              p-6 text-center">
    
    <h2 id="popupTitle" 
        class="text-xl font-semibold mb-4 
               text-gray-800 dark:text-gray-100">
      <!-- Title goes here, e.g. "Success" or "Error" -->
    </h2>
    
    <p id="popupMessage" 
       class="mb-6 text-gray-600 dark:text-gray-300">
       <!-- Message content goes here -->
    </p>
    
    <button id="popupOkBtn"
            class="px-4 py-2 bg-blue-500 text-white 
                   rounded hover:bg-blue-600 focus:outline-none">
      OK
    </button>
    
  </div>
</div>

            <!-- Order Book (Mobile) -->
            <div class="md:hidden bg-white dark:bg-gray-800 rounded">
                <div class="grid grid-cols-3 text-sm text-gray-600 dark:text-gray-300 p-3 border-b border-gray-200 dark:border-gray-600">
                    <div>Price</div>
                    <div>Amount</div>
                    <div>Total</div>
                </div>
                <div id="orderbook-mobile" class="overflow-y-auto pl-2 max-h-[400px]">
                    <!-- Filled by JS -->
                </div>
            </div>

            {{-- =========================
                 ACTIVE BOTS SECTION
            ========================== --}}
            <div class="bg-gray-100 dark:bg-gray-900 rounded">
                <h3 class="text-gray-600 dark:text-gray-200 text-sm mb-4">Active Bots</h3>
                <div class="space-y-3">
                    @php
                        // Retrieve the user's active subscriptions
                        $activeSubs = auth()->user()->botSubscriptions()
                            ->where('status', 'active')
                            ->with('bot')
                            ->get();
                    @endphp

                    @forelse($activeSubs as $sub)
                        @php
                            // Time-based progress
                            $progress = 100; 
                            if ($sub->subscribed_at && $sub->expires_at && $sub->expires_at->greaterThan($sub->subscribed_at)) {
                                $totalSecs   = $sub->expires_at->diffInSeconds($sub->subscribed_at);
                                $elapsedSecs = now()->diffInSeconds($sub->subscribed_at);
                                $calculated  = ($elapsedSecs / $totalSecs) * 100;
                                $progress    = min(max($calculated, 0), 100);
                            }
                            $pair           = $sub->bot->trading_pair ?? 'N/A';
                            $botType        = $sub->bot->bot_type ?? 'Unknown';
                            $formattedAmount = number_format($sub->amount, 2);
                        @endphp

                        <div class="bot-card bg-white dark:bg-gray-700 rounded p-3">
                            <!-- HEADER -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex flex-col md:flex-row md:items-center md:gap-2">
                                    <span class="text-gray-800 dark:text-gray-100">{{ $pair }}</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">
                                        {{ ucfirst($botType) }} Bot
                                    </span>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">
                                        Amount: {{ $formattedAmount }} USDT
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-300">
                                    Active
                                </span>
                            </div>

                            <!-- TIME-BASED PROGRESS BAR -->
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-1.5 mb-2">
                                <div class="bg-green-500 h-1.5 rounded-full transition-all duration-300"
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>

                            <!-- PROGRESS TEXT -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">
                                        {{ round($progress, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 dark:text-gray-300">
                            No active bots found
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('subscribeForm');
    const popup = document.getElementById('customPopup');
    const popupTitle = document.getElementById('popupTitle');
    const popupMessage = document.getElementById('popupMessage');
    const popupOkBtn = document.getElementById('popupOkBtn');

    // Utility function to show the popup
    function showPopup(title, message, reloadOnOk = false) {
        popupTitle.textContent = title;
        popupMessage.textContent = message;
        popup.classList.remove('hidden');

        // If we want to reload page when user clicks OK
        if (reloadOnOk) {
            popupOkBtn.onclick = function() {
                window.location.reload();
            };
        } else {
            popupOkBtn.onclick = function() {
                popup.classList.add('hidden');
            };
        }
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            // Send AJAX request to subscribe
            const response = await fetch('{{ route("bots.subscribe") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // success
                showPopup(
                    "Success",
                    result.message,
                    true  // reload page on OK
                );
            } else {
                // error
                const errMsg = result.message || "An error occurred.";
                showPopup("Error", errMsg, false);
            }
        } catch (err) {
            console.error(err);
            showPopup("Error", "Something went wrong. Please try again.", false);
        }
    });
});
</script>

<script>
    // Track current selections
    let currentBaseSymbol = 'BTC';
    let currentInterval = 60; // default to 1h

    // Function to detect dark mode (class-based approach)
	function isDarkMode() {
    return document.documentElement.classList.contains('dark');
	}

    // WebSocket config
    const wsConfig = {
        reconnectAttempts: 0,
        maxReconnectDelay: 30000,
        baseReconnectDelay: 1000,
        
        getReconnectDelay() {
            const delay = Math.min(
                this.baseReconnectDelay * Math.pow(2, this.reconnectAttempts),
                this.maxReconnectDelay
            );
            this.reconnectAttempts++;
            return delay;
        },
        
        resetReconnectAttempts() {
            this.reconnectAttempts = 0;
        }
    };

    // Huobi Stats
    const huobiStats = {
        ws: null,
        pair: 'BTCUSDT',

        init() {
            this.connect();
        },

        connect() {
            this.ws = new WebSocket('wss://api.huobi.pro/ws');
            
            this.ws.onopen = () => {
                console.log('Stats WebSocket connected');
                this.subscribe();
                wsConfig.resetReconnectAttempts();
            };

            this.ws.onmessage = (event) => {
                const reader = new FileReader();
                reader.onload = () => {
                    try {
                        const array = new Uint8Array(reader.result);
                        const data = pako.ungzip(array, { to: 'string' });
                        const json = JSON.parse(data);
                        
                        if (json.ping) {
                            this.ws.send(JSON.stringify({ pong: json.ping }));
                        } else if (json.tick) {
                            this.renderStats(json.tick);
                        }
                    } catch (err) {
                        console.error('Stats message error:', err);
                    }
                };
                reader.readAsArrayBuffer(event.data);
            };

            this.ws.onerror = (err) => {
                console.error('Stats WebSocket error:', err);
            };

            this.ws.onclose = () => {
                console.log('Stats WebSocket closed');
                const delay = wsConfig.getReconnectDelay();
                console.log(`Reconnecting stats in ${delay/1000} seconds...`);
                setTimeout(() => this.connect(), delay);
            };
        },

        subscribe() {
            const msg = {
                sub: `market.${this.pair.toLowerCase()}.detail`,
                id: `stat_${this.pair}`
            };
            this.ws.send(JSON.stringify(msg));
        },

        changePair(newPair) {
            this.pair = newPair;
            if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                this.ws.close();
            }
        },

        renderStats(tick) {
            const updateElement = (id, content, className = null) => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = content;
                    if (className) {
                        element.className = className;
                    }
                }
            };

            // Update volume info
            updateElement('baseVolumeLabel', `${currentBaseSymbol} Volume:`);
            updateElement('baseVolume', tick.amount.toFixed(2));

            // Calculate and update price changes
            const changePercent = ((tick.close - tick.open) / tick.open * 100).toFixed(2);
            const isPositive = changePercent >= 0;

            // Update change percentage
            updateElement('24hChange',
                `${isPositive ? '+' : ''}${changePercent}%`,
                isPositive ? 'text-green-500 ml-1' : 'text-red-500 ml-1'
            );

            // Update high and low
            updateElement('24hHigh',
                tick.high.toLocaleString('en-US', { minimumFractionDigits: 2 })
            );
            updateElement('24hLow',
                tick.low.toLocaleString('en-US', { minimumFractionDigits: 2 })
            );
        }
    };

    // Huobi Orderbook
    const huobiOrderbook = {
        ws: null,
        pair: 'BTCUSDT',

        init() {
            this.connect();
        },

        connect() {
            this.ws = new WebSocket('wss://api.huobi.pro/ws');
            
            this.ws.onopen = () => {
                console.log('Orderbook WebSocket connected');
                this.subscribe();
                wsConfig.resetReconnectAttempts();
            };

            this.ws.onmessage = (event) => {
                const reader = new FileReader();
                reader.onload = () => {
                    try {
                        const array = new Uint8Array(reader.result);
                        const data = pako.ungzip(array, { to: 'string' });
                        const json = JSON.parse(data);
                        if (json.ping) {
                            this.ws.send(JSON.stringify({ pong: json.ping }));
                        } else if (json.tick) {
                            this.renderOrderBook(json.tick);
                        }
                    } catch (err) {
                        console.error('Orderbook message error:', err);
                    }
                };
                reader.readAsArrayBuffer(event.data);
            };

            this.ws.onerror = (err) => {
                console.error('Orderbook WebSocket error:', err);
            };

            this.ws.onclose = () => {
                console.log('Orderbook WebSocket closed');
                const delay = wsConfig.getReconnectDelay();
                console.log(`Reconnecting orderbook in ${delay/1000} seconds...`);
                setTimeout(() => this.connect(), delay);
            };
        },

        subscribe() {
            const msg = {
                sub: `market.${this.pair.toLowerCase()}.depth.step0`,
                id: `depth_${this.pair}`
            };
            this.ws.send(JSON.stringify(msg));
        },

        changePair(newPair) {
            this.pair = newPair;
            if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                this.ws.close();
            }
        },

        renderOrderBook(data) {
            if (!data || !data.asks || !data.bids) return;

            const asks = data.asks.slice(0, 15).reverse();
            const bids = data.bids.slice(0, 15);

            const orderbookHTML = `
                <div class="space-y-1">
                    ${asks.map(ask => `
                        <div class="grid grid-cols-3 text-xs">
                            <div class="text-red-500">${parseFloat(ask[0]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                            <div class="text-gray-600 dark:text-gray-300">${parseFloat(ask[1]).toFixed(6)}</div>
                            <div class="text-gray-600 dark:text-gray-300">${(ask[0] * ask[1]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                        </div>
                    `).join('')}

                    <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>

                    ${bids.map(bid => `
                        <div class="grid grid-cols-3 text-xs">
                            <div class="text-green-500">${parseFloat(bid[0]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                            <div class="text-gray-600 dark:text-gray-300">${parseFloat(bid[1]).toFixed(6)}</div>
                            <div class="text-gray-600 dark:text-gray-300">${(bid[0] * bid[1]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                        </div>
                    `).join('')}
                </div>
            `;

            const mobOrderbook = document.getElementById('orderbook-mobile');
            const deskOrderbook = document.getElementById('orderbook');
            if (mobOrderbook) mobOrderbook.innerHTML = orderbookHTML;
            if (deskOrderbook) deskOrderbook.innerHTML = orderbookHTML;
        }
    };

   // Your existing TradingView initialization function
	function initTradingViewChart(pairSymbol, interval = 60) {
    const isDarkTheme = isDarkMode();
    
    // Clear existing chart
    document.getElementById('chart').innerHTML = '';
    
    new TradingView.widget({
        autosize: true,
        symbol: `HUOBI:${pairSymbol.replace('/', '')}`,
        interval: interval.toString(),
        timezone: "exchange",
        theme: isDarkTheme ? "dark" : "light",
        style: "1",
        toolbar_bg: isDarkTheme ? "#1C2638" : "#FFFFFF",
        loading_screen: { backgroundColor: isDarkTheme ? "#131722" : "#FFFFFF" },
        backgroundColor: isDarkTheme ? "#131722" : "#FFFFFF",
        enable_publishing: false,
        hide_side_toolbar: true,
        allow_symbol_change: true,
        container_id: "chart",
        overrides: {
            "paneProperties.backgroundType": "solid",
        }
    });
	}
    // DOM Ready
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize
        huobiStats.init();
        huobiOrderbook.init();
        initTradingViewChart('BTC/USDT', currentInterval);

        // Interval buttons
        document.querySelectorAll('.chart-interval').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active from all
                document.querySelectorAll('.chart-interval').forEach(b => {
                    b.classList.remove('bg-blue-500', 'text-white');
                    b.classList.add('text-gray-600', 'dark:text-gray-300');
                });
                
                // Add active to this
                this.classList.remove('text-gray-600', 'dark:text-gray-300');
                this.classList.add('bg-blue-500', 'text-white');
                
                // Update chart
                currentInterval = parseInt(this.dataset.interval);
                initTradingViewChart(`${currentBaseSymbol}/USDT`, currentInterval);
            });
        });
    });

    // Dropdown Toggle
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Global Click Handler
    document.addEventListener('click', function(e) {
        // Handle pair selection
        if (e.target.closest('#pairDropdown a')) {
            e.preventDefault();
            const link = e.target.closest('#pairDropdown a');
            const symbol = link.dataset.symbol;
            const icon = link.dataset.icon;

            // Update icon (fallback to default if not found)
            const iconElement = document.getElementById('pairIcon');
            if (iconElement) {
                iconElement.onerror = function() {
                    this.src = '/btc-icon.png';
                };
                iconElement.src = icon;
            }

            // Update displayed text
            const textElement = document.getElementById('selectedPairText');
            if (textElement) {
                textElement.textContent = `${symbol}/USDT`;
            }

            // Update trading pair input
            const pairInput = document.getElementById('tradingPair');
            if (pairInput) {
                pairInput.value = `${symbol}/USDT`;
            }

            // Hide dropdown
            document.getElementById('pairDropdown')?.classList.add('hidden');

            // Reinitialize with new symbol
            currentBaseSymbol = symbol;
            huobiStats.changePair(symbol + 'USDT');
            huobiOrderbook.changePair(symbol + 'USDT');
            initTradingViewChart(`${symbol}/USDT`, currentInterval);
        }

        // Close dropdown when clicking outside
        if (!e.target.closest('.relative')) {
            document.getElementById('pairDropdown')?.classList.add('hidden');
        }
    });

    // Bot Duration Handler
    document.getElementById('botType')?.addEventListener('change', function () {
        const botDuration = document.getElementById('botDuration');
        const allBotSettings = @json(\App\Models\BotSetting::all());
        const selected = this.value;

        botDuration.innerHTML = '<option value="" disabled selected>Select Duration</option>';
        const setting = allBotSettings.find(s => s.bot_type === selected);
        if (setting && Array.isArray(setting.duration_options)) {
            setting.duration_options.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                botDuration.appendChild(opt);
            });
        }
    });
</script>
@endpush
</x-user-layout>
