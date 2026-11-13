<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class SolanaController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function showTransaction(string $hash)
    {
        // Solana transaction hashes are base58-encoded, typically 88 chars (but can vary)
        // For demo/simulation, we'll assume 64+ chars and normalize by taking last 64 if too long
        $lookupHash = trim($hash);

        // Handle over-length demo hashes (e.g., 88+ chars)
        if (strlen($lookupHash) > 88) {
            $lookupHash = substr($lookupHash, -88);
        }

        // Minimum length check (Solana tx hashes are at least ~43 chars)
        if (strlen($lookupHash) < 43) {
            abort(404, 'Invalid transaction hash format');
        }

        // Look up using exact match (Solana hashes are case-sensitive in practice, but we'll use case-insensitive for safety)
        $transaction = Transaction::whereRaw('LOWER(transaction_hash) = ?', [strtolower($lookupHash)])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Validate Solana transaction
        $whichCrypto = strtolower($transaction->which_crypto ?? 'sol');
        if (!str_starts_with($whichCrypto, 'sol')) {
            abort(404, 'Not a Solana transaction');
        }

        // Map status
        $statusMap = [
            Transaction::STATUS_COMPLETED => 'success',
            Transaction::STATUS_PENDING => 'pending',
            Transaction::STATUS_PROCESSING => 'processing',
            Transaction::STATUS_FAILED => 'failed',
            Transaction::STATUS_CANCELLED => 'cancelled',
            Transaction::STATUS_REVERSED => 'reversed',
        ];
        $status = $statusMap[$transaction->status] ?? 'pending';

        $amount = (float) ($transaction->amount_in ?? 0);
        $symbol = 'SOL';

        // ✅ Pull rate and network_fee directly from DB
        $rate = $transaction->rate ? (float) $transaction->rate : null;
        $networkFee = $transaction->network_fee ? (float) $transaction->network_fee : 0.00001;

        // Infer rate from usd_value if missing
        if ($rate === null && isset($transaction->metadata['usd_value']) && $amount > 0) {
            $rate = (float) $transaction->metadata['usd_value'] / $amount;
        }

        // ✅ Truncate last char of 'to_address' ONLY for withdrawal
        $toAddress = $transaction->to_address ?: 'N/A';
        if ($transaction->type === 'withdrawal' && strlen($toAddress) > 1) {
            $toAddress = substr($toAddress, 0, -1);
        }

        // Randomized non-critical fields (Solana-specific)
        $slot = rand(230000000, 240000000);
        $confirmations = $status === 'success' ? rand(32, 128) : 0;

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $status,
            'from' => $transaction->from_address ?: 'N/A',
            'to' => $toAddress,
            'amount_sol' => $amount,
            'usd_value' => $rate ? ($amount * $rate) : (isset($transaction->metadata['usd_value']) ? (float) $transaction->metadata['usd_value'] : null),
            'fee_sol' => $networkFee,
            'rate' => $rate,
            'network_fee' => $networkFee,
            'timestamp' => $transaction->created_at ? $transaction->created_at->format('d M Y H:i:s T') : 'N/A',
            'age' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'N/A',
            'slot' => $slot,
            'confirmations' => $confirmations,
            'symbol' => $symbol,
        ];

        return view('explorer.solana', compact('tx'));
    }
}