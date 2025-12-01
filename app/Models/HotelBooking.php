<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HotelBooking extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_id',
        'guest_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'number_of_rooms',
        'status',
        'total_price',
        'payment_status',
        'payment_method',
        'promo_code',
        'special_requests',
        'booking_reference',
        'cancelled_at',
        'cancellation_reason',
        'checked_in_at',
        'checked_in_by',
        'check_in_notes',
        'checked_out_at',
        'checked_out_by',
        'check_out_notes',
        'additional_charges',
        'room_condition',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // Relationships
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'checked_in_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'checked_out_by');
    }

    // Accessor for number of nights
    public function getNumberOfNightsAttribute(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'confirmed')
            ->where('check_in_date', '>=', now()->toDateString());
    }

    public function scopeCurrent($query)
    {
        return $query->where('status', 'confirmed')
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->whereIn('status', ['completed', 'no-show'])
            ->orWhere(function ($q) {
                $q->where('check_out_date', '<', now()->toDateString());
            });
    }

    // Helper methods
    public function cancel(string $reason = null): bool
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        return $this->save();
    }

    public function complete(): bool
    {
        $this->status = 'completed';
        return $this->save();
    }

    public function markAsNoShow(): bool
    {
        $this->status = 'no-show';
        return $this->save();
    }

    public function isActive(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function canCheckIn(): bool
    {
        return $this->status === 'confirmed' &&
               $this->check_in_date->isToday();
    }

    public function canCheckOut(): bool
    {
        return $this->status === 'checked_in';
    }

    // Operations methods
    public function checkIn(int $staffId, string $notes = null): bool
    {
        $this->checked_in_at = now();
        $this->checked_in_by = $staffId;
        $this->check_in_notes = $notes;
        $this->status = 'checked_in';
        return $this->save();
    }

    public function checkOut(int $staffId, ?string $notes = null): bool
    {
        $this->checked_out_at = now();
        $this->checked_out_by = $staffId;
        $this->check_out_notes = $notes;
        $this->status = 'checked_out';
        return $this->save();
    }

    // Generate unique booking reference
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK-' . strtoupper(Str::random(8));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    // Boot method to auto-generate booking reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateBookingReference();
            }
        });
    }
}
