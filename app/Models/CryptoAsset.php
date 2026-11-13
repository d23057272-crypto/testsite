<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Log;
use LogicException;
use Throwable;

class CryptoAsset extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',

        'btc_balance',
        'btc_status',
        'btc_address',
        'btc_fee',

        'usdt_trc20_balance',
        'usdt_trc20_status',
        'usdt_trc20_address',
        'usdt_trc20_fee',

        'usdt_erc20_balance',
        'usdt_erc20_status',
        'usdt_erc20_address',
        'usdt_erc20_fee',

        'eth_balance',
        'eth_status',
        'eth_address',
        'eth_fee',

        'trx_balance',
        'trx_status',
        'trx_address',
        'trx_fee',

        'usdt_bep20_balance',
        'usdt_bep20_status',
        'usdt_bep20_address',
        'usdt_bep20_fee',

        'bch_balance',
        'bch_status',
        'bch_address',
        'bch_fee',

        'ltc_balance',
        'ltc_status',
        'ltc_address',
        'ltc_fee',

        'xlm_balance',
        'xlm_status',
        'xlm_address',
        'xlm_fee',

        'dash_balance',
        'dash_status',
        'dash_address',
        'dash_fee',

        'sol_balance',
        'sol_status',
        'sol_address',
        'sol_fee',

        'bnb_balance',
        'bnb_status',
        'bnb_address',
        'bnb_fee',

        'dot_balance',
        'dot_status',
        'dot_address',
        'dot_fee',

        'doge_balance',
        'doge_status',
        'doge_address',
        'doge_fee',

        'xdc_balance',
        'xdc_status',
        'xdc_address',
        'xdc_fee',

        'xrp_balance',
        'xrp_status',
        'xrp_address',
        'xrp_fee',

        'btc_swap_fee',
        'usdt_trc20_swap_fee',
        'usdt_erc20_swap_fee',
        'eth_swap_fee',
        'trx_swap_fee',
        'usdt_bep20_swap_fee',
        'bch_swap_fee',
        'ltc_swap_fee',
        'xlm_swap_fee',
        'dash_swap_fee',
        'sol_swap_fee',
        'bnb_swap_fee',
        'dot_swap_fee',
        'doge_swap_fee',
        'xdc_swap_fee',
        'xrp_swap_fee',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        // Statuses
        'btc_status' => 'boolean',
        'usdt_trc20_status' => 'boolean',
        'usdt_erc20_status' => 'boolean',
        'eth_status' => 'boolean',
        'trx_status' => 'boolean',
        'usdt_bep20_status' => 'boolean',
        'bch_status' => 'boolean',
        'ltc_status' => 'boolean',
        'xlm_status' => 'boolean',
        'dash_status' => 'boolean',
        'sol_status' => 'boolean',
        'bnb_status' => 'boolean',
        'dot_status' => 'boolean',
        'doge_status' => 'boolean',
        'xdc_status' => 'boolean',
        'xrp_status' => 'boolean',

        // Balances
        'btc_balance' => 'decimal:8',
        'usdt_trc20_balance' => 'decimal:8',
        'usdt_erc20_balance' => 'decimal:8',
        'eth_balance' => 'decimal:8',
        'trx_balance' => 'decimal:8',
        'usdt_bep20_balance' => 'decimal:8',
        'bch_balance' => 'decimal:8',
        'ltc_balance' => 'decimal:8',
        'xlm_balance' => 'decimal:8',
        'dash_balance' => 'decimal:8',
        'sol_balance' => 'decimal:8',
        'bnb_balance' => 'decimal:8',
        'dot_balance' => 'decimal:8',
        'doge_balance' => 'decimal:8',
        'xdc_balance' => 'decimal:8',
        'xrp_balance' => 'decimal:8',

        // Transaction fees
        'btc_fee' => 'decimal:8',
        'usdt_trc20_fee' => 'decimal:8',
        'usdt_erc20_fee' => 'decimal:8',
        'eth_fee' => 'decimal:8',
        'trx_fee' => 'decimal:8',
        'usdt_bep20_fee' => 'decimal:8',
        'bch_fee' => 'decimal:8',
        'ltc_fee' => 'decimal:8',
        'xlm_fee' => 'decimal:8',
        'dash_fee' => 'decimal:8',
        'sol_fee' => 'decimal:8',
        'bnb_fee' => 'decimal:8',
        'dot_fee' => 'decimal:8',
        'doge_fee' => 'decimal:8',
        'xdc_fee' => 'decimal:8',
        'xrp_fee' => 'decimal:8',

        // Swap fees
        'btc_swap_fee' => 'decimal:8',
        'usdt_trc20_swap_fee' => 'decimal:8',
        'usdt_erc20_swap_fee' => 'decimal:8',
        'eth_swap_fee' => 'decimal:8',
        'trx_swap_fee' => 'decimal:8',
        'usdt_bep20_swap_fee' => 'decimal:8',
        'bch_swap_fee' => 'decimal:8',
        'ltc_swap_fee' => 'decimal:8',
        'xlm_swap_fee' => 'decimal:8',
        'dash_swap_fee' => 'decimal:8',
        'sol_swap_fee' => 'decimal:8',
        'bnb_swap_fee' => 'decimal:8',
        'dot_swap_fee' => 'decimal:8',
        'doge_swap_fee' => 'decimal:8',
        'xdc_swap_fee' => 'decimal:8',
        'xrp_swap_fee' => 'decimal:8',
    ];

    /**
     * User relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Debug cryptocurrency status fields.
     */
    public function debugStatusFields(): array
    {
        $statuses = [];

        foreach (self::getSupportedCryptos() as $crypto) {
            $field = $crypto['status_field'];
            $statuses[$field] = (bool) $this->{$field};
        }

        return $statuses;
    }

    /**
     * Check whether an address exists for a cryptocurrency.
     */
    public function hasAddress(string $crypto): bool
    {
        $addressField = strtolower(trim($crypto)) . '_address';

        if (!in_array($addressField, $this->getAddressFields(), true)) {
            return false;
        }

        return !empty($this->{$addressField});
    }

    /**
     * Retrieve a cryptocurrency address.
     */
    public function getAddress(string $crypto): ?string
    {
        $addressField = strtolower(trim($crypto)) . '_address';

        if (!in_array($addressField, $this->getAddressFields(), true)) {
            return null;
        }

        $address = $this->{$addressField};

        return !empty($address) ? (string) $address : null;
    }

    /**
     * List valid address fields.
     */
    protected function getAddressFields(): array
    {
        return [
            'btc_address',
            'usdt_trc20_address',
            'usdt_erc20_address',
            'eth_address',
            'trx_address',
            'usdt_bep20_address',
            'bch_address',
            'ltc_address',
            'xlm_address',
            'dash_address',
            'sol_address',
            'bnb_address',
            'dot_address',
            'doge_address',
            'xdc_address',
            'xrp_address',
        ];
    }

    /**
     * Supported cryptocurrencies.
     */
    public static function getSupportedCryptos(): array
    {
        return [
            'btc' => [
                'name' => 'Bitcoin',
                'balance_field' => 'btc_balance',
                'status_field' => 'btc_status',
                'address_field' => 'btc_address',
                'icon' => 'fab fa-bitcoin',
                'color' => 'bg-orange-500',
            ],

            'usdt_trc20' => [
                'name' => 'USDT TRC20',
                'balance_field' => 'usdt_trc20_balance',
                'status_field' => 'usdt_trc20_status',
                'address_field' => 'usdt_trc20_address',
                'icon' => 'fas fa-dollar-sign',
                'color' => 'bg-green-500',
            ],

            'usdt_erc20' => [
                'name' => 'USDT ERC20',
                'balance_field' => 'usdt_erc20_balance',
                'status_field' => 'usdt_erc20_status',
                'address_field' => 'usdt_erc20_address',
                'icon' => 'fas fa-dollar-sign',
                'color' => 'bg-green-500',
            ],

            'eth' => [
                'name' => 'Ethereum',
                'balance_field' => 'eth_balance',
                'status_field' => 'eth_status',
                'address_field' => 'eth_address',
                'icon' => 'fab fa-ethereum',
                'color' => 'bg-blue-500',
            ],

            'trx' => [
                'name' => 'Tron',
                'balance_field' => 'trx_balance',
                'status_field' => 'trx_status',
                'address_field' => 'trx_address',
                'icon' => 'fas fa-bolt',
                'color' => 'bg-red-500',
            ],

            'usdt_bep20' => [
                'name' => 'USDT BNB',
                'balance_field' => 'usdt_bep20_balance',
                'status_field' => 'usdt_bep20_status',
                'address_field' => 'usdt_bep20_address',
                'icon' => 'fas fa-dollar-sign',
                'color' => 'bg-green-500',
            ],

            'bch' => [
                'name' => 'Bitcoin Cash',
                'balance_field' => 'bch_balance',
                'status_field' => 'bch_status',
                'address_field' => 'bch_address',
                'icon' => 'fab fa-bitcoin',
                'color' => 'bg-green-600',
            ],

            'ltc' => [
                'name' => 'Litecoin',
                'balance_field' => 'ltc_balance',
                'status_field' => 'ltc_status',
                'address_field' => 'ltc_address',
                'icon' => 'fas fa-coins',
                'color' => 'bg-gray-500',
            ],

            'xlm' => [
                'name' => 'Stellar',
                'balance_field' => 'xlm_balance',
                'status_field' => 'xlm_status',
                'address_field' => 'xlm_address',
                'icon' => 'fas fa-star',
                'color' => 'bg-blue-400',
            ],

            'dash' => [
                'name' => 'Dash',
                'balance_field' => 'dash_balance',
                'status_field' => 'dash_status',
                'address_field' => 'dash_address',
                'icon' => 'fas fa-tachometer-alt',
                'color' => 'bg-blue-600',
            ],

            'sol' => [
                'name' => 'Solana',
                'balance_field' => 'sol_balance',
                'status_field' => 'sol_status',
                'address_field' => 'sol_address',
                'icon' => 'fas fa-sun',
                'color' => 'bg-purple-500',
            ],

            'bnb' => [
                'name' => 'Binance Coin',
                'balance_field' => 'bnb_balance',
                'status_field' => 'bnb_status',
                'address_field' => 'bnb_address',
                'icon' => 'fas fa-coins',
                'color' => 'bg-yellow-500',
            ],

            'dot' => [
                'name' => 'Polkadot',
                'balance_field' => 'dot_balance',
                'status_field' => 'dot_status',
                'address_field' => 'dot_address',
                'icon' => 'fas fa-circle',
                'color' => 'bg-pink-500',
            ],

            'doge' => [
                'name' => 'Dogecoin',
                'balance_field' => 'doge_balance',
                'status_field' => 'doge_status',
                'address_field' => 'doge_address',
                'icon' => 'fas fa-circle',
                'color' => 'bg-yellow-500',
            ],

            'xdc' => [
                'name' => 'XDC Network',
                'balance_field' => 'xdc_balance',
                'status_field' => 'xdc_status',
                'address_field' => 'xdc_address',
                'icon' => 'fas fa-network-wired',
                'color' => 'bg-blue-700',
            ],

            'xrp' => [
                'name' => 'XRP',
                'balance_field' => 'xrp_balance',
                'status_field' => 'xrp_status',
                'address_field' => 'xrp_address',
                'icon' => 'fas fa-coins',
                'color' => 'bg-blue-600',
            ],
        ];
    }

    /**
     * Resolve the legacy balance key represented by a catalog cryptocurrency.
     */
    public static function getLegacyKeyForCryptocurrency(
        string $symbol,
        ?string $network = null,
        ?string $name = null
    ): ?string {
        $symbol = strtolower(trim($symbol));
        $name = strtolower(trim($name ?? ''));
        $network = strtoupper(trim($network ?? ''));

        if (
            $name === 'usdt' ||
            $symbol === 'usdt' ||
            str_starts_with($symbol, 'usdt_')
        ) {
            $network = match ($network) {
                '', 'NATIVE' => str_contains($symbol, 'trc20')
                    ? 'TRC20'
                    : (str_contains($symbol, 'erc20')
                        ? 'ERC20'
                        : (str_contains($symbol, 'bep20') ? 'BEP20' : 'NATIVE')),
                'BNB', 'BSC', 'BINANCE SMART CHAIN' => 'BEP20',
                'TRON' => 'TRC20',
                'ETH', 'ETHEREUM' => 'ERC20',
                default => $network,
            };

            return match ($network) {
                'TRC20' => 'usdt_trc20',
                'ERC20' => 'usdt_erc20',
                'BEP20' => 'usdt_bep20',
                default => null,
            };
        }

        $aliases = [
            'bitcoin' => 'btc',
            'ethereum' => 'eth',
            'tron' => 'trx',
            'binancecoin' => 'bnb',
            'solana' => 'sol',
            'polkadot' => 'dot',
            'dogecoin' => 'doge',
            'litecoin' => 'ltc',
            'ripple' => 'xrp',
        ];

        $symbol = $aliases[$symbol] ?? $symbol;

        return isset(self::getSupportedCryptos()[$symbol])
            ? $symbol
            : null;
    }

    /**
     * Get the network name for a cryptocurrency.
     */
    private function getCryptoNetwork(string $symbol): ?string
    {
        $networkMap = [
            'usdt_trc20' => 'TRC20',
            'usdt_erc20' => 'ERC20',
            'usdt_bep20' => 'BEP20',
        ];

        return $networkMap[strtolower($symbol)] ?? null;
    }

    /**
     * Get active cryptocurrencies with configured addresses.
     *
     * A cryptocurrency is included only when:
     * - Its status is enabled.
     * - It has a configured wallet address.
     *
     * A positive balance alone does not activate a disabled asset.
     */
    public function getActiveCryptos(): array
    {
        $activeCryptos = [];

        foreach (self::getSupportedCryptos() as $key => $crypto) {
            $balance = (float) $this->{$crypto['balance_field']};
            $status = (bool) $this->{$crypto['status_field']};
            $address = $this->{$crypto['address_field']};

            if (!$status || empty($address)) {
                continue;
            }

            $activeCryptos[] = [
                'symbol' => strtoupper($key),
                'name' => $crypto['name'],
                'balance' => $balance,
                'address' => $address,
                'icon' => $crypto['icon'],
                'color' => $crypto['color'],
                'network' => $this->getCryptoNetwork($key),
            ];
        }

        return $activeCryptos;
    }

    /**
     * Map internal symbols to CryptoService price IDs.
     */
    public static function getPriceKey(string $symbol): string
    {
        $symbol = strtolower(trim($symbol));

        if (str_starts_with($symbol, 'usdt_')) {
            return 'tether';
        }

        $map = [
            'btc' => 'bitcoin',
            'eth' => 'ethereum',
            'trx' => 'tron',
            'bnb' => 'binancecoin',
            'dot' => 'polkadot',
            'doge' => 'dogecoin',
            'sol' => 'solana',
            'bch' => 'bitcoin-cash',
            'ltc' => 'litecoin',
            'xlm' => 'stellar',
            'dash' => 'dash',
            'xdc' => 'xdc-network',
            'xrp' => 'ripple',
        ];

        return $map[$symbol] ?? $symbol;
    }

    /**
     * Extract a valid USD price from CryptoService response.
     *
     * Supported formats:
     *
     * ['bitcoin' => ['usd' => 65000]]
     * ['bitcoin' => 65000]
     *
     * Also supports objects containing a USD property.
     */
    private static function extractUsdPrice($priceData): float
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
            (float) $price <= 0
        ) {
            return 0.0;
        }

        return (float) $price;
    }

    /**
     * Retrieve prices safely from CryptoService.
     */
    private function fetchCryptoPrices(): array
    {
        $prices = app(CryptoService::class)->getPrices();

        return is_array($prices) ? $prices : [];
    }

    /**
     * Calculate total enabled cryptocurrency balance in USD.
     */
    public function getTotalBalanceUSD(): float
    {
        try {
            $prices = $this->fetchCryptoPrices();

            if (empty($prices)) {
                return 0.0;
            }

            $total = 0.0;

            foreach (self::getSupportedCryptos() as $symbol => $config) {
                $balance = (float) $this->{$config['balance_field']};
                $enabled = (bool) $this->{$config['status_field']};

                if (!$enabled || $balance <= 0) {
                    continue;
                }

                $priceKey = self::getPriceKey($symbol);

                $price = self::extractUsdPrice(
                    $prices[$priceKey] ?? null
                );

                if ($price > 0) {
                    $total += $balance * $price;
                }
            }

            return round($total, 2);

        } catch (Throwable $e) {
            Log::error('CryptoAsset total USD balance calculation failed.', [
                'user_id' => $this->user_id,
                'crypto_asset_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return 0.0;
        }
    }

    /**
     * Calculate the USD value of one supported cryptocurrency.
     */
    public function getCryptoValueUSD(string $symbol): float
    {
        $symbol = strtolower(trim($symbol));

        $supportedCryptos = self::getSupportedCryptos();

        if (!isset($supportedCryptos[$symbol])) {
            return 0.0;
        }

        $config = $supportedCryptos[$symbol];

        $balance = (float) $this->{$config['balance_field']};
        $enabled = (bool) $this->{$config['status_field']};

        if (!$enabled || $balance <= 0) {
            return 0.0;
        }

        try {
            $prices = $this->fetchCryptoPrices();

            $priceKey = self::getPriceKey($symbol);

            $price = self::extractUsdPrice(
                $prices[$priceKey] ?? null
            );

            if ($price <= 0) {
                return 0.0;
            }

            return round($balance * $price, 2);

        } catch (Throwable $e) {
            Log::error('CryptoAsset individual USD value calculation failed.', [
                'user_id' => $this->user_id,
                'crypto_asset_id' => $this->id,
                'symbol' => $symbol,
                'error' => $e->getMessage(),
            ]);

            return 0.0;
        }
    }

    /**
     * Populate missing wallet addresses from system settings.
     *
     * IMPORTANT:
     * This copies configured system wallet addresses.
     * It does not generate unique blockchain addresses for users.
     *
     * Existing user addresses are never overwritten.
     */
    public function generateAddressesIfNull(): void
    {
        $addressSettings = [
            'btc_address',
            'usdt_trc20_address',
            'usdt_erc20_address',
            'eth_address',
            'trx_address',
            'usdt_bep20_address',
            'bch_address',
            'ltc_address',
            'xlm_address',
            'dash_address',
            'sol_address',
            'bnb_address',
            'dot_address',
            'doge_address',
            'xdc_address',
            'xrp_address',
        ];

        // Fetch all configured wallet addresses in one query.
        $settings = Setting::whereIn('key', $addressSettings)
            ->pluck('value', 'key');

        foreach ($addressSettings as $addressField) {
            if (!empty($this->{$addressField})) {
                continue;
            }

            $configuredAddress = $settings->get($addressField);

            if (!empty($configuredAddress)) {
                $this->{$addressField} = $configuredAddress;
            }
        }

        // Avoid unnecessary database writes.
        if ($this->isDirty()) {
            $this->save();
        }
    }

    /**
     * Reject fake blockchain address generation.
     *
     * Real addresses must come from a properly implemented
     * wallet/key-management system.
     */
    protected function rejectFakeAddressGeneration(): never
    {
        throw new LogicException(
            'Fake cryptocurrency address generation is disabled. '
            . 'Configure a real blockchain wallet or address provider.'
        );
    }

    protected function generateBitcoinAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateEthereumAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateBitcoinCashAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateLitecoinAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateTronAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateBinanceSmartChainAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateStellarAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateDashAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateSolanaAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateBinanceAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generatePolkadotAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateXdcAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    protected function generateXrpAddress(): string
    {
        return $this->rejectFakeAddressGeneration();
    }

    /**
     * Default cryptocurrency balances and statuses.
     */
    public static function getDefaultBalances(): array
    {
        return [
            // Balances
            'btc_balance' => 0.0,
            'usdt_trc20_balance' => 0.0,
            'usdt_erc20_balance' => 0.0,
            'eth_balance' => 0.0,
            'trx_balance' => 0.0,
            'usdt_bep20_balance' => 0.0,
            'bch_balance' => 0.0,
            'ltc_balance' => 0.0,
            'xlm_balance' => 0.0,
            'dash_balance' => 0.0,
            'sol_balance' => 0.0,
            'bnb_balance' => 0.0,
            'dot_balance' => 0.0,
            'doge_balance' => 0.0,
            'xdc_balance' => 0.0,
            'xrp_balance' => 0.0,

            // Statuses
            'btc_status' => false,
            'usdt_trc20_status' => false,
            'usdt_erc20_status' => false,
            'eth_status' => false,
            'trx_status' => false,
            'usdt_bep20_status' => false,
            'bch_status' => false,
            'ltc_status' => false,
            'xlm_status' => false,
            'dash_status' => false,
            'sol_status' => false,
            'bnb_status' => false,
            'dot_status' => false,
            'doge_status' => false,
            'xdc_status' => false,
            'xrp_status' => false,
        ];
    }
}