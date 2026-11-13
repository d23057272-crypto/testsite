<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SwapController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\WithdrawController;
use App\Http\Controllers\User\BotController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\CryptoController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\BuyController;
use App\Http\Controllers\User\ReceiveController;
use App\Http\Controllers\User\SendController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\LanguageController;
use App\Http\Controllers\User\MarketController;
use App\Http\Controllers\User\CurrencyController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\User\SupportController;

// Explorer Controllers (chain-specific)
use App\Http\Controllers\EthereumController;
use App\Http\Controllers\BitcoinController;
use App\Http\Controllers\TronController;
use App\Http\Controllers\SolanaController;
use App\Http\Controllers\BscController;
use App\Http\Controllers\PolygonController;

// Livewire & Middleware
use App\Livewire\Auth\PasscodePage;
use App\Livewire\Auth\PasscodeSetup;
use App\Livewire\User\Dashboard;
use App\Http\Middleware\VerifyPasscode;
use App\Http\Controllers\BackupPhraseController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::get('/test-lang', function () {
    app()->setLocale('fr');
    return [
        'locale' => app()->getLocale(),
        'test1' => __('Settings'),
        'test2' => __('Manage Crypto'),
        'file_exists' => file_exists(resource_path('lang/fr.json')),
        'user_locale' => auth()->user()->locale ?? 'guest',
    ];
});

// 🔍 Chain-Specific Explorer Routes
Route::get('/explorer/ethereum/tx/{hash}', [EthereumController::class, 'showTransaction'])->name('explorer.ethereum.tx');
Route::get('/explorer/bitcoin/tx/{hash}', [BitcoinController::class, 'showTransaction'])->name('explorer.bitcoin.tx');
Route::get('/explorer/tron/tx/{hash}', [TronController::class, 'showTransaction'])->name('explorer.tron.tx');
Route::get('/explorer/solana/tx/{hash}', [SolanaController::class, 'showTransaction'])->name('explorer.solana.tx');
Route::get('/explorer/polygon/tx/{hash}', [PolygonController::class, 'showTransaction'])->name('explorer.polygon.tx');
Route::get('/explorer/bsc/tx/{hash}', [BscController::class, 'showTransaction'])->name('explorer.bsc.tx');

// 🔗 Short Aliases
Route::get('/ethereum/tx/{hash}', [EthereumController::class, 'showTransaction'])->name('ethereum.tx');
Route::get('/bitcoin/tx/{hash}', [BitcoinController::class, 'showTransaction'])->name('bitcoin.tx');
Route::get('/tron/tx/{hash}', [TronController::class, 'showTransaction'])->name('tron.tx');
Route::get('/solana/tx/{hash}', [SolanaController::class, 'showTransaction'])->name('solana.tx');
Route::get('/polygon/tx/{hash}', [PolygonController::class, 'showTransaction'])->name('polygon.tx');
Route::get('/bsc/tx/{hash}', [BscController::class, 'showTransaction'])->name('bsc.tx');


