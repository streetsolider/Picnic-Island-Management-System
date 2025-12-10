<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomTypePricing extends Model
{
    protected $table = 'room_type_pricing';

    protected $fillable = [
        'hotel_id',
        'room_type',
        'base_price',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
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
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->base_price, 2);
    }

    /**
     * Get price for a specific room type and hotel
     */
    public static function getPriceForRoomType(int $hotelId, string $roomType): ?float
    {
        $pricing = self::where('hotel_id', $hotelId)
            ->where('room_type', $roomType)
            ->active()
            ->first();

        return $pricing ? (float) $pricing->base_price : null;
    }
}
