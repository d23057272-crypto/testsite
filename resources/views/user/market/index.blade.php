<x-user-layout :title="__('Market')">
    @php
        $labels = [
            'trending' => __('Trending'),
            'top' => __('Top'),
            'stonks-szn' => __('Stonks Szn'),
            'top-gainers' => __('Top Gainers'),
            'pre-ipo' => __('Pre-IPO'),
            'meme' => __('Meme'),
            'defi' => __('DeFi'),
            'perps' => __('Perps'),
        ];
    @endphp

    <main class="space-y-6">
        <header class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Market') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Live market data. Prices are informational and not investment advice.') }}
                </p>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($section) }}</span>
        </header>

        <nav class="flex gap-2 overflow-x-auto pb-2" aria-label="{{ __('Market categories') }}">
            @foreach($sections as $category)
                <a
                    href="{{ route('markets', ['section' => $category]) }}"
                    class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium {{ $section === $category ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}"
                >
                    {{ $labels[$category] }}
                </a>
            @endforeach
        </nav>

        @if($section === 'perps')
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200">
                {{ __('Read-only perpetual market data aggregated from public providers. This page does not place orders or manage positions.') }}
            </div>
        @elseif($section === 'pre-ipo')
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-200">
                {{ __('Upcoming IPO calendar information supplied by Finnhub. This is not access to pre-IPO shares.') }}
            </div>
        @endif

        @if($isStale)
            <div role="status" class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200">
                {{ __('Showing recently cached data because the live market provider is temporarily unavailable.') }}
            </div>
        @endif

        @if($error)
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
                {{ $error }}
            </div>
        @elseif(empty($items))
            <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                {{ $isUnavailable
                    ? __('Live market data is temporarily unavailable. Please try again shortly.')
                    : __('No market data is currently available for this section.') }}
            </div>
        @elseif($section === 'perps')
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">{{ __('Contract') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Mark Price') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('24h') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Funding Rate') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('24h Volume') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                        @foreach($items as $item)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item['symbol'] }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ format_fiat((float) $item['mark_price']) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right {{ $item['price_change_percent'] < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($item['price_change_percent'], 2) }}%</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ number_format($item['funding_rate'] * 100, 4) }}%</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ format_fiat((float) $item['quote_volume']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($section === 'stonks-szn')
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($items as $item)
                    <article class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">{{ $item['symbol'] }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item['name'] }}</p>
                            </div>
                            <span class="text-sm font-medium {{ $item['change'] < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($item['change'], 2) }}%</span>
                        </div>
                        <p class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">{{ format_fiat((float) $item['price']) }}</p>
                    </article>
                @endforeach
            </div>
        @elseif($section === 'pre-ipo')
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">{{ __('Company') }}</th>
                            <th class="px-4 py-3">{{ __('Exchange') }}</th>
                            <th class="px-4 py-3">{{ __('Expected Date') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Price Range') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                        @foreach($items as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item['name'] ?? __('Unavailable') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item['exchange'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item['date'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">
                                    {{ $item['price'] ?? '—' }} {{ $item['currency'] ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">{{ __('Asset') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Price') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('24h') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Market Cap') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                        @foreach($items as $item)
                            @php
                                $coin = $item['item'] ?? $item;
                                $symbol = strtoupper($coin['symbol'] ?? '');
                                $price = $coin['current_price'] ?? data_get($coin, 'data.price');
                                $change = $coin['price_change_percentage_24h'] ?? data_get($coin, 'data.price_change_percentage_24h.usd', 0);
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $coin['name'] ?? '—' }}</span>
                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ $symbol }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700 dark:text-gray-300">
                                    {{ is_numeric($price) ? format_fiat((float) $price) : '—' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right {{ (float) $change < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ is_numeric($change) ? number_format((float) $change, 2) . '%' : '—' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-gray-700 dark:text-gray-300">
                                    {{ isset($coin['market_cap']) ? format_fiat((float) $coin['market_cap']) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>
</x-user-layout>
