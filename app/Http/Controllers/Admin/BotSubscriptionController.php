<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotSubscription;
use App\Models\BotSetting;
use Illuminate\Http\Request;

class BotSubscriptionController extends Controller
{
   public function index()
   {
       $subscriptions = BotSubscription::with(['bot', 'user'])
           ->latest()
           ->paginate(10);

       return view('admin.subscriptions.index', compact('subscriptions'));
   }

   public function show(BotSubscription $subscription)
   {
       $subscription->load(['bot', 'user', 'profits.trade']);
       
       return view('admin.subscriptions.show', compact('subscription'));
   }

}