<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    protected $type;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->type = $transaction->type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $metadata = $this->transaction->metadata;
        $subject = $this->getSubject();
        $created_at = $this->transaction->created_at ? 
        \Carbon\Carbon::parse($this->transaction->created_at) : 
        null;

        return $this->subject($subject)
                    ->view('emails.transaction')  // Changed from markdown() to view()
                    ->with([
                        'type' => $this->type,
                        'amount' => $this->getAmount(),
                        'crypto' => $this->getCrypto(),
                        'network' => $this->getNetwork(),
                        'status' => $this->transaction->status,
                        'created_at' => $created_at,
                        'metadata' => $metadata,
                        'transaction_id' => $this->transaction->id,
                        'companyName' => setting('company_name', config('app.name')),
                        'supportEmail' => setting('company_email', 'support@example.com'),
                        // Swap specific data
                        'from_crypto' => strtoupper($this->transaction->from_crypto ?? ''),
                        'to_crypto' => strtoupper($this->transaction->to_crypto ?? ''),
                        'amount_in' => $this->transaction->amount_in,
                        'amount_out' => $this->transaction->amount_out,
                        'rate' => $this->transaction->rate,
                        'usd_value' => $metadata['usd_value'] ?? 0
                    ]);
    }

    /**
     * Get email subject based on transaction type
     */
    private function getSubject(): string
    {
        if ($this->type === Transaction::TYPE_SWAP) {
            return sprintf(
                "Crypto Swap: %s %s to %s %s",
                number_format($this->transaction->amount_in, 8),
                strtoupper($this->transaction->from_crypto),
                number_format($this->transaction->amount_out, 8),
                strtoupper($this->transaction->to_crypto)
            );
        }

        $amount = $this->getAmount();
        $crypto = $this->getCrypto();

        return match($this->type) {
            'deposit' => "Crypto Received: $amount $crypto",
            'withdrawal' => "Crypto Sent: $amount $crypto",
            default => "Crypto Transaction: $amount $crypto"
        };
    }

    private function getAmount()
    {
        return $this->type === 'deposit' 
            ? $this->transaction->amount_in 
            : $this->transaction->amount_out;
    }

    private function getCrypto()
    {
        return strtoupper($this->transaction->which_crypto);
    }

    private function getNetwork()
    {
        if (strpos($this->transaction->which_crypto, '_') !== false) {
            [, $network] = explode('_', $this->transaction->which_crypto);
            return strtoupper($network);
        }
        return null;
    }
}