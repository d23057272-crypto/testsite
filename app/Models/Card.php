<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CardTransaction;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'card_number',
        'card_holder',
        'crypto_asset_id', 
        'expiry_month',
        'expiry_year',
        'cvv',
        'billing_address',
        'zip_code',
        'status', // active, inactive, frozen
        'balance',
        'card_type', // virtual, physical
        'last_four',
        'is_default'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_default' => 'boolean'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(CardTransaction::class);
    }

    // Helper methods
    public function freeze()
    {
        $this->status = 'frozen';
        $this->save();
    }

    public function unfreeze()
    {
        $this->status = 'active';
        $this->save();
    }

    public function deactivate()
    {
        $this->status = 'inactive';
        $this->save();
    }

    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isFrozen()
    {
        return $this->status === 'frozen';
    }
    
    public function cryptoAsset()
    {
    return $this->belongsTo(CryptoAsset::class);
    }
}