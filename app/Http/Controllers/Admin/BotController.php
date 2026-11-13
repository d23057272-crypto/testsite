<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotSubscription;
use App\Models\BotTrade;
use App\Models\BotSetting;
use Illuminate\Http\Request;

class BotController extends Controller
{
 public function index()
{
   $bots = Bot::withCount([
       'subscriptions',
       'subscriptions as active_subscriptions_count' => fn($q) => $q->where('status', 'active'),
       'trades'
   ])
   ->withSum('subscriptions', 'amount')
   ->withSum('subscriptions', 'total_profit')
   ->latest()
   ->paginate(10);

   $botSettings = BotSetting::get(); // Removed 'where' condition to get all settings

   $bots->getCollection()->transform(function($bot) use($botSettings) {
       $bot->settings = $botSettings->where('bot_type', $bot->bot_type)->first();
       return $bot;
   });

   return view('admin.bots.index', [
       'bots' => $bots,
       'botSettings' => $botSettings
   ]);
}

public function toggleStatus(Bot $bot)
{
    $botSetting = BotSetting::where('bot_type', $bot->bot_type)->first();
    
    if ($botSetting) {
        $botSetting->is_active = !$botSetting->is_active;
        $botSetting->save();
    }

    return redirect()->route('admin.bots.index')
        ->with('status', 'Bot status updated successfully');
}


  public function store(Request $request)
{
   $validated = $request->validate([
       'name' => 'required|string|max:255',
       'bot_type' => 'required|string',
       'min_amount' => 'required|numeric|min:0',
       'max_amount' => 'required|numeric|gt:min_amount',
       'duration_options' => 'required|array',
       'duration_options.*' => 'required|string',
       'supported_pairs' => 'nullable|array',
       'supported_pairs.*' => 'nullable|string',
       'parameters' => 'nullable|array',
       'parameters.*' => 'nullable|string'
   ]);

   $bot = Bot::create([
       'name' => $validated['name'],
       'bot_type' => $validated['bot_type'],
       'min_amount' => $validated['min_amount'],
       'max_amount' => $validated['max_amount']
   ]);

   BotSetting::create([
       'bot_type' => $validated['bot_type'],
   	   'name' => $validated['name'],
       'is_active' => true,
       'supported_pairs' => $validated['supported_pairs'] ?? [],
       'duration_options' => $validated['duration_options'],
       'parameters' => $validated['parameters'] ?? []
   ]);

   return redirect()
       ->route('admin.bots.index')
       ->with('status', 'Bot created successfully');
}

  public function create()
{
   $botTypes = BotSetting::where('is_active', true)->get();
   return view('admin.bots.create', compact('botTypes'));
}

public function show(Bot $bot)
{
   $botSettings = BotSetting::where('bot_type', $bot->bot_type)
       ->where('is_active', true)
       ->first();

   $bot->loadCount([
       'subscriptions',
       'subscriptions as active_subscriptions_count' => function($query) {
           $query->where('status', 'active');
       }
   ])->loadSum('subscriptions', 'amount')
     ->loadSum('subscriptions', 'total_profit')
     ->load(['subscriptions.user', 'trades' => function($query) {
         $query->latest()->take(20);
     }]);

   return view('admin.bots.show', compact('bot', 'botSettings'));
}

   public function edit(Bot $bot)
{
   $botSettings = BotSetting::where('bot_type', $bot->bot_type)
       ->where('is_active', true)
       ->first();
       
   $botTypes = BotSetting::where('is_active', true)->get();
       
   return view('admin.bots.edit', compact('bot', 'botTypes', 'botSettings'));
}

  public function update(Request $request, Bot $bot)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bot_type' => 'required|string',
        'min_amount' => 'required|numeric|min:0',
        'max_amount' => 'required|numeric|gt:min_amount',
        'duration_options' => 'required|array',
        'duration_options.*' => 'required|string',
        'parameters' => 'nullable|array',
        'parameters.*' => 'nullable|string',
        'supported_pairs' => 'nullable|array',
        'supported_pairs.*' => 'nullable|string|regex:/^[A-Z]+\/[A-Z]+$/',
    ]);

    $bot->update([
        'name' => $validated['name'],
        'bot_type' => $validated['bot_type'],
        'min_amount' => $validated['min_amount'],
        'max_amount' => $validated['max_amount'],
    ]);

    $botSettings = BotSetting::where('bot_type', $bot->bot_type)
                              ->where('is_active', true)
                              ->first();

    if ($botSettings) {
        $botSettings->update([
            'parameters' => $validated['parameters'] ?? $botSettings->parameters,
            'supported_pairs' => $validated['supported_pairs'] ?? $botSettings->supported_pairs,
            'duration_options' => $validated['duration_options'] ?? $botSettings->duration_options,
        ]);
    }

    return redirect()
        ->route('admin.bots.show', $bot)
        ->with('status', 'Bot updated successfully');
}


   public function destroy(Bot $bot)
{
   if($bot->subscriptions()->where('status', 'active')->exists()) {
       return back()->with('error', 'Cannot delete bot with active subscriptions');
   }
   
   // Delete associated bot settings
   BotSetting::where('bot_type', $bot->bot_type)->delete();
   
   $bot->delete();
   
   return redirect()
       ->route('admin.bots.index')
       ->with('status', 'Bot deleted successfully');
}
}