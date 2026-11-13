<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\CryptoAsset;
use App\Mail\KycEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where(function($query) {
            $query->whereNotNull('front_id')
                  ->whereNotNull('back_id')
                  ->whereNotNull('proof_of_residence');
        });
        
        if ($request->filled('status')) {
            $query->where('kyc_status', $request->status);
        }
        
        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('admin.kyc.index', compact('users'));
    }

    public function show(User $submission)
    {
        return view('admin.kyc.show', compact('submission'));
    }

    public function update(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($userId);
            
            $validated = $request->validate([
                'status' => ['required', 'in:approved,rejected'],
                'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string', 'max:500'],
            ]);

            $user->updateKycStatus($validated['status']);

            // Send email notification about status update
            Mail::to($user->email)->queue(new KycEmail(
                $user,
                'status_update',
                [],
                $validated['status'],
                $validated['rejection_reason'] ?? null
            ));

            // Handle referral bonus if KYC is approved
            if ($validated['status'] === 'approved' && 
                setting('referral_enabled') && 
                setting('referral_bonus_type') === 'after_kyc' && 
                $user->referred_by) {
                
                $referrer = User::find($user->referred_by);
                // Only give bonus if referrer hasn't received it yet (wasn't KYC verified at time of referral)
                if ($referrer && $referrer->kyc_status === 'approved') {
                    $bonus_amount = setting('referral_bonus_amount', 0);
                    
                    $referrerCryptoAsset = CryptoAsset::firstOrCreate(
                        ['user_id' => $referrer->id]
                    );
                    $referrerCryptoAsset->increment('usdt_trc20_balance', $bonus_amount);

                    // Create notification for referrer
                    Notification::createNotification([
                        'user_id' => $referrer->id,
                        'type' => 'referral_bonus',
                        'title' => 'Referral Bonus Received',
                        'message' => "You have received a referral bonus of $${bonus_amount} USDT for referring {$user->name}",
                        'extra_data' => json_encode([
                            'amount' => $bonus_amount,
                            'referred_user' => $user->name
                        ])
                    ]);

                    // Send referral bonus email to referrer
                    Mail::to($referrer->email)->queue(new ReferralBonusEmail(
                        $referrer,
                        $user,
                        $bonus_amount
                    ));
                }
            }

            DB::commit();
            return back()->with('status', 'KYC submission has been ' . $validated['status']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update KYC status: ' . $e->getMessage()]);
        }
    }
}