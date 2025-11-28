<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DayTypePricing extends Model
{
    protected $table = 'day_type_pricing';

    protected $fillable = [
        'hotel_id',
        'day_type_name',
        'applicable_days',
        'modifier_type',
        'modifier_value',
        'is_active',
    ];

    protected $casts = [
        'applicable_days' => 'array',
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
     * Scope to get pricing for a specific day of week
     */
    public function scopeForDayOfWeek($query, int $dayNumber)
    {
        return $query->whereJsonContains('applicable_days', $dayNumber);
    }

    /**
     * Check if this pricing applies to a given date
     */
    public function appliesTo(Carbon $date): bool
    {
        // Carbon: 0 = Sunday, 6 = Saturday
        $dayOfWeek = $date->dayOfWeek;
        return in_array($dayOfWeek, $this->applicable_days ?? []);
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
     * Get day type pricing for a specific date and hotel
     */
    public static function getForDate(int $hotelId, Carbon $date): ?self
    {
        $dayOfWeek = $date->dayOfWeek;

        return self::where('hotel_id', $hotelId)
            ->active()
            ->forDayOfWeek($dayOfWeek)
            ->first();
    }
}
