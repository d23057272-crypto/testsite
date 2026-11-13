<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'trading_pair',
        'action',
        'amount',
        'price',
        'profit',
    	'result',
        'metadata'
    ];

	 public function isWin(): bool
    {
        return $this->result === 'win';
    }

    public function isLoss(): bool
    {
        return $this->result === 'loss';
    }

    protected $casts = [
        'amount' => 'decimal:8',
        'price' => 'decimal:8',
        'profit' => 'decimal:8',
        'metadata' => 'array'
    ];

	public function subscriptions()
    {
        return $this->belongsToMany(BotSubscription::class, 'subscription_profits')
            ->withPivot('profit_amount')
            ->withTimestamps();
    }

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function subscriptionProfits()
    {
        return $this->hasMany(SubscriptionProfit::class);
    }
}
