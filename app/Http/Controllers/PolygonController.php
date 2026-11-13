<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function showTransaction(string $hash)
    {
        // Normalize and look up transaction
        $lookupHash = strtolower(trim($hash));
        if (str_starts_with($lookupHash, '0x')) {
            $lookupHash = substr($lookupHash, 2);
        }

        if (strlen($lookupHash) !== 64 || !ctype_xdigit($lookupHash)) {
            abort(404, 'Invalid transaction hash format');
        }

        $transaction = Transaction::whereRaw('LOWER(REPLACE(transaction_hash, "0x", "")) = ?', [$lookupHash])
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        // Ensure it's a Polygon transaction
        $whichCrypto = strtolower($transaction->which_crypto ?? '');
        if (!str_contains($whichCrypto, 'matic') && !str_contains($whichCrypto, 'polygon')) {
            abort(404, 'Not a Polygon transaction');
        }

        // Fetch MATIC price (optional)
        $maticPrice = 0.65; // fallback
        try {
            $prices = $this->cryptoService->getPrices();
            $maticPrice = $prices['matic-network']['usd'] ?? $maticPrice;
        } catch (\Exception $e) {
            \Log::warning('Failed to fetch MATIC price: ' . $e->getMessage());
        }

        // Extract amount and fee
        $amount = (float) ($transaction->amount_in ?? 0);
        $fee = (float) ($transaction->network_fee ?? 0.0001);
        $timestamp = $transaction->created_at;
        $isCompleted = in_array($transaction->status, ['completed', 'success']);

        // Determine token
        $parts = explode('_', $whichCrypto, 2);
        $symbol = strtoupper($parts[0] === 'polygon' ? 'MATIC' : $parts[0]);
        $isMatic = ($symbol === 'MATIC');

        // USD value logic
        if (isset($transaction->metadata['usd_value'])) {
            $usdValue = (float) $transaction->metadata['usd_value'];
        } else {
            $rate = (float) ($transaction->rate ?? $maticPrice);
            $usdValue = $amount * $rate;
        }

        // Fee in USD
        $feeUsd = $fee * (float) ($transaction->rate ?? $maticPrice);

        // Token name mapping
        $tokenName = match ($symbol) {
            'USDT' => 'Tether USD (Polygon)',
            'USDC' => 'USD Coin (Polygon)',
            'MATIC' => 'Polygon',
            'WETH' => 'Wrapped Ethereum (Polygon)',
            'WBTC' => 'Wrapped Bitcoin (Polygon)',
            default => $symbol,
        };

        $tx = (object) [
            'hash' => $transaction->transaction_hash,
            'status' => $isCompleted ? 'success' : 'failed',
            'from' => $transaction->from_address ?: '0x0000000000000000000000000000000000000000',
            'to' => $transaction->to_address,
            'block_number' => rand(50_000_000, 60_000_000),
            'confirmations' => $isCompleted ? rand(100, 500) : 0,
            'value_matic' => $isMatic ? $amount : 0,
            'value_usd' => $isMatic ? $usdValue : 0,
            'fee_matic' => $fee,
            'fee_usd' => $feeUsd,
            'gas_price_gwei' => $fee > 0 ? round(($fee * 1e9) / 100000, 3) : 30,
            'token_name' => $tokenName,
            'token_symbol' => $symbol,
            'token_amount' => $amount,
            'usd_value' => $usdValue,
            'nonce' => $transaction->id % 1000000,
            'position' => rand(1, 5000),
            'timestamp' => $timestamp?->format('d M Y H:i:s T') ?? 'N/A',
            'age' => $timestamp?->diffForHumans() ?? 'N/A',
        ];

        return view('explorer.polygon', compact('tx', 'maticPrice'));
    }
}