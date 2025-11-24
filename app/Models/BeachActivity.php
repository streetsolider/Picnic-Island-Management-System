<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeachActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'beach_service_id',
        'name',
        'description',
        'price',
        'capacity',
        'duration_minutes',
        'available_from',
        'available_until',
        'requires_booking',
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
            'price' => 'decimal:2',
            'capacity' => 'integer',
            'duration_minutes' => 'integer',
            'available_from' => 'date',
            'available_until' => 'date',
            'requires_booking' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the beach service this activity belongs to
     */
    public function beachService(): BelongsTo
    {
        return $this->belongsTo(BeachService::class);
    }

    /**
     * Scope to filter only active activities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter activities currently available
     */
    public function scopeAvailable($query)
    {
        $today = now()->toDateString();
        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('available_from')
                    ->orWhere('available_from', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('available_until')
                    ->orWhere('available_until', '>=', $today);
            });
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get duration in human-readable format
     */
    public function getFormattedDurationAttribute(): ?string
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}
