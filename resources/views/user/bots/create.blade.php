<x-user-layout :title="'Launch Bots'">
    <div class="min-h-screen bg-[#0B1120]">
        <div class="space-y-4">
            <!-- Top Stats Bar -->
            <div class="w-full bg-[#1C2638] border-b border-gray-800">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-2 p-2 text-sm">
                    <!-- Last Price -->
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1">
                            <img src="/btc-icon.png" alt="BTC" class="w-6 h-6">
                            <div>
                                <span class="text-gray-400">Last Price:</span><br>
                                <span id="lastPrice" class="text-red-500 ml-1">0.00</span>
                            </div>
                        </div>
                    </div>
                    <!-- 24h Change -->
                    <div>
                        <span class="text-gray-400">24h Change:</span><br>
                        <span id="24hChange" class="text-red-500 ml-1">0.00%</span>
                    </div>
                    <!-- BTC Volume -->
                    <div>
                        <span class="text-gray-400">BTC Volume:</span><br>
                        <span id="btcVolume" class="text-gray-200 ml-1">0.00</span>
                    </div>
                    <!-- USDT Volume -->
                    <div>
                        <span class="text-gray-400">USDT Volume:</span><br>
                        <span id="usdtVolume" class="text-gray-200 ml-1">0.00</span>
                    </div>
                    <!-- 24h High -->
                    <div>
                        <span class="text-gray-400">24h High:</span><br>
                        <span id="24hHigh" class="text-gray-200 ml-1">0.00</span>
                    </div>
                    <!-- 24h Low -->
                    <div>
                        <span class="text-gray-400">24h Low:</span><br>
                        <span id="24hLow" class="text-gray-200 ml-1">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Main Trading Area -->
            <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                <!-- Chart Area -->
                <div class="md:col-span-2 bg-[#1C2638] rounded">
                    <div class="flex items-center gap-2 p-3 border-b border-gray-700/50">
                        <button class="text-gray-400 px-3 py-1 text-sm hover:bg-[#2A3441] rounded">1m</button>
                        <button class="text-gray-400 px-3 py-1 text-sm hover:bg-[#2A3441] rounded">30m</button>
                        <button class="bg-[#37A0F6] text-white px-3 py-1 text-sm rounded">1h</button>
                    </div>
                    <div class="h-[300px] md:h-[600px]">
                        <div id="chart" class="w-full h-full"></div>
                    </div>
                </div>

                <!-- Order Book - Hidden on mobile -->
                <div class="hidden md:block bg-[#1C2638] rounded">
                    <div class="grid grid-cols-3 text-sm text-gray-400 p-3">
                        <div>Price</div>
                        <div>Amount</div>
                        <div>Total</div>
                    </div>
                    <div id="orderbook" class="overflow-y-auto pl-2 max-h-[600px]">
                        <!-- Will be populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Bot Settings Form -->
            <form id="botForm" action="#" method="POST" class="bg-[#1C2638] rounded p-4">
                @csrf
                <h3 class="text-gray-400 mb-4 text-sm">Bot Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- Bot Selection -->
                    <div class="relative">
                        <input type="hidden" name="selected_bot" id="selected_bot">
                        <button type="button" 
                                class="w-full bg-[#37A0F6] text-white px-4 py-2.5 rounded flex items-center justify-between"
                                onclick="toggleDropdown('botDropdown')">
                            <span id="botButtonText">Select Bot</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="botDropdown" class="hidden absolute w-full mt-1 bg-[#2A3441] rounded shadow-lg z-10">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="grid">Grid Bot</a>
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="dca">DCA Bot</a>
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="arbitrage">Arbitrage Bot</a>
                            </div>
                        </div>
                    </div>

                    <!-- Duration Selection -->
                    <div class="relative">
                        <input type="hidden" name="selected_duration" id="selected_duration">
                        <button type="button" 
                                class="w-full bg-[#2A3441] text-gray-300 px-4 py-2.5 rounded flex items-center justify-between"
                                onclick="toggleDropdown('durationDropdown')">
                            <span id="durationButtonText">Select Duration</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="durationDropdown" class="hidden absolute w-full mt-1 bg-[#2A3441] rounded shadow-lg z-10">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="1h">1 Hour</a>
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="4h">4 Hours</a>
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="12h">12 Hours</a>
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="24h">24 Hours</a>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Input -->
                    <div class="relative">
                        <input type="number" 
                               name="amount"
                               step="0.00000001"
                               class="w-full bg-[#2A3441] text-gray-300 px-4 py-2.5 rounded border-0 focus:ring-0"
                               placeholder="Amount">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">USDT</span>
                    </div>
                </div>

                <!-- Wallet Balance -->
                <div class="mb-4">
                    <div class="text-gray-400 text-sm">Wallet</div>
                    <div class="mt-2 bg-[#2A3441] px-4 py-2.5 rounded">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">0.00000000</span>
                            <span class="text-gray-500">USDT</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white rounded py-2.5 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    </svg>
                    Start Bot
                </button>
            </form>

            <!-- Order Book - Shown only on mobile -->
            <div class="md:hidden bg-[#1C2638] rounded">
                <div class="grid grid-cols-3 text-sm text-gray-400 p-3">
                    <div>Price</div>
                    <div>Amount</div>
                    <div>Total</div>
                </div>
                <div id="orderbook-mobile" class="overflow-y-auto pl-2 max-h-[400px]">
                    <!-- Will be populated by JS -->
                </div>
            </div>

            <!-- Active Bots Section -->
            <div class="bg-[#1C2638] rounded p-4">
                <h3 class="text-gray-400 text-sm mb-4">Active Bots</h3>
                <div class="space-y-3" id="activeBotsList">
                    <!-- Will be populated by JS -->
                </div>
            </div>
        </div>
    </div>
   @push('scripts')
