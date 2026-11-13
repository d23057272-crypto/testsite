<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction {{ substr($tx->hash ?? '', 0, 12) }}... | Solscan</title>
    <meta name="description" content="Solscan is an explorer for real-time data tracking. Monitor $SOL and Solana tokens, explore transactions, blocks, and token details with ease.">
    <link rel="icon" href="https://solscan.io/favicon.png">
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
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .mobile-scroll {
                overflow-x: auto;
            }
            .mobile-text {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo and Price -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <img src="https://solscan.io/_next/static/media/solPriceLogo.76eeb122.png" alt="SOL" class="w-8 h-8">
                        <div class="hidden sm:block">
                            <span class="text-lg font-semibold text-gray-900">${{ number_format($tx->rate ?? 195.63, 2) }}</span>
                            <span class="text-red-500 text-sm ml-1">-3.25%</span>
                        </div>
                    </div>
                    <div class="hidden md:block text-sm text-gray-600">
                        <a href="#" class="hover:text-blue-600">Avg Fee: {{ number_format($tx->network_fee ?? 0.00000595, 8) }}</a>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Analytics</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Defi</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Leaderboard</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Blockchain</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Resources</a>
                </nav>

                <!-- Sign In -->
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Sign in</a>
                    <img src="https://solscan.io/_next/static/media/solana-sol-logo.ecf2bf3a.svg" alt="Solana" class="w-6 h-6">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Transaction Details</h1>
            
            <!-- Sponsored Ad -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg p-4 mb-6 text-white">
                <div class="flex items-center space-x-3">
                    <img src="https://statics.solscan.io/ex-img/metawin_20.png" alt="MetaWin" class="w-8 h-8 rounded">
                    <div>
                        <span class="font-semibold">Sponsored: MetaWin:</span>
                        <span class="ml-1">Play & WIN $50k+ each Week!</span>
                        <a href="#" class="ml-2 underline font-semibold">Claim Your FREE Entry Now.</a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Buy</button>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">Presale</button>
                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Play</button>
                <button class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">Gaming</button>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
                <button class="bg-green-600 text-white px-4 py-2 rounded-t-lg font-medium">Overview</button>
                <button class="text-gray-600 hover:text-gray-900 px-4 py-2 font-medium">Sol Balance Change</button>
                <button class="text-gray-600 hover:text-gray-900 px-4 py-2 font-medium">Token Balance Change</button>
            </nav>
        </div>

        <!-- Transaction Info Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Signature</span>
                            <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                Inspect Tx
                            </button>
                        </div>
                        <div class="text-sm text-gray-900 break-all">
                            {{ $tx->hash ?? 'N/A' }}
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t">
                            <div>
                                <span class="text-gray-600 text-sm">Block</span>
                                <div class="text-blue-600 hover:text-blue-800 cursor-pointer">{{ $tx->slot ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Timestamp</span>
                                <div class="text-gray-900">{{ $tx->timestamp ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-sm">{{ $tx->age ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600 text-sm">Result</span>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-{{ $tx->status === 'success' ? 'green' : 'red' }}-100 text-{{ $tx->status === 'success' ? 'green' : 'red' }}-800 px-2 py-1 rounded text-sm font-medium">
                                        {{ ucfirst($tx->status ?? 'pending') }}
                                    </span>
                                </div>
                                <div class="text-gray-500 text-sm">
                                    {{ $tx->status === 'success' ? 'finalized (MAX confirmations)' : 'failed' }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Signer</span>
                                <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">
                                    {{ substr($tx->from ?? '', 0, 6) }}...{{ substr($tx->from ?? '', -6) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Actions Card -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Transaction Actions</h3>
                    
                    <div class="flex space-x-4 mb-4">
                        <button class="text-gray-600 hover:text-gray-900">Legacy Mode</button>
                        <button class="text-blue-600 font-medium border-b-2 border-blue-600">Summary Mode</button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Show Token Account</span>
                            <button class="text-green-600 hover:text-green-700 text-sm">View Tx Maps</button>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-sm text-gray-600">Interact with instruction</span>
                                <span class="bg-gray-200 px-2 py-1 rounded text-xs">transfer</span>
                                <span class="text-sm text-gray-600">on</span>
                            </div>
                            
                            <div class="flex items-center space-x-2 mb-3">
                                <img src="https://solscan.io/_next/static/media/FallbackNft.8dcbd626.png" alt="SOL" class="w-6 h-6 rounded">
                                <span class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">SOL Token Program</span>
                            </div>

                            <div class="text-sm space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600">Transfer from</span>
                                    <span class="text-blue-600 hover:text-blue-800 cursor-pointer">{{ substr($tx->from ?? '', 0, 6) }}...{{ substr($tx->from ?? '', -6) }}</span>
                                    <span class="text-gray-600">to</span>
                                    <span class="text-blue-600 hover:text-blue-800 cursor-pointer">{{ substr($tx->to ?? '', 0, 6) }}...{{ substr($tx->to ?? '', -6) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600">for</span>
                                    <span class="font-semibold">{{ number_format($tx->amount_sol ?? 0, 6) }}</span>
                                    <img src="https://solscan.io/_next/static/media/solana-sol-logo.ecf2bf3a.svg" alt="SOL" class="w-4 h-4">
                                    <span class="text-blue-600 hover:text-blue-800 cursor-pointer">SOL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Fee and Technical Details -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fee</span>
                            <div class="text-right">
                                <div class="text-gray-900">{{ number_format($tx->fee_sol ?? 0, 8) }} SOL</div>
                                <div class="text-gray-500 text-sm">(${{ number_format($tx->fee_sol * ($tx->rate ?? 195.63), 4) }})</div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Compute Units Consumed</span>
                            <span class="text-gray-900">{{ $tx->confirmations ?? rand(32, 128) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction Version</span>
                            <span class="text-gray-900">0</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Recent Block Hash</span>
                            <span class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">
                                {{ substr($tx->hash ?? '', 0, 10) }}...{{ substr($tx->hash ?? '', -6) }}
                            </span>
                        </div>

                        <div class="pt-4 border-t">
                            <span class="text-gray-600">Your Notes</span>
                            <div class="mt-2">
                                <textarea class="w-full border border-gray-300 rounded-lg p-2 text-sm" rows="3" placeholder="Add your notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Link -->
                <div class="bg-white rounded-lg card-shadow p-4">
                    <a href="#" class="flex items-center justify-between text-blue-600 hover:text-blue-800">
                        <span class="font-medium">API</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Expandable Sections -->
        <div class="space-y-4 mb-8">
            <div class="bg-white rounded-lg card-shadow">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-900">Instruction Details</span>
                    <span class="text-blue-600 text-sm">Show details</span>
                </button>
            </div>

            <div class="bg-white rounded-lg card-shadow">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-900">Program Logs</span>
                    <span class="text-blue-600 text-sm">Show details</span>
                </button>
            </div>
        </div>

        <!-- Balance Changes Tables -->
        <div class="space-y-8">
            <!-- SOL Balance Changes -->
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">SOL Balance Changes</h3>
                </div>
                <div class="mobile-scroll">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance Before</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change (SOL)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->from ?? '', 0, 6) }}...{{ substr($tx->from ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs space-y-1">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Writable</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Signer</span>
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Fee Payer</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($tx->amount_sol ?? 0, 6) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-red-600">- {{ number_format($tx->amount_sol ?? 0, 6) }}</span>
                                        <img src="https://solscan.io/_next/static/media/solana-sol-logo.ecf2bf3a.svg" alt="SOL" class="w-4 h-4">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">${{ number_format(($tx->amount_sol ?? 0) * ($tx->rate ?? 195.63), 2) }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->to ?? '', 0, 6) }}...{{ substr($tx->to ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Writable</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($tx->amount_sol ?? 0, 6) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-green-600">+ {{ number_format($tx->amount_sol ?? 0, 6) }}</span>
                                        <img src="https://solscan.io/_next/static/media/solana-sol-logo.ecf2bf3a.svg" alt="SOL" class="w-4 h-4">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">${{ number_format(($tx->amount_sol ?? 0) * ($tx->rate ?? 195.63), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Token Balance Changes -->
            @if(($tx->symbol ?? 'SOL') !== 'SOL')
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Token Balance Changes</h3>
                </div>
                <div class="mobile-scroll">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance Before</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->to ?? '', 0, 6) }}...{{ substr($tx->to ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->to ?? '', 0, 6) }}...{{ substr($tx->to ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($tx->amount_sol ?? 0, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">+ {{ number_format($tx->amount_sol ?? 0, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://solscan.io/_next/static/media/FallbackNft.8dcbd626.png" alt="{{ $tx->symbol }}" class="w-6 h-6">
                                        <span class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ $tx->symbol }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->from ?? '', 0, 6) }}...{{ substr($tx->from ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ substr($tx->from ?? '', 0, 6) }}...{{ substr($tx->from ?? '', -6) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($tx->amount_sol ?? 0, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">- {{ number_format($tx->amount_sol ?? 0, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://solscan.io/_next/static/media/FallbackNft.8dcbd626.png" alt="{{ $tx->symbol }}" class="w-6 h-6">
                                        <span class="text-blue-600 hover:text-blue-800 cursor-pointer text-sm">{{ $tx->symbol }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <p class="text-sm text-gray-700">
                A transaction is a cryptographically signed instruction that changes the blockchain state. 
                Block explorers track the details of all transactions in the network. 
                <a href="#" class="text-blue-600 hover:text-blue-800">Learn more about transactions in our Knowledge Base</a>.
            </p>
        </div>

        <!-- Back to Top -->
        <div class="text-center">
            <button class="text-blue-600 hover:text-blue-800 font-medium">Back to Top</button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="https://solscan.io/_next/static/media/solana-sol-logo.ecf2bf3a.svg" alt="Solana" class="w-8 h-8">
                        <span class="text-lg font-semibold">Powered by Solana Blockchain</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        Solscan is the leading Block Explorer and Search, API & Analytics Platform for Solana blockchain.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Substack</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.5 7.5h15v10h-15v-10zm0-2.5h15v2.5h-15v-2.5zm0-2.5h15v2.5h-15v-2.5z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Branding</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Bug Bounty</a></li>
                    </ul>
                </div>

                <!-- Community -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Community</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">API Docs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Solscan Docs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Knowledge Base</a></li>
                    </ul>
                </div>

                <!-- Products & Services -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Products & Services</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Advertise</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Explorer as a Service (EaaS)</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">API Plans</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Priority Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Token Update</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">Solscan @2025</p>
                <div class="text-gray-400 text-sm mt-4 sm:mt-0">
                    Donations: <a href="#" class="text-blue-400 hover:text-blue-300">D27Dgi...L5EuLK</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expandableButtons = document.querySelectorAll('.text-blue-600.text-sm');
            expandableButtons.forEach(button => {
                button.closest('button')?.addEventListener('click', function() {
                    const text = this.querySelector('.text-blue-600.text-sm');
                    if (text.textContent === 'Show details') {
                        text.textContent = 'Hide details';
                    } else {
                        text.textContent = 'Show details';
                    }
                });
            });

            document.querySelector('button:contains("Back to Top")')?.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>