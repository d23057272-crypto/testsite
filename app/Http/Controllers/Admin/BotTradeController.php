<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotTrade;
use App\Models\Bot;
use Illuminate\Http\Request;

class BotTradeController extends Controller
{
    public function index()
    {
        $trades = BotTrade::with(['bot', 'subscriptionProfits.subscription.user'])
            ->latest()
            ->paginate(20);

        return view('admin.trades.index', compact('trades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bot_id' => 'required|exists:bots,id',
            'trading_pair' => 'required|string',
            'action' => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'profit' => 'required|numeric',
            'result' => 'nullable|in:win,loss'
        ]);

        BotTrade::create($validated);

        return redirect()
            ->route('admin.trades.index')
            ->with('success', 'Trade created successfully');
    }

    public function update(Request $request, BotTrade $trade)
    {
        $validated = $request->validate([
            'result' => 'required|in:win,loss',
            'profit' => 'required|numeric'
        ]);

        $trade->update($validated);

        return redirect()
            ->route('admin.trades.index')
            ->with('success', 'Trade updated successfully');
    }
}