<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThemeParkActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_park_zone_id',
        'assigned_staff_id',
        'name',
        'description',
        'activity_type',
        'capacity',
        'credit_cost',
        'duration_minutes',
        'operating_hours_start',
        'operating_hours_end',
        'min_age',
        'max_age',
        'height_requirement_cm',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_cost' => 'integer',
        'capacity' => 'integer',
        'duration_minutes' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
        'height_requirement_cm' => 'integer',
        'operating_hours_start' => 'datetime:H:i',
        'operating_hours_end' => 'datetime:H:i',
    ];

    /**
     * Get the zone this activity belongs to.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ThemeParkZone::class, 'theme_park_zone_id');
    }

    /**
     * Get the staff member assigned to this activity.
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    /**
     * Get the show schedules for this activity (only for scheduled shows).
     */
    public function showSchedules(): HasMany
    {
        return $this->hasMany(ThemeParkShowSchedule::class, 'activity_id');
    }

    /**
     * Get the activity tickets for this activity.
     */
    public function activityTickets(): HasMany
    {
        return $this->hasMany(ThemeParkActivityTicket::class, 'activity_id');
    }

    /**
     * Check if this activity is a continuous ride.
     */
    public function isContinuous(): bool
    {
        return $this->activity_type === 'continuous';
    }

    /**
     * Check if this activity is a scheduled show.
     */
    public function isScheduled(): bool
    {
        return $this->activity_type === 'scheduled';
    }

    /**
     * Get the operating hours range as a formatted string.
     */
    public function getOperatingHoursAttribute(): ?string
    {
        if (!$this->operating_hours_start || !$this->operating_hours_end) {
            return null;
        }

        return "{$this->operating_hours_start} - {$this->operating_hours_end}";
    }

    /**
     * Check if the activity is currently open (within operating hours).
     */
    public function isCurrentlyOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->operating_hours_start || !$this->operating_hours_end) {
            // If no specific hours, use zone hours
            return $this->zone->isCurrentlyOpen();
        }

        $now = now()->format('H:i');
        return $now >= $this->operating_hours_start && $now <= $this->operating_hours_end;
    }
}
