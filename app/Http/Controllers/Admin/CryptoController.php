<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CryptoAsset;
use App\Services\CryptoBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CryptoController extends Controller
{
    public function show(User $user)
    {
        $cryptoAssets = $user->cryptoAssets ?? $user->createCryptoAssets();
        $supportedCryptos = \App\Models\CryptoAsset::getSupportedCryptos();
        return view('admin.users.crypto', compact('user', 'cryptoAssets', 'supportedCryptos'));
    }

    public function update(Request $request, User $user)
    {
        $balanceService = app(CryptoBalanceService::class);

        // If we're just updating status, validate differently
        if ($request->has('status_update')) {
            $validated = $request->validate([
                'crypto' => ['required', 'string', Rule::in($balanceService->supportedSymbols())],
            ]);

            DB::transaction(function () use ($balanceService, $user, $request, $validated) {
                $balanceService->setEnabled(
                    $user,
                    $validated['crypto'],
                    $request->boolean(strtolower($validated['crypto']) . '_status')
                );
            });

            return back()->with('status', 'Status updated successfully');
        }

        $validated = $request->validate([
            'crypto' => ['required', 'string', Rule::in($balanceService->supportedSymbols())],
            'amount' => 'nullable|numeric|min:0',
            'action' => 'nullable|in:add,subtract',
        ]);

        if ($request->filled('amount') && $request->filled('action')) {
            $delta = (float) $validated['amount'];

            if ($validated['action'] === 'subtract') {
                $delta *= -1;
            }

            DB::transaction(function () use ($balanceService, $user, $validated, $delta) {
                $balanceService->adjust($user, $validated['crypto'], $delta);
            });
        }

        return back()->with('status', 'Crypto asset updated successfully');
    }
}