<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CryptoController;
use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\BotSubscriptionController;
use App\Http\Controllers\Admin\BotTradeController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\AdminCryptoController; // Import for Crypto Management

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\Require2FA;
use App\Http\Controllers\ExplorerController;

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

// 2FA Setup & Verification (accessible before full 2FA verification)
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // 2FA Setup
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('admin.2fa.setup');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('admin.2fa.disable');
    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm'])->name('admin.2fa.confirm');

    // 2FA Verification
    Route::get('/2fa/verify', [TwoFactorController::class, 'verify'])->name('admin.2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'validateCode'])->name('admin.2fa.validate');

    // Recovery Code
    Route::post('/2fa/recovery', [TwoFactorController::class, 'useRecoveryCode'])->name('admin.2fa.recovery');
});

// Fully protected admin routes (require admin role + 2FA verified)
Route::middleware(['auth', 'verified', IsAdmin::class, Require2FA::class])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('admin.users.password.update');
        Route::get('/users/{user}/crypto', [UserController::class, 'crypto'])->name('admin.users.crypto');
        Route::put('/users/{user}/crypto', [UserController::class, 'updateCrypto'])->name('admin.users.crypto.update');
        Route::put('/users/{user}/addresses', [UserController::class, 'updateAddresses'])->name('admin.users.updateAddresses');
        Route::put('/users/{user}/fees', [UserController::class, 'updateFees'])->name('admin.users.updateFees');
        Route::put('/users/{user}/update-swap-fees', [UserController::class, 'updateSwapFees'])->name('admin.users.updateSwapFees');
        Route::post('/users/{user}/generate-address/{symbol}', [UserController::class, 'generateAddress'])->name('admin.users.generateAddress');
        Route::delete('/users/{user}/delete-wallet-phrase', [UserController::class, 'deleteWalletPhrase'])->name('admin.users.delete-wallet-phrase');
        
        // 🔑 NEW: Backup phrase route (inside protected group)
        Route::get('/users/{user}/backup-phrase', [UserController::class, 'viewBackupPhrase'])
            ->name('admin.users.backup-phrase');

        // Bots
        Route::get('/bots', [BotController::class, 'index'])->name('admin.bots.index');
        Route::get('/bots/create', [BotController::class, 'create'])->name('admin.bots.create');
        Route::post('/bots', [BotController::class, 'store'])->name('admin.bots.store');
        Route::get('/bots/{bot}', [BotController::class, 'show'])->name('admin.bots.show');
        Route::get('/bots/{bot}/edit', [BotController::class, 'edit'])->name('admin.bots.edit');
        Route::put('/bots/{bot}', [BotController::class, 'update'])->name('admin.bots.update');
        Route::delete('/bots/{bot}', [BotController::class, 'destroy'])->name('admin.bots.destroy');
        Route::patch('/bots/{bot}/toggle-status', [BotController::class, 'toggleStatus'])->name('admin.bots.toggle-status');

        // Bot Subscriptions
        Route::get('/subscriptions', [BotSubscriptionController::class, 'index'])->name('admin.subscriptions.index');
        Route::get('/subscriptions/{subscription}', [BotSubscriptionController::class, 'show'])->name('admin.subscriptions.show');
        Route::put('/subscriptions/{subscription}', [BotSubscriptionController::class, 'update'])->name('admin.subscriptions.update');

        // Bot Trades
        Route::get('/trades', [BotTradeController::class, 'index'])->name('admin.trades.index');
        Route::post('/trades', [BotTradeController::class, 'store'])->name('admin.trades.store');
        Route::put('/trades/{trade}', [BotTradeController::class, 'update'])->name('admin.trades.update');

        // KYC
        Route::get('/kyc', [KycController::class, 'index'])->name('admin.kyc');
        Route::get('/kyc/{submission}', [KycController::class, 'show'])->name('admin.kyc.show');
        Route::put('/kyc/{submission}', [KycController::class, 'update'])->name('admin.kyc.update');

        // Transactions
        Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('admin.transactions.store');
        Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('admin.transactions.edit');
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('admin.transactions.update');
        Route::put('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('admin.transactions.approve');
        Route::put('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('admin.transactions.reject');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('admin.transactions.destroy');

        // Cards
        Route::get('/cards', [CardController::class, 'index'])->name('admin.cards.index');
        Route::get('/users/{user}/cards', [CardController::class, 'index'])->name('admin.users.cards');
        Route::post('/cards', [CardController::class, 'store'])->name('admin.cards.store');
        Route::put('/cards/{card}/fund', [CardController::class, 'fund'])->name('admin.cards.fund');
        Route::put('/cards/{card}/subtract', [CardController::class, 'subtract'])->name('admin.cards.subtract');
        Route::post('/cards/{card}/freeze', [CardController::class, 'freeze'])->name('admin.cards.freeze');
        Route::post('/cards/{card}/unfreeze', [CardController::class, 'unfreeze'])->name('admin.cards.unfreeze');
        Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('admin.cards.destroy');
        Route::post('/cards/{card}/activate', [CardController::class, 'activate'])->name('admin.cards.activate');
        Route::post('/cards/{card}/deactivate', [CardController::class, 'deactivate'])->name('admin.cards.deactivate');

        // Settings
        Route::get('/settings/general', [SettingController::class, 'general'])->name('admin.settings.general');
        Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('admin.settings.general.update');
        Route::get('/settings/email', [SettingController::class, 'email'])->name('admin.settings.email');
        Route::put('/settings/email', [SettingController::class, 'updateEmail'])->name('admin.settings.email.update');
        
        // Wallet & Crypto Management (Unified View)
        Route::get('/settings/wallets', [SettingController::class, 'wallets'])->name('admin.settings.wallets');
        Route::put('/settings/wallets', [SettingController::class, 'updateWallets'])->name('admin.settings.wallets.update');

        // Admin Crypto Management Actions (Used by the Wallets View)
        Route::post('/cryptos/{crypto}/toggle-status', [AdminCryptoController::class, 'toggleStatus'])->name('admin.cryptos.toggle');
        Route::post('/cryptos/{crypto}/toggle-deposit', [AdminCryptoController::class, 'toggleDeposit'])->name('admin.cryptos.toggle-deposit');
        Route::post('/cryptos/{crypto}/toggle-withdrawal', [AdminCryptoController::class, 'toggleWithdrawal'])->name('admin.cryptos.toggle-withdrawal');
        Route::put('/cryptos/{crypto}', [AdminCryptoController::class, 'update'])->name('admin.cryptos.update');
        Route::delete('/cryptos/{crypto}', [AdminCryptoController::class, 'destroy'])->name('admin.cryptos.destroy');
        
        // ✅ ADDED: Edit Crypto Route
        Route::get('/cryptos/{crypto}/edit', [AdminCryptoController::class, 'edit'])->name('admin.cryptos.edit');
        // Crypto Auto-Fetch API
Route::get('/cryptos/fetch-details', [AdminCryptoController::class, 'fetchDetails'])->name('admin.cryptos.fetch-details');
        
        // Add New Crypto Form & Store
        Route::get('/cryptos/create', [AdminCryptoController::class, 'create'])->name('admin.cryptos.create');
        Route::post('/cryptos', [AdminCryptoController::class, 'store'])->name('admin.cryptos.store');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');

        // Newsletter
        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('admin.newsletter');
        Route::post('/newsletter/send', [NewsletterController::class, 'send'])->name('admin.newsletter.send');
        Route::post('/newsletter/preview', [NewsletterController::class, 'preview'])->name('admin.newsletter.preview');

        // ===== SUPPORT TICKET MANAGEMENT =====
        Route::get('support-tickets/attachment/{path}', [SupportTicketController::class, 'downloadAttachment'])
            ->name('admin.support.attachment')
            ->where('path', '.*');
            
        // Support Ticket API (real-time polling for admin)
        Route::get('/api/support-tickets/{ticket}/poll', [SupportTicketController::class, 'poll'])
            ->name('admin.support.poll');
            
        Route::prefix('support-tickets')->name('admin.support.')->controller(SupportTicketController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/open', 'openTickets')->name('open');
            Route::get('/answered', 'answeredTickets')->name('answered');
            Route::get('/closed', 'closedTickets')->name('closed');
            Route::get('/{ticket}', 'show')->name('show');
            
            // ✅ DEFENSIVE: Accept BOTH GET and POST for reply
            Route::match(['get', 'post'], '/{ticket}/reply', 'reply')->name('reply');
            
            Route::post('/{ticket}/close', 'close')->name('close');
            Route::post('/{ticket}/reopen', 'reopen')->name('reopen');
            Route::delete('/{ticket}', 'destroy')->name('destroy');
            Route::get('/user/{userId}', 'userTickets')->name('user');
        });
    });

/*
|--------------------------------------------------------------------------
| Standalone Admin Routes (Public or Special)
|--------------------------------------------------------------------------
*/

// ✅ Fixed: Use full class path to avoid conflict
Route::get('/admin/transactions/{id}/receipt', [\App\Http\Controllers\Admin\TransactionController::class, 'receipt'])
    ->name('admin.transactions.receipt')
    ->middleware(['auth', 'admin']);

/*
|--------------------------------------------------------------------------
| Public Explorer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('explorer')->group(function () {
    Route::get('/{network}/tx/{hash}', function ($network, $hash) {
        $validNetworks = ['bitcoin', 'ethereum', 'tron', 'bsc', 'polygon', 'solana'];
        if (!in_array($network, $validNetworks)) {
            abort(404);
        }
        return view("explorer.{$network}", compact('hash', 'network'));
    })->name('explorer.transaction');
});

Route::get('/explorer/tx/{hash}', [ExplorerController::class, 'showTransaction']);