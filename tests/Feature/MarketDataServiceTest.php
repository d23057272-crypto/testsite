<?php

namespace Tests\Feature;

use App\Services\MarketDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MarketDataServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config()->set('services.finnhub.keys', []);
    }

    public function test_it_uses_coingecko_derivatives_when_binance_futures_is_blocked(): void
    {
        Http::fake([
            'fapi.binance.com/*' => Http::response([], 451),
            'api.coingecko.com/api/v3/derivatives*' => Http::response([
                [
                    'market' => 'Binance (Futures)',
                    'symbol' => 'BTCUSDT',
                    'price' => '65000.25',
                    'price_percentage_change_24h' => 1.5,
                    'contract_type' => 'perpetual',
                    'funding_rate' => 0.01,
                    'volume_24h' => 123456,
                ],
                [
                    'market' => 'Example (Futures)',
                    'symbol' => 'BTC-2027',
                    'price' => '65100',
                    'contract_type' => 'futures',
                    'volume_24h' => 999999,
                ],
            ]),
        ]);

        $markets = app(MarketDataService::class)->perpetualMarkets();

        $this->assertCount(1, $markets);
        $this->assertSame(
            'Binance (Futures) BTCUSDT',
            $markets[0]['symbol']
        );
        $this->assertSame(65000.25, $markets[0]['mark_price']);
        $this->assertSame(0.0001, $markets[0]['funding_rate']);
        Http::assertSentCount(2);
    }

    public function test_it_serves_labeled_stale_coin_data_after_a_rate_limit(): void
    {
        Http::fake([
            'api.coingecko.com/api/v3/coins/markets*' => Http::sequence()
                ->push([
                    [
                        'id' => 'bitcoin',
                        'symbol' => 'btc',
                        'name' => 'Bitcoin',
                        'current_price' => 65000,
                    ],
                ])
                ->push([], 429),
        ]);

        $service = app(MarketDataService::class);
        $firstResponse = $service->topCoins();
        Cache::forget('market.top.coins');
        $secondResponse = $service->topCoins();

        $this->assertSame($firstResponse, $secondResponse);
        $this->assertTrue($service->wasLastResponseStale());
        Http::assertSentCount(2);
    }

    public function test_it_suppresses_retries_when_no_cached_market_data_exists(): void
    {
        Http::fake([
            'api.coingecko.com/api/v3/search/trending*' => Http::response([], 429),
        ]);

        $service = app(MarketDataService::class);

        $this->assertSame([], $service->trendingCoins());
        $this->assertTrue($service->wasLastResponseUnavailable());

        $this->assertSame([], $service->trendingCoins());
        $this->assertTrue($service->wasLastResponseUnavailable());
        Http::assertSentCount(1);
    }
}
