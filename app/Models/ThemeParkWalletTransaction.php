<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ThemeParkWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_ticket_id',
        'transaction_type',
        'amount_mvr',
        'credits_amount',
        'balance_before_mvr',
        'balance_after_mvr',
        'balance_before_credits',
        'balance_after_credits',
        'transaction_reference',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'amount_mvr' => 'decimal:2',
        'balance_before_mvr' => 'decimal:2',
        'balance_after_mvr' => 'decimal:2',
        'credits_amount' => 'integer',
        'balance_before_credits' => 'integer',
        'balance_after_credits' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_reference)) {
                $transaction->transaction_reference = self::generateReference();
            }
        });
    }

    /**
     * Get the user that owns this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity ticket associated with this transaction (if any).
     */
    public function activityTicket(): BelongsTo
    {
        return $this->belongsTo(ThemeParkActivityTicket::class, 'activity_ticket_id');
    }

    /**
     * Check if this is a top-up transaction.
     */
    public function isTopUp(): bool
    {
        return $this->transaction_type === 'top_up';
    }

    /**
     * Check if this is a credit purchase transaction.
     */
    public function isCreditPurchase(): bool
    {
        return $this->transaction_type === 'credit_purchase' || $this->transaction_type === 'ticket_purchase';
    }

    /**
     * Check if this is an activity ticket purchase transaction.
     */
    public function isActivityTicketPurchase(): bool
    {
        return $this->transaction_type === 'activity_ticket_purchase';
    }

    /**
     * Generate a unique transaction reference (TPW-XXXXXXXX).
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'TPW-' . strtoupper(Str::random(8));
        } while (self::where('transaction_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Scope to get only top-up transactions.
     */
    public function scopeTopUps($query)
    {
        return $query->where('transaction_type', 'top_up');
    }

    /**
     * Scope to get only credit purchase transactions.
     */
    public function scopeCreditPurchases($query)
    {
        return $query->whereIn('transaction_type', ['ticket_purchase', 'credit_purchase']);
    }

    /**
     * Scope to get only activity ticket purchase transactions.
     */
    public function scopeActivityTicketPurchases($query)
    {
        return $query->where('transaction_type', 'activity_ticket_purchase');
    }
}
