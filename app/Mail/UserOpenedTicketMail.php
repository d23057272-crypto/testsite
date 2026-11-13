<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserOpenedTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageContent;

    public function __construct(Ticket $ticket, $messageContent)
    {
        $this->ticket = $ticket->load('user');
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->subject('New Support Ticket: #' . $this->ticket->ticket_id)
            ->view('emails.ticket.user_opened');
    }
}