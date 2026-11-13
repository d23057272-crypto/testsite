<?php

namespace App\Services;

use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\User;
use DomainException;

class CryptoBalanceService
{
    /**
     * List catalog and legacy symbols that can hold a user balance.
     */
    public function supportedSymbols(): array
    {
        return Cryptocurrency::query()
            ->pluck('symbol')
            ->map(fn ($symbol) => strtolower($symbol))
            ->merge(array_keys(CryptoAsset::getSupportedCryptos()))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Read a balance from the catalog, falling back to legacy storage.
     */
    public function balance(User $user, string $symbol): float
    {
        $symbol = strtolower(trim($symbol));
        $crypto = $this->findCryptocurrency($symbol);

        if ($crypto) {
            return (float) $crypto->getOrCreateUserBalance($user)->balance;
        }

        $legacy = CryptoAsset::getSupportedCryptos()[$symbol] ?? null;

        if (!$legacy) {
            throw new DomainException("Unsupported cryptocurrency: {$symbol}.");
        }

        $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();

        return (float) ($cryptoAsset?->{$legacy['balance_field']} ?? 0);
    }

    /**
     * Apply a balance delta to the catalog and its legacy counterpart.
     */
    public function adjust(User $user, string $symbol, float $delta): float
    {
        $symbol = strtolower(trim($symbol));
        $crypto = $this->findCryptocurrency($symbol);
        $legacyKey = $crypto
            ? CryptoAsset::getLegacyKeyForCryptocurrency(
                (string) $crypto->symbol,
                $crypto->network,
                $crypto->name
            )
            : $symbol;
        $legacy = $legacyKey
            ? (CryptoAsset::getSupportedCryptos()[$legacyKey] ?? null)
            : null;

        if (!$crypto && !$legacy) {
            throw new DomainException("Unsupported cryptocurrency: {$symbol}.");
        }

        $cryptoBalance = null;
        $newBalance = null;

        if ($crypto) {
            $crypto->getOrCreateUserBalance($user);
            $cryptoBalance = $crypto->userBalances()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();
            $newBalance = (float) $cryptoBalance->balance + $delta;

            if ($newBalance < -0.000000005) {
                throw new DomainException(
                    "Insufficient {$symbol} balance for this transaction."
                );
            }

            $newBalance = max(0, $newBalance);
        }

        if ($legacy) {
            $cryptoAsset = CryptoAsset::firstOrCreate(
                ['user_id' => $user->id],
                CryptoAsset::getDefaultBalances()
            );
            $cryptoAsset = CryptoAsset::whereKey($cryptoAsset->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($crypto) {
                $cryptoAsset->{$legacy['balance_field']} = $newBalance;
            } else {
                $legacyBalance = (float) $cryptoAsset->{$legacy['balance_field']};
                $legacyNewBalance = $legacyBalance + $delta;

                if ($legacyNewBalance < -0.000000005) {
                    throw new DomainException(
                        "Insufficient {$symbol} balance for this transaction."
                    );
                }

                $cryptoAsset->{$legacy['balance_field']} = max(0, $legacyNewBalance);
                $newBalance = (float) $cryptoAsset->{$legacy['balance_field']};
            }

            $cryptoAsset->save();
        }

        if ($cryptoBalance) {
            $cryptoBalance->balance = $newBalance;
            $cryptoBalance->save();
        }

        return (float) $newBalance;
    }

    /**
     * Keep the legacy status and catalog visibility in sync.
     */
    public function setEnabled(User $user, string $symbol, bool $enabled): void
    {
        $symbol = strtolower(trim($symbol));
        $crypto = $this->findCryptocurrency($symbol);
        $legacyKey = $crypto
            ? CryptoAsset::getLegacyKeyForCryptocurrency(
                (string) $crypto->symbol,
                $crypto->network,
                $crypto->name
            )
            : $symbol;
        $legacy = $legacyKey
            ? (CryptoAsset::getSupportedCryptos()[$legacyKey] ?? null)
            : null;

        if (!$crypto && !$legacy) {
            throw new DomainException("Unsupported cryptocurrency: {$symbol}.");
        }

        if ($crypto) {
            $crypto->getOrCreateUserBalance($user);
            $balance = $crypto->userBalances()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();
            $balance->is_enabled = $enabled;
            $balance->save();
        }

        if ($legacy) {
            $cryptoAsset = CryptoAsset::firstOrCreate(
                ['user_id' => $user->id],
                CryptoAsset::getDefaultBalances()
            );
            $cryptoAsset = CryptoAsset::whereKey($cryptoAsset->id)
                ->lockForUpdate()
                ->firstOrFail();
            $cryptoAsset->{$legacy['status_field']} = $enabled;
            $cryptoAsset->save();
        }
    }

    private function findCryptocurrency(string $symbol): ?Cryptocurrency
    {
        return Cryptocurrency::query()
            ->whereRaw('LOWER(symbol) = ?', [$symbol])
            ->first();
    }
}
