<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DurationDiscount extends Model
{
    protected $fillable = [
        'hotel_id',
        'discount_name',
        'minimum_nights',
        'maximum_nights',
        'discount_type',
        'discount_value',
        'is_active',
    ];

    protected $casts = [
        'minimum_nights' => 'integer',
        'maximum_nights' => 'integer',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel that owns this discount
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope to get only active discounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get discount for a specific number of nights
     */
    public function scopeForDuration($query, int $nights)
    {
        return $query->where('minimum_nights', '<=', $nights)
            ->where(function ($q) use ($nights) {
                $q->whereNull('maximum_nights')
                  ->orWhere('maximum_nights', '>=', $nights);
            });
    }

    /**
     * Check if this discount applies to a given number of nights
     */
    public function appliesTo(int $nights): bool
    {
        if ($nights < $this->minimum_nights) {
            return false;
        }

        if ($this->maximum_nights !== null && $nights > $this->maximum_nights) {
            return false;
        }

        return true;
    }

    /**
     * Apply the discount to a total price
     */
    public function applyDiscount(float $totalPrice): float
    {
        if ($this->discount_type === 'fixed') {
            return max(0, $totalPrice - $this->discount_value);
        }

        // Percentage
        return $totalPrice * (1 - ($this->discount_value / 100));
    }

    /**
     * Get discount for a specific duration and hotel
     */
    public static function getForDuration(int $hotelId, int $nights): ?self
    {
        return self::where('hotel_id', $hotelId)
            ->active()
            ->forDuration($nights)
            ->orderByDesc('discount_value')
            ->first();
    }
}
