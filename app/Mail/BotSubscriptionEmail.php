<?php

namespace App\Mail;

use App\Models\BotSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BotSubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    /**
     * Create a new message instance.
     */
    public function __construct(BotSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🤖 Trading Bot Subscription Activated')
                    ->view('emails.bot')
                    ->with([
                        'subscription' => $this->subscription,
                        'bot' => $this->subscription->bot,
                        'amount' => $this->subscription->amount,
                        'trading_pair' => $this->subscription->bot->trading_pair ?? 'BTC/USDT',
                        'expires_at' => $this->subscription->expires_at,
                        'companyName' => setting('company_name', config('app.name')),
                        'supportEmail' => setting('company_email', 'support@example.com')
                    ]);
    }
}