<script>
    const huobiStats = {
        init: function() {
            const ws = new WebSocket('wss://api.huobi.pro/ws');

            ws.onopen = () => {
                console.log('Connected to Huobi Stats WebSocket');
                ws.send(JSON.stringify({
                    "sub": "market.btcusdt.detail",
                    "id": "stat_btcusdt"
                }));
            };

            ws.onmessage = (event) => {
                const blob = event.data;
                const reader = new FileReader();
                
                reader.onload = function() {
                    try {
                        const array = new Uint8Array(reader.result);
                        const data = pako.ungzip(array, { to: 'string' });
                        const json = JSON.parse(data);
                        
                        if (json.ping) {
                            ws.send(JSON.stringify({ pong: json.ping }));
                        } else if (json.tick) {
                            this.renderStats(json.tick);
                        }
                    } catch (err) {
                        console.error('Error processing stats message:', err);
                    }
                }.bind(this);

                reader.readAsArrayBuffer(blob);
            };

            ws.onerror = (error) => {
                console.error('Stats WebSocket Error:', error);
                setTimeout(() => this.init(), 5000);
            };

            ws.onclose = () => {
                console.log('Stats WebSocket Disconnected');
                setTimeout(() => this.init(), 5000);
            };
        },

        renderStats: function(tick) {
            const changePercent = ((tick.close - tick.open) / tick.open * 100).toFixed(2);
            const isPositive = changePercent >= 0;

            document.getElementById('lastPrice').textContent = tick.close.toLocaleString('en-US', { minimumFractionDigits: 2 });
            document.getElementById('lastPrice').className = isPositive ? 'text-green-500 ml-1' : 'text-red-500 ml-1';
            
            document.getElementById('24hChange').textContent = `${isPositive ? '+' : ''}${changePercent}%`;
            document.getElementById('24hChange').className = isPositive ? 'text-green-500 ml-1' : 'text-red-500 ml-1';
            
            document.getElementById('btcVolume').textContent = tick.amount.toFixed(2);
            document.getElementById('usdtVolume').textContent = tick.vol.toLocaleString('en-US', { minimumFractionDigits: 2 });
            document.getElementById('24hHigh').textContent = tick.high.toLocaleString('en-US', { minimumFractionDigits: 2 });
            document.getElementById('24hLow').textContent = tick.low.toLocaleString('en-US', { minimumFractionDigits: 2 });
        }
    };

    // Initialize TradingView chart
    new TradingView.widget({
        "autosize": true,
        "symbol": "HUOBI:BTCUSDT",
        "interval": "60",
        "timezone": "exchange",
        "theme": "dark",
        "style": "1",
        "toolbar_bg": "#1C2638",
        "enable_publishing": false,
        "hide_side_toolbar": true,
        "allow_symbol_change": true,
        "container_id": "chart",
        "backgroundColor": "#131722",
    });

    // Form handling
    const toggleDropdown = (id) => {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });

        dropdown.classList.toggle('hidden');
    };

    // Handle bot selection
    document.querySelectorAll('#botDropdown a').forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            const value = e.target.dataset.value;
            const text = e.target.textContent;
            
            document.getElementById('selected_bot').value = value;
            document.getElementById('botButtonText').textContent = text;
            document.getElementById('botDropdown').classList.add('hidden');
        });
    });

    // Handle duration selection
    document.querySelectorAll('#durationDropdown a').forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            const value = e.target.dataset.value;
            const text = e.target.textContent;
            
            document.getElementById('selected_duration').value = value;
            document.getElementById('durationButtonText').textContent = text;
            document.getElementById('durationDropdown').classList.add('hidden');
        });
    });

    // Form validation
    document.getElementById('botForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bot = document.getElementById('selected_bot').value;
        const duration = document.getElementById('selected_duration').value;
        const amount = parseFloat(this.amount.value);

        if (!bot || !duration || !amount) {
            alert('Please fill in all fields');
            return;
        }

        if (amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }

        this.submit();
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
                d.classList.add('hidden');
            });
        }
    });

    // Initialize everything
    document.addEventListener('DOMContentLoaded', () => {
        huobiStats.init();
    });
