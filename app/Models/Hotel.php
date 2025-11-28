<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'room_capacity',
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
            'room_capacity' => 'integer',
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

    /**
     * Get the rooms for this hotel
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the room views for this hotel
     */
    public function roomViews(): HasMany
    {
        return $this->hasMany(RoomView::class);
    }

    /**
     * Get the amenity categories for this hotel
     */
    public function amenityCategories(): HasMany
    {
        return $this->hasMany(AmenityCategory::class)->orderBy('sort_order');
    }

    /**
     * Get the amenities for this hotel
     */
    public function amenities(): HasMany
    {
        return $this->hasMany(Amenity::class);
    }

    /**
     * Get the room type images for this hotel
     */
    public function roomTypeImages(): HasMany
    {
        return $this->hasMany(RoomTypeImage::class)->orderBy('room_type')->orderBy('sort_order');
    }

    /**
     * Get the room type pricing for this hotel
     */
    public function roomTypePricing(): HasMany
    {
        return $this->hasMany(RoomTypePricing::class);
    }

    /**
     * Get the view pricing for this hotel
     */
    public function viewPricing(): HasMany
    {
        return $this->hasMany(ViewPricing::class);
    }

    /**
     * Get the seasonal pricing for this hotel
     */
    public function seasonalPricing(): HasMany
    {
        return $this->hasMany(SeasonalPricing::class);
    }

    /**
     * Get the day type pricing for this hotel
     */
    public function dayTypePricing(): HasMany
    {
        return $this->hasMany(DayTypePricing::class);
    }

    /**
     * Get the duration discounts for this hotel
     */
    public function durationDiscounts(): HasMany
    {
        return $this->hasMany(DurationDiscount::class);
    }

    /**
     * Get the policies for this hotel
     */
    public function policies(): HasMany
    {
        return $this->hasMany(HotelPolicy::class);
    }

    /**
     * Get the active policies for this hotel
     */
    public function activePolicies(): HasMany
    {
        return $this->hasMany(HotelPolicy::class)->where('is_active', true);
    }

    /**
     * Get the room type policy overrides for this hotel
     */
    public function roomTypePolicyOverrides(): HasMany
    {
        return $this->hasMany(RoomTypePolicyOverride::class);
    }
}
