<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardTransaction;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\CryptoAsset;
use App\Services\CryptoService;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function index()
    {
        $user = Auth::user();
        $card = $user->cards()->first();

        return view('user.card.index', ['card' => $card]);
    }

    public function request()
    {
        $user = Auth::user();
        $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();
        
        // Check if the user already has a card
        if ($user->cards()->exists()) {
            return back()->with('error', 'You already have a card');
        }
        
        if (!$cryptoAsset) {
            $cryptoAsset = new CryptoAsset();
            $cryptoAsset->user_id = $user->id;
        }

        $cryptoAsset->generateAddressesIfNull();

        // Create a new virtual card
        $card = $user->cards()->create([
            'card_holder' => $user->name,
            'card_number' => $this->generateCardNumber(),
            'expiry_month' => now()->addYears(3)->format('m'),
            'expiry_year' => now()->addYears(3)->format('y'),
            'cvv' => $this->generateCVV(),
            'billing_address' => '651 N Broad Street, Middletown, Delaware, US',
            'zip_code' => '19709',
            'status' => 'inactive',
            'card_type' => 'virtual',
            'last_four' => substr($this->generateCardNumber(), -4),
            'is_default' => true,
            'crypto_asset_id' => $cryptoAsset->id,
        ]);

        return back()->with('success', 'Card Request Successful');
    }

    public function freeze(Card $card)
    {
        $this->authorize('update', $card);
        $card->freeze();

        return back()->with('success', 'Card frozen successfully');
    }

    public function unfreeze(Card $card)
    {
        $this->authorize('update', $card);
        $card->unfreeze();

        return back()->with('success', 'Card unfrozen successfully');
    }

    public function delete(Card $card)
{
    $this->authorize('delete', $card);

    try {
        DB::transaction(function () use ($card) {
            // Check if card has remaining balance
            if ($card->balance > 0) {
                // Get the user's crypto asset
                $cryptoAsset = CryptoAsset::where('user_id', $card->user_id)->first();

                if (!$cryptoAsset) {
                    throw new \Exception('Crypto asset not found');
                }

                // Add the remaining balance to USDT ERC20
                $cryptoAsset->usdt_erc20_balance += $card->balance;
                $cryptoAsset->save();
            }

            // Delete the card
            $card->delete();
        });

        return back()->with('success', 'Card deleted successfully.');
    } catch (\Exception $e) {
        \Log::error('Card deletion failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to delete card. Please try again.');
    }
}

    public function addMoney()
    {
        $user = Auth::user();
        $card = $user->cards()->first();
        $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();
        $prices = $this->cryptoService->getPrices();

        return view('user.card.add-money', [
            'card' => $card,
            'cryptoAsset' => $cryptoAsset,
            'prices' => $prices,
        ]);
    }

    public function fund(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'crypto' => 'required|string',
    ]);
    $user = Auth::user();
    $card = $user->cards()->first();
    $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();
    $prices = $this->cryptoService->getPrices();
    // Get balance field for the selected crypto
    $balanceField = $request->crypto . '_balance';
    
    // Get crypto name for price lookup
    $cryptoName = $this->getCryptoNameForPriceLookup($request->crypto);
    $cryptoPrice = $prices[$cryptoName]['usd'] ?? 0;
    
    if ($cryptoPrice === 0) {
        return back()->with('error', 'Unable to get current price');
    }
    // Calculate required crypto amount
    $requiredCryptoAmount = $request->amount / $cryptoPrice;
    // Check if the user has enough balance
    if ($cryptoAsset->$balanceField < $requiredCryptoAmount) {
        return back()->with('error', 'Insufficient balance');
    }
    try {
        // Deduct from crypto balance and add to card balance using UserActivityService
        UserActivityService::logInTransaction(
            'card_funding',
            $user,
            function () use ($cryptoAsset, $card, $request, $balanceField, $requiredCryptoAmount, $cryptoName, $cryptoPrice, $user) {
                // Update crypto balance
                $cryptoAsset->$balanceField -= $requiredCryptoAmount;
                $cryptoAsset->save();
                // Update card balance
                $card->balance += $request->amount;
                $card->save();
                // Create card transaction record
                $cardTransaction = CardTransaction::create([
                    'card_id' => $card->id,
                    'amount' => $request->amount,
                    'type' => 'credit',
                    'description' => sprintf(
                        'Funded from %s (%s %s)', 
                        strtoupper(str_replace(['_', '-'], ' ', $request->crypto)),
                        number_format($requiredCryptoAmount, 8),
                        strtoupper(explode('_', $request->crypto)[0])
                    )
                ]);
                
                // Create crypto transaction record
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'cryptoasset_id' => $cryptoAsset->id,
                    'type' => 'funding',
                    'which_crypto' => $request->crypto,
                    'amount_out' => $requiredCryptoAmount,
                    'transaction_hash' => null,
                    'status' => Transaction::STATUS_COMPLETED,
                    'metadata' => [
                        'card_id' => $card->id,
                        'usd_value' => $request->amount,
                        'price' => $cryptoPrice,
                        'card_amount' => $request->amount
                    ],
                    'processed_at' => now()
                ]);

                // Create notification for card funding
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'card_funding',
                    'title' => 'Card Funded',
                    'message' => sprintf(
                        'You funded your card with %s USD using %s',
                        number_format($request->amount, 2),
                        strtoupper(str_replace(['_', '-'], ' ', $request->crypto))
                    ),
                    'is_read' => false,
                    'extra_data' => json_encode([
                        'transaction_id' => $transaction->id,
                        'card_transaction_id' => $cardTransaction->id,
                        'amount' => $request->amount,
                        'crypto' => $request->crypto,
                        'crypto_amount' => $requiredCryptoAmount
                    ])
                ]);
                
                return $transaction;
            },
            [
                'card_id' => $card->id,
                'card_last_four' => $card->last_four,
                'amount' => $request->amount,
                'currency' => 'USD',
                'source_currency' => strtoupper($request->crypto),
                'source_amount' => $requiredCryptoAmount,
                'exchange_rate' => $cryptoPrice
            ]
        );
        
        return back()->with('success', 'Card funded successfully');
    } catch (\Exception $e) {
        \Log::error('Card funding failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to process transaction. Please try again.');
    }
}

    private function getCryptoNameForPriceLookup($crypto)
    {
        // Handle special cases for USDT on different networks
        if (str_starts_with($crypto, 'usdt_')) {
            return 'tether';
        }

        // Map for other cryptos
        $cryptoMap = [
            'btc' => 'bitcoin',
            'eth' => 'ethereum',
            'trx' => 'tron',
            'bnb' => 'binancecoin',
            'dot' => 'polkadot',
            'bch' => 'bitcoin-cash',
            'ltc' => 'litecoin',
            'xlm' => 'stellar',
            'dash' => 'dash',
            'sol' => 'solana',
            'doge' => 'dogecoin'
        ];

        return $cryptoMap[$crypto] ?? $crypto;
    }

    private function generateCardNumber()
    {
        
        return '4187' . str_pad(mt_rand(0, 9999999999), 12, '0', STR_PAD_LEFT);
    }

    private function generateCVV()
    {
        // Generate a random 3-digit CVV
        return str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
    }
}