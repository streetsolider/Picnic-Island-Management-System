<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'location',
        'description',
        'star_rating',
        'manager_id',
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
            'star_rating' => 'integer',
            'location' => 'array',
        ];
    }

    /**
     * Get the hotel manager assigned to this hotel
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }

    /**
     * Scope to filter only active hotels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the star rating as a string (for display)
     */
    public function getStarRatingDisplayAttribute(): string
    {
        return str_repeat('⭐', $this->star_rating);
    }
}
