<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCryptoBalance extends Model
{
    protected $table = 'user_crypto_balances';

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'balance',
        'is_enabled',
        'address',
    ];

    protected $casts = [
        'balance' => 'decimal:18',
        'is_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cryptocurrency(): BelongsTo
    {
        return $this->belongsTo(Cryptocurrency::class, 'cryptocurrency_id');
    }
}
