<?php

namespace Tests\Unit;

use App\Models\CryptoAsset;
use PHPUnit\Framework\TestCase;

class CryptoAssetLegacyKeyTest extends TestCase
{
    public function test_native_catalog_assets_map_to_legacy_symbols(): void
    {
        $this->assertSame(
            'btc',
            CryptoAsset::getLegacyKeyForCryptocurrency('BTC', 'NATIVE', 'Bitcoin')
        );
    }

    public function test_usdt_network_aliases_map_to_legacy_network_keys(): void
    {
        $this->assertSame(
            'usdt_trc20',
            CryptoAsset::getLegacyKeyForCryptocurrency('USDT', 'TRON', 'Tether')
        );
        $this->assertSame(
            'usdt_erc20',
            CryptoAsset::getLegacyKeyForCryptocurrency('USDT', 'ETH', 'Tether')
        );
        $this->assertSame(
            'usdt_bep20',
            CryptoAsset::getLegacyKeyForCryptocurrency('USDT', 'BSC', 'Tether')
        );
    }

    public function test_unrepresented_catalog_assets_have_no_legacy_key(): void
    {
        $this->assertNull(
            CryptoAsset::getLegacyKeyForCryptocurrency('custom-token', 'NATIVE', 'Custom Token')
        );
    }
}
