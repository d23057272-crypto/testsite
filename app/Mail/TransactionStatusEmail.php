<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    protected $status;
    protected $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction, string $status, ?string $reason = null)
    {
        $this->transaction = $transaction;
        $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->getSubject())
                    ->view('emails.transaction-status')
                    ->with([
                        'transaction' => $this->transaction,
                        'status' => $this->status,
                        'reason' => $this->reason,
                        'type' => $this->transaction->type,
                        'amount' => $this->getAmount(),
                        'crypto' => $this->getCrypto(),
                        'fee' => $this->getFee(),
                        'hash' => $this->transaction->transaction_hash,
                        'companyName' => setting('company_name', config('app.name')),
                        'supportEmail' => setting('company_email', 'support@example.com')
                    ]);
    }

    /**
     * Get email subject based on transaction type and status
     */
    private function getSubject(): string
    {
        $type = ucfirst($this->transaction->type);
        $crypto = strtoupper($this->getCrypto());
        $amount = $this->getAmount();

        return match($this->status) {
            'completed' => "✅ Transaction Completed: {$type} {$amount} {$crypto}",
            'pending' => "⏳ Transaction Pending: {$type} {$amount} {$crypto}",
            'failed' => "❌ Transaction Failed: {$type} {$amount} {$crypto}",
            default => "Transaction Status Update: {$type} {$amount} {$crypto}"
        };
    }

    /**
     * Get the primary transaction amount (always non-null float)
     */
    private function getAmount(): float
    {
        return $this->transaction->amount_in ?? 0.0;
    }

    /**
     * Get the cryptocurrency symbol
     */
    private function getCrypto(): string
    {
        return strtoupper($this->transaction->which_crypto ?? 'CRYPTO');
    }

    /**
     * Get network fee as a non-null float
     */
    private function getFee(): float
    {
        if ($this->transaction->network_fee !== null) {
            return $this->transaction->network_fee;
        }
        if (isset($this->transaction->metadata['network_fee'])) {
            return (float) $this->transaction->metadata['network_fee'];
        }
        return 0.0;
    }
}