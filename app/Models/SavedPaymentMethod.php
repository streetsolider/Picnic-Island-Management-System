<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SavedPaymentMethod extends Model
{
    protected $fillable = [
        'guest_id',
        'card_token',
        'bank',
        'card_type',
        'card_last_four',
        'card_expiry',
        'card_holder_name',
        'is_default',
        'last_used_at',
        'usage_count',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'last_used_at' => 'datetime',
        'usage_count' => 'integer',
    ];

    // Relationships
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper methods
    public function markAsUsed(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    public function setAsDefault(): void
    {
        // Unset other default cards for this guest
        self::where('guest_id', $this->guest_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    public function getDisplayName(): string
    {
        return "{$this->bank} {$this->card_type} •••• {$this->card_last_four}";
    }

    public function isExpired(): bool
    {
        $expiry = \Carbon\Carbon::createFromFormat('m/Y', $this->card_expiry)->endOfMonth();
        return $expiry->isPast();
    }

    // Generate card token (fake)
    public static function generateCardToken(): string
    {
        do {
            $token = 'TKN-' . strtoupper(Str::random(16));
        } while (self::where('card_token', $token)->exists());

        return $token;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($card) {
            if (empty($card->card_token)) {
                $card->card_token = self::generateCardToken();
            }
        });
    }
}
