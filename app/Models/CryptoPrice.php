<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    protected $fillable = ['cryptocurrency_id', 'price_usd', 'price_change_24h', 'fetched_at'];
    
    protected $casts = [
        'fetched_at' => 'datetime',
        'price_usd' => 'decimal:8',
    ];

    public function cryptocurrency() {
        return $this->belongsTo(Cryptocurrency::class);
    }
}