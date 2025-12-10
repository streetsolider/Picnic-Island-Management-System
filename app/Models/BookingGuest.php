<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingGuest extends Model
{
    protected $fillable = [
        'booking_id',
        'guest_type',
        'full_name',
        'date_of_birth',
        'relationship_to_primary',
        'contact_phone',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the booking this guest belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class, 'booking_id');
    }

    /**
     * Check if this is the primary guest
     */
    public function isPrimary(): bool
    {
        return $this->guest_type === 'primary';
    }

    /**
     * Check if this is an additional guest
     */
    public function isAdditional(): bool
    {
        return $this->guest_type === 'additional';
    }

    /**
     * Get the guest's age
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }
}
