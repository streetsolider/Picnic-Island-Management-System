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
        'beach_activity_category_id',
        'name',
        'service_type',  // Kept for backward compatibility
        'description',
        'booking_type',
        'slot_duration_minutes',
        'slot_price',
        'price_per_hour',
        'capacity_limit',
        'concurrent_capacity',
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
            'concurrent_capacity' => 'integer',
            'slot_duration_minutes' => 'integer',
            'slot_price' => 'decimal:2',
            'price_per_hour' => 'decimal:2',
        ];
    }

    /**
     * Service types available (for backward compatibility)
     */
    public const SERVICE_TYPES = [
        'Excursions',
        'Water Sports',
        'Beach Sports',
        'Beach Huts',
    ];

    /**
     * Get the category this service belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BeachActivityCategory::class, 'beach_activity_category_id');
    }

    /**
     * Get the staff member assigned to this beach service
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    /**
     * Get all activities for this beach service (old relationship, kept for backward compatibility)
     */
    public function activities(): HasMany
    {
        return $this->hasMany(BeachActivity::class, 'beach_service_id');
    }

    /**
     * Get all bookings for this beach service
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(BeachServiceBooking::class);
    }

    /**
     * Scope to filter only active beach services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by service type (backward compatibility)
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
     * Check if this service uses fixed time slots
     */
    public function isFixedSlot(): bool
    {
        return $this->booking_type === 'fixed_slot';
    }

    /**
     * Check if this service uses flexible duration
     */
    public function isFlexibleDuration(): bool
    {
        return $this->booking_type === 'flexible_duration';
    }

    /**
     * Get the price per unit (slot or hour)
     */
    public function getPricePerUnit(): float
    {
        return $this->isFixedSlot() ? (float) $this->slot_price : (float) $this->price_per_hour;
    }

    /**
     * Check if service is available for a given date and time range
     *
     * @param string $date Date in Y-m-d format
     * @param string $startTime Time in H:i:s format
     * @param string $endTime Time in H:i:s format
     * @return bool
     */
    public function isAvailable(string $date, string $startTime, string $endTime): bool
    {
        $overlappingBookings = $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed', 'redeemed'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->count();

        return $overlappingBookings < $this->concurrent_capacity;
    }

    /**
     * Check if this service is Beach Huts (backward compatibility)
     */
    public function isBeachHuts(): bool
    {
        return $this->service_type === 'Beach Huts';
    }
}
