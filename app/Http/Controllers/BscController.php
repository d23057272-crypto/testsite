<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class BscController extends Controller
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

        // Validate BSC-compatible crypto (bnb, busd, usdt_bep20, etc.)
        $whichCrypto = strtolower($transaction->which_crypto ?? 'bnb');
        $validPrefixes = ['bnb', 'busd', 'usdt', 'usdc', 'xrp', 'xdc', 'dot', 'ltc', 'doge', 'dash', 'xlm', 'bch', 'eth', 'trx'];
        $isValidBsc = false;
        foreach ($validPrefixes as $prefix) {
            if (str_starts_with($whichCrypto, $prefix)) {
                $isValidBsc = true;
                break;
            }
        }

        if (!$isValidBsc) {
            abort(404, 'Not a BSC transaction');
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
        $symbol = strtoupper(explode('_', $whichCrypto, 2)[0]);

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

        // Randomized non-critical fields (BSC-specific)
        $blockNumber = rand(35000000, 37000000);
        $confirmations = $status === 'success' ? rand(40, 100) : 0;

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $status,
            'from' => $transaction->from_address ?: 'N/A',
            'to' => $toAddress,
            'amount' => $amount,
            'usd_value' => $rate ? ($amount * $rate) : (isset($transaction->metadata['usd_value']) ? (float) $transaction->metadata['usd_value'] : null),
            'fee_bnb' => $networkFee,
            'fee_usd' => $rate ? ($networkFee * $rate) : null,
            'rate' => $rate,
            'network_fee' => $networkFee,
            'timestamp' => $transaction->created_at ? $transaction->created_at->format('d M Y H:i:s T') : 'N/A',
            'age' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'N/A',
            'block_number' => $blockNumber,
            'confirmations' => $confirmations,
            'symbol' => $symbol,
            'coin' => $symbol,
        ];

        return view('explorer.bsc', compact('tx'));
    }
}