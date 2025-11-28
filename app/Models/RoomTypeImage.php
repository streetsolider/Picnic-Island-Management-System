<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RoomTypeImage extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_type',
        'image_path',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the hotel that owns the room type image.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    /**
     * Scope to get images for a specific hotel and room type.
     */
    public function scopeForType($query, $hotelId, $roomType)
    {
        return $query->where('hotel_id', $hotelId)
                    ->where('room_type', $roomType)
                    ->orderBy('sort_order');
    }

    /**
     * Scope to get primary image.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
