<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionProfit extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_subscription_id',
        'bot_trade_id',
        'amount',
        'profit'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'profit' => 'decimal:8'
    ];

    public function subscription()
    {
        return $this->belongsTo(BotSubscription::class, 'bot_subscription_id');
    }

    public function trade()
    {
        return $this->belongsTo(BotTrade::class, 'bot_trade_id');
    }
}