<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type',
        'bed_size',
        'bed_count',
        'view',
        'base_price',
        'max_occupancy',
        'is_available',
        'is_active',
        'gallery_id',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'max_occupancy' => 'integer',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel that owns this room
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the gallery assigned to this room
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }


    /**
     * Get the amenities assigned to this room
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity')
            ->withTimestamps();
    }

    /**
     * Get the formatted room type name
     */
    public function getRoomTypeNameAttribute(): string
    {
        return ucfirst($this->room_type);
    }

    /**
     * Get the full room description with type, bed config, and view
     */
    public function getFullDescriptionAttribute(): string
    {
        $description = "{$this->room_type} Room - {$this->bed_count} {$this->bed_size}";

        if ($this->view) {
            $description .= " - {$this->view} View";
        }

        return $description;
    }

    /**
     * Get the images specific to this room (overrides)
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    /**
     * Get all images for this room.
     * Returns room-specific images if they exist, otherwise falls back to room type default images.
     */
    public function getAllImages(): Collection
    {
        // First, check if this room has specific images
        $roomImages = $this->images;

        if ($roomImages->isNotEmpty()) {
            return $roomImages;
        }

        // Fall back to room type default images
        return RoomTypeImage::forType($this->hotel_id, $this->room_type)->get();
    }

    /**
     * Get the primary image for this room.
     * Returns room-specific primary image if it exists, otherwise falls back to room type primary image.
     */
    public function getPrimaryImage(): ?object
    {
        // First, check if this room has a specific primary image
        $roomPrimaryImage = $this->images()->primary()->first();

        if ($roomPrimaryImage) {
            return $roomPrimaryImage;
        }

        // Fall back to room type primary image
        return RoomTypeImage::forType($this->hotel_id, $this->room_type)->primary()->first();
    }

    /**
     * Check if this room has specific images (overrides)
     */
    public function hasSpecificImages(): bool
    {
        return $this->images()->exists();
    }
}
