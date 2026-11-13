<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KycApprovedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('KYC Verification Approved')
            ->line('Your KYC verification has been approved.')
            ->line('You now have full access to all platform features.')
            ->action('Go to Dashboard', url('/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'kyc_approved',
            'message' => 'Your KYC verification has been approved.'
        ];
    }
}