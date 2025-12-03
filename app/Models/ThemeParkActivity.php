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
        'ticket_cost',
        'capacity_per_session',
        'duration_minutes',
        'min_age',
        'max_age',
        'height_requirement_cm',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ticket_cost' => 'integer',
        'capacity_per_session' => 'integer',
        'duration_minutes' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
        'height_requirement_cm' => 'integer',
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
     * Get the schedules for this activity.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ThemeParkActivitySchedule::class, 'activity_id');
    }

    /**
     * Get the redemptions for this activity.
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(ThemeParkTicketRedemption::class, 'activity_id');
    }
}
