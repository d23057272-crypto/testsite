<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AccountDeletionService
{
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $userId = $user->getKey();

            $this->anonymizeTransactions($userId);
            $this->anonymizeCardTransactions($userId);
            $this->deleteSupportRecords($userId);
            $this->deleteUserRecords($user);

            User::query()
                ->where('referred_by', $userId)
                ->update(['referred_by' => null]);

            $deleted = DB::table('users')
                ->where('id', $userId)
                ->delete();

            if ($deleted !== 1) {
                throw new RuntimeException(
                    'The account could not be deleted.'
                );
            }
        });
    }

    private function anonymizeTransactions(int|string $userId): void
    {
        if (
            !Schema::hasTable('transactions') ||
            !Schema::hasColumn('transactions', 'user_id')
        ) {
            return;
        }

        $personalFields = [
            'user_id' => null,
            'cryptoasset_id' => null,
            'transaction_hash' => null,
            'from_address' => null,
            'to_address' => null,
            'metadata' => null,
        ];
        $updates = array_filter(
            $personalFields,
            fn ($value, $column): bool => Schema::hasColumn(
                'transactions',
                $column
            ),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('transactions')
            ->where('user_id', $userId)
            ->update($updates);
    }

    private function anonymizeCardTransactions(int|string $userId): void
    {
        if (
            !Schema::hasTable('card_transactions') ||
            !Schema::hasTable('cards') ||
            !Schema::hasColumn('card_transactions', 'card_id')
        ) {
            return;
        }

        $cardIds = DB::table('cards')
            ->where('user_id', $userId)
            ->pluck('id');

        if ($cardIds->isEmpty()) {
            return;
        }

        $updates = ['card_id' => null];

        if (Schema::hasColumn('card_transactions', 'description')) {
            $updates['description'] = null;
        }

        DB::table('card_transactions')
            ->whereIn('card_id', $cardIds)
            ->update($updates);
    }

    private function deleteSupportRecords(int|string $userId): void
    {
        if (Schema::hasTable('tickets')) {
            $ticketIds = DB::table('tickets')
                ->where('user_id', $userId)
                ->pluck('id');

            if (Schema::hasTable('ticket_replies')) {
                $replies = DB::table('ticket_replies')
                    ->where('user_id', $userId)
                    ->orWhereIn('ticket_id', $ticketIds);

                if (Schema::hasColumn('ticket_replies', 'attachment')) {
                    $attachments = $replies->pluck('attachment')
                        ->filter()
                        ->all();

                    if (
                        $attachments !== [] &&
                        !Storage::disk('private')->delete($attachments)
                    ) {
                        throw new RuntimeException(
                            'Support attachments could not be deleted.'
                        );
                    }
                }

                $replies->delete();
            }

            DB::table('tickets')->where('user_id', $userId)->delete();
        }
    }

    private function deleteUserRecords(User $user): void
    {
        $userId = $user->getKey();
        $records = [
            'notifications',
            'user_crypto_balances',
            'crypto_assets',
            'bot_subscriptions',
            'sessions',
        ];

        foreach ($records as $table) {
            if (
                Schema::hasTable($table) &&
                Schema::hasColumn($table, 'user_id')
            ) {
                DB::table($table)->where('user_id', $userId)->delete();
            }
        }

        if (Schema::hasTable('cards')) {
            DB::table('cards')->where('user_id', $userId)->delete();
        }

        $passwordResetTable = config(
            'auth.passwords.users.table',
            'password_reset_tokens'
        );

        if (
            Schema::hasTable($passwordResetTable) &&
            Schema::hasColumn($passwordResetTable, 'email')
        ) {
            DB::table($passwordResetTable)
                ->where('email', $user->email)
                ->delete();
        }
    }
}
