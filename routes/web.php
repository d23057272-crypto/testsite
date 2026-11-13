<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/user/web.php';

require __DIR__ . '/admin/web.php';

require __DIR__.'/auth.php';

Route::get('/cron/check-subscriptions', [CronController::class, 'checkSubscriptions'])
    ->name('cron.check-subscriptions');
Route::post('/verify-referral', [RegisteredUserController::class, 'verifyReferralCode'])
    ->name('referral.verify');
Route::get('/ref/{ref}', [RegisteredUserController::class, 'create'])
    ->name('referral.register');
Route::get('/admin/settings/wallets/sync', [SettingController::class, 'syncWalletAddresses'])
    ->name('admin.settings.wallets.sync');


