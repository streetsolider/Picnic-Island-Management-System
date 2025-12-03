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
        'transaction_type',
        'amount_mvr',
        'tickets_amount',
        'balance_before_mvr',
        'balance_after_mvr',
        'balance_before_tickets',
        'balance_after_tickets',
        'transaction_reference',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'amount_mvr' => 'decimal:2',
        'balance_before_mvr' => 'decimal:2',
        'balance_after_mvr' => 'decimal:2',
        'tickets_amount' => 'integer',
        'balance_before_tickets' => 'integer',
        'balance_after_tickets' => 'integer',
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
     * Generate a unique transaction reference (TPW-XXXXXXXX).
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'TPW-' . strtoupper(Str::random(8));
        } while (self::where('transaction_reference', $reference)->exists());

        return $reference;
    }
}
