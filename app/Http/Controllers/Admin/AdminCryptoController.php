<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Models\CryptoAsset;
use App\Models\Setting;
use App\Services\CryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminCryptoController extends Controller
{
    private const BINANCE_COOLDOWN = 300;
    private const COINGECKO_COOLDOWN = 180;
    private const TWELVE_DATA_COOLDOWN = 900;
    private const ALPHA_VANTAGE_COOLDOWN = 3600;
    private const FINNHUB_COOLDOWN = 900;

    private const PRICE_CACHE_SECONDS = 60;
    private const METADATA_CACHE_SECONDS = 21600;

    /**
     * Display cryptocurrency management.
     */
    public function index()
    {
        $cryptos = Cryptocurrency::orderBy('name')->paginate(15);

        try {
            $livePrices = Cache::remember(
                'admin_crypto_live_prices',
                now()->addSeconds(self::PRICE_CACHE_SECONDS),
                function () {
                    $prices = app(CryptoService::class)->getPrices();

                    return is_array($prices) ? $prices : [];
                }
            );
        } catch (\Throwable $e) {
            Log::error('Admin cryptocurrency price retrieval failed.', [
                'message' => $e->getMessage(),
            ]);

            $livePrices = [];
        }

        return view('admin.cryptos.index', compact(
            'cryptos',
            'livePrices'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.cryptos.create');
    }

    /**
     * Store a cryptocurrency.
     */
    public function store(Request $request)
    {
        $request->merge([
            'symbol' => strtolower(trim(
                (string) $request->input('symbol', '')
            )),
            'wallet_address' => trim(
                (string) $request->input('wallet_address', '')
            ),
        ]);

        $validated = $request->validate([
            'symbol' => 'required|string|unique:cryptocurrencies,symbol|max:20',
            'name' => 'required|string|max:100',
            'network' => 'nullable|string|max:50',

            'coin_id' => 'required|string|unique:cryptocurrencies,coin_id|max:50',
            'price_coin_id' => 'nullable|string|max:100',

            'icon_url' => 'nullable|url|max:255',
            'manual_price' => 'nullable|numeric|min:0',

            'wallet_address' => 'nullable|string|max:1000',

            'is_active' => 'sometimes|boolean',
            'is_deposit_enabled' => 'sometimes|boolean',
            'is_withdrawal_enabled' => 'sometimes|boolean',

            'min_deposit' => 'nullable|numeric|min:0',
            'min_withdrawal' => 'nullable|numeric|min:0',
            'withdrawal_fee' => 'nullable|numeric|min:0',
        ]);

        $crypto = Cryptocurrency::create([
            'symbol' => $validated['symbol'],
            'name' => $validated['name'],
            'network' => $validated['network'] ?? null,

            'coin_id' => $validated['coin_id'],
            'price_coin_id' => $validated['price_coin_id'] ?? null,

            'icon_url' => $validated['icon_url'] ?? null,
            'manual_price' => $validated['manual_price'] ?? null,

            'is_active' => $request->boolean('is_active'),
            'is_deposit_enabled' => $request->boolean('is_deposit_enabled'),
            'is_withdrawal_enabled' => $request->boolean('is_withdrawal_enabled'),

            'min_deposit' => $validated['min_deposit'] ?? 0,
            'min_withdrawal' => $validated['min_withdrawal'] ?? 0,
            'withdrawal_fee' => $validated['withdrawal_fee'] ?? 0,
        ]);

        // Save the system receiving address separately in settings.
        $this->saveSystemWalletAddress(
            $crypto,
            $validated['wallet_address'] ?? null
        );

        $this->clearCryptoPriceCache();

        return redirect()
            ->route('admin.settings.wallets')
            ->with('status', 'Cryptocurrency added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Cryptocurrency $crypto)
    {
        return view('admin.cryptos.edit', compact('crypto'));
    }

    /**
     * Update a cryptocurrency.
     */
    public function update(Request $request, Cryptocurrency $crypto)
    {
        $previousAddressKey = $crypto->wallet_address_setting_key;

        $request->merge([
            'wallet_address' => trim(
                (string) $request->input('wallet_address', '')
            ),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'network' => 'nullable|string|max:50',

            'coin_id' => 'required|string|unique:cryptocurrencies,coin_id,' . $crypto->id . '|max:50',
            'price_coin_id' => 'nullable|string|max:100',

            'icon_url' => 'nullable|url|max:255',
            'manual_price' => 'nullable|numeric|min:0',

            'wallet_address' => 'nullable|string|max:1000',

            'is_active' => 'sometimes|boolean',
            'is_deposit_enabled' => 'sometimes|boolean',
            'is_withdrawal_enabled' => 'sometimes|boolean',

            'min_deposit' => 'nullable|numeric|min:0',
            'min_withdrawal' => 'nullable|numeric|min:0',
            'withdrawal_fee' => 'nullable|numeric|min:0',
        ]);

        $crypto->update([
            'name' => $validated['name'],
            'network' => $validated['network'] ?? null,

            'coin_id' => $validated['coin_id'],
            'price_coin_id' => $validated['price_coin_id'] ?? null,

            'icon_url' => $validated['icon_url'] ?? null,
            'manual_price' => $validated['manual_price'] ?? null,

            'is_active' => $request->boolean('is_active'),
            'is_deposit_enabled' => $request->boolean('is_deposit_enabled'),
            'is_withdrawal_enabled' => $request->boolean('is_withdrawal_enabled'),

            'min_deposit' => $validated['min_deposit'] ?? 0,
            'min_withdrawal' => $validated['min_withdrawal'] ?? 0,
            'withdrawal_fee' => $validated['withdrawal_fee'] ?? 0,
        ]);

        if ($previousAddressKey !== $crypto->wallet_address_setting_key) {
            Setting::where('key', $previousAddressKey)->delete();
        }

        // Save or clear the system receiving address.
        $this->saveSystemWalletAddress(
            $crypto,
            $validated['wallet_address'] ?? null
        );

        $this->clearCryptoPriceCache();

        return redirect()
            ->route('admin.settings.wallets')
            ->with('status', 'Cryptocurrency updated successfully.');
    }

    /**
     * Delete a cryptocurrency.
     */
    public function destroy(Cryptocurrency $crypto)
    {
        $staticColumns = [
            'btc_balance',
            'usdt_trc20_balance',
            'usdt_erc20_balance',
            'eth_balance',
            'trx_balance',
            'usdt_bep20_balance',
            'bch_balance',
            'ltc_balance',
            'xlm_balance',
            'dash_balance',
            'sol_balance',
            'bnb_balance',
            'dot_balance',
            'doge_balance',
            'xdc_balance',
            'xrp_balance',
        ];

        $balanceColumn = strtolower($crypto->symbol) . '_balance';

        if (in_array($balanceColumn, $staticColumns, true)) {
            $hasBalances = CryptoAsset::where(
                $balanceColumn,
                '>',
                0
            )->exists();

            if ($hasBalances) {
                return redirect()
                    ->route('admin.settings.wallets')
                    ->with(
                        'error',
                        'Cannot delete cryptocurrency with existing user balances. Please disable it instead.'
                    );
            }
        }

        $deletedSymbol = strtolower($crypto->symbol);
        $addressKey = $crypto->wallet_address_setting_key;

        $crypto->delete();

        // Remove the associated system wallet address.
        Setting::where('key', $addressKey)->delete();

        $this->clearSymbolLookupCache($deletedSymbol);
        $this->clearCryptoPriceCache();

        return redirect()
            ->route('admin.settings.wallets')
            ->with('status', 'Cryptocurrency deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Cryptocurrency $crypto)
    {
        $crypto->update([
            'is_active' => !$crypto->is_active,
        ]);

        $this->clearCryptoPriceCache();

        return back()->with('status', 'Status updated successfully.');
    }

    /**
     * Toggle deposits.
     */
    public function toggleDeposit(Cryptocurrency $crypto)
    {
        $crypto->update([
            'is_deposit_enabled' => !$crypto->is_deposit_enabled,
        ]);

        return back()->with('status', 'Deposit status updated successfully.');
    }

    /**
     * Toggle withdrawals.
     */
    public function toggleWithdrawal(Cryptocurrency $crypto)
    {
        $crypto->update([
            'is_withdrawal_enabled' => !$crypto->is_withdrawal_enabled,
        ]);

        return back()->with('status', 'Withdrawal status updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CRYPTOCURRENCY LOOKUP
    |--------------------------------------------------------------------------
    */

    /**
     * Fetch cryptocurrency metadata and live price.
     */
    public function fetchDetails(Request $request)
    {
        $symbol = strtoupper(trim(
            (string) $request->query('symbol', '')
        ));

        if ($symbol === '') {
            return response()->json([
                'success' => false,
                'error' => 'Cryptocurrency symbol is required.',
            ], 422);
        }

        if (!preg_match('/^[A-Z0-9._-]{1,20}$/', $symbol)) {
            return response()->json([
                'success' => false,
                'error' => 'Please enter a valid cryptocurrency symbol.',
            ], 422);
        }

        try {
            $metadata = $this->getCryptocurrencyMetadata($symbol);

            if (!$metadata) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unable to identify this cryptocurrency. No exact symbol match was found.',
                ], 404);
            }

            /*
             * Retrieve a recently cached live price.
             * If no valid cached price exists, retry providers.
             *
             * Failed price lookups are not cached, allowing
             * the next Fetch Details request to try again.
             */
            $priceCacheKey = 'crypto_lookup_price_' . strtolower($symbol);

            $price = Cache::get($priceCacheKey);

            if (
                !is_array($price) ||
                !$this->isValidPrice($price['usd'] ?? null)
            ) {
                $price = $this->getLookupPrice(
                    $symbol,
                    $metadata['coin_id']
                );

                if (
                    is_array($price) &&
                    $this->isValidPrice($price['usd'] ?? null)
                ) {
                    Cache::put(
                        $priceCacheKey,
                        $price,
                        now()->addSeconds(self::PRICE_CACHE_SECONDS)
                    );
                } else {
                    $price = null;
                }
            }

            $result = [
                'name' => $metadata['name'],
                'coin_id' => $metadata['coin_id'],
                'icon_url' => $metadata['icon_url'] ?? '',
                'live_price' => $price['usd'] ?? null,
                'provider' => $price['provider'] ?? null,
            ];

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => $result['live_price'] !== null
                    ? 'Cryptocurrency details and live market price retrieved successfully.'
                    : 'Cryptocurrency details retrieved, but live pricing providers are currently unavailable.',
            ]);

        } catch (\Throwable $e) {
            Log::error('Cryptocurrency details lookup failed.', [
                'symbol' => $symbol,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An unexpected server error occurred while retrieving cryptocurrency details. Please try again.',
            ], 500);
        }
    }

    /**
     * Retrieve and cache cryptocurrency metadata separately
     * from live market prices.
     */
    private function getCryptocurrencyMetadata(string $symbol): ?array
    {
        $cacheKey = 'crypto_details_' . strtolower($symbol);

        $cached = Cache::get($cacheKey);

        if (
            is_array($cached) &&
            !empty($cached['name']) &&
            !empty($cached['coin_id'])
        ) {
            return [
                'name' => $cached['name'],
                'coin_id' => $cached['coin_id'],
                'icon_url' => $cached['icon_url'] ?? '',
            ];
        }

        /*
         * Resolve a standard CoinGecko ID when supported.
         */
        $standardCoinId = app(CryptoService::class)
            ->getStandardSymbol($symbol);

        /*
         * Search CoinGecko for an exact symbol match.
         */
        $coin = $this->searchCoinGecko($symbol);

        if (!$coin && !$standardCoinId) {
            return null;
        }

        if ($coin) {
            $name = $coin['name'];
            $coinId = $coin['id'];

            $iconUrl = $coin['large']
                ?? $coin['thumb']
                ?? '';
        } else {
            $coinId = $standardCoinId;
            $name = ucwords(str_replace('-', ' ', $coinId));

            $iconUrl = app(CryptoService::class)
                ->getIconUrl($coinId) ?? '';
        }

        $metadata = [
            'name' => $name,
            'coin_id' => $coinId,
            'icon_url' => $iconUrl,
        ];

        Cache::put(
            $cacheKey,
            $metadata,
            now()->addSeconds(self::METADATA_CACHE_SECONDS)
        );

        return $metadata;
    }

    /**
     * Try price providers in priority order.
     */
    private function getLookupPrice(
        string $symbol,
        string $coinId
    ): ?array {
        /*
         * 1. Binance
         */
        $price = $this->getBinancePrice($symbol);

        if ($this->isValidPrice($price)) {
            return [
                'usd' => (float) $price,
                'provider' => 'binance',
            ];
        }

        /*
         * 2. CoinGecko
         */
        $price = $this->getCoinGeckoPrice($coinId);

        if ($this->isValidPrice($price)) {
            return [
                'usd' => (float) $price,
                'provider' => 'coingecko',
            ];
        }

        /*
         * 3. Twelve Data
         */
        $price = $this->getTwelveDataPrice($symbol);

        if ($this->isValidPrice($price)) {
            return [
                'usd' => (float) $price,
                'provider' => 'twelvedata',
            ];
        }

        /*
         * 4. Alpha Vantage
         */
        $price = $this->getAlphaVantagePrice($symbol);

        if ($this->isValidPrice($price)) {
            return [
                'usd' => (float) $price,
                'provider' => 'alphavantage',
            ];
        }

        /*
         * 5. Finnhub
         */
        $price = $this->getFinnhubPrice($symbol);

        if ($this->isValidPrice($price)) {
            return [
                'usd' => (float) $price,
                'provider' => 'finnhub',
            ];
        }

        return null;
    }

    /**
     * Search CoinGecko by exact symbol.
     */
    private function searchCoinGecko(string $symbol): ?array
    {
        if ($this->providerIsDisabled('coingecko')) {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->withUserAgent(
                    config('app.name', 'Laravel') . ' cryptocurrency lookup'
                )
                ->connectTimeout(5)
                ->timeout(12)
                ->get('https://api.coingecko.com/api/v3/search', [
                    'query' => $symbol,
                ]);

            if (!$response->successful()) {
                $status = $response->status();

                if ($status === 429 || $status >= 500) {
                    $this->disableProvider(
                        'coingecko',
                        self::COINGECKO_COOLDOWN,
                        'HTTP ' . $status
                    );
                }

                Log::warning('CoinGecko metadata lookup failed.', [
                    'symbol' => $symbol,
                    'status' => $status,
                ]);

                return null;
            }

            $coins = $response->json('coins', []);

            if (!is_array($coins)) {
                return null;
            }

            $matches = collect($coins)->filter(
                function ($item) use ($symbol) {
                    return strtoupper(
                        (string) ($item['symbol'] ?? '')
                    ) === $symbol
                        && !empty($item['id'])
                        && !empty($item['name']);
                }
            );

            if ($matches->isEmpty()) {
                return null;
            }

            /*
             * Prefer the highest-ranked exact symbol match.
             */
            $coin = $matches->sortBy(function ($item) {
                return (int) ($item['market_cap_rank'] ?? PHP_INT_MAX);
            })->first();

            return [
                'id' => $coin['id'],
                'name' => $coin['name'],
                'symbol' => $coin['symbol'],
                'large' => $coin['large'] ?? null,
                'thumb' => $coin['thumb'] ?? null,
            ];

        } catch (\Throwable $e) {
            Log::warning('CoinGecko metadata exception.', [
                'symbol' => $symbol,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Binance USDT pair price.
     */
    private function getBinancePrice(string $symbol): ?float
    {
        if ($symbol === 'USDT') {
            return 1.0;
        }

        if ($this->providerIsDisabled('binance')) {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->connectTimeout(5)
                ->timeout(10)
                ->get('https://api.binance.com/api/v3/ticker/price', [
                    'symbol' => $symbol . 'USDT',
                ]);

            if (!$response->successful()) {
                $status = $response->status();

                if ($status === 429 || $status >= 500) {
                    $this->disableProvider(
                        'binance',
                        self::BINANCE_COOLDOWN,
                        'HTTP ' . $status
                    );
                }

                return null;
            }

            $price = $response->json('price');

            return $this->isValidPrice($price)
                ? (float) $price
                : null;

        } catch (\Throwable $e) {
            $this->disableProvider(
                'binance',
                self::BINANCE_COOLDOWN,
                $e->getMessage()
            );

            Log::warning('Binance lookup connection failed.', [
                'symbol' => $symbol,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * CoinGecko USD price.
     */
    private function getCoinGeckoPrice(string $coinId): ?float
    {
        if ($this->providerIsDisabled('coingecko')) {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->withUserAgent(
                    config('app.name', 'Laravel') . ' cryptocurrency lookup'
                )
                ->connectTimeout(5)
                ->timeout(12)
                ->get('https://api.coingecko.com/api/v3/simple/price', [
                    'ids' => $coinId,
                    'vs_currencies' => 'usd',
                ]);

            if (!$response->successful()) {
                $status = $response->status();

                if ($status === 429 || $status >= 500) {
                    $this->disableProvider(
                        'coingecko',
                        self::COINGECKO_COOLDOWN,
                        'HTTP ' . $status
                    );
                }

                return null;
            }

            $data = $response->json();
            $price = $data[$coinId]['usd'] ?? null;

            return $this->isValidPrice($price)
                ? (float) $price
                : null;

        } catch (\Throwable $e) {
            Log::warning('CoinGecko price lookup exception.', [
                'coin_id' => $coinId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Twelve Data price lookup with multiple API keys.
     */
    private function getTwelveDataPrice(string $symbol): ?float
    {
        if ($this->providerIsDisabled('twelvedata')) {
            return null;
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
                    if (in_array($response->status(), [403, 429], true)) {
                        $rateLimited = true;
                    }

                    continue;
                }

                $data = $response->json();

                if (
                    !is_array($data) ||
                    ($data['status'] ?? null) === 'error'
                ) {
                    continue;
                }

                $price = $data['price'] ?? null;

                if ($this->isValidPrice($price)) {
                    return (float) $price;
                }

            } catch (\Throwable $e) {
                Log::warning('Twelve Data lookup failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($rateLimited) {
            $this->disableProvider(
                'twelvedata',
                self::TWELVE_DATA_COOLDOWN,
                'API keys rate-limited or forbidden'
            );
        }

        return null;
    }

    /**
     * Alpha Vantage price lookup with multiple API keys.
     */
    private function getAlphaVantagePrice(string $symbol): ?float
    {
        if ($this->providerIsDisabled('alphavantage')) {
            return null;
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
                    if (
                        $response->status() === 429 ||
                        $response->status() >= 500
                    ) {
                        $providerLimited = true;
                    }

                    continue;
                }

                $data = $response->json();

                if (!is_array($data)) {
                    continue;
                }

                if (
                    isset($data['Note']) ||
                    isset($data['Information']) ||
                    isset($data['Error Message'])
                ) {
                    $providerLimited = true;
                    continue;
                }

                $price = $data['Realtime Currency Exchange Rate']['5. Exchange Rate']
                    ?? null;

                if ($this->isValidPrice($price)) {
                    return (float) $price;
                }

            } catch (\Throwable $e) {
                Log::warning('Alpha Vantage lookup failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($providerLimited) {
            $this->disableProvider(
                'alphavantage',
                self::ALPHA_VANTAGE_COOLDOWN,
                'API keys rate-limited or provider returned an error notice'
            );
        }

        return null;
    }

    /**
     * Finnhub price lookup with multiple API keys.
     */
    private function getFinnhubPrice(string $symbol): ?float
    {
        if ($this->providerIsDisabled('finnhub')) {
            return null;
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
                    if (in_array($response->status(), [403, 429], true)) {
                        $rateLimited = true;
                    }

                    continue;
                }

                $data = $response->json();
                $price = $data['c'] ?? null;

                if ($this->isValidPrice($price)) {
                    return (float) $price;
                }

            } catch (\Throwable $e) {
                Log::warning('Finnhub lookup failed.', [
                    'symbol' => $symbol,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($rateLimited) {
            $this->disableProvider(
                'finnhub',
                self::FINNHUB_COOLDOWN,
                'API keys rate-limited or forbidden'
            );
        }

        return null;
    }

    /**
     * Get provider API keys.
     *
     * Supports multiple keys and legacy single-key settings.
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

            if (is_string($singleKey) && trim($singleKey) !== '') {
                $keys = [$singleKey];
            }
        }

        $keys = array_map(
            static function ($key) {
                return trim((string) $key);
            },
            $keys
        );

        $keys = array_filter($keys, static function ($key) {
            return $key !== '';
        });

        return array_values(array_unique($keys));
    }

    /**
     * Rotate the first key used between lookup requests.
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

        $cacheKey = 'crypto_api_key_index_' . $provider;

        $index = (int) Cache::get($cacheKey, 0);
        $index %= count($keys);

        $rotated = array_merge(
            array_slice($keys, $index),
            array_slice($keys, 0, $index)
        );

        Cache::put(
            $cacheKey,
            ($index + 1) % count($keys),
            now()->addDay()
        );

        return $rotated;
    }

    /**
     * Check provider cooldown.
     */
    private function providerIsDisabled(string $provider): bool
    {
        return Cache::get(
            'crypto_provider_disabled_' . $provider,
            false
        ) === true;
    }

    /**
     * Temporarily disable a provider.
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

        Log::warning('Cryptocurrency provider temporarily disabled.', [
            'provider' => $provider,
            'cooldown_seconds' => $seconds,
            'reason' => $reason,
        ]);
    }

    /**
     * Validate a price.
     */
    private function isValidPrice($price): bool
    {
        return is_numeric($price)
            && is_finite((float) $price)
            && (float) $price > 0;
    }

    /**
     * Clear metadata and price caches for one symbol.
     */
    private function clearSymbolLookupCache(string $symbol): void
    {
        $symbol = strtolower(trim($symbol));

        if ($symbol === '') {
            return;
        }

        Cache::forget('crypto_details_' . $symbol);
        Cache::forget('crypto_lookup_price_' . $symbol);
    }

    /**
     * Clear price and metadata caches.
     */
    private function clearCryptoPriceCache(): void
    {
        foreach ([
            'crypto_prices',
            'crypto_prices_v2',
            'crypto_prices_last_good',
            'admin_crypto_live_prices',
        ] as $key) {
            Cache::forget($key);
        }

        /*
         * Clear lookup caches for every configured cryptocurrency.
         */
        foreach (Cryptocurrency::pluck('symbol') as $symbol) {
            $this->clearSymbolLookupCache($symbol);
        }
    }

    /**
     * Save or clear a cryptocurrency's system wallet address.
     *
     * Addresses are stored in settings using the catalog's wallet key.
     */
    private function saveSystemWalletAddress(
        Cryptocurrency $crypto,
        ?string $address
    ): void {
        $crypto->wallet_address = $address;
    }
}