<?php

namespace Tests\Unit;

use App\Models\Cryptocurrency;
use PHPUnit\Framework\TestCase;

class CryptocurrencyWalletAddressTest extends TestCase
{
    public function test_native_assets_use_the_symbol_address_key(): void
    {
        $crypto = new Cryptocurrency([
            'symbol' => 'BTC',
        ]);

        $this->assertSame(
            'btc_address',
            $crypto->wallet_address_setting_key
        );
    }

    public function test_network_is_included_in_the_address_key(): void
    {
        $crypto = new Cryptocurrency([
            'symbol' => 'USDT',
            'network' => 'TRC20',
        ]);

        $this->assertSame(
            'usdt_trc20_address',
            $crypto->wallet_address_setting_key
        );
    }

    public function test_network_suffix_is_not_duplicated(): void
    {
        $crypto = new Cryptocurrency([
            'symbol' => 'usdt_trc20',
            'network' => 'TRC20',
        ]);

        $this->assertSame(
            'usdt_trc20_address',
            $crypto->wallet_address_setting_key
        );
    }

    public function test_network_aliases_use_the_canonical_address_key(): void
    {
        $crypto = new Cryptocurrency([
            'symbol' => 'USDT',
            'network' => 'BSC',
        ]);

        $this->assertSame(
            'usdt_bep20_address',
            $crypto->wallet_address_setting_key
        );
    }
}
