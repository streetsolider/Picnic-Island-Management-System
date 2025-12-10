<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewPricing extends Model
{
    protected $table = 'view_pricing';

    protected $fillable = [
        'hotel_id',
        'view',
        'modifier_type',
        'modifier_value',
        'is_active',
    ];

    protected $casts = [
        'modifier_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel that owns this pricing
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope to get only active pricing
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Apply the modifier to a base price
     */
    public function applyModifier(float $basePrice): float
    {
        if ($this->modifier_type === 'fixed') {
            return $basePrice + $this->modifier_value;
        }

        // Percentage
        return $basePrice * (1 + ($this->modifier_value / 100));
    }

    /**
     * Get modifier for a specific view and hotel
     */
    public static function getModifierForView(int $hotelId, string $view): ?self
    {
        return self::where('hotel_id', $hotelId)
            ->where('view', $view)
            ->active()
            ->first();
    }
}
