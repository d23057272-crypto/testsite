<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CurrencyService
{
    private const RATES_CACHE_KEY = 'fiat_rates_usd';
    private const LAST_GOOD_RATES_CACHE_KEY = 'fiat_rates_usd_last_good';
    private const UNAVAILABLE_CACHE_KEY = 'fiat_rates_unavailable';

    public function __construct(
        private FinnhubClient $finnhub
    ) {
    }

    public function supportedCurrencies(): array
    {
        return config('currencies', []);
    }

    public function rate(string $currency): ?float
    {
        $currency = strtoupper($currency);

        if (!array_key_exists($currency, $this->supportedCurrencies())) {
            return null;
        }

        if ($currency === 'USD') {
            return 1.0;
        }

        $rates = Cache::get(self::RATES_CACHE_KEY);

        if (!is_array($rates)) {
            if (Cache::get(self::UNAVAILABLE_CACHE_KEY)) {
                $rates = Cache::get(self::LAST_GOOD_RATES_CACHE_KEY, []);
            } else {
                try {
                    $rates = $this->fetchRates();
                    Cache::put(
                        self::RATES_CACHE_KEY,
                        $rates,
                        now()->addMinutes(30)
                    );
                    Cache::forget(self::UNAVAILABLE_CACHE_KEY);
                    Cache::put(
                        self::LAST_GOOD_RATES_CACHE_KEY,
                        $rates,
                        now()->addHours(24)
                    );
                } catch (RuntimeException $exception) {
                    Log::warning('Fiat exchange rates unavailable.', [
                        'error' => $exception->getMessage(),
                    ]);
                    Cache::put(
                        self::UNAVAILABLE_CACHE_KEY,
                        true,
                        now()->addMinute()
                    );
                    $rates = Cache::get(
                        self::LAST_GOOD_RATES_CACHE_KEY,
                        []
                    );
                }
            }
        }

        $rate = $rates[$currency] ?? null;

        return is_numeric($rate) && (float) $rate > 0
            ? (float) $rate
            : null;
    }

    public function formatForCurrentUser(float $usdAmount): string
    {
        $currency = strtoupper(
            auth()->user()?->preferred_currency ?? 'USD'
        );

        return $this->format($usdAmount, $currency);
    }

    public function format(float $usdAmount, string $currency): string
    {
        $currency = strtoupper($currency);
        $rate = $this->rate($currency);

        if ($rate === null) {
            return 'USD ' . number_format($usdAmount, 2);
        }

        $digits = in_array($currency, ['JPY', 'KRW', 'VND'], true)
            ? 0
            : 2;
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            'KRW' => '₩',
            'BRL' => 'R$',
            'MXN' => 'MX$',
            'ZAR' => 'R',
            'NGN' => '₦',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'TRY' => '₺',
            'PLN' => 'zł',
            'SEK' => 'kr',
            'NOK' => 'kr',
            'DKK' => 'kr',
            'THB' => '฿',
            'PHP' => '₱',
            'IDR' => 'Rp',
            'VND' => '₫',
            'ILS' => '₪',
        ];
        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . number_format($usdAmount * $rate, $digits);
    }

    private function fetchRates(): array
    {
        $finnhubQuotes = [];

        if (config('services.finnhub.keys', []) !== []) {
            try {
                $data = $this->finnhub->getJson(
                    '/forex/rates',
                    ['base' => 'USD']
                );
                $finnhubQuotes = is_array($data['quote'] ?? null)
                    ? $data['quote']
                    : [];
            } catch (RuntimeException $exception) {
                Log::notice(
                    'Finnhub FX rates unavailable; trying ECB reference rates.',
                    ['error' => $exception->getMessage()]
                );
            }
        }

        $rates = ['USD' => 1.0];

        foreach (array_keys($this->supportedCurrencies()) as $currency) {
            $rate = $finnhubQuotes[$currency] ?? null;

            if (is_numeric($rate) && (float) $rate > 0) {
                $rates[$currency] = (float) $rate;
            }
        }

        $missingCurrencies = array_diff(
            array_keys($this->supportedCurrencies()),
            array_keys($rates)
        );

        if ($missingCurrencies !== []) {
            try {
                $referenceQuotes = $this->fetchReferenceRates();
            } catch (RuntimeException $exception) {
                if (count($rates) === 1) {
                    throw $exception;
                }

                Log::notice(
                    'ECB reference rates unavailable; using available Finnhub rates.',
                    ['error' => $exception->getMessage()]
                );
                $referenceQuotes = [];
            }

            foreach ($missingCurrencies as $currency) {
                $rate = $referenceQuotes[$currency] ?? null;

                if (is_numeric($rate) && (float) $rate > 0) {
                    $rates[$currency] = (float) $rate;
                }
            }
        }

        if (count($rates) === 1) {
            throw new RuntimeException(
                'No configured exchange-rate provider returned supported currencies.'
            );
        }

        return $rates;
    }

    private function fetchReferenceRates(): array
    {
        try {
            $response = Http::acceptJson()
                ->connectTimeout(5)
                ->timeout(10)
                ->get('https://api.frankfurter.dev/v1/latest', [
                    'base' => 'USD',
                ]);
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Unable to connect to the ECB reference-rate provider.',
                previous: $exception
            );
        }

        if (!$response->successful()) {
            throw new RuntimeException(
                'ECB reference-rate provider returned HTTP '
                    . $response->status() . '.'
            );
        }

        $rates = $response->json('rates');

        if (!is_array($rates)) {
            throw new RuntimeException(
                'ECB reference-rate provider returned an invalid response.'
            );
        }

        return $rates;
    }
}