/*
|--------------------------------------------------------------------------
| Authenticated Routes - Email Verification OPTIONAL
| (Wallet Setup, Passcode Setup - Onboarding Flow)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Wallet Setup Routes
    Route::get('/wallet-option', [App\Http\Controllers\Auth\WalletSetupController::class, 'showOptions'])
        ->name('wallet.option');
    Route::get('/recovery-phrase', [App\Http\Controllers\Auth\WalletSetupController::class, 'showRecoveryPhrase'])
        ->name('recovery.phrase');
    Route::post('/recovery-phrase', [App\Http\Controllers\Auth\WalletSetupController::class, 'storeRecoveryPhrase'])
        ->name('recovery.phrase.store');
    Route::get('/import-wallet', [App\Http\Controllers\Auth\WalletSetupController::class, 'showImportWallet'])
        ->name('import.wallet');
    Route::post('/import-wallet', [App\Http\Controllers\Auth\WalletSetupController::class, 'storeImportedWallet'])
        ->name('import.wallet.store');

    // Passcode Setup & Verification - FIXED: 'passcode.page' not 'passcode.show'
    Route::get('/passcode', PasscodePage::class)->name('passcode.page');
    Route::get('/passcode/setup', PasscodeSetup::class)->name('passcode.setup');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes - Email Verification REQUIRED
| (Main Application Features - Respects Admin Setting)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\CheckEmailVerificationSetting::class])->group(function () {
    
    Route::get('/help-center', [HelpCenterController::class, 'create'])->name('help.center');
    Route::post('/help-center', [HelpCenterController::class, 'store'])->name('help.center.submit');

    // Password reset routes (for authenticated users)
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // ✅ Backup Phrase (requires email verification per admin setting)
    Route::get('/settings/backup-phrase', [BackupPhraseController::class, 'show'])->name('backup.phrase');
});


/*
|--------------------------------------------------------------------------
| Fully Authenticated + Passcode Verified Routes
| (Core App Features - Highest Security)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\CheckEmailVerificationSetting::class, VerifyPasscode::class])->group(function () {
    
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Referral
    Route::prefix('referral')->group(function () {
        Route::get('/', [ReferralController::class, 'index'])->name('referral.index');
        Route::get('/stats', [ReferralController::class, 'stats'])->name('referral.stats');
    });

    // Crypto
    Route::prefix('crypto')->group(function () {
        Route::get('/details/{symbol}/{network?}', [CryptoController::class, 'details'])->name('crypto.details');
        Route::get('/manage', [CryptoController::class, 'manage'])->name('crypto.manage');
        Route::post('/crypto/update', [CryptoController::class, 'updateManage'])->name('crypto.manage.update');
        Route::get('/address', [CryptoController::class, 'address'])->name('crypto.address');
    });

    // Buy
    Route::prefix('buy')->group(function () {
        Route::get('/', [BuyController::class, 'index'])->name('buy.index');
        Route::get('/details/{symbol}/{network?}', [BuyController::class, 'details'])->name('buy.details');
    });

    // Send
    Route::prefix('send')->group(function () {
        // PayID
        Route::get('/payid', [SendController::class, 'payidAssets'])->name('send.payid');
        Route::get('/payid/{symbol}/{network?}', [SendController::class, 'payidDetails'])->name('send.payid.details');
        Route::post('/payid/verify', [SendController::class, 'verifyPayId'])->name('send.payid.verify');
        Route::post('/send/payid/process/{symbol}/{network?}', [SendController::class, 'processPayId'])->name('send.payid.process');
        Route::get('/payid/{symbol}/{network?}/success/{transaction}', [SendController::class, 'success'])->name('send.payid.success');
        Route::get('/payid/{symbol}/{network?}/failed', [SendController::class, 'failed'])->name('send.payid.failed');

        // External
        Route::get('/external', [SendController::class, 'externalAssets'])->name('send.external');
        Route::get('/external/{symbol}/{network?}', [SendController::class, 'externalDetails'])->name('send.external.details');
        Route::post('/external/verify', [SendController::class, 'verifyExternal'])->name('send.external.verify');
        Route::post('/send/external/process/{symbol}/{network?}', [SendController::class, 'processExternal'])->name('send.external.process');
        Route::get('/external/{symbol}/{network?}/success/{transaction}', [SendController::class, 'externalSuccess'])->name('send.external.success');
        Route::get('/external/{symbol}/{network?}/failed', [SendController::class, 'externalFailed'])->name('send.external.failed');
    });

    // Receive
    Route::prefix('receive')->group(function () {
        Route::get('/payid', [ReceiveController::class, 'payidAssets'])->name('receive.payid');
        Route::get('/payid/{symbol}/{network?}', [ReceiveController::class, 'payidDetails'])->name('receive.payid.details');
        Route::get('/external', [ReceiveController::class, 'externalAssets'])->name('receive.external');
        Route::get('/external/{symbol}/{network?}', [ReceiveController::class, 'externalDetails'])->name('receive.external.details');
    });

    // Swap
    Route::get('/swap', [SwapController::class, 'index'])->name('swap');
    Route::post('/swap/process', [SwapController::class, 'swap'])->name('swap.process');

    // Deposit / Withdraw
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit');
    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw');

    // Card
    Route::prefix('card')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('card.index');
        Route::post('/request', [CardController::class, 'request'])->name('card.request');
        Route::get('/add-money', [CardController::class, 'addMoney'])->name('card.add-money');
        Route::post('/fund', [CardController::class, 'fund'])->name('card.fund');
        Route::post('/{card}/freeze', [CardController::class, 'freeze'])->name('card.freeze');
        Route::post('/{card}/unfreeze', [CardController::class, 'unfreeze'])->name('card.unfreeze');
        Route::delete('/{card}', [CardController::class, 'delete'])->name('card.delete');
    });

    // Bots
    Route::get('/bots', [BotController::class, 'index'])->name('bots');
    Route::get('/bots/show', [BotController::class, 'show'])->name('bots.show');
    Route::post('/bots/subscribe', [BotController::class, 'subscribe'])->name('bots.subscribe');
    Route::get('/bots/history', [BotController::class, 'history'])->name('bots.history');
    Route::get('/bots/trades/update', [BotController::class, 'updateTrades'])->name('bots.trades.update');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/currency', [CurrencyController::class, 'update'])
        ->name('currency.update');

    // Market data
    Route::get('/markets/{section?}', [MarketController::class, 'index'])
        ->defaults('section', 'trending')
        ->whereIn('section', [
            'trending',
            'top',
            'stonks-szn',
            'top-gainers',
            'pre-ipo',
            'meme',
            'defi',
            'perps',
        ])
        ->name('markets');

    // ✅ Language Settings
    Route::get('/settings/language', [LanguageController::class, 'edit'])->name('language.settings');
    Route::put('/settings/language', [LanguageController::class, 'update'])->name('language.update');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->notifications()->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

    // ===== SUPPORT TICKET ROUTES =====
    Route::get('support/attachment/{path}', [SupportController::class, 'downloadAttachment'])
        ->name('support.attachment')
        ->where('path', '.*');
        
        // Support Ticket API (real-time polling)
Route::get('/api/tickets/{ticketId}/poll', [SupportController::class, 'poll'])
    ->name('support.poll');

    // KYC
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc');
    Route::post('/kyc/upload', [KycController::class, 'uploadDocuments'])->name('user.kyc.upload');
    Route::get('/kyc/status', [KycController::class, 'getDocumentStatus'])->name('user.kyc.status');
    
    // ===== SUPPORT TICKET ROUTES =====
    Route::get('support', [SupportController::class, 'index'])->name('support');
    Route::get('support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('support', [SupportController::class, 'store'])->name('support.store');
    Route::get('support/{ticketId}', [SupportController::class, 'show'])->name('support.show');
    Route::post('support/{ticketId}/reply', [SupportController::class, 'reply'])->name('support.reply');
    Route::post('support/{ticketId}/close', [SupportController::class, 'close'])->name('support.close');
});