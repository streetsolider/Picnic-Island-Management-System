<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeParkActivitySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'schedule_date',
        'start_time',
        'end_time',
        'available_slots',
        'booked_slots',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'available_slots' => 'integer',
        'booked_slots' => 'integer',
    ];

    /**
     * Get the activity this schedule belongs to.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ThemeParkActivity::class, 'activity_id');
    }

    /**
     * Check if there are available slots.
     */
    public function hasAvailableSlots(): bool
    {
        return $this->booked_slots < $this->available_slots;
    }

    /**
     * Get the number of remaining slots.
     */
    public function getRemainingSlots(): int
    {
        return max(0, $this->available_slots - $this->booked_slots);
    }

    /**
     * Increment booked slots.
     */
    public function incrementBookedSlots(int $count = 1): void
    {
        $this->increment('booked_slots', $count);
    }

    /**
     * Decrement booked slots.
     */
    public function decrementBookedSlots(int $count = 1): void
    {
        $this->decrement('booked_slots', $count);
    }
}
