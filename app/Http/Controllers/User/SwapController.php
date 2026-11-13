<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CryptoService;
use App\Services\UserActivityService;
use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\UserCryptoBalance;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\TransactionEmail;

class SwapController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Display the database-driven swap page.
     */
    public function index()
    {
        $user = Auth::user();

        // Keep a legacy CryptoAsset record because transactions.cryptoasset_id
        // is currently NOT NULL in this database.
        $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();

        if (!$cryptoAsset) {
            $cryptoAsset = new CryptoAsset();
            $cryptoAsset->user_id = $user->id;
            $cryptoAsset->save();
        }

        $prices = $this->cryptoService->getPrices();

        $currencies = Cryptocurrency::where('is_active', 1)
            ->orderBy('name')
            ->orderBy('symbol')
            ->get();

        $assets = [];
        $activeCoins = [];
        $swapFees = [];

        foreach ($currencies as $currency) {
            $balance = $currency->getOrCreateUserBalance($user);

            if (!$balance->is_enabled) {
                continue;
            }

            $symbol = strtolower($currency->symbol);

            $price = $this->getCurrencyPrice($currency, $prices);

            // Normalized address first; legacy address is a fallback.
            $address = $currency->wallet_address ?: $balance->address;

            if (!$address) {
                $legacyAddressField = $symbol . '_address';
                $address = $cryptoAsset->{$legacyAddressField} ?? null;
            }

            // Existing currencies can continue using their old per-user fee.
            // New currencies use the catalog-level swap_fee column.
            $legacyFeeField = $symbol . '_swap_fee';

            if (isset($cryptoAsset->{$legacyFeeField})) {
                $swapFee = (float) $cryptoAsset->{$legacyFeeField};
            } else {
                $swapFee = (float) ($currency->swap_fee ?? 0);
            }

            $asset = [
                'id' => (int) $currency->id,
                'symbol' => $symbol,
                'name' => $currency->name,
                'network' => $currency->network,
                'balance' => (float) $balance->balance,
                'price' => $price,
                'icon_url' => $currency->icon_url,
                'address' => $address,
                'swap_fee' => $swapFee,
                'swap_fee_usd' => $swapFee * $price,
            ];

            $assets[] = $asset;
            $activeCoins[] = $symbol;

            if ($user->swap_fee && $swapFee > 0) {
                $swapFees[$symbol] = [
                    'fee' => $swapFee,
                    'usd_equivalent' => $swapFee * $price,
                ];
            }
        }

        return view('user.swap', [
            'cryptoAsset' => $cryptoAsset,
            'prices' => $prices,
            'assets' => $assets,
            'activeCoins' => $activeCoins,
            'swapFeeRequired' => (bool) $user->swap_fee,
            'swapFees' => $swapFees,
        ]);
    }

    /**
     * Execute an internal database-led cryptocurrency swap.
     *
     * This does not broadcast a blockchain transaction.
     */
    public function swap(Request $request)
    {
        $user = Auth::user();

        if (!$user->enable_swap) {
            return response()->json([
                'success' => false,
                'message' => 'Swaps are currently disabled. Please contact support.',
            ], 403);
        }

        /*
         * The existing application uses user.swap_fee as a global fee-required
         * flag. Keep the server-side restriction: opening the fee modal is not
         * proof that the fee has been received.
         */
        if ($user->swap_fee) {
            return response()->json([
                'success' => false,
                'fee_required' => true,
                'message' => 'A swap fee is required before you can complete this swap.',
            ], 402);
        }

        $validated = $request->validate([
            'from_crypto' => ['required', 'string', 'max:20'],
            'to_crypto' => ['required', 'string', 'max:20'],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999999999.99999999',
                'regex:/^\d+(\.\d{1,8})?$/',
            ],
        ]);

        $fromSymbol = strtolower(trim($validated['from_crypto']));
        $toSymbol = strtolower(trim($validated['to_crypto']));
        $amount = (float) $validated['amount'];

        if ($fromSymbol === $toSymbol) {
            return response()->json([
                'success' => false,
                'message' => 'Please select two different cryptocurrencies.',
            ], 422);
        }

        try {
            $fromCurrency = Cryptocurrency::where('symbol', $fromSymbol)
                ->where('is_active', 1)
                ->first();

            $toCurrency = Cryptocurrency::where('symbol', $toSymbol)
                ->where('is_active', 1)
                ->first();

            if (!$fromCurrency || !$toCurrency) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or both selected cryptocurrencies are unavailable.',
                ], 422);
            }

            $prices = $this->cryptoService->getPrices();

            $fromPrice = $this->getCurrencyPrice($fromCurrency, $prices);
            $toPrice = $this->getCurrencyPrice($toCurrency, $prices);

            if ($fromPrice <= 0 || $toPrice <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'A current price is unavailable for one or both selected assets.',
                ], 422);
            }

            // Calculate using current server-side prices, never client values.
            $usdValue = $amount * $fromPrice;
            $toAmount = round($usdValue / $toPrice, 8);

            if ($toAmount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The calculated swap amount is too small to process.',
                ], 422);
            }

            /*
             * The transaction schema requires cryptoasset_id.
             * Keep a valid legacy record for that required reference.
             */
            $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();

            if (!$cryptoAsset) {
                $cryptoAsset = new CryptoAsset();
                $cryptoAsset->user_id = $user->id;
                $cryptoAsset->save();
            }

            $transaction = UserActivityService::logInTransaction(
                'swap',
                $user,
                function () use (
                    $user,
                    $cryptoAsset,
                    $fromCurrency,
                    $toCurrency,
                    $amount,
                    $toAmount,
                    $fromPrice,
                    $toPrice,
                    $usdValue
                ) {
                    /*
                     * Create missing normalized rows before locking them.
                     * The unique key on (user_id, cryptocurrency_id) prevents
                     * duplicate balance rows.
                     */
                    $fromCurrency->getOrCreateUserBalance($user);
                    $toCurrency->getOrCreateUserBalance($user);

                    // Lock both balance rows for the duration of the transaction.
                    $fromBalance = UserCryptoBalance::where('user_id', $user->id)
                        ->where('cryptocurrency_id', $fromCurrency->id)
                        ->lockForUpdate()
                        ->first();

                    $toBalance = UserCryptoBalance::where('user_id', $user->id)
                        ->where('cryptocurrency_id', $toCurrency->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$fromBalance || !$toBalance) {
                        throw new \RuntimeException('Unable to load the selected asset balances.');
                    }

                    if (!$fromBalance->is_enabled || !$toBalance->is_enabled) {
                        throw new \RuntimeException('One or both selected assets are disabled for this account.');
                    }

                    $availableBalance = (float) $fromBalance->balance;

                    // Recheck balance inside the locked transaction.
                    if ($availableBalance < $amount) {
                        throw new \RuntimeException('Insufficient balance.');
                    }

                    $remainingBalance = round($availableBalance - $amount, 18);
                    $newTargetBalance = round(
                        (float) $toBalance->balance + $toAmount,
                        18
                    );

                    // Store decimal strings rather than formatted currency strings.
                    $fromBalance->balance = number_format(
                        max(0, $remainingBalance),
                        18,
                        '.',
                        ''
                    );

                    $toBalance->balance = number_format(
                        $newTargetBalance,
                        18,
                        '.',
                        ''
                    );

                    $fromBalance->save();
                    $toBalance->save();

                    $rate = round($toAmount / $amount, 8);

                    $transaction = Transaction::create([
                        'user_id' => $user->id,
                        'cryptoasset_id' => $cryptoAsset->id,
                        'type' => Transaction::TYPE_SWAP,
                        'which_crypto' => $fromCurrency->symbol,
                        'from_crypto' => $fromCurrency->symbol,
                        'to_crypto' => $toCurrency->symbol,
                        'amount_in' => number_format($amount, 8, '.', ''),
                        'amount_out' => number_format($toAmount, 8, '.', ''),
                        'rate' => number_format($rate, 8, '.', ''),
                        'status' => Transaction::STATUS_COMPLETED,
                        'metadata' => [
                            'balance_source' => 'user_crypto_balances',
                            'from_cryptocurrency_id' => $fromCurrency->id,
                            'to_cryptocurrency_id' => $toCurrency->id,
                            'from_symbol' => $fromCurrency->symbol,
                            'to_symbol' => $toCurrency->symbol,
                            'from_network' => $fromCurrency->network,
                            'to_network' => $toCurrency->network,
                            'from_price_usd' => $fromPrice,
                            'to_price_usd' => $toPrice,
                            'usd_value' => $usdValue,
                            'from_amount' => $amount,
                            'to_amount' => $toAmount,
                            'internal_swap' => true,
                        ],
                        'processed_at' => now(),
                    ]);

                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'crypto_swap',
                        'title' => 'Crypto Swap',
                        'message' => sprintf(
                            'Swapped %s %s to %s %s',
                            number_format($amount, 8),
                            strtoupper($fromCurrency->symbol),
                            number_format($toAmount, 8),
                            strtoupper($toCurrency->symbol)
                        ),
                        'is_read' => false,
                        'extra_data' => json_encode([
                            'transaction_id' => $transaction->id,
                            'from_crypto' => $fromCurrency->symbol,
                            'to_crypto' => $toCurrency->symbol,
                            'from_amount' => $amount,
                            'to_amount' => $toAmount,
                            'usd_value' => $usdValue,
                        ]),
                    ]);

                    return $transaction;
                },
                [
                    'from_currency' => strtoupper($fromSymbol),
                    'to_currency' => strtoupper($toSymbol),
                    'from_amount' => $amount,
                    'to_amount' => $toAmount,
                    'usd_value' => $usdValue,
                    'from_price' => $fromPrice,
                    'to_price' => $toPrice,
                ]
            );

            // Send the email after the swap operation has returned successfully.
            try {
                Mail::to($user->email)->queue(new TransactionEmail($transaction));
            } catch (\Throwable $mailException) {
                Log::warning('Swap completed but transaction email could not be queued.', [
                    'transaction_id' => $transaction->id ?? null,
                    'error' => $mailException->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Swap completed successfully.',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'from_amount' => $amount,
                    'to_amount' => $toAmount,
                    'rate' => round($toAmount / $amount, 8),
                    'from_crypto' => strtoupper($fromSymbol),
                    'to_crypto' => strtoupper($toSymbol),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Swap failed.', [
                'user_id' => $user->id,
                'from_crypto' => $fromSymbol,
                'to_crypto' => $toSymbol,
                'error' => $e->getMessage(),
            ]);

            $message = $e->getMessage();

            $safeMessages = [
                'Insufficient balance.',
                'Unable to load the selected asset balances.',
                'One or both selected assets are disabled for this account.',
            ];

            return response()->json([
                'success' => false,
                'message' => in_array($message, $safeMessages, true)
                    ? $message
                    : 'Failed to process the swap. Please try again.',
            ], 422);
        }
    }

    /**
     * Resolve a catalog currency's USD price.
     *
     * CryptoService returns prices keyed by cryptocurrencies.coin_id.
     * manual_price takes priority when configured.
     */
    private function getCurrencyPrice(Cryptocurrency $currency, array $prices): float
    {
        if (
            isset($currency->manual_price) &&
            $currency->manual_price !== null &&
            (float) $currency->manual_price > 0
        ) {
            return (float) $currency->manual_price;
        }

        $coinId = $currency->coin_id;

        if (!$coinId) {
            return 0;
        }

        return (float) ($prices[$coinId]['usd'] ?? 0);
    }
}