<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Card;
use App\Models\CardTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
   {
       $startOfMonth = Carbon::now()->startOfMonth();

       return view('admin.dashboard', [
           'userStats' => $this->getUserStats($startOfMonth),
           'transactionStats' => $this->getTransactionStats(),
           'cardStats' => $this->getCardStats(),
           'cryptoStats' => $this->getCryptoStats(),
           'recentTransactions' => Transaction::with('user')
               ->latest()
               ->take(5)
               ->get(),
           'pendingKyc' => User::where('kyc_status', 'pending')
               ->latest('updated_at')
               ->take(5)
               ->get(),
           'recentCardActivities' => CardTransaction::with(['card.user']) 
               ->latest()
               ->take(5)
               ->get(),
       ]);
   }

   private function getUserStats($startOfMonth)
   {
       $totalUsers = User::count();
       $lastMonthUsers = User::where('created_at', '<', $startOfMonth)->count();
       $newUsers = $totalUsers - $lastMonthUsers;
       $percentageChange = $lastMonthUsers > 0 ? ($newUsers / $lastMonthUsers) * 100 : 0;

       return [
           'total' => $totalUsers,
           'kyc_verified' => User::where('kyc_status', 'approved')->count(),
           'new_this_month' => $newUsers,
           'percentage_change' => round($percentageChange, 1)
       ];
   }

   private function getTransactionStats()
   {
       $now = Carbon::now();
       $lastMonth = $now->copy()->subMonth();

       $currentMonthVolume = Transaction::where('status', 'completed')
           ->whereMonth('created_at', $now->month)
           ->whereYear('created_at', $now->year)
           ->sum(DB::raw('amount_in * rate'));

       $lastMonthVolume = Transaction::where('status', 'completed')
           ->whereMonth('created_at', $lastMonth->month)
           ->whereYear('created_at', $lastMonth->year)
           ->sum(DB::raw('amount_in * rate'));

       $percentageChange = $lastMonthVolume > 0 ? 
           (($currentMonthVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

       return [
           'total_count' => Transaction::count(),
           'total_volume' => Transaction::where('status', 'completed')
               ->sum(DB::raw('amount_in * rate')),
           'current_month_volume' => $currentMonthVolume,
           'percentage_change' => round($percentageChange, 1),
           'pending_count' => Transaction::where('status', 'pending')->count()
       ];
   }

   private function getCardStats()
   {
       $now = Carbon::now();
       $lastMonth = $now->copy()->subMonth();

       $currentMonthCards = Card::whereMonth('created_at', $now->month)
           ->whereYear('created_at', $now->year)
           ->count();

       $lastMonthCards = Card::whereMonth('created_at', $lastMonth->month)
           ->whereYear('created_at', $lastMonth->year)
           ->count();

       $percentageChange = $lastMonthCards > 0 ? 
           (($currentMonthCards - $lastMonthCards) / $lastMonthCards) * 100 : 0;

       return [
           'active_cards' => Card::where('status', 'active')->count(),
           'frozen_cards' => Card::where('status', 'frozen')->count(),
           'total_balance' => Card::where('status', 'active')->sum('balance'),
           'new_this_month' => $currentMonthCards,
           'percentage_change' => round($percentageChange, 1)
       ];
   }

   private function getCryptoStats()
   {
       $now = Carbon::now();
       $startOfMonth = $now->copy()->startOfMonth();
       $totalValue = 0;
       $activeWallets = 0;
       $uniqueCurrencies = [];
       $monthlyTransactionsVolume = 0;

       // Get crypto stats
       foreach (User::with('cryptoAssets')->get() as $user) {
           if ($user->hasCryptoAssets()) {
               $totalValue += $user->getTotalBalanceUSD();
               $activeWallets += count($user->cryptoAssets->getActiveCryptos());
               
               foreach ($user->cryptoAssets->getActiveCryptos() as $crypto) {
                   $uniqueCurrencies[$crypto['symbol']] = true;
               }
           }
       }

       // Get monthly transaction volume
       $monthlyTransactionsVolume = Transaction::where('status', 'completed')
           ->where('created_at', '>=', $startOfMonth)
           ->sum(DB::raw('amount_in * rate'));

       // Get last month's transaction volume for comparison
       $lastMonthVolume = Transaction::where('status', 'completed')
           ->whereBetween('created_at', [
               $startOfMonth->copy()->subMonth(),
               $startOfMonth
           ])
           ->sum(DB::raw('amount_in * rate'));

       $percentageChange = $lastMonthVolume > 0 ? 
           (($monthlyTransactionsVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

       return [
           'total_value_usd' => $totalValue,
           'active_wallets' => $activeWallets,
           'currencies_count' => count($uniqueCurrencies),
           'monthly_volume' => $monthlyTransactionsVolume,
           'percentage_change' => round($percentageChange, 1)
       ];
   }
}