<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'description',
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
}
