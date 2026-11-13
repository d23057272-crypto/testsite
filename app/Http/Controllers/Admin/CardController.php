<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use App\Models\CardTransaction;
use App\Models\CryptoAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function index(User $user)
    {
    $cards = $user->cards()->with(['transactions' => function($query) {
        $query->latest()->take(5);
    }])->get();

    $cryptoAsset = CryptoAsset::where('user_id', $user->id)->first();

    return view('admin.users.cards', compact('user', 'cards', 'cryptoAsset'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'card_holder' => 'required|string|max:255',
        'expiry_month' => 'required|string|size:2',
        'expiry_year' => 'required|string|size:4',
        'cvv' => 'required|string|size:3',
        'billing_address' => 'required|string|max:255',
        'zip_code' => 'required|string|max:20',
        'card_type' => 'required|in:virtual,physical',
        'balance' => 'nullable|numeric|min:0',
        'crypto_asset_id' => 'required|exists:crypto_assets,id',
    ]);

    try {
        DB::beginTransaction();

        $expiryYearShort = substr($validated['expiry_year'], -2);

        $cardNumber = '4' . str_pad(mt_rand(0, 9999999999999999), 15, '0', STR_PAD_LEFT);

        $card = Card::create([
            'user_id' => $validated['user_id'],
            'card_holder' => $validated['card_holder'],
            'crypto_asset_id' => $validated['crypto_asset_id'],
            'card_number' => $cardNumber,
            'expiry_month' => $validated['expiry_month'],
            'expiry_year' => $expiryYearShort,
            'cvv' => $validated['cvv'],
            'billing_address' => $validated['billing_address'],
            'zip_code' => $validated['zip_code'],
            'card_type' => $validated['card_type'],
            'status' => 'inactive',
            'is_default' => true,
            'balance' => $validated['balance'] ?? 0,
            'last_four' => substr($cardNumber, -4),
        ]);

        DB::commit();

        return redirect()
            ->route('admin.users.cards', $validated['user_id'])
            ->with('status', 'Card created successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to create card: ' . $e->getMessage());
    }
}



    public function fund(Request $request, Card $card)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update card balance
            $card->balance += $validated['amount'];
            $card->save();

            // Create transaction record
            CardTransaction::create([
                'card_id' => $card->id,
                'amount' => $validated['amount'],
                'type' => 'credit',
                'description' => $validated['description'],
            ]);

            DB::commit();

            return back()->with('status', 'Funds added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add funds: ' . $e->getMessage());
        }
    }

    public function subtract(Request $request, Card $card)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Check sufficient balance
            if ($card->balance < $validated['amount']) {
                throw new \Exception('Insufficient card balance');
            }

            // Update card balance
            $card->balance -= $validated['amount'];
            $card->save();

            // Create transaction record
            CardTransaction::create([
                'card_id' => $card->id,
                'amount' => -$validated['amount'],
                'type' => 'debit',
                'description' => $validated['description'],
            ]);

            DB::commit();

            return back()->with('status', 'Funds subtracted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to subtract funds: ' . $e->getMessage());
        }
    }

    public function freeze(Card $card)
    {
        try {
            $card->freeze();

            return back()->with('status', 'Card frozen successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to freeze card: ' . $e->getMessage());
        }
    }

    public function unfreeze(Card $card)
    {
        try {
            $card->unfreeze();

            return back()->with('status', 'Card unfrozen successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to unfreeze card: ' . $e->getMessage());
        }
    }
    
    public function activate(Card $card)
    {
        try {
            $card->activate();
            

            return back()->with('status', 'Card activated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to activate card: ' . $e->getMessage());
        }
    }

    public function deactivate(Card $card)
    {
        try {
            $card->deactivate();
            

            return back()->with('status', 'Card deactivated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to deactivate card: ' . $e->getMessage());
        }
    }

    public function destroy(Card $card)
    {
        try {
            if ($card->balance > 0) {
                throw new \Exception('Cannot delete card with remaining balance');
            }

            $card->delete();

            return back()->with('status', 'Card deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete card: ' . $e->getMessage());
        }
    }
}
