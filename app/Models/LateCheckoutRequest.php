<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LateCheckoutRequest extends Model
{
    protected $fillable = [
        'hotel_booking_id',
        'requested_checkout_time',
        'status',
        'guest_notes',
        'reviewed_by',
        'reviewed_at',
        'manager_notes',
        'has_next_booking',
        'next_booking_info',
    ];

    protected $casts = [
        'requested_checkout_time' => 'datetime:H:i:s',
        'reviewed_at' => 'datetime',
        'has_next_booking' => 'boolean',
        'next_booking_info' => 'array',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reviewed_by');
    }

    // Status check methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // Action methods
    public function approve(int $staffId, ?string $notes = null): bool
    {
        $this->status = 'approved';
        $this->reviewed_by = $staffId;
        $this->reviewed_at = now();
        $this->manager_notes = $notes;
        return $this->save();
    }

    public function reject(int $staffId, string $reason): bool
    {
        $this->status = 'rejected';
        $this->reviewed_by = $staffId;
        $this->reviewed_at = now();
        $this->manager_notes = $reason;
        return $this->save();
    }

    public function cancel(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->status = 'cancelled';
        return $this->save();
    }

    // Display helpers
    public function getFormattedRequestedTimeAttribute(): string
    {
        return Carbon::parse($this->requested_checkout_time)->format('g:i A');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    // Validation
    public static function getMaxCheckoutTime(): string
    {
        return Hotel::MAX_LATE_CHECKOUT_TIME;
    }

    public function getNextBookingDetails(): ?array
    {
        if (!$this->has_next_booking) {
            return null;
        }

        return $this->next_booking_info;
    }
}
