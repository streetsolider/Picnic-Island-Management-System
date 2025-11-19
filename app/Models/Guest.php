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
        'email',
        'password',
        'guest_id',
        'phone',
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
