<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThemeParkWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance_mvr',
        'credit_balance',
        'total_topped_up_mvr',
        'total_credits_purchased',
        'total_credits_redeemed',
    ];

    protected $casts = [
        'balance_mvr' => 'decimal:2',
        'total_topped_up_mvr' => 'decimal:2',
        'credit_balance' => 'integer',
        'total_credits_purchased' => 'integer',
        'total_credits_redeemed' => 'integer',
    ];

    /**
     * Get the user that owns this wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for this wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ThemeParkWalletTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Check if wallet has sufficient MVR balance.
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance_mvr >= $amount;
    }

    /**
     * Check if wallet has sufficient credits.
     */
    public function hasSufficientCredits(int $credits): bool
    {
        return $this->credit_balance >= $credits;
    }

    /**
     * Get or create wallet for a user.
     */
    public static function getOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'balance_mvr' => 0.00,
                'credit_balance' => 0,
                'total_topped_up_mvr' => 0.00,
                'total_credits_purchased' => 0,
                'total_credits_redeemed' => 0,
            ]
        );
    }
}
