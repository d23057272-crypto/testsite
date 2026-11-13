<?php

namespace App\Policies;

use App\Models\Bot;
use App\Models\User;
use App\Models\BotSubscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class BotPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all abilities to admin users
     */
    public function before(User $user, string $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if user can see available bots
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view available bots
    }

    /**
     * Determine if the user can view a specific bot
     */
    public function view(User $user, Bot $bot): bool
    {
        // Users can view active bots or bots they're subscribed to
        return $bot->status === 'active' || 
               $user->botSubscriptions()->where('bot_id', $bot->id)->exists();
    }

    /**
     * Only admins can create bots
     */
    public function create(User $user): bool
    {
        return false; // Regular users cannot create bots
    }

    /**
     * Only admins can update bots
     */
    public function update(User $user, Bot $bot): bool
    {
        return false;
    }

    /**
     * Only admins can delete bots
     */
    public function delete(User $user, Bot $bot): bool
    {
        return false;
    }

    /**
     * Determine if user can subscribe to a bot
     */
    public function subscribe(User $user, Bot $bot): bool
    {
        // Check if bot is active
        if ($bot->status !== 'active') {
            return false;
        }

        // Check if user is already subscribed
        if ($user->botSubscriptions()
            ->where('bot_id', $bot->id)
            ->where('status', 'active')
            ->exists()) {
            return false;
        }

        // Check if user is verified and KYC approved
        if (!$user->hasVerifiedEmail() || !$user->isKycApproved()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if user can cancel their subscription
     */
    public function cancelSubscription(User $user, BotSubscription $subscription): bool
    {
        return $user->id === $subscription->user_id && 
               $subscription->status === 'active';
    }

    /**
     * Determine if the user can view bot's trading history
     */
    public function viewHistory(User $user, Bot $bot): bool
    {
        // Users can view history of bots they're subscribed to
        return $user->botSubscriptions()
            ->where('bot_id', $bot->id)
            ->exists();
    }

    /**
     * Determine if user can view their subscription profits
     */
    public function viewProfits(User $user, BotSubscription $subscription): bool
    {
        return $user->id === $subscription->user_id;
    }
}