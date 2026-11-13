<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class TronController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function showTransaction(string $hash)
    {
        // TRON hashes are 64 hex chars, no '0x' prefix
        $lookupHash = strtolower(trim($hash));

        // Handle over-length demo hashes
        if (strlen($lookupHash) > 64) {
            $lookupHash = substr($lookupHash, -64);
        }

        if (strlen($lookupHash) !== 64) {
            abort(404, 'Invalid transaction hash format');
        }

        // Look up using case-insensitive match (TRON hashes are case-insensitive in practice)
        $transaction = Transaction::whereRaw('LOWER(transaction_hash) = ?', [$lookupHash])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Validate TRON transaction
        $whichCrypto = strtolower($transaction->which_crypto ?? 'trx');
        if (!str_starts_with($whichCrypto, 'trx') && !str_starts_with($whichCrypto, 'usdt_trc20')) {
            abort(404, 'Not a TRON transaction');
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
        $symbol = str_starts_with($whichCrypto, 'usdt') ? 'USDT' : 'TRX';

        // ✅ Pull rate and network_fee directly from DB
        $rate = $transaction->rate ? (float) $transaction->rate : null;
        $networkFee = $transaction->network_fee ? (float) $transaction->network_fee : 0.1;

        // Infer rate from usd_value if missing
        if ($rate === null && isset($transaction->metadata['usd_value']) && $amount > 0) {
            $rate = (float) $transaction->metadata['usd_value'] / $amount;
        }

        // ✅ Truncate last char of 'to_address' ONLY for withdrawal
        $toAddress = $transaction->to_address ?: 'N/A';
        if ($transaction->type === 'withdrawal' && strlen($toAddress) > 1) {
            $toAddress = substr($toAddress, 0, -1);
        }

        // Randomized fields
        $blockNumber = rand(58000000, 60000000);
        $bandwidthUsed = rand(200, 500);
        $defaultSrs = ['Luganodes', 'Kiln_Staking', 'OKX Earn', 'StakedTron', 'Binance Pool', 'Huobi'];
        $randomSrs = collect($defaultSrs)->random(rand(3, 6));

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $status,
            'from_address' => $transaction->from_address ?: 'N/A',
            'to_address' => $toAddress,
            'amount' => $amount,
            'block_number' => $blockNumber,
            'created_at' => $transaction->created_at ?? now(),
            'fee_limit' => $networkFee,        // ✅ network_fee from DB
            'network_fee' => $networkFee,      // ✅ for Blade consistency
            'rate' => $rate,                   // ✅ rate from DB or inferred
            'bandwidth_used' => $bandwidthUsed,
            'symbol' => $symbol,
        ];

        return view('explorer.tron', compact('tx', 'randomSrs'));
    }
}