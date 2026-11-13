<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function update(Request $request, CurrencyService $currencies)
    {
        $validated = $request->validate([
            'currency' => [
                'required',
                'string',
                Rule::in(array_keys($currencies->supportedCurrencies())),
            ],
        ]);

        $rate = $currencies->rate($validated['currency']);

        if ($rate === null) {
            return back()->withErrors([
                'currency' => __('Reference exchange rates are unavailable for this currency.'),
            ]);
        }

        $request->user()->preferred_currency = $validated['currency'];
        $request->user()->save();

        return back()->with('status', __('Saved.'));
    }
}
