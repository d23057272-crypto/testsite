<?php

namespace Tests\Feature;

use App\Services\CurrencyService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config()->set('currencies', [
            'USD' => 'US Dollar',
            'CAD' => 'Canadian Dollar',
        ]);
    }

    public function test_it_formats_usd_valuations_using_live_exchange_rates(): void
    {
        config()->set('services.finnhub.keys', ['test-key']);
        Http::fake([
            'https://finnhub.io/api/v1/forex/rates*' => Http::response([
                'base' => 'USD',
                'quote' => ['CAD' => 1.35],
            ]),
        ]);

        $formatted = app(CurrencyService::class)->format(100, 'CAD');

        $this->assertSame('CAD 135.00', $formatted);
        Http::assertSent(function (Request $request): bool {
            $query = [];
            parse_str(
                (string) parse_url($request->url(), PHP_URL_QUERY),
                $query
            );

            return str_starts_with(
                $request->url(),
                'https://finnhub.io/api/v1/forex/rates'
            ) && ($query['base'] ?? null) === 'USD'
                && ($query['token'] ?? null) === 'test-key';
        });
        Http::assertSentCount(1);
    }

    public function test_it_uses_ecb_reference_rates_when_finnhub_is_not_configured(): void
    {
        config()->set('services.finnhub.keys', []);
        Http::fake([
            'https://api.frankfurter.dev/v1/latest*' => Http::response([
                'base' => 'USD',
                'rates' => ['CAD' => 1.4249],
            ]),
        ]);

        $formatted = app(CurrencyService::class)->format(100, 'CAD');

        $this->assertSame('CAD 142.49', $formatted);
        Http::assertSent(fn (Request $request) =>
            str_starts_with(
                $request->url(),
                'https://api.frankfurter.dev/v1/latest'
            )
        );
    }

    public function test_it_tries_the_next_finnhub_key_after_a_rate_limit(): void
    {
        config()->set('services.finnhub.keys', ['first-key', 'second-key']);
        Http::fake(function (Request $request) {
            $query = [];
            parse_str(
                (string) parse_url($request->url(), PHP_URL_QUERY),
                $query
            );

            if (($query['token'] ?? null) === 'first-key') {
                return Http::response([], 429);
            }

            return Http::response([
                'base' => 'USD',
                'quote' => ['CAD' => 1.35],
            ]);
        });

        $formatted = app(CurrencyService::class)->format(100, 'CAD');

        $this->assertSame('CAD 135.00', $formatted);
        Http::assertSentCount(2);
    }

    public function test_it_falls_back_to_ecb_rates_when_finnhub_rejects_all_keys(): void
    {
        config()->set('services.finnhub.keys', ['first-key', 'second-key']);
        Http::fake([
            'https://finnhub.io/api/v1/forex/rates*' => Http::response([], 403),
            'https://api.frankfurter.dev/v1/latest*' => Http::response([
                'base' => 'USD',
                'rates' => ['CAD' => 1.42],
            ]),
        ]);

        $formatted = app(CurrencyService::class)->format(100, 'CAD');

        $this->assertSame('CAD 142.00', $formatted);
        Http::assertSentCount(3);
    }

    public function test_it_keeps_valid_finnhub_rates_when_the_ecb_fallback_fails(): void
    {
        config()->set('currencies', [
            'USD' => 'US Dollar',
            'CAD' => 'Canadian Dollar',
            'EUR' => 'Euro',
        ]);
        config()->set('services.finnhub.keys', ['test-key']);
        Http::fake([
            'https://finnhub.io/api/v1/forex/rates*' => Http::response([
                'base' => 'USD',
                'quote' => ['CAD' => 1.35],
            ]),
            'https://api.frankfurter.dev/v1/latest*' => Http::response([], 503),
        ]);

        $rate = app(CurrencyService::class)->rate('CAD');

        $this->assertSame(1.35, $rate);
    }
}
