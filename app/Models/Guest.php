<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guest extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'official_name',
        'email',
        'password',
        'guest_id',
        'google_id',
        'email_verified_at',
        'phone',
        'id_type',
        'id_number',
        'nationality',
        'date_of_birth',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Generate a unique guest ID
     */
    public static function generateGuestId(): string
    {
        $prefix = 'GST';

        // Get the last guest ID
        $lastGuest = self::orderByRaw('CAST(SUBSTRING(guest_id, 5) AS UNSIGNED) DESC')
            ->first();

        if ($lastGuest && $lastGuest->guest_id) {
            // Extract the number from the last guest ID and increment
            $number = (int) substr($lastGuest->guest_id, 4); // Skip 'GST-'
            $newNumber = $number + 1;
        } else {
            // First guest
            $newNumber = 1;
        }

        // Format: GST-001, GST-002, etc.
        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the ferry tickets for the guest
     */
    public function ferryTickets()
    {
        return $this->hasMany(\App\Models\Ferry\FerryTicket::class, 'guest_id');
    }

    /**
     * Get the payments for the guest
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the saved payment methods for the guest
     */
    public function savedPaymentMethods()
    {
        return $this->hasMany(SavedPaymentMethod::class);
    }

    /**
     * Get the default payment method for the guest
     */
    public function defaultPaymentMethod()
    {
        return $this->hasOne(SavedPaymentMethod::class)->where('is_default', true);
    }

    /**
     * Check if guest has national ID
     */
    public function hasNationalId(): bool
    {
        return $this->id_type === 'national_id';
    }

    /**
     * Check if guest has passport
     */
    public function hasPassport(): bool
    {
        return $this->id_type === 'passport';
    }

    /**
     * Get the guest's age
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Check if guest is Maldivian
     */
    public function isMaldivian(): bool
    {
        return $this->nationality === 'Maldivian';
    }

    /**
     * Get the display name (official name if available, otherwise registered name)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->official_name ?: $this->name;
    }

    /**
     * Boot method to auto-generate guest ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            // Auto-generate guest ID if not provided
            if (empty($guest->guest_id)) {
                $guest->guest_id = self::generateGuestId();
            }
        });
    }
}
