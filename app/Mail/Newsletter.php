<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class Newsletter extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $emailSubject;
    public $emailContent;
    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $subject, string $content, string $template = 'standard')
    {
        $this->user = $user;
        $this->emailSubject = $subject;
        $this->emailContent = $content;
        $this->template = $template;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $view = 'emails.newsletter.' . $this->template;

        return $this->subject($this->emailSubject)
                    ->view($view)
                    ->with([
                        'user' => $this->user,
                        'content' => $this->emailContent,
                        'subject' => $this->emailSubject,
                        'companyName' => setting('company_name', config('app.name')),
                        'companyEmail' => setting('company_email', 'support@example.com'),
                        'companyLogo' => setting('company_logo', '')
                    ]);
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 