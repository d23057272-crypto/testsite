<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class EthereumController extends Controller
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

        // Validate Ethereum transaction
        $whichCrypto = strtolower($transaction->which_crypto ?? 'eth');
        if (!str_starts_with($whichCrypto, 'eth') && !str_starts_with($whichCrypto, 'usdt_erc20')) {
            abort(404, 'Not an Ethereum transaction');
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
        $symbol = str_starts_with($whichCrypto, 'usdt') ? 'USDT' : 'ETH';

        // ✅ Pull rate and network_fee directly from DB
        $rate = $transaction->rate ? (float) $transaction->rate : null;
        $networkFee = $transaction->network_fee ? (float) $transaction->network_fee : 0.002; // typical ETH fee fallback

        // Infer rate from usd_value if missing
        if ($rate === null && isset($transaction->metadata['usd_value']) && $amount > 0) {
            $rate = (float) $transaction->metadata['usd_value'] / $amount;
        }

        // ✅ Truncate last char of 'to_address' ONLY for withdrawal
        $toAddress = $transaction->to_address ?: 'N/A';
        if ($transaction->type === 'withdrawal' && strlen($toAddress) > 1) {
            $toAddress = substr($toAddress, 0, -1);
        }

        // Randomized fields (for UI realism)
        $blockNumber = rand(20000000, 22000000);
        $gasUsed = rand(21000, 200000); // ETH base tx = 21k gas
        $defaultSrs = ['Infura', 'Alchemy', 'QuickNode', 'Blockdaemon', 'Chainstack', 'Ankr'];
        $randomSrs = collect($defaultSrs)->random(rand(3, 6));

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $status,
            'from_address' => $transaction->from_address ?: 'N/A',
            'to_address' => $toAddress,
            'amount' => $amount,
            'block_number' => $blockNumber,
            'created_at' => $transaction->created_at ?? now(),
            'fee_limit' => $networkFee,
            'network_fee' => $networkFee,      // ✅ for Blade consistency
            'rate' => $rate,                   // ✅ rate from DB or inferred
            'gas_used' => $gasUsed,
            'symbol' => $symbol,
        ];

        return view('explorer.ethereum', compact('tx', 'randomSrs'));
    }
}