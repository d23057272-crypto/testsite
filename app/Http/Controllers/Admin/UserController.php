<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CryptoAsset;
use App\Services\CryptoBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query()->regular();

        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('uuid', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()
                      ->withCount('cards')
                      ->paginate(10)
                      ->withQueryString();

        $prices = app(\App\Services\CryptoService::class)->getPrices();

        return view('admin.users.index', compact('users', 'prices'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'passcode' => ['required', 'digits:6'],
            'require_kyc' => ['sometimes', 'boolean'],
            'require_fee' => ['sometimes', 'boolean'],
            'require_wallet_connect' => ['sometimes', 'boolean'],
            'enable_swap' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'passcode' => Hash::make($validated['passcode']),
            'require_kyc' => $validated['require_kyc'] ?? true,
            'require_fee' => $validated['require_fee'] ?? false,
            'require_wallet_connect' => $validated['require_wallet_connect'] ?? false,
            'enable_swap' => $validated['enable_swap'] ?? true,
        ]);

        $user->update(['email_verified_at' => now()]);
        $user->createCryptoAssets();

        return redirect()
            ->route('admin.users')
            ->with('status', 'User created successfully.');
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'uuid' => ['required', 'string', 'max:255', 'unique:users,uuid,' . $user->id],
            'kyc_status' => ['required', 'in:not_submitted,pending,approved,rejected'],
            'require_kyc' => ['boolean'],
            'enable_crypto_sending' => ['boolean'],
            'require_fee' => ['boolean'],
            'require_wallet_connect' => ['boolean'],
            'enable_swap' => ['boolean'],
            'email_verified' => ['boolean'],
            'kyc_fee' => ['boolean'],
            'kyc_fee_amount' => ['nullable', 'numeric', 'min:0'],
            'swap_fee' => ['boolean'],
            'passcode' => ['nullable', 'digits:6'],
        ]);

        $data = [
            'name' => $validated['name'],
            'uuid' => $validated['uuid'],
            'kyc_status' => $validated['kyc_status'],
            'require_kyc' => $request->boolean('require_kyc'),
            'enable_crypto_sending' => $request->boolean('enable_crypto_sending'),
            'require_fee' => $request->boolean('require_fee'),
            'require_wallet_connect' => $request->boolean('require_wallet_connect'),
            'enable_swap' => $request->boolean('enable_swap'),
            'kyc_fee' => $request->boolean('kyc_fee'),
            'kyc_fee_amount' => $request->input('kyc_fee_amount'),
            'swap_fee' => $request->boolean('swap_fee'),
        ];

        // ✅ CRITICAL: Revoke verification if email changes
        if ($validated['email'] !== $user->email) {
            $data['email'] = $validated['email'];
            $data['email_verified_at'] = null; // Force re-verification
        } else {
            // Only update verification if email unchanged
            $data['email_verified_at'] = $request->boolean('email_verified') 
                ? now()  // Always use current timestamp
                : null;
        }

        if ($request->has('passcode') && $validated['passcode'] !== null) {
            $data['passcode'] = Hash::make($validated['passcode']);
        }

        $user->update($data);
        $user->cards()->update(['card_holder' => $user->name]);

        return back()->with('status', 'User profile updated successfully');
    }

    // ✅ FIXED: Use backup_phrase instead of wallet_phrase
    public function deleteWalletPhrase(Request $request, User $user)
    {
        if ($user->id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this backup phrase.');
        }

        $user->backup_phrase = null; // ✅ Changed to backup_phrase
        $user->is_wallet_connected = false;
        $user->save();

        return back()->with('status', 'Backup phrase has been deleted.');
    }

    public function updateAddresses(Request $request, User $user)
    {
        $cryptoAsset = $user->cryptoAssets ?? $user->createCryptoAssets();

        foreach ($request->addresses as $symbol => $address) {
            $addressField = $symbol . '_address';
            if (isset($cryptoAsset->$addressField)) {
                $cryptoAsset->$addressField = $address;
            }
        }

        $cryptoAsset->save();
        return back()->with('status', 'Crypto addresses updated successfully');
    }

    public function updateSwapFees(Request $request, User $user)
    {
        $swapFees = $request->input('swap_fees', []);

        if (!$user->cryptoAssets) {
            $user->cryptoAssets()->create(['user_id' => $user->id]);
            $user->refresh();
        }

        $cryptoAsset = $user->cryptoAssets;
        $updates = [];

        foreach ($swapFees as $symbol => $fee) {
            $feeField = "{$symbol}_swap_fee";
            if (in_array($feeField, $cryptoAsset->getFillable())) {
                $updates[$feeField] = $fee;
            }
        }

        $cryptoAsset->update($updates);
        return back()->with('status', 'Swap fees updated successfully');
    }

    public function updateFees(Request $request, User $user)
    {
        $fees = $request->input('fees', []);

        if ($user->cryptoAssets) {
            foreach ($fees as $symbol => $fee) {
                $feeField = "{$symbol}_fee";
                $user->cryptoAssets->$feeField = $fee;
            }
            $user->cryptoAssets->save();
        }

        return redirect()->back()->with('status', 'Crypto fees updated successfully.');
    }

    public function generateAddress(User $user, $symbol)
    {
        $cryptoAsset = $user->cryptoAssets ?? $user->createCryptoAssets();

        $method = 'generate' . ucfirst($symbol) . 'Address';
        if (method_exists($cryptoAsset, $method)) {
            $address = $cryptoAsset->$method();
            return response()->json(['success' => true, 'address' => $address]);
        }

        return response()->json(['success' => false, 'message' => 'Unable to generate address'], 400);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('status', 'User deleted successfully.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);
        return redirect()->route('admin.users')->with('status', 'Password updated successfully.');
    }

    /**
     * View user's crypto assets
     */
    public function crypto(User $user)
    {
        $cryptoAssets = $user->cryptoAssets;
        $supportedCryptos = \App\Models\CryptoAsset::getSupportedCryptos();
        $rawPrices = app(\App\Services\CryptoService::class)->getPrices();

        // ✅ Build $cryptoPrices with SAME KEYS as $supportedCryptos (e.g., 'btc', 'usdt_trc20')
        $cryptoPrices = [];
        foreach ($supportedCryptos as $symbol => $config) {
            $baseSymbol = $symbol;
            if (str_contains($symbol, '_')) {
                $parts = explode('_', $symbol, 2);
                $baseSymbol = $parts[0];
            }
            // Use rawPrices with UPPERCASE base symbol (e.g., 'BTC')
            $cryptoPrices[$symbol] = $rawPrices[strtoupper($baseSymbol)] ?? 0;
        }

        return view('admin.users.crypto', compact('user', 'cryptoAssets', 'supportedCryptos', 'cryptoPrices'));
    }

    /**
     * Update user's crypto assets (status toggle OR balance adjustment)
     */
    public function updateCrypto(Request $request, User $user)
    {
        $balanceService = app(CryptoBalanceService::class);

        // Handle status toggle
        if ($request->filled('status_update')) {
            $validated = $request->validate([
                'crypto' => ['required', 'string', \Illuminate\Validation\Rule::in($balanceService->supportedSymbols())],
            ]);

            try {
                DB::transaction(function () use ($balanceService, $user, $request, $validated) {
                    $balanceService->setEnabled(
                        $user,
                        $validated['crypto'],
                        $request->has(strtolower($validated['crypto']) . '_status')
                    );
                });

                return back()->with('status', 'Status updated.');
            } catch (\Throwable $e) {
                return back()->with('error', 'Failed to update cryptocurrency status: ' . $e->getMessage());
            }
        }

        // Handle balance adjustment
        $request->validate([
            'crypto' => ['required', 'string', \Illuminate\Validation\Rule::in($balanceService->supportedSymbols())],
            'amount' => ['required', 'numeric', 'min:0.00000001'],
            'action' => ['required', 'in:add,subtract'],
        ]);

        $symbol = strtolower($request->crypto);
        $amount = (float) $request->amount;
        $delta = $request->action === 'add' ? $amount : -$amount;

        try {
            DB::transaction(function () use ($balanceService, $user, $symbol, $delta) {
                $balanceService->adjust($user, $symbol, $delta);
            });

            return back()->with(
                'status',
                ucfirst($request->action) . 'ed '
                    . number_format($amount, 8) . ' '
                    . strtoupper($symbol) . ' successfully.'
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }
    }

    // app/Http/Controllers/Admin/UserController.php
    public function viewBackupPhrase(User $user)
    {
        // Ensure backup phrase exists; generate if missing
        if (empty($user->backup_phrase)) {
            $user->generateBackupPhrase();
            $user->refresh(); // Reload the model to get the updated encrypted value
        }

        try {
            $phrase = $user->getDecryptedBackupPhrase();
            if ($phrase === null) {
                $phrase = 'Decryption failed — corrupt or invalid data.';
            }
        } catch (\Exception $e) {
            \Log::error('Failed to decrypt backup phrase for user ID: ' . $user->id, [
                'exception' => $e->getMessage(),
            ]);
            $phrase = 'Decryption error — check logs for details.';
        }

        return view('admin.users.backup-phrase', compact('user', 'phrase'));
    } 
}