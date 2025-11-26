<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeachService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'service_type',  // Excursions, Water Sports, Beach Sports, Beach Huts
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
     * Service types available
     */
    public const SERVICE_TYPES = [
        'Excursions',
        'Water Sports',
        'Beach Sports',
        'Beach Huts',
    ];

    /**
     * Get the staff member assigned to this beach service
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get all activities for this beach service
     */
    public function activities(): HasMany
    {
        return $this->hasMany(BeachActivity::class, 'beach_service_id');
    }

    /**
     * Scope to filter only active beach services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by service type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
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

    /**
     * Check if this service is Beach Huts
     */
    public function isBeachHuts(): bool
    {
        return $this->service_type === 'Beach Huts';
    }
}
