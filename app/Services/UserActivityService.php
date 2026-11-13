<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserActivityService
{
    /**
     * Log user activity and notify the administrator.
     *
     * Email failures must never block a successful wallet operation.
     *
     * @param string $activity
     * @param User $user
     * @param array $data
     * @param string|null $description
     * @return bool
     */
    public static function log(
        string $activity,
        User $user,
        array $data = [],
        ?string $description = null
    ): bool {
        try {
            $adminEmail = setting('company_email');

            if (empty($adminEmail)) {
                Log::warning(
                    'User activity notification skipped: company_email is not configured.',
                    [
                        'activity' => $activity,
                        'user_id' => $user->id,
                    ]
                );

                return false;
            }

            $description = $description ?: self::getActivityDescription(
                $activity,
                $user,
                $data
            );

            $activityData = [
                'activity' => $activity,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => $data,
                'description' => $description,
            ];

            if (view()->exists('emails.user_activity')) {
                Mail::send(
                    'emails.user_activity',
                    ['activity' => $activityData],
                    function ($message) use ($adminEmail, $activity, $user) {
                        $message->to($adminEmail)
                            ->subject(
                                "User Activity: {$activity} - {$user->email}"
                            );
                    }
                );
            } else {
                Mail::raw(
                    $description,
                    function ($message) use ($adminEmail, $activity, $user) {
                        $message->to($adminEmail)
                            ->subject(
                                "User Activity: {$activity} - {$user->email}"
                            );
                    }
                );
            }

            Log::info('User activity notification sent.', $activityData);

            return true;

        } catch (Throwable $e) {
            Log::error('Failed to send user activity notification.', [
                'activity' => $activity,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Notification failures must not block wallet operations.
            return true;
        }
    }

    /**
     * Execute a database operation inside a transaction.
     *
     * The activity notification is sent only after the database
     * transaction has successfully committed.
     *
     * If the callback fails, the database transaction rolls back
     * and no notification is sent.
     *
     * @param string $activity
     * @param User $user
     * @param callable $callback
     * @param array $data
     * @param string|null $description
     * @return mixed
     */
    public static function logInTransaction(
        string $activity,
        User $user,
        callable $callback,
        array $data = [],
        ?string $description = null
    ) {
        $result = DB::transaction(function () use ($callback) {
            return $callback();
        });

        /*
         * This code executes only after DB::transaction()
         * has successfully committed.
         */

        try {
            if ($result instanceof Transaction) {
                $data['transaction_id'] = $result->id;
                $data['transaction_type'] = $result->type;
                $data['transaction_amount'] = $result->amount_in;
                $data['transaction_status'] = $result->status;
                $data['transaction_currency'] = $result->which_crypto;
            }

            self::log(
                $activity,
                $user,
                $data,
                $description
            );

        } catch (Throwable $e) {
            Log::error('Failed to process post-transaction user activity.', [
                'activity' => $activity,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    /**
     * Generate a human-readable activity description.
     *
     * @param string $activity
     * @param User $user
     * @param array $data
     * @return string
     */
    private static function getActivityDescription(
        string $activity,
        User $user,
        array $data
    ): string {
        switch ($activity) {
            case 'login':
                return "User {$user->email} logged in";

            case 'logout':
                return "User {$user->email} logged out";

            case 'register':
                return "New user {$user->email} registered";

            case 'deposit':
                $amount = $data['amount'] ?? 'unknown amount';
                $currency = $data['currency'] ?? 'unknown currency';

                return "User {$user->email} deposited {$amount} {$currency}";

            case 'withdraw':
                $amount = $data['amount'] ?? 'unknown amount';
                $currency = $data['currency'] ?? 'unknown currency';

                return "User {$user->email} withdrew {$amount} {$currency}";

            case 'swap':
                $fromAmount = $data['from_amount'] ?? 'unknown amount';
                $fromCurrency = $data['from_currency'] ?? 'unknown currency';
                $toAmount = $data['to_amount'] ?? 'unknown amount';
                $toCurrency = $data['to_currency'] ?? 'unknown currency';

                return "User {$user->email} swapped {$fromAmount} {$fromCurrency} to {$toAmount} {$toCurrency}";

            case 'profile_update':
                return "User {$user->email} updated their profile";

            case 'password_update':
                return "User {$user->email} updated their password";

            case 'kyc_submit':
                return "User {$user->email} submitted KYC documents";

            case 'bot_subscribe':
                $botName = $data['bot_name'] ?? 'unknown bot';
                $amount = $data['amount'] ?? 'unknown amount';

                return "User {$user->email} subscribed to {$botName} bot with {$amount}";

            default:
                return "User {$user->email} performed {$activity}";
        }
    }
}
