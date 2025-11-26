<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type',
        'bed_size',
        'bed_count',
        'view_id',
        'base_price',
        'max_occupancy',
        'floor_number',
        'description',
        'is_available',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'max_occupancy' => 'integer',
        'floor_number' => 'integer',
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
     * Get the view assigned to this room
     */
    public function view(): BelongsTo
    {
        return $this->belongsTo(RoomView::class, 'view_id');
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
            $description .= " - {$this->view->name} View";
        }

        return $description;
    }
}
