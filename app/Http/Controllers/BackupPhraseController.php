<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class BackupPhraseController extends Controller
{
    /**
     * Show the backup phrase page
     */
    public function show()
    {
        // Only allow verified users
        if (!Auth::user()->email_verified_at) {
            abort(403, 'Email verification required.');
        }

        $user = auth()->user();
        $phrase = null;
        
        if ($user->wallet_phrase) {
            try {
                // Try new method first (clean encryption without serialization)
                $phrase = Crypt::decryptString($user->wallet_phrase);
            } catch (\Exception $e) {
                // Fall back to old method (with PHP serialization)
                try {
                    $decrypted = decrypt($user->wallet_phrase);
                    // decrypt() returns serialized string, so we need to unserialize
                    if (is_string($decrypted)) {
                        $unserialized = @unserialize($decrypted);
                        $phrase = ($unserialized !== false) ? $unserialized : $decrypted;
                    } else {
                        $phrase = $decrypted;
                    }
                } catch (\Exception $e2) {
                    $phrase = null;
                }
            }
        }
        
        // Final cleanup - if still contains serialized artifacts, extract manually
        if (is_string($phrase) && preg_match('/^s:\d+:"(.*)";?$/s', $phrase, $matches)) {
            $phrase = $matches[1];
        }

        return view('user.backup-phrase', compact('phrase'));
    }
}