<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WalletSetupController extends Controller
{
    /**
     * Show wallet options (Create New or Import)
     */
    public function showOptions(): View|RedirectResponse  // ✅ Fixed: Union type
    {
        // If user already has wallet phrase, redirect to passcode setup
        if (Auth::user()?->wallet_phrase) {  // ✅ Also added null-safe operator
            return redirect()->route('passcode.setup');
        }
        
        return view('auth.wallet-option');
    }

    /**
     * Show recovery phrase generation page
     */
    public function showRecoveryPhrase(): View|RedirectResponse  // ✅ Added union type for consistency
    {
        $user = Auth::user();
        
        // If user already has wallet phrase, redirect to passcode setup
        if ($user?->wallet_phrase) {
            return redirect()->route('passcode.setup');
        }

        // Generate 12-word recovery phrase
        $wordListPath = base_path('resources/wordlists/english.txt');
        
        if (!file_exists($wordListPath)) {
            return redirect()->route('wallet.option')
                ->withErrors(['error' => 'Wordlist file not found. Please contact support.']);
        }
        
        $wordList = file($wordListPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (empty($wordList)) {
            return redirect()->route('wallet.option')
                ->withErrors(['error' => 'Wordlist is empty. Please contact support.']);
        }

        $phrase = [];
        for ($i = 0; $i < 12; $i++) {
            $phrase[] = $wordList[array_rand($wordList)];
        }
        
        // Store clean phrase in session (not encrypted yet)
        session(['temp_recovery_phrase' => implode(' ', $phrase)]);
        
        return view('auth.recovery-phrase', ['words' => $phrase]);
    }

    /**
     * Store recovery phrase and proceed to passcode setup
     */
    public function storeRecoveryPhrase(Request $request): RedirectResponse
    {
        $request->validate([
            'confirmation' => 'required|accepted',
        ]);

        $phrase = session('temp_recovery_phrase');
        
        if (!$phrase) {
            return redirect()->route('recovery.phrase')
                ->withErrors(['error' => 'Session expired. Please generate a new recovery phrase.']);
        }

        // Encrypt without serialization using Crypt::encryptString()
        $user = Auth::user();
        $user->wallet_phrase = Crypt::encryptString($phrase);
        $user->backup_phrase = Crypt::encryptString($phrase);
        $user->is_wallet_connected = true;
        $user->save();

        // Clear session
        session()->forget('temp_recovery_phrase');

        return redirect()->route('passcode.setup')
            ->with('status', 'Wallet created successfully. Now set up your passcode.');
    }

    /**
     * Show import wallet page
     */
    public function showImportWallet(): View|RedirectResponse  // ✅ Added union type for consistency
    {
        $user = Auth::user();
        
        // If user already has wallet phrase, redirect to passcode setup
        if ($user?->wallet_phrase) {
            return redirect()->route('passcode.setup');
        }
        
        return view('auth.import-wallet');
    }

    /**
     * Store imported wallet phrase
     */
    public function storeImportedWallet(Request $request): RedirectResponse
    {
        $request->validate([
            'wallet_name' => 'required|string',
            'custom_wallet_name' => 'nullable|string|required_if:wallet_name,Other',
            'recovery_phrase_paste' => 'nullable|string',
            'word_count' => 'required|in:12,24',
        ]);

        // Get the phrase from paste or individual words
        $phrase = $request->recovery_phrase_paste;
        
        if (!$phrase) {
            // Build from individual words
            $words = [];
            for ($i = 1; $i <= $request->word_count; $i++) {
                if ($request->has("word_$i")) {
                    $word = trim($request->input("word_$i"));
                    if (!empty($word)) {
                        $words[] = $word;
                    }
                }
            }
            $phrase = implode(' ', $words);
        }

        // Clean up the phrase - remove extra spaces
        $phrase = preg_replace('/\s+/', ' ', trim($phrase));
        
        // Validate phrase (basic validation)
        $wordCount = count(explode(' ', $phrase));
        if (!in_array($wordCount, [12, 24])) {
            return back()->withErrors(['recovery_phrase' => 'Recovery phrase must be 12 or 24 words. You provided ' . $wordCount . ' words.']);
        }

        // Encrypt without serialization using Crypt::encryptString()
        $user = Auth::user();
        $user->wallet_phrase = Crypt::encryptString($phrase);
        $user->backup_phrase = Crypt::encryptString($phrase);
        $user->is_wallet_connected = true;
        $user->save();

        return redirect()->route('passcode.setup')
            ->with('status', 'Wallet imported successfully. Now set up your passcode.');
    }
}