</script>
<script>
const huobiOrderbook = {
    init: function() {
        const ws = new WebSocket('wss://api.huobi.pro/ws');

        ws.onopen = () => {
            console.log('Connected to Huobi Orderbook WebSocket');
            // Subscribe to depth updates with specific symbol
            const subscribeMsg = {
                "sub": "market.btcusdt.depth.step0",
                "id": "depth_btcusdt"
            };
            console.log('Sending subscription:', subscribeMsg);
            ws.send(JSON.stringify(subscribeMsg));
        };

        ws.onmessage = (event) => {
            const blob = event.data;
            const reader = new FileReader();
            
            reader.onload = function() {
                try {
                    const array = new Uint8Array(reader.result);
                    const data = pako.ungzip(array, { to: 'string' });
                    const json = JSON.parse(data);
                    
                    console.log('Received data:', json); // Log received data
                    
                    if (json.ping) {
                        ws.send(JSON.stringify({ pong: json.ping }));
                    } else if (json.tick) {
                        this.renderOrderBook(json.tick);
                    }
                } catch (err) {
                    console.error('Error processing orderbook message:', err);
                }
            }.bind(this);

            reader.readAsArrayBuffer(blob);
        };

        ws.onerror = (error) => {
            console.error('Orderbook WebSocket Error:', error);
            setTimeout(() => this.init(), 5000);
        };

        ws.onclose = () => {
            console.log('Orderbook WebSocket Disconnected');
            setTimeout(() => this.init(), 5000);
        };
    },

    renderOrderBook: function(data) {
        console.log('Rendering orderbook with data:', data); // Log render data

        if (!data || !data.asks || !data.bids) {
            console.error('Invalid orderbook data:', data);
            return;
        }

        // Take top 15 asks and bids
        const asks = data.asks.slice(0, 15);
        const bids = data.bids.slice(0, 15);

        const orderbookHTML = `
            <div class="space-y-1">
                ${asks.reverse().map(ask => `
                    <div class="grid grid-cols-3 text-xs">
                        <div class="text-red-500">${parseFloat(ask[0]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                        <div class="text-gray-400">${parseFloat(ask[1]).toFixed(6)}</div>
                        <div class="text-gray-400">${(ask[0] * ask[1]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                    </div>
                `).join('')}
                
                <div class="border-t border-gray-700/50 my-2"></div>
                
                ${bids.map(bid => `
                    <div class="grid grid-cols-3 text-xs">
                        <div class="text-green-500">${parseFloat(bid[0]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                        <div class="text-gray-400">${parseFloat(bid[1]).toFixed(6)}</div>
                        <div class="text-gray-400">${(bid[0] * bid[1]).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                    </div>
                `).join('')}
            </div>
        `;

        // Get both orderbook elements
        const mobileOrderbook = document.getElementById('orderbook-mobile');
        const desktopOrderbook = document.getElementById('orderbook');

        // Log elements found
        console.log('Mobile orderbook element:', mobileOrderbook);
        console.log('Desktop orderbook element:', desktopOrderbook);

        // Update both orderbooks if they exist
        if (mobileOrderbook) mobileOrderbook.innerHTML = orderbookHTML;
        if (desktopOrderbook) desktopOrderbook.innerHTML = orderbookHTML;
    }
};

// Initialize WebSocket connection
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing Huobi Orderbook WebSocket');
    huobiOrderbook.init();
});
</script>
<script>
// Add this to your existing script:

const botManager = {
    // Sample data structure - replace with your actual data source
    activeBots: [
        {
            id: 1,
            pair: 'BTC/USDT',
            type: 'Grid Bot',
            profit: 2.45,
            progress: 45,
            status: 'running'
        },
        {
            id: 2,
            pair: 'ETH/USDT',
            type: 'DCA Bot',
            profit: -1.23,
            progress: 75,
            status: 'running'
        }
    ],

    renderBots: function() {
        const container = document.getElementById('activeBotsList');
        if (!container) return;

        if (this.activeBots.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-gray-400">
                    No active bots found
                </div>
            `;
            return;
        }

        container.innerHTML = this.activeBots.map(bot => `
            <div class="bot-card bg-[#2A3441] rounded p-3">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-200">${bot.pair}</span>
                        <span class="text-xs text-gray-400">${bot.type}</span>
                    </div>
                    <span class="${bot.profit >= 0 ? 'text-green-500' : 'text-red-500'} text-sm">
                        ${bot.profit >= 0 ? '+' : ''}${bot.profit}%
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-700 rounded-full h-1.5 mb-2">
                    <div class="progress-bar bg-green-500 h-1.5 rounded-full transition-all duration-300" 
                         style="width: ${bot.progress}%">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                        <span class="progress-text text-xs text-gray-400">
                            ${bot.status.charAt(0).toUpperCase() + bot.status.slice(1)} - ${bot.progress}%
                        </span>
                    </div>
                    <button class="toggle-details text-gray-400 hover:text-gray-300 transition-colors"
                            onclick="botManager.toggleDetails(${bot.id})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');

        // Start progress updates
        this.startProgressUpdates();
    },

    toggleDetails: function(botId) {
        // Implement detail toggle functionality if needed
        console.log(`Toggle details for bot ${botId}`);
    },

    startProgressUpdates: function() {
        // Update progress every 2 seconds
        setInterval(() => {
            this.activeBots.forEach(bot => {
                // Increment progress
                bot.progress += 1;
                if (bot.progress > 100) bot.progress = 0;
                
                // Update the UI
                this.updateBotProgress(bot);
            });
        }, 2000);
    },

    updateBotProgress: function(bot) {
        const botCard = document.querySelector(`[data-bot-id="${bot.id}"]`);
        if (!botCard) return;

        const progressBar = botCard.querySelector('.progress-bar');
        const progressText = botCard.querySelector('.progress-text');
        
        if (progressBar) progressBar.style.width = `${bot.progress}%`;
        if (progressText) progressText.textContent = `${bot.status.charAt(0).toUpperCase() + bot.status.slice(1)} - ${bot.progress}%`;
    }
};

// Initialize bot manager when document is ready
document.addEventListener('DOMContentLoaded', () => {
    // Your existing initialization code
    huobiStats.init();
    huobiOrderbook.init();
    
    // Initialize bot manager
    botManager.renderBots();
});
</script>
@endpush
</x-user-layout>