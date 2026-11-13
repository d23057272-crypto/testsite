<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptoasset_id',
        'type',
        'which_crypto',
        'from_crypto',
        'to_crypto',
        'transaction_hash',
        'from_address',
        'to_address',
        'amount_in',
        'amount_out',
        'network_fee',
        'rate',
        'status',
        'metadata',
        'related_transaction_id',
        'processed_at',
    ];

    protected $casts = [
        'amount_in' => 'decimal:8',
        'amount_out' => 'decimal:8',
        'network_fee' => 'decimal:8',
        'rate' => 'decimal:8',
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'is_simulation' => 'boolean',
    ];

    // Transaction types

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_SWAP = 'swap';
    const TYPE_FUNDING = 'funding';
    const TYPE_REFUND = 'refund';

   

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REVERSED = 'reversed';

    /**
     * Get valid transaction types.
     */
    public static function getValidTypes(): array
    {
        return [
            self::TYPE_DEPOSIT,
            self::TYPE_WITHDRAWAL,
            self::TYPE_SWAP,
            self::TYPE_FUNDING,
            self::TYPE_REFUND,
        ];
    }

    /**
     * Get valid transaction statuses.
     */
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
            self::STATUS_REVERSED,
        ];
    }

    /**
     * Resolve the blockchain explorer URL.
     *
     * Uses the transaction's stored network first.
     * Falls back to metadata and known currency identifiers.
     *
     * Unknown networks return '#' rather than incorrectly
     * sending users to the Bitcoin explorer.
     */
    public function getExplorerUrl(): string
    {
        $hash = $this->transaction_hash;

        if (empty($hash)) {
            return '#';
        }

        $supportedNetworks = [
            'bitcoin',
            'ethereum',
            'tron',
            'bsc',
            'solana',
            'polygon',
        ];

        $network = strtolower(trim((string) $this->network));

        // First preference: explicitly stored transaction network.
        if (in_array($network, $supportedNetworks, true)) {
            return $this->buildExplorerUrl($network, $hash);
        }

        // Second preference: network stored in transaction metadata.
        $metadata = $this->metadata ?? [];

        $metadataNetwork = strtolower(trim(
            (string) ($metadata['network'] ?? '')
        ));

        $networkAliases = [
            'btc' => 'bitcoin',
            'bitcoin' => 'bitcoin',

            'eth' => 'ethereum',
            'ethereum' => 'ethereum',
            'erc20' => 'ethereum',

            'trx' => 'tron',
            'tron' => 'tron',
            'trc20' => 'tron',

            'bnb' => 'bsc',
            'bsc' => 'bsc',
            'bep20' => 'bsc',

            'sol' => 'solana',
            'solana' => 'solana',

            'matic' => 'polygon',
            'polygon' => 'polygon',
        ];

        if (isset($networkAliases[$metadataNetwork])) {
            return $this->buildExplorerUrl(
                $networkAliases[$metadataNetwork],
                $hash
            );
        }

        // Final fallback: identify the network from the currency key.
        $currency = strtolower(trim((string) $this->which_crypto));

        $currencyNetworks = [
            'btc' => 'bitcoin',
            'bitcoin' => 'bitcoin',

            'eth' => 'ethereum',
            'ethereum' => 'ethereum',

            'trx' => 'tron',
            'tron' => 'tron',

            'bnb' => 'bsc',
            'binancecoin' => 'bsc',

            'sol' => 'solana',
            'solana' => 'solana',

            'matic' => 'polygon',
            'polygon' => 'polygon',

            'usdt_trc20' => 'tron',
            'tether-trc20' => 'tron',

            'usdt_erc20' => 'ethereum',
            'tether-erc20' => 'ethereum',

            'usdt_bep20' => 'bsc',
            'tether-bep20' => 'bsc',
        ];

        if (!isset($currencyNetworks[$currency])) {
            return '#';
        }

        return $this->buildExplorerUrl(
            $currencyNetworks[$currency],
            $hash
        );
    }

    /**
     * Build the internal explorer URL.
     */
    private function buildExplorerUrl(
        string $network,
        string $hash
    ): string {
        return '/explorer/' . $network . '/tx/' . rawurlencode($hash);
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Legacy relationship.
     *
     * Keep this while existing transactions still reference
     * the crypto_assets table.
     */
    public function cryptoAsset()
    {
        return $this->belongsTo(
            CryptoAsset::class,
            'cryptoasset_id'
        );
    }

    public function relatedTransaction()
    {
        return $this->belongsTo(
            self::class,
            'related_transaction_id'
        );
    }

    // Query scopes

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOfCrypto($query, $crypto)
    {
        return $query->where('which_crypto', $crypto);
    }

    // Status helpers

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSwap(): bool
    {
        return $this->type === self::TYPE_SWAP;
    }

    public function isWithdrawal(): bool
    {
        return $this->type === self::TYPE_WITHDRAWAL;
    }

    public function isDeposit(): bool
    {
        return $this->type === self::TYPE_DEPOSIT;
    }

    public function isFunding(): bool
    {
        return $this->type === self::TYPE_FUNDING;
    }

    public function isRefund(): bool
    {
        return $this->type === self::TYPE_REFUND;
    }

    // Display accessors

    /**
     * Get the human-readable source currency label.
     */
    public function getFromCryptoLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SWAP => strtoupper(
                $this->from_crypto ?: 'N/A'
            ),

            self::TYPE_WITHDRAWAL,
            self::TYPE_REFUND => strtoupper(
                $this->which_crypto ?: 'CRYPTO'
            ),

            self::TYPE_DEPOSIT,
            self::TYPE_FUNDING => 'External Source',

            default => 'System',
        };
    }

    /**
     * Get the human-readable destination currency label.
     */
    public function getToCryptoLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SWAP => strtoupper(
                $this->to_crypto ?: 'N/A'
            ),

            self::TYPE_DEPOSIT,
            self::TYPE_FUNDING => strtoupper(
                $this->which_crypto ?: 'CRYPTO'
            ),

            self::TYPE_WITHDRAWAL => 'External Wallet',

            self::TYPE_REFUND => 'User Wallet',

            default => 'System',
        };
    }
}
