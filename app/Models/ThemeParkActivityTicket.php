<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ThemeParkActivityTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'show_schedule_id',
        'credits_spent',
        'quantity',
        'total_credits_paid',
        'ticket_reference',
        'status',
        'purchase_datetime',
        'valid_until',
        'redeemed_by_staff_id',
        'redeemed_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'credits_spent' => 'integer',
        'quantity' => 'integer',
        'total_credits_paid' => 'decimal:2',
        'purchase_datetime' => 'datetime',
        'valid_until' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_reference)) {
                $ticket->ticket_reference = self::generateReference();
            }

            if (empty($ticket->purchase_datetime)) {
                $ticket->purchase_datetime = now();
            }
        });
    }

    /**
     * Get the user who purchased this ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity this ticket is for.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ThemeParkActivity::class, 'activity_id');
    }

    /**
     * Get the show schedule (if this is for a scheduled show).
     */
    public function showSchedule(): BelongsTo
    {
        return $this->belongsTo(ThemeParkShowSchedule::class, 'show_schedule_id');
    }

    /**
     * Get the staff member who redeemed this ticket.
     */
    public function redeemedByStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by_staff_id');
    }

    /**
     * Redeem this ticket (mark as used).
     */
    public function redeem(int $staffId): void
    {
        $this->update([
            'status' => 'redeemed',
            'redeemed_by_staff_id' => $staffId,
            'redeemed_at' => now(),
        ]);
    }

    /**
     * Cancel this ticket.
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        // If this is for a scheduled show, decrement tickets_sold
        if ($this->show_schedule_id && $this->showSchedule) {
            $this->showSchedule->decrementTicketsSold($this->quantity);
        }
    }

    /**
     * Mark ticket as expired.
     */
    public function expire(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Check if ticket is valid (not redeemed, expired, or cancelled).
     */
    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    /**
     * Check if ticket is redeemed.
     */
    public function isRedeemed(): bool
    {
        return $this->status === 'redeemed';
    }

    /**
     * Check if ticket is expired.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->valid_until && now()->greaterThan($this->valid_until)) {
            return true;
        }

        return false;
    }

    /**
     * Check if ticket is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if this ticket is for a continuous ride.
     */
    public function isContinuousRide(): bool
    {
        return $this->show_schedule_id === null;
    }

    /**
     * Check if this ticket is for a scheduled show.
     */
    public function isScheduledShow(): bool
    {
        return $this->show_schedule_id !== null;
    }

    /**
     * Generate a unique ticket reference (TPT-XXXXXXXX).
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'TPT-' . strtoupper(Str::random(8));
        } while (self::where('ticket_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Scope to get only valid tickets.
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    /**
     * Scope to get only redeemed tickets.
     */
    public function scopeRedeemed($query)
    {
        return $query->where('status', 'redeemed');
    }

    /**
     * Scope to get tickets for a specific activity.
     */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope to get tickets for a specific show schedule.
     */
    public function scopeForShowSchedule($query, $showScheduleId)
    {
        return $query->where('show_schedule_id', $showScheduleId);
    }

    /**
     * Scope to get tickets for continuous rides.
     */
    public function scopeContinuousRides($query)
    {
        return $query->whereNull('show_schedule_id');
    }

    /**
     * Scope to get tickets for scheduled shows.
     */
    public function scopeScheduledShows($query)
    {
        return $query->whereNotNull('show_schedule_id');
    }
}
