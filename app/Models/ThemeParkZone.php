<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeParkZone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'zone_type',
        'description',
        'capacity_limit',
        'opening_time',
        'closing_time',
        'assigned_staff_id',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity_limit' => 'integer',
        ];
    }

    /**
     * Get the staff member assigned to this zone
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    /**
     * Scope to filter only active zones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get operating hours as a formatted string
     */
    public function getOperatingHoursAttribute(): string
    {
        if ($this->opening_time && $this->closing_time) {
            return date('g:i A', strtotime($this->opening_time)) . ' - ' . date('g:i A', strtotime($this->closing_time));
        }
        return 'Not set';
    }
}
