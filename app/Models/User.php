<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA as G2fa;
use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\UserCryptoBalance;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'passcode',
        'is_admin',
        'uuid',
        'kyc_status', 
        'front_id',   
        'back_id',    
        'proof_of_residence', 
        'require_kyc',
        'require_fee',
        'require_wallet_connect',
        'enable_crypto_sending',
        'enable_swap',
        'kyc_fee',
        'kyc_fee_amount',
        'swap_fee',
        'is_wallet_connected',
        'email_verified_at',
        'referral_code',
        'referred_by',
        'referral_count',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'backup_phrase',
        'locale',
        'preferred_currency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'passcode',
        'backup_phrase',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_admin' => false,
        'kyc_status' => 'not_submitted',
        'require_kyc' => true,
        'enable_swap' => false,
        'kyc_fee' => false,
        'kyc_fee_amount' => '0',
        'swap_fee' => false
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'passcode' => 'hashed',
            'is_admin' => 'boolean',
            'require_kyc' => 'boolean',
            'require_fee' => 'boolean',
            'require_wallet_connect' => 'boolean',
            'enable_crypto_sending' => 'boolean',
            'enable_swap' => 'boolean',
            'kyc_fee' => 'boolean',
            'swap_fee' => 'boolean',
            'is_wallet_connected' => 'boolean'
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = 'T' . strtoupper(Str::random(6));
            }
            if (empty($model->referral_code)) {
                $model->referral_code = 'REF' . strtoupper(Str::random(8));
            }
        });
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function getReferralLink(): string
    {
        return route('referral.register', ['ref' => $this->referral_code]);
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Check if the user has set up their passcode
     */
    public function hasPasscodeSetup(): bool
    {
        return !is_null($this->passcode);
    }

    /**
     * Set the user's passcode
     */
    public function setPasscode(string $passcode): void
    {
        $this->update(['passcode' => $passcode]);
    }

    /**
     * Get the user's crypto assets
     */
    public function cryptoAssets()
    {
        return $this->hasOne(CryptoAsset::class);
    }

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function botSubscriptions()
    {
        return $this->hasMany(BotSubscription::class);
    }
    
    /**
 * All cryptocurrency balances belonging to this user.
 */
public function cryptoBalances()
{
    return $this->hasMany(UserCryptoBalance::class, 'user_id');
}

/**
 * Get a user's balance record for a specific cryptocurrency.
 */
public function cryptoBalance(int $cryptocurrencyId): ?UserCryptoBalance
{
    return $this->cryptoBalances()
        ->where('cryptocurrency_id', $cryptocurrencyId)
        ->first();
}

/**
 * Get or create a balance record for a cryptocurrency.
 */
public function getOrCreateCryptoBalance(
    Cryptocurrency $cryptocurrency
): UserCryptoBalance {
    return $cryptocurrency->getOrCreateUserBalance($this);
}

    /**
     * Get all active bot subscriptions for the user
     */
    public function activeSubscriptions()
    {
        return $this->botSubscriptions()->where('status', 'active');
    }

    /**
     * Check if user has any active bot subscriptions
     */
    public function hasActiveSubscriptions(): bool
    {
        return $this->botSubscriptions()->where('status', 'active')->exists();
    }

    /**
     * Get total invested amount in active subscriptions
     */
    public function getTotalInvestedAmount(): float
    {
        return $this->activeSubscriptions()->sum('amount');
    }

    /**
     * Check if the user has any crypto assets
     */
    public function hasCryptoAssets(): bool
    {
        return !is_null($this->cryptoAssets);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getTotalBalanceUSD(): float
{
    if (!$this->hasCryptoAssets()) {
        return 0.0;
    }

    return $this->cryptoAssets->getTotalBalanceUSD();
}

    /**
     * Create crypto assets for user if they don't exist
     */
    public function createCryptoAssets(): CryptoAsset
    {
        if (!$this->hasCryptoAssets()) {
            $cryptoAsset = $this->cryptoAssets()->create();
            $cryptoAsset->generateAddressesIfNull();
            return $cryptoAsset;
        }
        $this->cryptoAssets->generateAddressesIfNull();
        return $this->cryptoAssets;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Get user's display ID (UUID)
     */
    public function getDisplayId(): string
    {
        return $this->uuid;
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope a query to only include regular users.
     */
    public function scopeRegular($query)
    {
        return $query->where('is_admin', false);
    }
    
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function defaultCard()
    {
        return $this->cards()->where('is_default', true)->first();
    }

    /**
     * Check KYC status
     */
    public function getKycStatus(): string
    {
        return $this->kyc_status;
    }

    /**
     * Update KYC status
     */
    public function updateKycStatus(string $status): void
    {
        $this->update(['kyc_status' => $status]);
    }

    /**
     * Check if KYC is required
     */
    public function requiresKyc(): bool
    {
        return $this->require_kyc;
    }

    /**
     * Check if KYC is approved
     */
    public function isKycApproved(): bool
    {
        return $this->kyc_status === 'approved';
    }

    /**
     * Update KYC documents
     */
    public function updateKycDocuments(array $documents): void
    {
        $this->update([
            'front_id' => $documents['front_id'] ?? null,
            'back_id' => $documents['back_id'] ?? null,
            'proof_of_residence' => $documents['proof_of_residence'] ?? null,
            'kyc_status' => 'pending'
        ]);
    }

    /**
     * Scope a query to only include users with pending KYC
     */
    public function scopePendingKyc($query)
    {
        return $query->where('kyc_status', 'pending');
    }
    
    public function scopeRejectedKyc($query)
    {
        return $query->where('kyc_status', 'rejected');
    }

    /**
     * Scope a query to only include users with approved KYC
     */
    public function scopeApprovedKyc($query)
    {
        return $query->where('kyc_status', 'approved');
    }

    public function has2faEnabled(): bool
    {
        return !is_null($this->two_factor_confirmed_at);
    }

    public function generateTwoFactorSecret(): void
    {
        $google2fa = new G2fa();
        $this->two_factor_secret = $google2fa->generateSecretKey();
        $this->save();
    }

    public function getTwoFactorQrCodeUrl(): string
    {
        $google2fa = new G2fa();
        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }

    public function generateRecoveryCodes(): void
    {
        $this->two_factor_recovery_codes = json_encode(collect(range(1, 8))->map(function () {
            return Str::random(10);
        })->all());
        $this->save();
    }

    public function getRecoveryCodes(): array
    {
        return json_decode($this->two_factor_recovery_codes, true) ?? [];
    }

    public function validateTwoFactorCode($code): bool
    {
        if (empty($this->two_factor_secret)) {
            return false;
        }

        $google2fa = new G2fa();
        return $google2fa->verifyKey($this->two_factor_secret, $code);
    }

    // ✅ FIXED BACKUP PHRASE METHODS

   public function generateBackupPhrase()
{
    if ($this->backup_phrase) {
        return $this->getDecryptedBackupPhrase();
    }

    $wordlistPath = base_path('resources/wordlists/english.txt');

    if (!file_exists($wordlistPath)) {
        \Log::error('BIP-39 wordlist missing at: ' . $wordlistPath);
        throw new \RuntimeException('BIP-39 wordlist not found. Cannot generate secure backup phrase.');
    }

    $content = file_get_contents($wordlistPath);
    $words = array_values(array_filter(array_map('trim', explode("\n", $content))));

    if (count($words) !== 2048) {
        \Log::error('Invalid BIP-39 wordlist: expected 2048 words, got ' . count($words));
        throw new \RuntimeException('Invalid BIP-39 wordlist. Expected 2048 words.');
    }

    // Generate 12-word mnemonic (128 bits of entropy)
    $phrase = [];
    for ($i = 0; $i < 12; $i++) {
        $phrase[] = $words[random_int(0, 2047)]; // Use cryptographically secure random
    }

    $phraseStr = implode(' ', $phrase);

    // Encrypt and save
    $encrypted = Crypt::encryptString($phraseStr);
    $this->backup_phrase = $encrypted;
    $this->save();

    // Verify round-trip
    try {
        $decrypted = Crypt::decryptString($this->backup_phrase);
        if ($decrypted !== $phraseStr) {
            throw new \RuntimeException('Encryption round-trip failed.');
        }
    } catch (\Exception $e) {
        \Log::error('Backup phrase encryption verification failed for user ID: ' . $this->id, [
            'error' => $e->getMessage()
        ]);
        $this->backup_phrase = null;
        $this->save();
        return null;
    }

    return $phraseStr;
}

    public function getDecryptedBackupPhrase()
    {
        if (!$this->backup_phrase) {
            return null;
        }
        try {
            return Crypt::decryptString($this->backup_phrase);
        } catch (\Exception $e) {
            \Log::error('Backup phrase decryption failed for user ID: ' . $this->id, [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}