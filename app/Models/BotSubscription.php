<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'user_id',
        'amount',
        'current_profit',
        'total_profit',
        'status',
        'subscribed_at',
        'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'current_profit' => 'decimal:8',
        'total_profit' => 'decimal:8',
        'subscribed_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

	public function trades()
    {
        return $this->belongsToMany(BotTrade::class, 'subscription_profits')
            ->withPivot('profit_amount')
            ->withTimestamps();
    }

  
    public function latestTrade()
    {
        return $this->trades()->latest()->first();
    }

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profits()
    {
        return $this->hasMany(SubscriptionProfit::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }
}