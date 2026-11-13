<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KycRejectedNotification extends Notification
{
    use Queueable;

    protected $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('KYC Verification Rejected')
            ->line('Your KYC verification has been rejected.')
            ->line('Reason: ' . $this->reason)
            ->line('Please update your documents and try again.')
            ->action('Submit KYC Again', url('/kyc'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'kyc_rejected',
            'message' => 'Your KYC verification has been rejected.',
            'reason' => $this->reason
        ];
    }
}