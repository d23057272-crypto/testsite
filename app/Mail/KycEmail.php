<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $type;
    public $documents;
    protected $status;
    protected $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $type, array $documents = [], ?string $status = null, ?string $rejectionReason = null)
    {
        $this->user = $user;
        $this->type = $type; // 'submission' or 'status_update'
        $this->documents = $documents;
        $this->status = $status;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->getEmailSubject();

        return $this->subject($subject)
                    ->view('emails.kyc')
                    ->with([
                        'type' => $this->type,
                        'user' => $this->user,
                        'documents' => $this->documents,
                        'status' => $this->status,
                        'rejectionReason' => $this->rejectionReason,
                        'companyName' => setting('company_name', config('app.name')),
                        'supportEmail' => setting('company_email', 'support@example.com')
                    ]);
    }

    /**
     * Get email subject based on type and status
     */
    private function getEmailSubject(): string
    {
        if ($this->type === 'submission') {
            return '📄 KYC Documents Received';
        }

        return match($this->status) {
            'approved' => '✅ KYC Verification Approved - Account Unlocked',
            'rejected' => '⚠️ KYC Verification Update Required',
            default => 'KYC Status Update'
        };
    }
}