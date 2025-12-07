<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HotelBooking extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_id',
        'guest_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'number_of_rooms',
        'status',
        'total_price',
        'payment_status',
        'payment_method',
        'promo_code',
        'special_requests',
        'booking_reference',
        'cancelled_at',
        'cancellation_reason',
        'checked_in_at',
        'checked_in_by',
        'check_in_notes',
        'checked_out_at',
        'checked_out_by',
        'check_out_notes',
        'additional_charges',
        'room_condition',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // Relationships
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'checked_in_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'checked_out_by');
    }

    public function ferryTickets()
    {
        return $this->hasMany(\App\Models\Ferry\FerryTicket::class);
    }

    public function arrivalFerryTicket()
    {
        return $this->hasOne(\App\Models\Ferry\FerryTicket::class)
            ->where('direction', 'to_island')
            ->whereIn('status', ['confirmed', 'used']);
    }

    public function departureFerryTicket()
    {
        return $this->hasOne(\App\Models\Ferry\FerryTicket::class)
            ->where('direction', 'from_island')
            ->whereIn('status', ['confirmed', 'used']);
    }

    public function beachServiceBookings()
    {
        return $this->hasMany(BeachServiceBooking::class);
    }

    public function lateCheckoutRequest()
    {
        return $this->hasOne(LateCheckoutRequest::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    // Accessor for number of nights
    public function getNumberOfNightsAttribute(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'confirmed')
            ->whereNull('checked_in_at')
            ->where('check_in_date', '>=', now()->toDateString());
    }

    public function scopeCurrent($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in'])
            ->whereNotNull('checked_in_at')
            ->whereNull('checked_out_at');
    }

    public function scopePast($query)
    {
        return $query->where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereNotNull('checked_out_at')
                  ->orWhereIn('status', ['completed', 'no-show'])
                  ->orWhere(function($subq) {
                      $subq->where('check_out_date', '<', now()->toDateString())
                           ->whereNull('checked_in_at');
                  });
            });
    }

    // Helper methods
    public function cancel(string $reason = null): bool
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;

        $saved = $this->save();

        // Cancel all associated ferry tickets
        if ($saved) {
            $this->ferryTickets()
                ->whereIn('status', ['confirmed', 'pending'])
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Hotel booking cancelled: ' . ($reason ?? 'No reason provided'),
                ]);
        }

        return $saved;
    }

    public function complete(): bool
    {
        $this->status = 'completed';
        return $this->save();
    }

    public function markAsNoShow(): bool
    {
        $this->status = 'no-show';
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

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function canCheckIn(): bool
    {
        return $this->status === 'confirmed' &&
               $this->check_in_date->isToday();
    }

    public function canCheckOut(): bool
    {
        return $this->status === 'checked_in';
    }

    public function hasValidFerryTicketEligibility(): bool
    {
        return $this->status === 'confirmed' &&
               $this->check_in_date >= now()->toDateString();
    }

    // Ferry ticket helper methods
    public function getTotalFerryPassengers(): int
    {
        return $this->ferryTickets()
            ->whereIn('status', ['confirmed', 'used'])
            ->sum('number_of_passengers');
    }

    public function canBookMoreFerryTickets(): bool
    {
        $totalBooked = $this->getTotalFerryPassengers();
        return $totalBooked < $this->room->max_occupancy;
    }

    public function hasArrivalFerry(): bool
    {
        return $this->ferryTickets()
            ->where('direction', 'to_island')
            ->whereIn('status', ['confirmed', 'used'])
            ->exists();
    }

    public function hasDepartureFerry(): bool
    {
        return $this->ferryTickets()
            ->where('direction', 'from_island')
            ->whereIn('status', ['confirmed', 'used'])
            ->exists();
    }

    public function getTotalDeparturePassengers(): int
    {
        return $this->ferryTickets()
            ->where('direction', 'from_island')
            ->whereIn('status', ['confirmed', 'used'])
            ->sum('number_of_passengers');
    }

    public function getRemainingDeparturePassengers(): int
    {
        $arrivalTicket = $this->arrivalFerryTicket;
        if (!$arrivalTicket) {
            return 0;
        }

        $totalDeparted = $this->getTotalDeparturePassengers();
        return max(0, $arrivalTicket->number_of_passengers - $totalDeparted);
    }

    public function hasAllPassengersDeparted(): bool
    {
        $arrivalTicket = $this->arrivalFerryTicket;
        if (!$arrivalTicket) {
            return false;
        }

        return $this->getTotalDeparturePassengers() >= $arrivalTicket->number_of_passengers;
    }

    public function getAvailablePassengerSlots(): int
    {
        $totalBooked = $this->getTotalFerryPassengers();
        return max(0, $this->room->max_occupancy - $totalBooked);
    }

    // Late Checkout helper methods
    public function hasLateCheckoutRequest(): bool
    {
        return $this->lateCheckoutRequest()->exists();
    }

    public function hasPendingLateCheckoutRequest(): bool
    {
        return $this->lateCheckoutRequest()
            ->where('status', 'pending')
            ->exists();
    }

    public function hasApprovedLateCheckoutRequest(): bool
    {
        return $this->lateCheckoutRequest()
            ->where('status', 'approved')
            ->exists();
    }

    public function getEffectiveCheckoutTime(): \Carbon\Carbon
    {
        $approvedRequest = $this->lateCheckoutRequest()
            ->where('status', 'approved')
            ->first();

        if ($approvedRequest) {
            // Extract just the time portion (H:i:s) from the datetime
            $timeString = \Carbon\Carbon::parse($approvedRequest->requested_checkout_time)->format('H:i:s');
            return \Carbon\Carbon::parse(
                $this->check_out_date->format('Y-m-d') . ' ' . $timeString
            );
        }

        // Extract just the time portion from default_checkout_time
        $defaultTime = \Carbon\Carbon::parse($this->hotel->default_checkout_time)->format('H:i:s');
        return \Carbon\Carbon::parse(
            $this->check_out_date->format('Y-m-d') . ' ' . $defaultTime
        );
    }

    public function getCheckoutDateTimeAttribute(): \Carbon\Carbon
    {
        return $this->getEffectiveCheckoutTime();
    }

    public function canRequestLateCheckout(): bool
    {
        if (!in_array($this->status, ['confirmed', 'checked_in'])) {
            return false;
        }

        if ($this->hasLateCheckoutRequest() &&
            in_array($this->lateCheckoutRequest->status, ['pending', 'approved'])) {
            return false;
        }

        // Allow requests up to and including the checkout day
        // Only block if checkout date has already passed (yesterday or earlier)
        if ($this->check_out_date->isBefore(now()->startOfDay())) {
            return false;
        }

        return true;
    }

    // Operations methods
    public function checkIn(int $staffId, string $notes = null): bool
    {
        $this->checked_in_at = now();
        $this->checked_in_by = $staffId;
        $this->check_in_notes = $notes;
        $this->status = 'checked_in';
        return $this->save();
    }

    public function checkOut(int $staffId, ?string $notes = null): bool
    {
        $this->checked_out_at = now();
        $this->checked_out_by = $staffId;
        $this->check_out_notes = $notes;
        $this->status = 'checked_out';
        return $this->save();
    }

    // Generate unique booking reference
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK-' . strtoupper(Str::random(8));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    // Boot method to auto-generate booking reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateBookingReference();
            }
        });
    }
}
