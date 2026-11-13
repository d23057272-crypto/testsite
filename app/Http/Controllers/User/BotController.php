<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bot;
use App\Models\BotSubscription;
use App\Models\BotTrade;
use App\Models\Cryptocurrency;
use App\Models\UserCryptoBalance;
use App\Mail\BotSubscriptionEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BotController extends Controller
{
    /**
     * Get the USDT TRC20 cryptocurrency from the catalog.
     */
    protected function getUsdtTrc20Currency(): ?Cryptocurrency
    {
        return Cryptocurrency::query()
            ->where('is_active', true)
            ->whereRaw('UPPER(name) = ?', ['USDT'])
            ->whereRaw('UPPER(network) = ?', ['TRC20'])
            ->first();
    }

    /**
     * Get or create the user's USDT TRC20 balance record.
     */
    protected function getUserUsdtBalance($user)
    {
        $crypto = $this->getUsdtTrc20Currency();

        if (!$crypto) {
            return null;
        }

        return $crypto->getOrCreateUserBalance($user);
    }

    /**
     * Display the user's bots and trading activity.
     */
    public function index()
    {
        $user = auth()->user();

        $userSubscriptions = $user->botSubscriptions()
            ->with(['bot', 'profits'])
            ->where('status', 'active')
            ->get();

        $allSubscriptions = $user->botSubscriptions()->get();

        $trades = BotTrade::with('bot')
            ->whereHas('subscriptions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        $totalTrades = $trades->count();

        $winRate = $totalTrades > 0
            ? round(
                ($trades->where('result', 'win')->count() / $totalTrades) * 100,
                2
            )
            : 0;

        $totalInvestment = $allSubscriptions->sum('amount');
        $totalProfit = $allSubscriptions->sum('total_profit');

        return view('user.bots.index', compact(
            'userSubscriptions',
            'trades',
            'winRate',
            'totalInvestment',
            'totalProfit'
        ));
    }

    /**
     * Display a bot and the user's available USDT TRC20 balance.
     */
    public function show(Bot $bot)
    {
        $user = auth()->user();

        $userSubscription = $user->botSubscriptions()
            ->where('bot_id', $bot->id)
            ->where('status', 'active')
            ->first();

        $balanceRecord = $this->getUserUsdtBalance($user);

        $usdtBalance = (
            $balanceRecord && $balanceRecord->is_enabled
        )
            ? (float) $balanceRecord->balance
            : 0;

        return view('user.bots.show', [
            'bot' => $bot,
            'userSubscription' => $userSubscription,
            'userBalance' => $usdtBalance,
        ]);
    }

    /**
     * Subscribe a user to a trading bot.
     *
     * Subscription funds are deducted from user_crypto_balances.
     */
    public function subscribe(Request $request, Bot $bot)
    {
        $user = auth()->user();

        Log::info('Starting bot subscription process', [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
        ]);

        $data = $request->validate([
            'bot_type' => 'required|string',
            'duration' => [
                'required',
                'string',
                'in:5m,10m,15m,30m,1h,4h,12h,24h,3d,7d,30d',
            ],
            'amount' => 'required|numeric|gt:0|decimal:0,8',
            'trading_pair' => 'required|string|max:30',
        ]);

        // Use the bot supplied by route-model binding.
        if ($data['bot_type'] !== $bot->bot_type) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid bot selection.',
            ], 422);
        }

        // Validate the bot's investment limits.
        if (
            $data['amount'] < $bot->min_amount ||
            $data['amount'] > $bot->max_amount
        ) {
            return response()->json([
                'success' => false,
                'message' => "Amount must be between {$bot->min_amount} and {$bot->max_amount}.",
            ], 422);
        }

        // Find USDT TRC20 in the cryptocurrency catalog.
        $crypto = $this->getUsdtTrc20Currency();

        if (!$crypto) {
            Log::error('USDT TRC20 is missing from the cryptocurrency catalog.');

            return response()->json([
                'success' => false,
                'message' => 'USDT TRC20 is currently unavailable. Please contact support.',
            ], 422);
        }

        // Get the user's balance record.
        $balanceRecord = $crypto->getOrCreateUserBalance($user);

        if (!$balanceRecord || !$balanceRecord->is_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Your USDT TRC20 wallet is currently disabled.',
            ], 422);
        }

        // Check the available balance before processing.
        if ((float) $balanceRecord->balance < (float) $data['amount']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient USDT TRC20 balance.',
            ], 422);
        }

        /*
         * Fetch the market price before opening the database transaction.
         * This avoids holding database locks while waiting for an API.
         */
        try {
            $currentPrice = $this->fetchPriceFromApi(
                $data['trading_pair']
            );
        } catch (\Throwable $e) {
            Log::error('Unable to fetch trading pair price.', [
                'trading_pair' => $data['trading_pair'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve the current market price. Please try again.',
            ], 503);
        }

        $subscription = null;

        try {
            DB::transaction(function () use (
                $user,
                $bot,
                $crypto,
                $balanceRecord,
                $data,
                $currentPrice,
                &$subscription
            ) {
                /*
                 * Lock the user's balance row to prevent simultaneous
                 * subscription requests from spending the same funds.
                 */
                $lockedBalance = UserCryptoBalance::where(
                    'id',
                    $balanceRecord->id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$lockedBalance || !$lockedBalance->is_enabled) {
                    throw new \RuntimeException(
                        'Your USDT TRC20 wallet is unavailable.'
                    );
                }

                $amount = (float) $data['amount'];
                $availableBalance = (float) $lockedBalance->balance;

                if ($availableBalance < $amount) {
                    throw new \RuntimeException(
                        'Insufficient USDT TRC20 balance.'
                    );
                }

                // Deduct the subscription amount.
                $lockedBalance->balance = $availableBalance - $amount;
                $lockedBalance->save();

                Log::info('USDT TRC20 balance deducted.', [
                    'user_id' => $user->id,
                    'cryptocurrency_id' => $crypto->id,
                    'amount' => $amount,
                    'remaining_balance' => $lockedBalance->balance,
                ]);

                // Create the bot subscription.
                $subscription = BotSubscription::create([
                    'bot_id' => $bot->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'status' => 'active',
                    'subscribed_at' => now(),
                    'expires_at' => $this->calculateExpiryDate(
                        $data['duration']
                    ),
                ]);

                // Create the initial bot trade record.
                $botTrade = BotTrade::create([
                    'bot_id' => $bot->id,
                    'trading_pair' => $data['trading_pair'],
                    'action' => 'subscribe',
                    'amount' => $amount,
                    'price' => 0,
                    'profit' => 0,
                    'metadata' => [
                        'notes' => 'Initial trade record created for bot subscription.',
                        'subscription_id' => $subscription->id,
                        'payment_currency' => 'USDT',
                        'payment_network' => 'TRC20',
                    ],
                ]);

                // Preserve the existing initial trade-record behavior.
                $randomAction = rand(0, 1) === 1 ? 'buy' : 'sell';

                $botTrade->update([
                    'action' => $randomAction,
                    'price' => $currentPrice,
                    'metadata' => array_merge(
                        $botTrade->metadata ?? [],
                        [
                            'updated_for_trade' => now()->toDateTimeString(),
                        ]
                    ),
                ]);

                // Update bot subscriber count.
                $bot->increment('total_subscribers');

                Log::info('Bot subscription successfully created.', [
                    'user_id' => $user->id,
                    'bot_id' => $bot->id,
                    'subscription_id' => $subscription->id,
                    'trade_id' => $botTrade->id,
                ]);
            });

            // Queue the email only after the transaction commits.
            if ($subscription && $user->email) {
                try {
                    Mail::to($user->email)->queue(
                        new BotSubscriptionEmail($subscription)
                    );
                } catch (\Throwable $mailException) {
                    Log::error('Bot subscription email could not be queued.', [
                        'subscription_id' => $subscription->id,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to bot!',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Bot subscription process failed.', [
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'error' => $e->getMessage(),
            ]);

            $message = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Failed to subscribe. Please try again.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $e instanceof \RuntimeException ? 422 : 500);
        }
    }

    /**
     * Calculate subscription expiry date.
     */
    protected function calculateExpiryDate(string $duration)
    {
        return match ($duration) {
            '5m'  => now()->addMinutes(5),
            '10m' => now()->addMinutes(10),
            '15m' => now()->addMinutes(15),
            '30m' => now()->addMinutes(30),

            '1h'  => now()->addHour(),
            '4h'  => now()->addHours(4),
            '12h' => now()->addHours(12),
            '24h' => now()->addDay(),

            '3d'  => now()->addDays(3),
            '7d'  => now()->addDays(7),
            '30d' => now()->addDays(30),

            default => null,
        };
    }

    /**
     * Fetch the current trading pair price from Huobi.
     */
    protected function fetchPriceFromApi(string $tradingPair): float
    {
        $symbol = strtolower(
            str_replace(['/', '-', '_', ' '], '', $tradingPair)
        );

        if (!preg_match('/^[a-z0-9]+$/', $symbol)) {
            throw new \InvalidArgumentException(
                'Invalid trading pair format.'
            );
        }

        try {
            $response = Http::timeout(10)
                ->retry(2, 300)
                ->get(
                    'https://api.huobi.pro/market/detail/merged',
                    ['symbol' => $symbol]
                );

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'Market API returned HTTP ' . $response->status()
                );
            }

            $data = $response->json();

            if (
                !isset($data['status']) ||
                $data['status'] !== 'ok' ||
                !isset($data['tick']['close']) ||
                !is_numeric($data['tick']['close'])
            ) {
                throw new \RuntimeException(
                    'Invalid market price response.'
                );
            }

            $price = (float) $data['tick']['close'];

            if ($price <= 0) {
                throw new \RuntimeException(
                    'The market API returned an invalid price.'
                );
            }

            return $price;

        } catch (\Throwable $e) {
            Log::error('Huobi price request failed.', [
                'trading_pair' => $tradingPair,
                'formatted_symbol' => $symbol,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Return the user's latest trades and active subscriptions.
     */
    public function updateTrades()
    {
        $user = auth()->user();

        $trades = BotTrade::with('bot')
            ->whereHas('subscriptions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get()
            ->map(function ($trade) {
                return [
                    'id' => $trade->id,
                    'bot_name' => $trade->bot?->name ?? 'Trading Bot',
                    'amount' => $trade->amount,
                    'status' => 'completed',
                    'result' => $trade->result,
                    'created_at' => $trade->created_at?->diffForHumans(),
                ];
            });

        $userSubscriptions = $user->botSubscriptions()
            ->with('bot')
            ->where('status', 'active')
            ->get()
            ->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'bot_name' => $subscription->bot?->name ?? 'Trading Bot',
                    'amount' => $subscription->amount,
                    'status' => 'active',
                    'result' => 'pending',
                ];
            });

        return response()->json([
            'trades' => $trades,
            'subscriptions' => $userSubscriptions,
        ]);
    }

    /**
     * Display profits for a subscription.
     */
    public function profits(BotSubscription $subscription)
    {
        abort_unless(
            $subscription->user_id === auth()->id(),
            403
        );

        $profits = $subscription->profits()
            ->with('trade')
            ->latest()
            ->paginate(20);

        return view('user.bots.profits', compact(
            'subscription',
            'profits'
        ));
    }

    /**
     * Display the user's bot trading history.
     */
    public function history()
    {
        $user = auth()->user();

        $trades = BotTrade::with('bot')
            ->whereHas('subscriptions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('user.bots.history', compact('trades'));
    }

    /**
     * Get a bot's performance statistics.
     */
    public function stats(Bot $bot)
    {
        $stats = [
            'total_profit' => $bot->total_profit,
            'total_subscribers' => $bot->total_subscribers,
            'last_trade' => $bot->last_trade_at?->diffForHumans(),
            'current_pair' => $bot->trading_pair,

            'recent_trades' => $bot->trades()
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($trade) => [
                    'action' => $trade->action,
                    'amount' => $trade->amount,
                    'price' => $trade->price,
                    'profit' => $trade->profit,
                    'time' => $trade->created_at?->diffForHumans(),
                ]),
        ];

        return response()->json($stats);
    }
}
