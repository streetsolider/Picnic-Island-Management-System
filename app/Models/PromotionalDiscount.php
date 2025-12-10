<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionalDiscount extends Model
{
    protected $fillable = [
        'hotel_id',
        'promotion_name',
        'promotion_description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'minimum_rooms',
        'maximum_rooms',
        'minimum_nights',
        'maximum_nights',
        'booking_advance_days',
        'applicable_room_types',
        'promo_code',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'minimum_rooms' => 'integer',
        'maximum_rooms' => 'integer',
        'minimum_nights' => 'integer',
        'maximum_nights' => 'integer',
        'booking_advance_days' => 'integer',
        'applicable_room_types' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Get the hotel that owns this promotion
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope to get only active promotions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get promotions valid for a specific date
     */
    public function scopeValidForDate($query, Carbon $date)
    {
        return $query->where(function ($q) use ($date) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $date);
        });
    }

    /**
     * Scope to get promotions that match a promo code
     */
    public function scopeWithPromoCode($query, ?string $promoCode)
    {
        if (!$promoCode) {
            return $query->whereNull('promo_code');
        }

        return $query->where('promo_code', $promoCode);
    }

    /**
     * Check if this promotion is currently valid (date-wise)
     */
    public function isCurrentlyValid(): bool
    {
        $today = Carbon::today();

        if ($this->start_date && $today->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if this promotion applies to a specific booking
     *
     * @param int $numberOfRooms
     * @param int $numberOfNights
     * @param string $roomType
     * @param Carbon $checkInDate
     * @param int|null $bookingAdvanceDays How many days before check-in is the booking made
     * @return bool
     */
    public function appliesTo(
        int $numberOfRooms,
        int $numberOfNights,
        string $roomType,
        Carbon $checkInDate,
        ?int $bookingAdvanceDays = null
    ): bool {
        // Check if promotion is active
        if (!$this->is_active) {
            return false;
        }

        // Check date validity
        if (!$this->isCurrentlyValid()) {
            return false;
        }

        // Check minimum rooms
        if ($this->minimum_rooms !== null && $numberOfRooms < $this->minimum_rooms) {
            return false;
        }

        // Check maximum rooms
        if ($this->maximum_rooms !== null && $numberOfRooms > $this->maximum_rooms) {
            return false;
        }

        // Check minimum nights
        if ($this->minimum_nights !== null && $numberOfNights < $this->minimum_nights) {
            return false;
        }

        // Check maximum nights
        if ($this->maximum_nights !== null && $numberOfNights > $this->maximum_nights) {
            return false;
        }

        // Check room type restrictions
        if ($this->applicable_room_types !== null && !in_array($roomType, $this->applicable_room_types)) {
            return false;
        }

        // Check booking advance days (early bird)
        if ($this->booking_advance_days !== null && $bookingAdvanceDays !== null) {
            if ($bookingAdvanceDays < $this->booking_advance_days) {
                return false;
            }
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
     * Calculate the discount amount
     */
    public function calculateDiscountAmount(float $totalPrice): float
    {
        if ($this->discount_type === 'fixed') {
            return min($this->discount_value, $totalPrice);
        }

        // Percentage
        return $totalPrice * ($this->discount_value / 100);
    }

    /**
     * Get formatted discount display
     */
    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_type === 'fixed') {
            return 'MVR ' . number_format($this->discount_value, 2);
        }

        return number_format($this->discount_value, 0) . '% off';
    }

    /**
     * Get the best applicable promotion for a booking
     *
     * @param int $hotelId
     * @param int $numberOfRooms
     * @param int $numberOfNights
     * @param string $roomType
     * @param Carbon $checkInDate
     * @param string|null $promoCode
     * @param int|null $bookingAdvanceDays
     * @return self|null
     */
    public static function getBestForBooking(
        int $hotelId,
        int $numberOfRooms,
        int $numberOfNights,
        string $roomType,
        Carbon $checkInDate,
        ?string $promoCode = null,
        ?int $bookingAdvanceDays = null
    ): ?self {
        $query = self::where('hotel_id', $hotelId)
            ->active()
            ->validForDate(Carbon::today());

        // If promo code provided, only get promotions with that code or auto-apply promotions
        if ($promoCode) {
            $query->where(function ($q) use ($promoCode) {
                $q->where('promo_code', $promoCode)
                    ->orWhereNull('promo_code');
            });
        } else {
            // Only auto-apply promotions (no promo code required)
            $query->whereNull('promo_code');
        }

        $promotions = $query->orderByDesc('priority')
            ->orderByDesc('discount_value')
            ->get();

        // Find the first promotion that applies
        foreach ($promotions as $promotion) {
            if ($promotion->appliesTo($numberOfRooms, $numberOfNights, $roomType, $checkInDate, $bookingAdvanceDays)) {
                return $promotion;
            }
        }

        return null;
    }
}
