<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FinnhubClient
{
    private const BASE_URL = 'https://finnhub.io/api/v1';

    public function getJson(string $endpoint, array $parameters = []): array
    {
        if (Cache::get('crypto_provider_disabled_finnhub', false)) {
            throw new RuntimeException(
                'Finnhub is temporarily rate-limited. Please retry shortly.'
            );
        }

        $keys = $this->keys();

        if ($keys === []) {
            throw new RuntimeException(
                'Finnhub is not configured. Add FINNHUB_API_KEY or FINNHUB_API_KEYS.'
            );
        }

        $keys = $this->rotate($keys);
        $availableKeys = array_values(array_filter(
            $keys,
            fn (string $key): bool => !Cache::get(
                $this->keyCooldownCacheKey($endpoint, $key),
                false
            )
        ));

        if ($availableKeys === []) {
            throw new RuntimeException(
                'All configured Finnhub keys are temporarily unavailable for this data feed.'
            );
        }

        $lastStatus = null;

        foreach ($availableKeys as $key) {
            try {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(15)
                    ->get(self::BASE_URL . '/' . ltrim($endpoint, '/'), [
                        ...$parameters,
                        'token' => $key,
                    ]);
            } catch (ConnectionException $exception) {
                throw new RuntimeException(
                    'Unable to connect to Finnhub.',
                    previous: $exception
                );
            }

            if (!$response->successful()) {
                $lastStatus = $response->status();

                if (in_array($lastStatus, [401, 403, 429], true)) {
                    $this->markKeyCooldown($endpoint, $key, $lastStatus);
                    continue;
                }

                throw new RuntimeException(
                    'Finnhub returned HTTP ' . $lastStatus . '.'
                );
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new RuntimeException(
                    'Finnhub returned an invalid response.'
                );
            }

            if (isset($data['error'])) {
                $message = (string) $data['error'];
                $lastStatus = 403;
                $this->markKeyCooldown($endpoint, $key, $lastStatus);
                Log::warning('Finnhub rejected a configured API key.', [
                    'endpoint' => $endpoint,
                    'reason' => $message,
                ]);
                continue;
            }

            return $data;
        }

        if ($lastStatus === 429) {
            Cache::put(
                'crypto_provider_disabled_finnhub',
                true,
                now()->addMinutes(5)
            );
        }

        throw new RuntimeException(
            'Finnhub rejected all available API keys'
                . ($lastStatus ? ' (HTTP ' . $lastStatus . ').' : '.')
        );
    }

    private function keys(): array
    {
        $keys = config('services.finnhub.keys', []);

        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }

        if (!is_array($keys)) {
            return [];
        }

        return array_values(array_unique(array_filter(
            array_map(
                static fn ($key): string => trim((string) $key),
                $keys
            ),
            static fn (string $key): bool => $key !== ''
        )));
    }

    private function rotate(array $keys): array
    {
        if (count($keys) < 2) {
            return $keys;
        }

        $indexKey = 'finnhub_api_key_index';
        $index = (int) Cache::get($indexKey, 0) % count($keys);
        $orderedKeys = array_merge(
            array_slice($keys, $index),
            array_slice($keys, 0, $index)
        );

        Cache::put(
            $indexKey,
            ($index + 1) % count($keys),
            now()->addDay()
        );

        return $orderedKeys;
    }

    private function markKeyCooldown(
        string $endpoint,
        string $key,
        int $status
    ): void {
        Cache::put(
            $this->keyCooldownCacheKey($endpoint, $key),
            true,
            now()->addMinutes($status === 429 ? 5 : 15)
        );
    }

    private function keyCooldownCacheKey(string $endpoint, string $key): string
    {
        return 'finnhub_key_cooldown_'
            . hash('sha256', $endpoint . '|' . $key);
    }
}
