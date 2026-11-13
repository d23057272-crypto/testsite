<?php

namespace App\Http\Controllers;

use App\Models\BotSubscription;
use App\Models\BotTrade;
use App\Models\SubscriptionProfit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class CronController extends Controller
{
   public function checkSubscriptions()
   {
       try {
           $expiredSubscriptions = BotSubscription::where('status', 'active')
               ->where('expires_at', '<', now())
               ->with(['bot', 'user'])
               ->get();

           foreach ($expiredSubscriptions as $subscription) {
               DB::beginTransaction();
               try {
                   // Get trades for this subscription
                   $trades = BotTrade::where('bot_id', $subscription->bot_id)
                       ->whereDoesntHave('subscriptionProfits', function($query) use ($subscription) {
                           $query->where('bot_subscription_id', $subscription->id);
                       })
                       ->get();
                   
                   // Update null results to loss
                   foreach($trades as $trade) {
                       if ($trade->result === null) {
                           $trade->update(['result' => 'loss']);
                       }
                   }

                   $totalProfit = 0;

                  foreach ($trades as $trade) {
   $profitAmount = 0;

   // Set profit for wins, leave as 0 for losses
   if ($trade->result === 'win' && $trade->profit > 0) {
       $profitAmount = $trade->profit;
       $totalProfit += $profitAmount;
   }
   
   // Create subscription profit record for both wins and losses
   SubscriptionProfit::create([
       'bot_subscription_id' => $subscription->id,
       'bot_trade_id' => $trade->id,
       'amount' => $trade->amount,
       'profit' => $profitAmount
   ]);
}

                   $currentTotalProfit = $subscription->total_profit ?? 0;
                   
                   $subscription->update([
                       'status' => 'completed',
                       'total_profit' => bcadd($currentTotalProfit, $totalProfit, 8)
                   ]);

                   // Only update user balance if there was profit
                   if ($totalProfit > 0) {
                       $currentBalance = $subscription->user->cryptoAssets->usdt_trc20_balance ?? 0;
                       $newBalance = bcadd($currentBalance, bcadd($subscription->amount, $totalProfit, 8), 8);
                       
                       $subscription->user->cryptoAssets()->update([
                           'usdt_trc20_balance' => $newBalance
                       ]);
                   }

                   DB::commit();
                   
                   Log::info('Successfully processed subscription', [
                       'subscription_id' => $subscription->id,
                       'total_profit' => $totalProfit
                   ]);

               } catch (\Exception $e) {
                   DB::rollBack();
                   Log::error('Error processing subscription', [
                       'subscription_id' => $subscription->id,
                       'error' => $e->getMessage(),
                       'trace' => $e->getTraceAsString()
                   ]);
               }
           }

           return response()->json([
               'success' => true,
               'message' => 'Successfully checked subscriptions'
           ]);

       } catch (\Exception $e) {
           Log::error('Error in checkSubscriptions', [
               'error' => $e->getMessage(),
               'trace' => $e->getTraceAsString()
           ]);

           return response()->json([
               'success' => false,
               'message' => 'Error checking subscriptions: ' . $e->getMessage()
           ], Response::HTTP_INTERNAL_SERVER_ERROR);
       }
   }
}