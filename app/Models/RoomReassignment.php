<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomReassignment extends Model
{
    protected $fillable = [
        'booking_id',
        'old_room_id',
        'new_room_id',
        'reassigned_by',
        'reason',
        'reassigned_at',
    ];

    protected $casts = [
        'reassigned_at' => 'datetime',
    ];

    /**
     * Get the booking this reassignment belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    /**
     * Get the old room
     */
    public function oldRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'old_room_id');
    }

    /**
     * Get the new room
     */
    public function newRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'new_room_id');
    }

    /**
     * Get the staff member who performed the reassignment
     */
    public function reassignedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reassigned_by');
    }
}
