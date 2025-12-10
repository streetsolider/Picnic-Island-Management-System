<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BeachServiceBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'guest_id',
        'beach_service_id',
        'hotel_booking_id',
        'booking_reference',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'price_per_unit',
        'total_price',
        'status',
        'payment_status',
        'redeemed_by_staff_id',
        'redeemed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'redeemed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'price_per_unit' => 'decimal:2',
            'total_price' => 'decimal:2',
            'duration_hours' => 'integer',
        ];
    }

    /**
     * Boot method - Auto-generate booking reference on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateBookingReference();
            }
        });
    }

    /**
     * Generate unique booking reference
     *
     * @return string Format: BSB-XXXXXXXX
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BSB-' . strtoupper(Str::random(8));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get the guest who made this booking
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the beach service for this booking
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(BeachService::class, 'beach_service_id');
    }

    /**
     * Get the hotel booking associated with this beach booking
     */
    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    /**
     * Get the staff member who redeemed this booking
     */
    public function redeemedByStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'redeemed_by_staff_id');
    }

    /**
     * Get the payment for this booking
     */
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is redeemed
     */
    public function isRedeemed(): bool
    {
        return $this->status === 'redeemed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if booking is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Redeem this booking
     *
     * @param int $staffId The ID of the staff member redeeming the booking
     * @return void
     */
    public function redeem(int $staffId): void
    {
        $this->update([
            'status' => 'redeemed',
            'redeemed_by_staff_id' => $staffId,
            'redeemed_at' => now(),
        ]);
    }

    /**
     * Cancel this booking
     *
     * @param string|null $reason Optional cancellation reason
     * @return void
     */
    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Scope to filter confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to filter bookings for a specific date
     */
    public function scopeForDate($query, string $date)
    {
        return $query->where('booking_date', $date);
    }

    /**
     * Scope to filter upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now()->toDateString())
                     ->whereIn('status', ['confirmed', 'redeemed']);
    }

    /**
     * Scope to filter past bookings
     */
    public function scopePast($query)
    {
        return $query->where('booking_date', '<', now()->toDateString())
                     ->whereIn('status', ['confirmed', 'redeemed']);
    }
}
