<?php

use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\UserCryptoBalance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasTable('cryptocurrencies') ||
            !Schema::hasTable('crypto_assets') ||
            !Schema::hasTable('user_crypto_balances')
        ) {
            return;
        }

        Cryptocurrency::query()->orderBy('id')->chunkById(100, function ($cryptocurrencies) {
            foreach ($cryptocurrencies as $crypto) {
                $legacyKey = CryptoAsset::getLegacyKeyForCryptocurrency(
                    (string) $crypto->symbol,
                    $crypto->network,
                    $crypto->name
                );
                $legacyConfig = $legacyKey
                    ? CryptoAsset::getSupportedCryptos()[$legacyKey]
                    : null;

                if (!$legacyConfig) {
                    continue;
                }

                CryptoAsset::query()
                    ->where($legacyConfig['balance_field'], '>', 0)
                    ->orderBy('id')
                    ->chunkById(500, function ($legacyAssets) use ($crypto, $legacyConfig) {
                        foreach ($legacyAssets as $legacyAsset) {
                            $balance = UserCryptoBalance::firstOrCreate(
                                [
                                    'user_id' => $legacyAsset->user_id,
                                    'cryptocurrency_id' => $crypto->id,
                                ],
                                [
                                    'balance' => $legacyAsset->{$legacyConfig['balance_field']},
                                    'is_enabled' => (bool) $legacyAsset->{$legacyConfig['status_field']},
                                    'address' => null,
                                ]
                            );

                            if ((float) $balance->balance === 0.0) {
                                $balance->balance = $legacyAsset->{$legacyConfig['balance_field']};
                                $balance->save();
                            }
                        }
                    });
            }
        });
    }

    public function down(): void
    {
        // Existing user balances cannot be safely distinguished from the backfilled values.
    }
};
