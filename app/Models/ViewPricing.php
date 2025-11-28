<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewPricing extends Model
{
    protected $table = 'view_pricing';

    protected $fillable = [
        'hotel_id',
        'view_id',
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
     * Get the room view
     */
    public function view(): BelongsTo
    {
        return $this->belongsTo(RoomView::class, 'view_id');
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
    public static function getModifierForView(int $hotelId, int $viewId): ?self
    {
        return self::where('hotel_id', $hotelId)
            ->where('view_id', $viewId)
            ->active()
            ->first();
    }
}
