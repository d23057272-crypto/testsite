<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class BitcoinController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

   public function showTransaction(string $hash)
    {
        // Accept ANY hash — no length/format restrictions
        $originalHash = trim($hash);
        if (empty($originalHash)) {
            abort(400, 'Invalid transaction hash');
        }

        // Flexible DB lookup: match with or without '0x', case-insensitive
        $clean = strtolower($originalHash);
        $with0x = str_starts_with($clean, '0x') ? $clean : '0x' . $clean;
        $without0x = str_starts_with($clean, '0x') ? substr($clean, 2) : $clean;

        $transaction = Transaction::whereRaw('LOWER(transaction_hash) = ?', [$with0x])
            ->orWhereRaw('LOWER(transaction_hash) = ?', [$without0x])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Validate Bitcoin transaction (btc or bch)
        $whichCrypto = strtolower($transaction->which_crypto ?? 'btc');
        if (!str_starts_with($whichCrypto, 'btc') && !str_starts_with($whichCrypto, 'bch')) {
            abort(404, 'Not a Bitcoin transaction');
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
        $symbol = str_starts_with($whichCrypto, 'bch') ? 'BCH' : 'BTC';

        // ✅ Pull rate and network_fee directly from DB
        $rate = $transaction->rate ? (float) $transaction->rate : null;
        $networkFee = $transaction->network_fee ? (float) $transaction->network_fee : 0.0001;

        // Infer rate from usd_value if missing
        if ($rate === null && isset($transaction->metadata['usd_value']) && $amount > 0) {
            $rate = (float) $transaction->metadata['usd_value'] / $amount;
        }

        // ✅ Truncate last char of 'to_address' ONLY for withdrawal
        $toAddress = $transaction->to_address ?: 'N/A';
        if ($transaction->type === 'withdrawal' && strlen($toAddress) > 1) {
            $toAddress = substr($toAddress, 0, -1);
        }

        // Randomized non-critical fields (Bitcoin-specific)
        $blockHeight = rand(800000, 900000);
        $confirmations = $status === 'success' ? rand(50, 200) : 0;
        $size = rand(180, 250);
        $weight = rand(800, 1000);

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $status,
            'from' => $transaction->from_address ?: 'N/A',
            'to' => $toAddress,
            'amount_btc' => $amount,
            'amount_usd' => $rate ? ($amount * $rate) : (isset($transaction->metadata['usd_value']) ? (float) $transaction->metadata['usd_value'] : null),
            'fee_btc' => $networkFee,
            'fee_usd' => $rate ? ($networkFee * $rate) : null,
            'rate' => $rate,
            'network_fee' => $networkFee,
            'timestamp' => $transaction->created_at ? $transaction->created_at->format('d M Y H:i:s T') : 'N/A',
            'age' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'N/A',
            'block_height' => $blockHeight,
            'confirmations' => $confirmations,
            'size' => $size,
            'weight' => $weight,
            'symbol' => $symbol,
        ];

        return view('explorer.bitcoin', compact('tx'));
    }
}