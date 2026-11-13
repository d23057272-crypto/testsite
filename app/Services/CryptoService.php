<?php

namespace App\Services;

use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CryptoService
{
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Prices are cached for 3 minutes to reduce unnecessary provider calls.
    | Last-known-good prices are retained for up to 48 hours.
    |
    */

    private const PRICE_CACHE_SECONDS = 180;
    private const LAST_GOOD_CACHE_SECONDS = 172800;

    /*
    |--------------------------------------------------------------------------
    | Provider Cooldowns
    |--------------------------------------------------------------------------
    */

    private const BINANCE_COOLDOWN = 300;
    private const COINGECKO_COOLDOWN = 180;
    private const TWELVE_DATA_COOLDOWN = 900;
    private const ALPHA_VANTAGE_COOLDOWN = 3600;
    private const FINNHUB_COOLDOWN = 900;

    /*
    |--------------------------------------------------------------------------
    | Main Price Retrieval
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve prices for all active cryptocurrencies.
     *
     * Database fields:
     * - coin_id: unique catalog identifier
     * - price_coin_id: shared market-price identifier
     * - symbol: wallet ticker
     * - manual_price: optional admin override
     *
     * Provider priority:
     * 1. Binance
     * 2. CoinGecko
     * 3. Twelve Data
     * 4. Alpha Vantage
     * 5. Finnhub
     * 6. Last-known-good market price
     * 7. Manual admin override
     *
     * Returned prices remain keyed by the original catalog coin_id.
     */
    public function getPrices(): array
    {
        $cryptos = Cryptocurrency::where('is_active', true)->get();

        if ($cryptos->isEmpty()) {
            return [];
        }

        /*
         * Build a fingerprint from the active cryptocurrency configuration.
         * Changes to the catalog automatically produce a new cache key.
         */
        $fingerprintData = $cryptos
            ->sortBy('id')
            ->map(function ($crypto) {
                return [
                    'id' => $crypto->id,
                    'symbol' => $crypto->symbol,
                    'name' => $crypto->name,
                    'network' => $crypto->network,
                    'coin_id' => $crypto->coin_id,
                    'price_coin_id' => $this->getPriceCoinId($crypto),
                    'manual_price' => $crypto->manual_price,
                ];
            })
            ->values()
            ->all();

        $fingerprint = md5(json_encode($fingerprintData));

        $cacheKey = 'crypto_prices_dynamic_' . $fingerprint;
        $lastGoodCacheKey = 'crypto_prices_last_good_v4';

        /*
         * Return cached prices when available.
         * Manual overrides are reapplied in case an administrator
         * changes a manual price while the market cache is active.
         */
        $cachedPrices = Cache::get($cacheKey);

        if (is_array($cachedPrices) && !empty($cachedPrices)) {
            return $this->applyManualOverrides(
                $cachedPrices,
                $cryptos
            );
        }

        $livePrices = [];
        $missing = [];

        $lastGoodPrices = Cache::get($lastGoodCacheKey, []);

        if (!is_array($lastGoodPrices)) {
            $lastGoodPrices = [];
        }

        /*
         * Build the list of catalog assets requiring prices.
         * Original coin_id values remain the array keys.
         */
        foreach ($cryptos as $crypto) {
            $coinId = trim((string) $crypto->coin_id);

            if ($coinId !== '') {
                $missing[$coinId] = $crypto;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 1. BINANCE
        |--------------------------------------------------------------------------
        |
        | Binance is matched using catalog symbols.
        | Ambiguous symbols are excluded to prevent assigning the wrong price.
        |
        */

        if (!empty($missing)) {
            $prices = $this->getBinancePrices($missing);

            foreach ($prices as $coinId => $priceData) {
                if ($this->isValidPrice($priceData['usd'] ?? null)) {
                    $livePrices[$coinId] = $priceData;
                    unset($missing[$coinId]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. COINGECKO
        |--------------------------------------------------------------------------
        |
        | Uses price_coin_id when configured.
        | Falls back to coin_id when price_coin_id is empty.
        |
        | Multiple catalog records can share one market-price identifier.
        | This is how USDT TRC20, ERC20 and BEP20 share Tether's market price.
        |
        */

        if (!empty($missing)) {
            $priceIdGroups = [];

            foreach ($missing as $coinId => $crypto) {
                $priceId = $this->getPriceCoinId($crypto);

                if ($priceId !== '') {
                    $priceIdGroups[$priceId][] = $coinId;
                }
            }

            if (!empty($priceIdGroups)) {
                $prices = $this->getCoinGeckoPrices(
                    array_keys($priceIdGroups)
                );

                foreach ($priceIdGroups as $priceId => $catalogCoinIds) {
                    $priceData = $prices[$priceId] ?? null;

                    if (!$this->isValidPrice($priceData['usd'] ?? null)) {
                        continue;
                    }

                    foreach ($catalogCoinIds as $catalogCoinId) {
                        $livePrices[$catalogCoinId] = $priceData;
                        unset($missing[$catalogCoinId]);
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. TWELVE DATA
        |--------------------------------------------------------------------------
        |
        | USDT network variants are skipped because identifiers such as
        | USDT_TRC20 are not valid Twelve Data market symbols.
        |
        */

        if (
            !empty($missing) &&
            !empty($this->getProviderKeys('twelvedata'))
        ) {
            foreach ($missing as $coinId => $crypto) {
                if ($this->isUsdtVariant($crypto)) {
                    continue;
                }

                $price = $this->getTwelveDataPrice($crypto);

                if ($this->isValidPrice($price)) {
                    $livePrices[$coinId] = $this->formatPrice(
                        (float) $price,
                        0,
                        'twelvedata'
                    );

                    unset($missing[$coinId]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. ALPHA VANTAGE
        |--------------------------------------------------------------------------
        */

        if (
            !empty($missing) &&
            !empty($this->getProviderKeys('alphavantage'))
        ) {
            foreach ($missing as $coinId => $crypto) {
                if ($this->isUsdtVariant($crypto)) {
                    continue;
                }

                $price = $this->getAlphaVantagePrice($crypto);

                if ($this->isValidPrice($price)) {
                    $livePrices[$coinId] = $this->formatPrice(
                        (float) $price,
                        0,
                        'alphavantage'
                    );

                    unset($missing[$coinId]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. FINNHUB
        |--------------------------------------------------------------------------
        */

        if (
            !empty($missing) &&
            !empty($this->getProviderKeys('finnhub'))
        ) {
            foreach ($missing as $coinId => $crypto) {
                if ($this->isUsdtVariant($crypto)) {
                    continue;
                }

                $price = $this->getFinnhubPrice($crypto);

                if ($this->isValidPrice($price)) {
                    $livePrices[$coinId] = $this->formatPrice(
                        (float) $price,
                        0,
                        'finnhub'
                    );

                    unset($missing[$coinId]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. LAST-KNOWN VALID MARKET PRICE
        |--------------------------------------------------------------------------
        |
        | If every provider fails, use the last successfully retrieved
        | market price, marked as stale.
        |
        */

        foreach ($missing as $coinId => $crypto) {
            $previous = $lastGoodPrices[$coinId] ?? null;

            if ($this->isValidPrice($previous['usd'] ?? null)) {
                $previous['is_stale'] = true;
                $previous['provider'] = 'cached';

                $livePrices[$coinId] = $previous;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE LAST-GOOD CACHE
        |--------------------------------------------------------------------------
        |
        | Only valid, fresh provider prices are stored.
        | Manual and stale prices are never saved as fresh market prices.
        |
        */

        $updatedLastGood = $lastGoodPrices;

        foreach ($livePrices as $coinId => $priceData) {
            $provider = $priceData['provider'] ?? '';

            if (
                !in_array($provider, ['manual', 'cached'], true) &&
                empty($priceData['is_stale']) &&
                $this->isValidPrice($priceData['usd'] ?? null)
            ) {
                $updatedLastGood[$coinId] = $priceData;
            }
        }

        if (!empty($updatedLastGood)) {
            Cache::put(
                $lastGoodCacheKey,
                $updatedLastGood,
                now()->addSeconds(self::LAST_GOOD_CACHE_SECONDS)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. MANUAL ADMIN OVERRIDES
        |--------------------------------------------------------------------------
        |
        | Manual prices always take precedence over provider prices.
        |
        */

        $livePrices = $this->applyManualOverrides(
            $livePrices,
            $cryptos
        );

        Cache::put(
            $cacheKey,
            $livePrices,
            now()->addSeconds(self::PRICE_CACHE_SECONDS)
        );

        return $livePrices;
    }

    /*
    |--------------------------------------------------------------------------
    | Cryptocurrency Identifier Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve the provider-facing market-price identifier.
     *
     * Explicit price_coin_id takes precedence.
     *
     * If it is empty, recognised USDT network variants automatically
     * resolve to CoinGecko's "tether" identifier.
     */
    private function getPriceCoinId(Cryptocurrency $crypto): string
    {
        $priceCoinId = trim(
            (string) $crypto->getAttribute('price_coin_id')
        );

        if ($priceCoinId !== '') {
            return $priceCoinId;
        }

        if ($this->isUsdtVariant($crypto)) {
            return 'tether';
        }

        return trim((string) $crypto->coin_id);
    }

    /**
     * Determine whether a catalog record represents USDT.
     *
     * Supports symbols such as:
     * - USDT
     * - USDT_TRC20
     * - USDT_ERC20
     * - USDT_BEP20
     * - USDT-TRC20
     *
     * Also recognises Tether coin IDs and records named USDT.
     */
    private function isUsdtVariant(Cryptocurrency $crypto): bool
    {
        $symbol = strtoupper(trim((string) $crypto->symbol));
        $name = strtoupper(trim((string) $crypto->name));
        $coinId = strtolower(trim((string) $crypto->coin_id));

        if ($symbol === 'USDT' || $name === 'USDT') {
            return true;
        }

        if (
            strpos($symbol, 'USDT_') === 0 ||
            strpos($symbol, 'USDT-') === 0
        ) {
            return true;
        }

        if (strpos($coinId, 'tether-') === 0) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Manual Price Overrides
    |--------------------------------------------------------------------------
    */

    /**
     * Apply administrator-configured manual prices.
     */
    private function applyManualOverrides(
        array $prices,
        $cryptos
    ): array {
        foreach ($cryptos as $crypto) {
            $coinId = trim((string) $crypto->coin_id);

            if (
                $coinId !== '' &&
                $crypto->manual_price !== null &&
                $this->isValidPrice($crypto->manual_price)
            ) {
                $prices[$coinId] = $this->formatPrice(
                    (float) $crypto->manual_price,
                    0,
                    'manual'
                );
            }
        }

        return $prices;
    }

    /*
    |--------------------------------------------------------------------------
    | API Key Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get all configured API keys for a provider.
     *
     * Supports:
     * - Multiple API keys in an array.
     * - Comma-separated API keys.
     * - A single API key.
     */
    private function getProviderKeys(string $provider): array
    {
        $keys = config("services.{$provider}.keys", []);

        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        if (!is_array($keys)) {
            $keys = [];
        }

        if (empty($keys)) {
            $singleKey = config("services.{$provider}.key");

            if (
                is_string($singleKey) &&
                trim($singleKey) !== ''
            ) {
                $keys = [$singleKey];
            }
        }

        $keys = array_map(
            static function ($key) {
                return trim((string) $key);
            },
            $keys
        );

        $keys = array_filter(
            $keys,
            static function ($key) {
                return $key !== '';
            }
        );

        return array_values(array_unique($keys));
    }

    /**
     * Rotate API key order between requests.
     */
    private function getRotatedApiKeys(string $provider): array
    {
        $keys = $this->getProviderKeys($provider);

        if (empty($keys)) {
            return [];
        }

        if (count($keys) === 1) {
            return $keys;
        }

        $indexCacheKey = 'crypto_api_key_index_' . $provider;

        $index = (int) Cache::get($indexCacheKey, 0);
        $index = $index % count($keys);

        $rotatedKeys = array_merge(
            array_slice($keys, $index),
            array_slice($keys, 0, $index)
        );

        Cache::put(
            $indexCacheKey,
            ($index + 1) % count($keys),
            now()->addDay()
        );

        return $rotatedKeys;
    }

    /*
    |--------------------------------------------------------------------------
    | Binance
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve cryptocurrency prices from Binance.
     *
     * Binance USDT trading pairs are matched against catalog symbols.
     * Ambiguous catalog symbols are deliberately excluded.
     */
    private function getBinancePrices(array $cryptos): array
    {
        if ($this->providerIsDisabled('binance')) {
            return [];
        }

        $symbolMap = [];
        $ambiguousSymbols = [];

        foreach ($cryptos as $coinId => $crypto) {
            $symbol = strtoupper(trim((string) $crypto->symbol));

            if (
                $symbol === '' ||
                $this->isUsdtVariant($crypto)
            ) {
                continue;
            }

            if (isset($symbolMap[$symbol])) {
                unset($symbolMap[$symbol]);
                $ambiguousSymbols[$symbol] = true;

                continue;
            }

            if (isset($ambiguousSymbols[$symbol])) {
                continue;
            }

            $symbolMap[$symbol] = $coinId;
        }

        if (empty($symbolMap)) {
            return [];
        }

        try {
            $response = Http::acceptJson()
                ->withUserAgent(
                    config('app.name', 'Laravel') . ' CryptoService'
                )
                ->connectTimeout(5)
                ->timeout(15)
                ->get('https://api.binance.com/api/v3/ticker/price');

            if (!$response->successful()) {
                $status = $response->status();

                if (
                    $status === 429 ||
                    $status >= 500
                ) {
                    $this->disableProvider(
                        'binance',
                        self::BINANCE_COOLDOWN,
                        'HTTP ' . $status
                    );
                }

                Log::warning('Binance price request failed.', [
                    'status' => $status,
                ]);

                return [];
            }

            $tickers = $response->json();

            if (!is_array($tickers)) {
                return [];
            }

            $result = [];

            foreach ($tickers as $ticker) {
                $pair = strtoupper(
                    (string) ($ticker['symbol'] ?? '')
                );

                if (substr($pair, -4) !== 'USDT') {
                    continue;
                }

                $base = substr($pair, 0, -4);

                if (!isset($symbolMap[$base])) {
                    continue;
                }

                $price = $ticker['price'] ?? null;

                if (!$this->isValidPrice($price)) {
                    continue;
                }

                $result[$symbolMap[$base]] = $this->formatPrice(
                    (float) $price,
                    0,
                    'binance'
                );
            }

            return $result;

        } catch (\Throwable $e) {
            $this->disableProvider(
                'binance',
                self::BINANCE_COOLDOWN,
                $e->getMessage()
            );

            Log::warning('Binance connection failed.', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CoinGecko
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve cryptocurrency prices from CoinGecko.
     *
     * Accepts CoinGecko IDs, including shared IDs such as "tether".
     * Requests are split into batches of 80 IDs.
     */
    private function getCoinGeckoPrices(array $coinIds): array
    {
        if ($this->providerIsDisabled('coingecko')) {
            return [];
        }

        $coinIds = array_values(array_unique(array_filter(
            $coinIds,
            static function ($id) {
                return is_string($id) && trim($id) !== '';
            }
        )));

        if (empty($coinIds)) {
            return [];
        }

        $result = [];

        foreach (array_chunk($coinIds, 80) as $chunk) {
            if ($this->providerIsDisabled('coingecko')) {
                break;
            }

            try {
                $response = Http::acceptJson()
                    ->withUserAgent(
                        config('app.name', 'Laravel') . ' CryptoService'
                    )
                    ->connectTimeout(5)
                    ->timeout(15)
                    ->get(
                        'https://api.coingecko.com/api/v3/simple/price',
                        [
                            'ids' => implode(',', $chunk),
                            'vs_currencies' => 'usd',
                            'include_24hr_change' => 'true',
                        ]
                    );

                if (!$response->successful()) {
                    $status = $response->status();

                    if (
                        $status === 429 ||
                        $status >= 500
                    ) {
                        $this->disableProvider(
                            'coingecko',
                            self::COINGECKO_COOLDOWN,
                            'HTTP ' . $status
                        );
                    }

                    Log::warning('CoinGecko price request failed.', [
                        'status' => $status,
                        'coin_ids' => $chunk,
                    ]);

                    continue;
                }

                $data = $response->json();

                if (!is_array($data)) {
                    continue;
                }

                foreach ($chunk as $coinId) {
                    $price = $data[$coinId]['usd'] ?? null;

                    if (!$this->isValidPrice($price)) {
                        continue;
                    }

                    $change = $data[$coinId]['usd_24h_change'] ?? 0;

                    if (!is_numeric($change)) {
                        $change = 0;
                    }

                    $result[$coinId] = $this->formatPrice(
                        (float) $price,
                        (float) $change,
                        'coingecko'
                    );
                }

            } catch (\Throwable $e) {
                $this->disableProvider(
                    'coingecko',
                    self::COINGECKO_COOLDOWN,
                    $e->getMessage()
                );

                Log::warning('CoinGecko connection failed.', [
                    'coin_ids' => $chunk,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Twelve Data
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve a cryptocurrency price from Twelve Data.
     */
    private function getTwelveDataPrice($crypto): ?float
    {
        if ($this->providerIsDisabled('twelvedata')) {
            return null;
        }

        if ($this->isUsdtVariant($crypto)) {
            return null;
        }

        $symbol = strtoupper(trim((string) $crypto->symbol));

        if ($symbol === '') {
            return null;
        }

        $cacheKey = 'crypto_twelvedata_' . md5(
            $crypto->coin_id . '|' . $symbol
        );

        $cached = Cache::get($cacheKey);

        if ($this->isValidPrice($cached)) {
            return (float) $cached;
        }

        $keys = $this->getRotatedApiKeys('twelvedata');

        if (empty($keys)) {
            return null;
        }

        $rateLimited = false;

        foreach ($keys as $key) {
            try {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(12)
                    ->get('https://api.twelvedata.com/price', [
                        'symbol' => $symbol . '/USD',
                        'apikey' => $key,
                    ]);

                if (!$response->successful()) {
                    $status = $response->status();

                    if (
                        $status === 429 ||
                        $status === 403
                    ) {
                        $rateLimited = true;
                    }

                    Log::warning('Twelve Data price request failed.', [
                        'symbol' => $symbol,
                        'status' => $status,
                    ]);

                    continue;
                }

                $data = $response->json();

                if (!is_array($data)) {
                    continue;
                }

                if (
                    isset($data['status']) &&
                    strtolower((string) $data['status']) === 'error'
                ) {
                    $message = strtolower(
                        (string) ($data['message'] ?? '')
                    );

                    if (
                        strpos($message, 'rate limit') !== false ||
                        strpos($message, 'api credits') !== false ||
                        strpos($message, 'api call frequency') !== false
                    ) {
                        $rateLimited = true;
                    }

                    Log::warning('Twelve Data returned an error.', [
                        'symbol' => $symbol,
                        'message' => $data['message'] ?? 'Unknown error',
                    ]);

                    continue;
                }

                $price = $data['price'] ?? null;

                if (!$this->isValidPrice($price)) {
                    continue;
                }

                Cache::put(
                    $cacheKey,
                    (float) $price,
                    now()->addMinutes(5)
                );

                return (float) $price;

            } catch (\Throwable $e) {
                Log::warning('Twelve Data connection failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($rateLimited) {
            $this->disableProvider(
                'twelvedata',
                self::TWELVE_DATA_COOLDOWN,
                'API rate limit or credits limit reached'
            );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Alpha Vantage
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve a cryptocurrency exchange rate from Alpha Vantage.
     */
    private function getAlphaVantagePrice($crypto): ?float
    {
        if ($this->providerIsDisabled('alphavantage')) {
            return null;
        }

        if ($this->isUsdtVariant($crypto)) {
            return null;
        }

        $symbol = strtoupper(trim((string) $crypto->symbol));

        if ($symbol === '') {
            return null;
        }

        $cacheKey = 'crypto_alphavantage_' . md5(
            $crypto->coin_id . '|' . $symbol
        );

        $cached = Cache::get($cacheKey);

        if ($this->isValidPrice($cached)) {
            return (float) $cached;
        }

        $keys = $this->getRotatedApiKeys('alphavantage');

        if (empty($keys)) {
            return null;
        }

        $providerLimited = false;

        foreach ($keys as $key) {
            try {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(12)
                    ->get('https://www.alphavantage.co/query', [
                        'function' => 'CURRENCY_EXCHANGE_RATE',
                        'from_currency' => $symbol,
                        'to_currency' => 'USD',
                        'apikey' => $key,
                    ]);

                if (!$response->successful()) {
                    $status = $response->status();

                    if (
                        $status === 429 ||
                        $status >= 500
                    ) {
                        $providerLimited = true;
                    }

                    Log::warning('Alpha Vantage request failed.', [
                        'symbol' => $symbol,
                        'status' => $status,
                    ]);

                    continue;
                }

                $data = $response->json();

                if (!is_array($data)) {
                    continue;
                }

                /*
                 * Note and Information commonly indicate API quota
                 * or request-frequency restrictions.
                 */
                if (
                    isset($data['Note']) ||
                    isset($data['Information'])
                ) {
                    $providerLimited = true;

                    Log::warning(
                        'Alpha Vantage rate limit or provider notice.',
                        [
                            'symbol' => $symbol,
                            'message' => $data['Note']
                                ?? $data['Information'],
                        ]
                    );

                    continue;
                }

                /*
                 * Error Message usually indicates an unsupported or
                 * invalid symbol. Do not automatically place the
                 * entire provider in cooldown for this.
                 */
                if (isset($data['Error Message'])) {
                    Log::warning(
                        'Alpha Vantage rejected the requested symbol.',
                        [
                            'symbol' => $symbol,
                            'message' => $data['Error Message'],
                        ]
                    );

                    continue;
                }

                $price = $data['Realtime Currency Exchange Rate']
                    ['5. Exchange Rate'] ?? null;

                if (!$this->isValidPrice($price)) {
                    continue;
                }

                Cache::put(
                    $cacheKey,
                    (float) $price,
                    now()->addMinutes(5)
                );

                return (float) $price;

            } catch (\Throwable $e) {
                Log::warning('Alpha Vantage connection failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($providerLimited) {
            $this->disableProvider(
                'alphavantage',
                self::ALPHA_VANTAGE_COOLDOWN,
                'API quota or request-frequency restriction'
            );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Finnhub
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve cryptocurrency prices from Finnhub.
     *
     * Uses Binance trading-pair symbol format.
     */
    private function getFinnhubPrice($crypto): ?float
    {
        if ($this->providerIsDisabled('finnhub')) {
            return null;
        }

        if ($this->isUsdtVariant($crypto)) {
            return null;
        }

        $symbol = strtoupper(trim((string) $crypto->symbol));

        if ($symbol === '') {
            return null;
        }

        $cacheKey = 'crypto_finnhub_' . md5(
            $crypto->coin_id . '|' . $symbol
        );

        $cached = Cache::get($cacheKey);

        if ($this->isValidPrice($cached)) {
            return (float) $cached;
        }

        $keys = $this->getRotatedApiKeys('finnhub');

        if (empty($keys)) {
            return null;
        }

        $rateLimited = false;

        foreach ($keys as $key) {
            try {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(12)
                    ->get('https://finnhub.io/api/v1/quote', [
                        'symbol' => 'BINANCE:' . $symbol . 'USDT',
                        'token' => $key,
                    ]);

                if (!$response->successful()) {
                    $status = $response->status();

                    if (
                        $status === 429 ||
                        $status === 403
                    ) {
                        $rateLimited = true;
                    }

                    Log::warning('Finnhub price request failed.', [
                        'symbol' => $symbol,
                        'status' => $status,
                    ]);

                    continue;
                }

                $data = $response->json();

                if (!is_array($data)) {
                    continue;
                }

                $price = $data['c'] ?? null;

                if (!$this->isValidPrice($price)) {
                    continue;
                }

                Cache::put(
                    $cacheKey,
                    (float) $price,
                    now()->addMinutes(5)
                );

                return (float) $price;

            } catch (\Throwable $e) {
                Log::warning('Finnhub connection failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($rateLimited) {
            $this->disableProvider(
                'finnhub',
                self::FINNHUB_COOLDOWN,
                'API rate limit reached'
            );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Provider Cooldown Management
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether a provider is currently in cooldown.
     */
    private function providerIsDisabled(string $provider): bool
    {
        return Cache::get(
            'crypto_provider_disabled_' . $provider,
            false
        ) === true;
    }

    /**
     * Temporarily disable a provider after a connection or rate-limit error.
     */
    private function disableProvider(
        string $provider,
        int $seconds,
        string $reason
    ): void {
        Cache::put(
            'crypto_provider_disabled_' . $provider,
            true,
            now()->addSeconds($seconds)
        );

        Log::warning('Crypto provider placed in cooldown.', [
            'provider' => $provider,
            'cooldown_seconds' => $seconds,
            'reason' => $reason,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Price Formatting and Validation
    |--------------------------------------------------------------------------
    */

    /**
     * Standard price response.
     */
    private function formatPrice(
        float $price,
        float $change = 0,
        string $provider = 'unknown'
    ): array {
        return [
            'usd' => $price,
            'usd_24h_change' => $change,
            'last_updated' => now()->timestamp,
            'provider' => $provider,
        ];
    }

    /**
     * Validate a cryptocurrency price.
     */
    private function isValidPrice($price): bool
    {
        return is_numeric($price)
            && is_finite((float) $price)
            && (float) $price > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Backward Compatibility
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve a standard market-price identifier from a wallet symbol.
     *
     * Returns null if the symbol is ambiguous.
     */
    public function getStandardSymbol($symbol): ?string
    {
        $symbol = strtoupper(trim((string) $symbol));

        if ($symbol === '') {
            return null;
        }

        $cryptos = Cryptocurrency::where('symbol', $symbol)
            ->where('is_active', true)
            ->get();

        $priceIds = $cryptos
            ->map(function ($crypto) {
                return $this->getPriceCoinId($crypto);
            })
            ->filter()
            ->unique()
            ->values();

        return $priceIds->count() === 1
            ? $priceIds->first()
            : null;
    }

    /**
     * Get an icon URL from the cryptocurrency catalog.
     *
     * If multiple network variants share a symbol, the first
     * configured icon is returned.
     */
    public function getIconUrl($symbol): ?string
    {
        $symbol = strtoupper(trim((string) $symbol));

        if ($symbol === '') {
            return null;
        }

        $cryptos = Cryptocurrency::where('symbol', $symbol)
            ->where('is_active', true)
            ->whereNotNull('icon_url')
            ->where('icon_url', '!=', '')
            ->get();

        return $cryptos->first()->icon_url ?? null;
    }

    /**
     * Get an icon directly from a cryptocurrency record.
     */
    public function getCryptoIconUrl(Cryptocurrency $crypto): ?string
    {
        $url = trim((string) $crypto->icon_url);

        return $url !== '' ? $url : null;
    }

    /**
     * Backward-compatible network icon method.
     *
     * Returns null until a separate network_icon_url field
     * is configured in the database.
     */
    public function getNetworkIcon($network): ?string
    {
        return null;
    }
}
