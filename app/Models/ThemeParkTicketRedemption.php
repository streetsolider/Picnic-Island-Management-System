<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ThemeParkTicketRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'tickets_redeemed',
        'status',
        'validated_by',
        'validated_at',
        'redemption_reference',
        'cancellation_reason',
    ];

    protected $casts = [
        'tickets_redeemed' => 'integer',
        'validated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($redemption) {
            if (empty($redemption->redemption_reference)) {
                $redemption->redemption_reference = self::generateReference();
            }
        });
    }

    /**
     * Get the user (guest) that made this redemption.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'user_id');
    }

    /**
     * Get the activity being redeemed.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ThemeParkActivity::class, 'activity_id');
    }

    /**
     * Get the staff member who validated this redemption.
     */
    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Validate this redemption.
     */
    public function validate(int $staffId): void
    {
        $this->update([
            'status' => 'validated',
            'validated_by' => $staffId,
            'validated_at' => now(),
        ]);
    }

    /**
     * Cancel this redemption.
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Generate a unique redemption reference (TPR-XXXXXXXX).
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'TPR-' . strtoupper(Str::random(8));
        } while (self::where('redemption_reference', $reference)->exists());

        return $reference;
    }
}
