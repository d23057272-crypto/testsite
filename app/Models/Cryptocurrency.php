<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserCryptoBalance;
use App\Models\Setting;

class Cryptocurrency extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'symbol',
        'name',
        'network',
        'coin_id',
        'icon_url',
        'manual_price',
        'is_active',
        'is_deposit_enabled',
        'is_withdrawal_enabled',
        'min_deposit',
        'min_withdrawal',
        'withdrawal_fee',
        'price_coin_id',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'is_active'             => 'boolean',
        'is_deposit_enabled'    => 'boolean',
        'is_withdrawal_enabled' => 'boolean',

        'manual_price'          => 'decimal:8',
        'min_deposit'           => 'decimal:8',
        'min_withdrawal'        => 'decimal:8',
        'withdrawal_fee'        => 'decimal:8',
    ];

    /**
     * Get the effective cryptocurrency price.
     *
     * Priority:
     * 1. Manual admin price override.
     * 2. Live market price.
     * 3. Null when no valid price is available.
     */
    public function getEffectivePrice(array $livePrices): ?float
    {
        if ($this->hasManualPrice()) {
            return (float) $this->manual_price;
        }

        if (empty($this->coin_id)) {
            return null;
        }

        $marketPrice = data_get(
            $livePrices,
            $this->coin_id . '.usd'
        );

        if (
            is_numeric($marketPrice) &&
            is_finite((float) $marketPrice) &&
            (float) $marketPrice > 0
        ) {
            return (float) $marketPrice;
        }

        return null;
    }

    /**
     * Check whether a manual price override exists.
     */
    public function hasManualPrice(): bool
    {
        return $this->manual_price !== null
            && is_numeric($this->manual_price)
            && (float) $this->manual_price > 0;
    }

    /**
     * Get the manual price override.
     */
    public function getManualPrice(): ?float
    {
        if (!$this->hasManualPrice()) {
            return null;
        }

        return (float) $this->manual_price;
    }

    /**
     * Check whether a valid live market price exists.
     */
    public function hasLivePrice(array $livePrices): bool
    {
        if (empty($this->coin_id)) {
            return false;
        }

        $price = data_get(
            $livePrices,
            $this->coin_id . '.usd'
        );

        return is_numeric($price)
            && is_finite((float) $price)
            && (float) $price > 0;
    }

    /**
     * Get the current price source.
     *
     * Returns:
     * - manual
     * - market
     * - unavailable
     */
    public function getPriceSource(array $livePrices): string
    {
        if ($this->hasManualPrice()) {
            return 'manual';
        }

        if ($this->hasLivePrice($livePrices)) {
            return 'market';
        }

        return 'unavailable';
    }

    /*
    |--------------------------------------------------------------------------
    | SYSTEM WALLET ADDRESS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the setting record for this cryptocurrency's system address.
     *
     * Example:
     * BTC -> btc_address
     * USDT / TRC20 -> usdt_trc20_address
     * USDT_TRC20 -> usdt_trc20_address
     */
    public function systemWalletAddressSetting()
    {
        return $this->hasOne(
            Setting::class,
            'key',
            'wallet_address_setting_key'
        );
    }

    /**
     * Get the settings key used for the system receiving address.
     */
    public function getWalletAddressSettingKeyAttribute(): string
    {
        $symbol = strtolower(trim((string) $this->symbol));
        $network = strtolower(trim((string) $this->network));

        if (
            $network === '' ||
            in_array($network, ['native', 'mainnet', $symbol], true)
        ) {
            return $symbol . '_address';
        }

        $network = match ($network) {
            'bnb', 'bsc', 'binance smart chain' => 'bep20',
            'tron' => 'trc20',
            'eth', 'ethereum' => 'erc20',
            default => $network,
        };

        $networkSuffix = '_' . $network;

        return str_ends_with($symbol, $networkSuffix)
            ? $symbol . '_address'
            : $symbol . $networkSuffix . '_address';
    }

    /**
     * Retrieve the system receiving wallet address.
     *
     * The settings table is authoritative; the legacy column is a fallback.
     */
    public function getWalletAddressAttribute(): string
    {
        $settingAddress = Setting::where(
            'key',
            $this->wallet_address_setting_key
        )->value('value');

        if ($settingAddress !== null) {
            return (string) $settingAddress;
        }

        return (string) ($this->attributes['wallet_address'] ?? '');
    }

    /**
     * Save or update the system receiving wallet address.
     */
    public function setWalletAddressAttribute($address): void
    {
        $address = trim((string) $address);

        if ($address === '') {
            Setting::where(
                'key',
                $this->wallet_address_setting_key
            )->delete();

            $this->attributes['wallet_address'] = null;

            return;
        }

        Setting::updateOrCreate(
            [
                'key' => $this->wallet_address_setting_key,
            ],
            [
                'value' => $address,
                'group' => 'wallets',
                'type' => 'text',
                'display_name' => strtoupper($this->symbol) . ' Address',
                'is_public' => false,
            ]
        );

        // Keep the current model instance synchronized.
        $this->attributes['wallet_address'] = $address;
    }

    /*
    |--------------------------------------------------------------------------
    | USER CRYPTOCURRENCY BALANCES
    |--------------------------------------------------------------------------
    */

    /**
     * User balances belonging to this cryptocurrency.
     */
    public function userBalances()
    {
        return $this->hasMany(
            UserCryptoBalance::class,
            'cryptocurrency_id',
            'id'
        );
    }

    /**
     * Get or create a user's balance for this cryptocurrency.
     *
     * Newly added currencies can therefore be made available
     * to existing users without adding database columns.
     */
    public function getOrCreateUserBalance(User $user): UserCryptoBalance
    {
        $balance = $this->userBalances()
            ->where('user_id', $user->id)
            ->first();

        if ($balance) {
            return $balance;
        }

        $legacyKey = CryptoAsset::getLegacyKeyForCryptocurrency(
            (string) $this->symbol,
            $this->network,
            $this->name
        );
        $legacyConfig = $legacyKey
            ? CryptoAsset::getSupportedCryptos()[$legacyKey]
            : null;
        $legacyAsset = $legacyConfig
            ? CryptoAsset::where('user_id', $user->id)->first()
            : null;

        return $this->userBalances()->firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'balance' => $legacyAsset
                    ? $legacyAsset->{$legacyConfig['balance_field']}
                    : 0,
                'is_enabled' => $legacyAsset
                    ? (bool) $legacyAsset->{$legacyConfig['status_field']}
                    : true,
                'address' => null,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AVAILABILITY
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether this cryptocurrency can be used for wallet operations.
     */
    public function isAvailable(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Determine whether deposits are allowed.
     */
    public function allowsDeposits(): bool
    {
        return $this->is_active && $this->is_deposit_enabled;
    }

    /**
     * Determine whether withdrawals are allowed.
     */
    public function allowsWithdrawals(): bool
    {
        return $this->is_active && $this->is_withdrawal_enabled;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Active cryptocurrencies.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Deposit-enabled cryptocurrencies.
     */
    public function scopeDepositEnabled(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('is_deposit_enabled', true);
    }

    /**
     * Scope: Withdrawal-enabled cryptocurrencies.
     */
    public function scopeWithdrawalEnabled(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('is_withdrawal_enabled', true);
    }

    /**
     * Scope: Cryptocurrencies with manual price overrides.
     */
    public function scopeWithManualPrice(Builder $query): Builder
    {
        return $query
            ->whereNotNull('manual_price')
            ->where('manual_price', '>', 0);
    }

    /**
     * Scope: Cryptocurrencies without manual price overrides.
     */
    public function scopeWithoutManualPrice(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('manual_price')
                ->orWhere('manual_price', '<=', 0);
        });
    }
}
