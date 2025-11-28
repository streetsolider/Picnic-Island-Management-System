<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RoomImage extends Model
{
    protected $fillable = [
        'room_id',
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
     * Get the room that owns the image.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    /**
     * Scope to get primary image.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
