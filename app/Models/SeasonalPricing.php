<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonalPricing extends Model
{
    protected $table = 'seasonal_pricing';

    protected $fillable = [
        'hotel_id',
        'season_name',
        'start_date',
        'end_date',
        'modifier_type',
        'modifier_value',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'modifier_value' => 'decimal:2',
        'is_active' => 'boolean',
        'priority' => 'integer',
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
     * Scope to get pricing for a specific date
     */
    public function scopeForDate($query, Carbon $date)
    {
        return $query->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date);
    }

    /**
     * Check if a date falls within this seasonal period
     */
    public function isDateInRange(Carbon $date): bool
    {
        return $date->between($this->start_date, $this->end_date);
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
     * Get seasonal pricing for a specific date and hotel
     */
    public static function getForDate(int $hotelId, Carbon $date): ?self
    {
        return self::where('hotel_id', $hotelId)
            ->active()
            ->forDate($date)
            ->orderByDesc('priority')
            ->first();
    }
}
