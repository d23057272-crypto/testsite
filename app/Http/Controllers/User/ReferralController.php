<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
   public function index()
   {
       $user = Auth::user();
       $referrals = User::where('referred_by', $user->id)
                       ->select('id', 'name', 'email', 'created_at')
                       ->latest()
                       ->get();

       return view('user.referral.index', [
           'user' => $user,
           'referrals' => $referrals,
           'referral_code' => $user->referral_code,
           'referral_count' => $user->referral_count,
           'referral_link' => url("/ref/{$user->referral_code}")
       ]);
   }

   public function stats()
   {
       $user = Auth::user();
       $referrals = User::where('referred_by', $user->id)
                       ->select('id', 'name', 'created_at')
                       ->latest()
                       ->get();

       $stats = [
           'total_referrals' => $user->referral_count,
           'recent_referrals' => $referrals->take(5),
           'this_month' => $referrals->whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
           'last_month' => $referrals->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count()
       ];

       return response()->json($stats);
   }
}