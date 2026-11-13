<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\MarketDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MarketController extends Controller
{
    public function index(MarketDataService $markets, string $section = 'trending')
    {
        $sections = [
            'trending',
            'top',
            'stonks-szn',
            'top-gainers',
            'pre-ipo',
            'meme',
            'defi',
            'perps',
        ];

        abort_unless(in_array($section, $sections, true), 404);

        $items = [];
        $error = null;
        $isStale = false;
        $isUnavailable = false;

        try {
            $items = match ($section) {
                'trending' => $markets->trendingCoins(),
                'top' => $markets->topCoins(),
                'top-gainers' => $markets->topGainers(),
                'meme' => $markets->categoryCoins('meme'),
                'defi' => $markets->categoryCoins('defi'),
                'stonks-szn' => $markets->stockQuotes(),
                'pre-ipo' => $markets->upcomingIpos(),
                'perps' => $markets->perpetualMarkets(),
            };
            $isStale = $markets->wasLastResponseStale();
            $isUnavailable = $markets->wasLastResponseUnavailable();
        } catch (RuntimeException $exception) {
            $error = __('Market data is temporarily unavailable. Please try again shortly.');
            $logKey = 'market.section.failure_logged.' . md5($section);

            if (Cache::add($logKey, true, now()->addMinutes(3))) {
                Log::warning('Market section could not be loaded.', [
                    'section' => $section,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return view('user.market.index', compact(
            'section',
            'sections',
            'items',
            'error',
            'isStale',
            'isUnavailable'
        ));
    }
}
