<?php

namespace App\Models\Ferry;

use App\Models\Guest;
use App\Models\HotelBooking;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FerryTicket extends Model
{
    protected $fillable = [
        'guest_id',
        'hotel_booking_id',
        'ferry_schedule_id',
        'ferry_route_id',
        'ferry_vessel_id',
        'direction',
        'ticket_reference',
        'travel_date',
        'number_of_passengers',
        'price_per_passenger',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'validated_by',
        'validated_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'price_per_passenger' => 'decimal:2',
        'total_price' => 'decimal:2',
        'validated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(FerrySchedule::class, 'ferry_schedule_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(FerryRoute::class, 'ferry_route_id');
    }

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(FerryVessel::class, 'ferry_vessel_id');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'validated_by');
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'confirmed')
            ->where('travel_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->whereIn('status', ['used', 'expired'])
            ->orWhere(function ($q) {
                $q->where('travel_date', '<', now()->toDateString());
            });
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('travel_date', $date);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeToIsland($query)
    {
        return $query->where('direction', 'to_island');
    }

    public function scopeFromIsland($query)
    {
        return $query->where('direction', 'from_island');
    }

    public function scopeForHotelBooking($query, $bookingId)
    {
        return $query->where('hotel_booking_id', $bookingId);
    }

    // Helper methods
    public function isArrival(): bool
    {
        return $this->direction === 'to_island';
    }

    public function isDeparture(): bool
    {
        return $this->direction === 'from_island';
    }

    // Existing helper methods
    public function cancel(string $reason = null): bool
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        return $this->save();
    }

    public function markAsUsed(int $staffId): bool
    {
        $this->status = 'used';
        $this->validated_by = $staffId;
        $this->validated_at = now();
        return $this->save();
    }

    public function isActive(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function canBeUsed(): bool
    {
        return $this->status === 'confirmed' &&
               $this->travel_date->isToday();
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'confirmed' &&
               $this->travel_date->isFuture();
    }

    // Generate unique ticket reference
    public static function generateTicketReference(): string
    {
        do {
            $reference = 'FT-' . strtoupper(Str::random(8));
        } while (self::where('ticket_reference', $reference)->exists());

        return $reference;
    }

    // Boot method to auto-generate ticket reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_reference)) {
                $ticket->ticket_reference = self::generateTicketReference();
            }
        });
    }
}
