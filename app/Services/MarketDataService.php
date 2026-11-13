<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MarketDataService
{
    private const COINGECKO_BASE_URL = 'https://api.coingecko.com/api/v3';
    private const BINANCE_FUTURES_BASE_URL = 'https://fapi.binance.com/fapi/v1';
    private const COINGECKO_COOLDOWN_SECONDS = 180;
    private const MARKET_FAILURE_COOLDOWN_SECONDS = 180;
    private bool $servedStaleData = false;
    private bool $servedUnavailableData = false;

    public function __construct(
        private FinnhubClient $finnhub
    ) {
    }

    public function trendingCoins(): array
    {
        return $this->rememberMarketData('market.trending.coins', 60, function () {
            $data = $this->getJson(
                self::COINGECKO_BASE_URL . '/search/trending'
            );

            return array_map(
                fn ($entry) => $entry['item'] ?? [],
                array_slice($data['coins'] ?? [], 0, 20)
            );
        });
    }

    public function topCoins(): array
    {
        return $this->coinMarkets('market.top.coins', [
            'order' => 'market_cap_desc',
            'per_page' => 50,
            'page' => 1,
        ]);
    }

    public function topGainers(): array
    {
        return $this->rememberMarketData('market.top.gainers', 60, function () {
            $markets = $this->getCoinMarkets([
                'order' => 'market_cap_desc',
                'per_page' => 100,
                'page' => 1,
            ]);

            usort($markets, fn ($left, $right) =>
                (float) ($right['price_change_percentage_24h'] ?? -INF)
                <=> (float) ($left['price_change_percentage_24h'] ?? -INF)
            );

            return array_slice($markets, 0, 50);
        });
    }

    public function categoryCoins(string $category): array
    {
        if (!in_array($category, ['meme', 'defi'], true)) {
            throw new RuntimeException('Unsupported market category.');
        }

        return $this->rememberMarketData(
            "market.category.{$category}",
            300,
            function () use ($category) {
            $categories = $this->rememberMarketData(
                'market.coingecko.categories',
                3600,
                fn () => $this->getJson(
                    self::COINGECKO_BASE_URL . '/coins/categories/list'
                )
            );

            $categoryId = $this->findCategoryId($categories, $category);

            if (!$categoryId) {
                throw new RuntimeException(
                    'The requested market category is not available from the data provider.'
                );
            }

            return $this->getCoinMarkets([
                'category' => $categoryId,
                'order' => 'market_cap_desc',
                'per_page' => 50,
                'page' => 1,
            ]);
        });
    }

    public function perpetualMarkets(): array
    {
        return $this->rememberMarketData(
            'market.binance.perpetuals',
            60,
            function () {
                if (!Cache::get('market.binance.futures.unavailable', false)) {
                    try {
                        return $this->loadBinancePerpetualMarkets();
                    } catch (RuntimeException $exception) {
                        Log::notice(
                            'Binance Futures market feed unavailable; using the CoinGecko derivatives feed.',
                            ['error' => $exception->getMessage()]
                        );
                    }
                }

                return $this->loadCoinGeckoPerpetualMarkets();
            }
        );
    }

    public function wasLastResponseStale(): bool
    {
        return $this->servedStaleData;
    }

    public function wasLastResponseUnavailable(): bool
    {
        return $this->servedUnavailableData;
    }

    private function loadBinancePerpetualMarkets(): array
    {
        $tickers = $this->getJson(
            self::BINANCE_FUTURES_BASE_URL . '/ticker/24hr'
        );
        $premiums = $this->getJson(
            self::BINANCE_FUTURES_BASE_URL . '/premiumIndex'
        );

        if (!array_is_list($tickers) || !array_is_list($premiums)) {
            throw new RuntimeException(
                'Binance Futures returned an invalid market response.'
            );
        }

        $premiumBySymbol = [];
        foreach ($premiums as $premium) {
            if (is_array($premium) && isset($premium['symbol'])) {
                $premiumBySymbol[$premium['symbol']] = $premium;
            }
        }

        $markets = [];
        foreach ($tickers as $ticker) {
            if (!is_array($ticker)) {
                continue;
            }

            $symbol = $ticker['symbol'] ?? '';

            if (!is_string($symbol) || !str_ends_with($symbol, 'USDT')) {
                continue;
            }

            $premium = $premiumBySymbol[$symbol] ?? [];
            $markets[] = [
                'symbol' => $symbol,
                'last_price' => (float) ($ticker['lastPrice'] ?? 0),
                'price_change_percent' => (float) ($ticker['priceChangePercent'] ?? 0),
                'quote_volume' => (float) ($ticker['quoteVolume'] ?? 0),
                'mark_price' => (float) ($premium['markPrice'] ?? 0),
                'funding_rate' => (float) ($premium['lastFundingRate'] ?? 0),
                'next_funding_time' => (int) ($premium['nextFundingTime'] ?? 0),
            ];
        }

        usort($markets, fn ($left, $right) =>
            $right['quote_volume'] <=> $left['quote_volume']
        );

        return array_slice($markets, 0, 30);
    }

    private function loadCoinGeckoPerpetualMarkets(): array
    {
        $derivatives = $this->getJson(
            self::COINGECKO_BASE_URL . '/derivatives'
        );

        if (!array_is_list($derivatives)) {
            throw new RuntimeException(
                'CoinGecko returned an invalid derivatives response.'
            );
        }

        $markets = [];

        foreach ($derivatives as $derivative) {
            if (
                !is_array($derivative) ||
                strtolower((string) ($derivative['contract_type'] ?? ''))
                    !== 'perpetual'
            ) {
                continue;
            }

            $price = $derivative['price'] ?? null;

            if (!is_numeric($price) || (float) $price <= 0) {
                continue;
            }

            $fundingRate = $derivative['funding_rate'] ?? 0;
            $symbol = trim(
                (string) ($derivative['market'] ?? 'Exchange') . ' '
                . (string) ($derivative['symbol'] ?? '')
            );

            $markets[] = [
                'symbol' => $symbol,
                'last_price' => (float) $price,
                'price_change_percent' => (float) (
                    $derivative['price_percentage_change_24h'] ?? 0
                ),
                'quote_volume' => (float) ($derivative['volume_24h'] ?? 0),
                'mark_price' => (float) $price,
                'funding_rate' => is_numeric($fundingRate)
                    ? (float) $fundingRate / 100
                    : 0.0,
                'next_funding_time' => 0,
            ];
        }

        usort($markets, fn ($left, $right) =>
            $right['quote_volume'] <=> $left['quote_volume']
        );

        if ($markets === []) {
            throw new RuntimeException(
                'CoinGecko did not return usable perpetual-market data.'
            );
        }

        return array_slice($markets, 0, 30);
    }

    public function stockQuotes(): array
    {
        return $this->rememberMarketData('market.finnhub.stocks', 60, function () {
            $symbols = [
                'AAPL' => 'Apple',
                'MSFT' => 'Microsoft',
                'NVDA' => 'NVIDIA',
                'AMZN' => 'Amazon',
                'GOOGL' => 'Alphabet',
                'META' => 'Meta Platforms',
                'TSLA' => 'Tesla',
                'AVGO' => 'Broadcom',
                'JPM' => 'JPMorgan Chase',
                'BRK.B' => 'Berkshire Hathaway',
            ];
            $quotes = [];

            foreach ($symbols as $symbol => $name) {
                $quote = $this->finnhub->getJson('/quote', [
                    'symbol' => $symbol,
                ]);

                if (!isset($quote['c']) || !is_numeric($quote['c'])) {
                    continue;
                }

                $quotes[] = [
                    'symbol' => $symbol,
                    'name' => $name,
                    'price' => (float) $quote['c'],
                    'change' => (float) ($quote['dp'] ?? 0),
                ];
            }

            return $quotes;
        });
    }

    public function upcomingIpos(): array
    {
        return $this->rememberMarketData(
            'market.finnhub.ipos',
            3600,
            function () {
                $data = $this->finnhub->getJson('/calendar/ipo', [
                    'from' => now()->toDateString(),
                    'to' => now()->addDays(90)->toDateString(),
                ]);

                return $data['ipoCalendar'] ?? [];
            }
        );
    }

    private function coinMarkets(string $cacheKey, array $parameters): array
    {
        return $this->rememberMarketData(
            $cacheKey,
            60,
            fn () => $this->getCoinMarkets($parameters)
        );
    }

    private function getCoinMarkets(array $parameters): array
    {
        $data = $this->getJson(
            self::COINGECKO_BASE_URL . '/coins/markets',
            $parameters + [
                'vs_currency' => 'usd',
                'sparkline' => 'false',
                'price_change_percentage' => '24h',
            ]
        );

        return array_values(array_filter($data, 'is_array'));
    }

    private function findCategoryId(array $categories, string $category): ?string
    {
        foreach ($categories as $entry) {
            $name = strtolower((string) ($entry['name'] ?? ''));
            $id = (string) ($entry['category_id'] ?? $entry['id'] ?? '');
            $matches = $category === 'meme'
                ? str_contains($name, 'meme')
                : (
                    str_contains($name, 'decentralized finance') ||
                    $name === 'defi'
                );

            if ($matches && $id !== '') {
                return $id;
            }
        }

        return null;
    }

    private function getJson(string $url, array $parameters = []): array
    {
        if (
            str_starts_with($url, self::COINGECKO_BASE_URL) &&
            (
                Cache::get('market.coingecko.rate_limited', false) ||
                Cache::get('crypto_provider_disabled_coingecko', false)
            )
        ) {
            throw new RuntimeException(
                'CoinGecko is temporarily rate-limiting market data. Please retry shortly.'
            );
        }

        try {
            $response = Http::acceptJson()
                ->connectTimeout(5)
                ->timeout(15)
                ->get($url, $parameters);
        } catch (ConnectionException $exception) {
            if (str_starts_with($url, self::BINANCE_FUTURES_BASE_URL)) {
                Cache::put(
                    'market.binance.futures.unavailable',
                    true,
                    now()->addMinutes(5)
                );
            }

            throw new RuntimeException(
                'Unable to connect to the market data provider.',
                previous: $exception
            );
        }

        if (!$response->successful()) {
            $status = $response->status();

            if (
                $status === 451 &&
                str_starts_with($url, self::BINANCE_FUTURES_BASE_URL)
            ) {
                Cache::put(
                    'market.binance.futures.unavailable',
                    true,
                    now()->addHours(6)
                );
                throw new RuntimeException(
                    'Binance Futures data is unavailable from this server location.'
                );
            }

            if (
                $status === 429 &&
                str_starts_with($url, self::COINGECKO_BASE_URL)
            ) {
                Cache::put(
                    'market.coingecko.rate_limited',
                    true,
                    now()->addSeconds(self::COINGECKO_COOLDOWN_SECONDS)
                );
                Cache::put(
                    'crypto_provider_disabled_coingecko',
                    true,
                    now()->addSeconds(self::COINGECKO_COOLDOWN_SECONDS)
                );
                throw new RuntimeException(
                    'CoinGecko is temporarily rate-limiting market data. Please retry shortly.'
                );
            }

            if (
                $status === 429 &&
                str_starts_with($url, self::BINANCE_FUTURES_BASE_URL)
            ) {
                Cache::put(
                    'market.binance.futures.unavailable',
                    true,
                    now()->addMinutes(5)
                );
            }

            throw new RuntimeException(
                'Market data provider returned HTTP ' . $status . '.'
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'Market data provider returned an invalid response.'
            );
        }

        return $data;
    }

    private function rememberMarketData(
        string $cacheKey,
        int $seconds,
        callable $loader
    ): array {
        $staleFlagKey = 'market_data_stale_' . md5($cacheKey);
        $unavailableKey = 'market_data_unavailable_' . md5($cacheKey);
        $failureLogKey = 'market_data_provider_failure_logged';

        $this->servedStaleData = false;
        $this->servedUnavailableData = false;

        if (Cache::get($unavailableKey, false)) {
            $this->servedUnavailableData = true;

            return [];
        }

        if (Cache::get($staleFlagKey, false)) {
            $this->servedStaleData = true;
        }

        return Cache::remember(
            $cacheKey,
            $seconds,
            function () use (
                $cacheKey,
                $seconds,
                $loader,
                $staleFlagKey,
                $unavailableKey,
                $failureLogKey
            ) {
                try {
                    $data = $loader();
                    Cache::put(
                        $cacheKey . '_last_good',
                        $data,
                        now()->addHours(6)
                    );
                    Cache::put(
                        $staleFlagKey,
                        false,
                        now()->addSeconds($seconds)
                    );
                    Cache::forget($unavailableKey);

                    return $data;
                } catch (RuntimeException $exception) {
                    $staleData = Cache::get($cacheKey . '_last_good');

                    if (is_array($staleData)) {
                        Cache::put(
                            $staleFlagKey,
                            true,
                            now()->addSeconds($seconds)
                        );
                        $this->servedStaleData = true;
                        Log::notice(
                            'Serving cached market data after provider failure.',
                            [
                                'cache_key' => $cacheKey,
                                'error' => $exception->getMessage(),
                            ]
                        );

                        return $staleData;
                    }

                    Cache::put(
                        $unavailableKey,
                        true,
                        now()->addSeconds(self::MARKET_FAILURE_COOLDOWN_SECONDS)
                    );
                    $this->servedUnavailableData = true;

                    if (Cache::add(
                        $failureLogKey,
                        true,
                        now()->addSeconds(self::MARKET_FAILURE_COOLDOWN_SECONDS)
                    )) {
                        Log::warning(
                            'Market data unavailable; suppressing retries during provider cooldown.',
                            [
                                'cache_key' => $cacheKey,
                                'error' => $exception->getMessage(),
                            ]
                        );
                    }

                    return [];
                }
            }
        );
    }
}
