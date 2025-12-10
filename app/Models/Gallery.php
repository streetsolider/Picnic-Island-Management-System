<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    // Gallery type constants
    public const TYPE_HOTEL = 'hotel';
    public const TYPE_ROOM = 'room';

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'type',
    ];

    /**
     * Get the hotel that owns this gallery.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the images in this gallery.
     */
    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }

    /**
     * Get the rooms using this gallery.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the primary image for this gallery.
     */
    public function getPrimaryImage(): ?GalleryImage
    {
        return $this->images()->where('is_primary', true)->first();
    }

    /**
     * Get the count of images in this gallery.
     */
    public function getImageCountAttribute(): int
    {
        return $this->images()->count();
    }

    /**
     * Scope to filter hotel galleries.
     */
    public function scopeHotelType($query)
    {
        return $query->where('type', self::TYPE_HOTEL);
    }

    /**
     * Scope to filter room galleries.
     */
    public function scopeRoomType($query)
    {
        return $query->where('type', self::TYPE_ROOM);
    }

    /**
     * Check if this is a hotel gallery.
     */
    public function isHotelGallery(): bool
    {
        return $this->type === self::TYPE_HOTEL;
    }

    /**
     * Check if this is a room gallery.
     */
    public function isRoomGallery(): bool
    {
        return $this->type === self::TYPE_ROOM;
    }
}
