<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThemeParkShowSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'show_date',
        'show_time',
        'venue_capacity',
        'tickets_sold',
        'status',
    ];

    protected $casts = [
        'show_date' => 'date',
        'venue_capacity' => 'integer',
        'tickets_sold' => 'integer',
    ];

    /**
     * Get the activity this show schedule belongs to.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ThemeParkActivity::class, 'activity_id');
    }

    /**
     * Get the activity tickets for this show schedule.
     */
    public function activityTickets(): HasMany
    {
        return $this->hasMany(ThemeParkActivityTicket::class, 'show_schedule_id');
    }

    /**
     * Check if there are available seats for this show.
     */
    public function hasAvailableSeats(): bool
    {
        return $this->tickets_sold < $this->venue_capacity;
    }

    /**
     * Get the number of remaining seats.
     */
    public function getRemainingSeats(): int
    {
        return max(0, $this->venue_capacity - $this->tickets_sold);
    }

    /**
     * Get the percentage of seats sold.
     */
    public function getSoldPercentage(): float
    {
        if ($this->venue_capacity === 0) {
            return 0;
        }

        return ($this->tickets_sold / $this->venue_capacity) * 100;
    }

    /**
     * Check if the show is sold out.
     */
    public function isSoldOut(): bool
    {
        return $this->tickets_sold >= $this->venue_capacity;
    }

    /**
     * Check if the show is scheduled (not cancelled or completed).
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if the show is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the show is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Increment tickets sold.
     */
    public function incrementTicketsSold(int $count = 1): void
    {
        $this->increment('tickets_sold', $count);
    }

    /**
     * Decrement tickets sold (for cancellations).
     */
    public function decrementTicketsSold(int $count = 1): void
    {
        $this->decrement('tickets_sold', max(0, $count));
    }

    /**
     * Get the show date and time as a formatted string.
     */
    public function getShowDateTimeAttribute(): string
    {
        return $this->show_date->format('F j, Y') . ' at ' . $this->show_time;
    }

    /**
     * Scope to get only scheduled shows.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope to get upcoming shows.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('show_date', '>=', now()->toDateString());
    }

    /**
     * Scope to get shows by date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('show_date', $date);
    }
}
