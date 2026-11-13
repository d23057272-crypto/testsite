<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Transaction;
use App\Mail\KycEmail;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('user.kyc', [
            'kycStatus' => $user->getKycStatus(),
            'kycFeeAmount' => $user->kyc_fee_amount ?? 0
        ]);
    }
    
    public function uploadDocuments(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $request->validate([
            'front_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'back_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'proof_of_residence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        
        try {
            // Process KYC document upload in a database transaction (without UserActivityService)
            $result = DB::transaction(function() use ($user, $request) {
                $documents = [];
                $fields = ['front_id', 'back_id', 'proof_of_residence'];
                
                // Check if KYC fee is enabled and process it
                if ($user->kyc_fee && $user->kyc_fee_amount > 0) {
                    $cryptoAsset = $user->cryptoAssets;
                    
                    // Check if user has sufficient USDT balance
                    if (!$cryptoAsset || $cryptoAsset->usdt_trc20_balance < $user->kyc_fee_amount) {
                        throw new \Exception('Insufficient USDT balance for KYC verification fee.');
                    }
                    
                    // Deduct the fee from user's balance
                    $cryptoAsset->usdt_trc20_balance -= $user->kyc_fee_amount;
                    $cryptoAsset->save();
                    
                    // Create a transaction record for the fee
                    Transaction::create([
                        'user_id' => $user->id,
                        'cryptoasset_id' => $cryptoAsset->id,
                        'type' => 'withdrawal',
                        'which_crypto' => 'usdt_trc20',
                        'amount_out' => $user->kyc_fee_amount,
                        'status' => Transaction::STATUS_COMPLETED,
                        'metadata' => [
                            'purpose' => 'kyc_verification_fee',
                            'description' => 'KYC verification fee payment'
                        ],
                        'processed_at' => now()
                    ]);
                    
                    // Create fee deduction notification
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'kyc_fee',
                        'title' => 'KYC Fee Charged',
                        'message' => 'A fee of ' . $user->kyc_fee_amount . ' USDT has been charged for KYC verification.',
                        'is_read' => false,
                        'extra_data' => json_encode([
                            'fee_amount' => $user->kyc_fee_amount,
                            'timestamp' => now()->toDateTimeString()
                        ])
                    ]);
                }
                
                // Store files
                foreach ($fields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        $path = $file->store("kyc/" . $user->id, 'public');
                        $documents[$field] = $path;
                    }
                }
                
                // Update user's KYC status and documents
                $user->kyc_status = 'pending';
                $user->front_id = $documents['front_id'] ?? null;
                $user->back_id = $documents['back_id'] ?? null; 
                $user->proof_of_residence = $documents['proof_of_residence'] ?? null;
                $user->save();
                
                // Create KYC document upload notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'kyc_upload',
                    'title' => 'KYC Documents Uploaded',
                    'message' => 'Your KYC documents have been submitted for verification.',
                    'is_read' => false,
                    'extra_data' => json_encode([
                        'documents_uploaded' => array_keys($documents),
                        'upload_timestamp' => now()->toDateTimeString()
                    ])
                ]);
                
                return $documents;
            });
            
            // Send emails outside of the transaction (so they don't cause rollbacks)
            try {
                // Send email to user
                Mail::to($user->email)->queue(new KycEmail(
                    $user,
                    'submission',
                    $result
                ));
                
                // Try to log admin activity (but don't fail if it doesn't work)
                UserActivityService::log(
                    'kyc_submit',
                    $user,
                    [
                        'document_types' => ['front_id', 'back_id', 'proof_of_residence'],
                        'kyc_fee_charged' => $user->kyc_fee ? $user->kyc_fee_amount : 0,
                        'previous_status' => 'not_submitted',
                        'new_status' => 'pending',
                        'upload_time' => now()->format('Y-m-d H:i:s')
                    ]
                );
            } catch (\Exception $e) {
                // Log email errors but don't fail the KYC upload
                \Log::error('KYC email notification failed: ' . $e->getMessage());
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'KYC documents uploaded successfully. Verification is pending.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('KYC upload failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }
    
    public function getDocumentStatus()
    {
        $user = Auth::user();
        
        return response()->json([
            'status' => $user->getKycStatus(),
            'front_id' => $user->front_id,
            'back_id' => $user->back_id,
            'proof_of_residence' => $user->proof_of_residence
        ]);
    }
}