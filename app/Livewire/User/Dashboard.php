<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\Setting;
use App\Services\CryptoService;
use Throwable;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public $showDropdown = false;
    public $showCopyMessage = false;
    public $balanceShow = true;

    public $accountId;
    public $balance = '0.00';
    public $searchQuery = '';

    public $cryptoAssets = [];

    private CryptoService $cryptoService;

    public function boot(CryptoService $cryptoService): void
    {
        $this->cryptoService = $cryptoService;
    }

    public function mount(): void
    {
        $this->refreshDashboardData();
    }

    /**
     * Refresh dashboard data.
     */
    public function refreshDashboardData(): void
    {
        $user = Auth::user();

        if (!$user) {
            $this->accountId = null;
            $this->cryptoAssets = [];
            $this->balance = '0.00';

            return;
        }

        $this->accountId = $user->getDisplayId();

        $legacyCryptoAsset = CryptoAsset::where('user_id', $user->id)
            ->first();

        $this->cryptoAssets = $this->mapCryptoAssets($legacyCryptoAsset);

        $this->calculateTotalBalance();
    }

    /**
     * Calculate total displayed cryptocurrency value in USD.
     */
    public function calculateTotalBalance(): void
    {
        $totalBalance = 0.0;

        foreach ($this->cryptoAssets as $asset) {
            $totalBalance += (float) ($asset['value'] ?? 0);
        }

        $this->balance = number_format($totalBalance, 2, '.', '');
    }

    /**
     * Retrieve market prices safely.
     */
    private function getMarketPrices(): array
    {
        try {
            $prices = $this->cryptoService->getPrices();

            return is_array($prices) ? $prices : [];
        } catch (Throwable $e) {
            Log::warning('Dashboard cryptocurrency price retrieval failed.', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Extract USD price.
     */
    private function extractUsdPrice($priceData): float
    {
        if (is_array($priceData)) {
            $price = $priceData['usd'] ?? null;
        } elseif (is_object($priceData)) {
            $price = $priceData->usd ?? null;
        } else {
            $price = $priceData;
        }

        if (
            !is_numeric($price) ||
            !is_finite((float) $price) ||
            (float) $price < 0
        ) {
            return 0.0;
        }

        return (float) $price;
    }

    /**
     * Extract 24-hour price change.
     */
    private function extractPriceChange($priceData): float
    {
        if (is_array($priceData)) {
            $change = $priceData['usd_24h_change'] ?? 0;
        } elseif (is_object($priceData)) {
            $change = $priceData->usd_24h_change ?? 0;
        } else {
            $change = 0;
        }

        return is_numeric($change) && is_finite((float) $change)
            ? (float) $change
            : 0.0;
    }

    /**
     * Normalize network names.
     */
    private function normalizeNetwork(?string $network): string
    {
        $network = strtoupper(trim($network ?? ''));

        if ($network === '' || $network === 'NATIVE') {
            return 'NATIVE';
        }

        return match ($network) {
            'BNB', 'BSC', 'BINANCE SMART CHAIN' => 'BEP20',
            'TRON' => 'TRC20',
            'ETH', 'ETHEREUM' => 'ERC20',
            default => $network,
        };
    }

    /**
     * Identify the legacy key represented by a catalog currency.
     */
    private function getLegacyCryptoKey(
        string $symbol,
        ?string $network = null,
        ?string $name = null
    ): ?string {
        return CryptoAsset::getLegacyKeyForCryptocurrency(
            $symbol,
            $network,
            $name
        );
    }

    /**
     * Get legacy network.
     */
    private function getLegacyNetwork(string $key): ?string
    {
        return match ($key) {
            'usdt_trc20' => 'TRC20',
            'usdt_erc20' => 'ERC20',
            'usdt_bep20' => 'BEP20',
            default => null,
        };
    }

    /**
     * Retrieve a legacy balance.
     */
    private function getLegacyBalance(
        ?CryptoAsset $cryptoAsset,
        ?string $legacyKey
    ): float {
        if (!$cryptoAsset || !$legacyKey) {
            return 0.0;
        }

        $supported = CryptoAsset::getSupportedCryptos();

        if (!isset($supported[$legacyKey])) {
            return 0.0;
        }

        $field = $supported[$legacyKey]['balance_field'];

        return (float) ($cryptoAsset->{$field} ?? 0);
    }

    /**
     * Get legacy price identifier.
     */
    private function getLegacyPriceId(string $key): string
    {
        return CryptoAsset::getPriceKey($key);
    }

    /**
     * Retrieve cryptocurrency icon safely.
     */
    private function getCryptoIcon(
        ?string $iconUrl,
        ?string $coinId
    ): string {
        if (!empty($iconUrl)) {
            return $iconUrl;
        }

        if (!empty($coinId)) {
            try {
                return $this->cryptoService->getIconUrl($coinId) ?? '';
            } catch (Throwable $e) {
                Log::warning('Cryptocurrency icon retrieval failed.', [
                    'coin_id' => $coinId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return '';
    }

    /**
     * Retrieve network icon.
     */
    private function getNetworkIcon(?string $network): ?string
    {
        $coinId = match ($this->normalizeNetwork($network)) {
            'TRC20' => 'tron',
            'BEP20' => 'binancecoin',
            'ERC20' => 'ethereum',
            default => null,
        };

        return $coinId
            ? $this->getCryptoIcon(null, $coinId)
            : null;
    }

    /**
     * Format a cryptocurrency asset for the dashboard.
     */
    private function formatAsset(
        string $symbol,
        string $name,
        ?string $coinId,
        float $balance,
        ?string $network,
        ?string $iconUrl,
        array $prices,
        ?Cryptocurrency $crypto = null,
        ?string $walletAddress = null
    ): array {
        $priceData = $coinId
            ? ($prices[$coinId] ?? null)
            : null;

        if ($crypto) {
            try {
                $effectivePrice = $crypto->getEffectivePrice($prices);

                $price = is_numeric($effectivePrice)
                    && is_finite((float) $effectivePrice)
                    && (float) $effectivePrice >= 0
                        ? (float) $effectivePrice
                        : 0.0;
            } catch (Throwable $e) {
                Log::warning('Catalog cryptocurrency price retrieval failed.', [
                    'cryptocurrency_id' => $crypto->id,
                    'error' => $e->getMessage(),
                ]);

                $price = $this->extractUsdPrice($priceData);
            }
        } else {
            $price = $this->extractUsdPrice($priceData);
        }

        $change = $this->extractPriceChange($priceData);

        $network = $this->normalizeNetwork($network);

        $displaySymbol = strtoupper($symbol);

        if (
            $crypto &&
            !empty($crypto->network) &&
            strtoupper($crypto->name ?? '') === 'USDT'
        ) {
            $displaySymbol = 'USDT';
        }

        $displayName = $name;

        if ($network !== 'NATIVE') {
            $displayName .= " ({$network})";
        }

        $priceSource = 'market';

        if ($crypto) {
            try {
                $priceSource = $crypto->getPriceSource($prices);
            } catch (Throwable $e) {
                $priceSource = 'market';
            }
        }

        return [
            'id' => $crypto?->id,
            'symbol' => $displaySymbol,
            'name' => $displayName,
            'price' => number_format($price, 2, '.', ''),
            'change' => number_format($change, 2, '.', ''),
            'balance' => number_format($balance, 8, '.', ''),
            'value' => round($balance * $price, 2),
            'icon_url' => $this->getCryptoIcon($iconUrl, $coinId),
            'network_url' => $this->getNetworkIcon(
                $network === 'NATIVE' ? null : $network
            ),
            'network' => $network === 'NATIVE' ? 'native' : $network,
            'coin_id' => $coinId,
            'price_source' => $priceSource,
            'address' => trim((string) ($walletAddress ?? '')),
        ];
    }

    /**
     * Build dashboard assets.
     *
     * Primary source:
     * cryptocurrencies + user_crypto_balances
     *
     * Legacy fallback:
     * crypto_assets, but only for currencies not represented
     * in the catalog.
     */
    public function mapCryptoAssets(?CryptoAsset $cryptoAsset): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $assets = [];
        $prices = $this->getMarketPrices();
        $walletAddresses = Setting::where('group', 'wallets')
            ->pluck('value', 'key')
            ->toArray();

        $managedCryptos = Cryptocurrency::query()
            ->orderBy('name')
            ->orderBy('network')
            ->get();

        $managedLegacyKeys = [];

        foreach ($managedCryptos as $crypto) {
            $legacyKey = $this->getLegacyCryptoKey(
                (string) $crypto->symbol,
                $crypto->network,
                $crypto->name
            );

            if ($legacyKey) {
                $managedLegacyKeys[$legacyKey] = true;
            }

            if (!(bool) $crypto->is_active) {
                continue;
            }

            try {
                /*
                 * Use the existing unique user_id + cryptocurrency_id
                 * relationship to retrieve the user's balance.
                 */
                $userBalance = $crypto->getOrCreateUserBalance($user);
            } catch (Throwable $e) {
                Log::error('Unable to retrieve user cryptocurrency balance.', [
                    'user_id' => $user->id,
                    'cryptocurrency_id' => $crypto->id,
                    'symbol' => $crypto->symbol,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }

            if (!(bool) $userBalance->is_enabled) {
                continue;
            }

            $balance = (float) $userBalance->balance;

            $network = $crypto->network;

            $displaySymbol = strtoupper($crypto->symbol);

            $assets[] = $this->formatAsset(
                $displaySymbol,
                (string) $crypto->name,
                $crypto->coin_id,
                $balance,
                $network,
                $crypto->icon_url,
                $prices,
                $crypto,
                $walletAddresses[$crypto->wallet_address_setting_key]
                    ?? $crypto->wallet_address
            );
        }

        /*
         * Legacy assets are shown only when the catalog
         * does not manage the corresponding currency.
         */
        if ($cryptoAsset) {
            $supportedCryptos = CryptoAsset::getSupportedCryptos();

            foreach ($supportedCryptos as $key => $config) {
                if (isset($managedLegacyKeys[$key])) {
                    continue;
                }

                $statusField = $config['status_field'];

                if (!(bool) $cryptoAsset->{$statusField}) {
                    continue;
                }

                $balance = $this->getLegacyBalance(
                    $cryptoAsset,
                    $key
                );

                $coinId = $this->getLegacyPriceId($key);

                $symbol = in_array($key, [
                    'usdt_trc20',
                    'usdt_erc20',
                    'usdt_bep20',
                ], true)
                    ? 'USDT'
                    : strtoupper($key);

                $assets[] = $this->formatAsset(
                    $symbol,
                    $config['name'],
                    $coinId,
                    $balance,
                    $this->getLegacyNetwork($key),
                    null,
                    $prices,
                    null,
                    $walletAddresses[$key . '_address'] ?? ''
                );
            }
        }

        return $assets;
    }

    /**
     * Do not collect or store wallet recovery phrases.
     */
    public function connectWallet($phrase = null): void
    {
        throw ValidationException::withMessages([
            'phrase' => 'For your security, never enter your wallet recovery phrase into this website. Use a secure wallet connection or wallet signature instead.',
        ]);
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function toggleBalance(): void
    {
        $this->balanceShow = !$this->balanceShow;
    }

    public function hideDropdown(): void
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.user.dashboard')
            ->layout('layouts.user.app');
    }
}
