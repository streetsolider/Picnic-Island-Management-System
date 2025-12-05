<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'opening_time',
        'closing_time',
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
            'opening_time' => 'datetime:H:i',
            'closing_time' => 'datetime:H:i',
        ];
    }

    /**
     * Get all activities in this zone
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ThemeParkActivity::class, 'theme_park_zone_id');
    }

    /**
     * Get continuous ride activities in this zone.
     */
    public function continuousRides(): HasMany
    {
        return $this->activities()->where('activity_type', 'continuous');
    }

    /**
     * Get scheduled show activities in this zone.
     */
    public function scheduledShows(): HasMany
    {
        return $this->activities()->where('activity_type', 'scheduled');
    }

    /**
     * Check if the zone is currently open (within operating hours).
     */
    public function isCurrentlyOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->opening_time || !$this->closing_time) {
            return true; // If no hours set, assume always open when active
        }

        $now = now()->format('H:i');
        return $now >= $this->opening_time && $now <= $this->closing_time;
    }

    /**
     * Get the operating hours range as a formatted string.
     */
    public function getOperatingHoursAttribute(): ?string
    {
        if (!$this->opening_time || !$this->closing_time) {
            return null;
        }

        return "{$this->opening_time} - {$this->closing_time}";
    }

    /**
     * Scope to filter only active zones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
