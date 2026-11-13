<?php

if (! function_exists('shorten_string')) {
    /**
     * Shortens a string by showing the first and last few characters.
     *
     * @param string $string The string to shorten.
     * @param int $startChars Number of characters to show at the start.
     * @param int $endChars Number of characters to show at the end.
     * @return string The shortened string.
     */
    function shorten_string($string, $startChars = 5, $endChars = 5)
    {
        $length = strlen($string);
        if ($length <= ($startChars + $endChars + 3)) { // 3 for ellipsis
            return $string;
        }

        $start = substr($string, 0, $startChars);
        $end = substr($string, -$endChars);
        return "{$start}...{$end}";
    }
}

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  string|array|null  $key
     * @param  mixed  $default
     * @return mixed|\App\Models\Setting
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\App\Models\Setting::class);
        }

        if (is_array($key)) {
            return app(\App\Models\Setting::class)->set($key);
        }

        return app(\App\Models\Setting::class)->get($key, $default);
    }
}

if (!function_exists('format_fiat')) {
    function format_fiat(float $usdAmount): string
    {
        return app(\App\Services\CurrencyService::class)
            ->formatForCurrentUser($usdAmount);
    }
}
