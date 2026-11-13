<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminRepliedTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket->load('user');
    }

    public function build()
    {
        return $this->subject('Your Ticket Has Been Replied')
            ->view('emails.ticket.admin_replied');
    }
}