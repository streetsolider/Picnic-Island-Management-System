<?php

namespace App\Models;

use App\Enums\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
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
        'role',
        'staff_id',
        'is_active',
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
            'role' => StaffRole::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if staff has a specific role
     */
    public function hasRole(StaffRole|string $role): bool
    {
        if (is_string($role)) {
            return $this->role->value === $role;
        }

        return $this->role === $role;
    }

    /**
     * Check if staff has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if staff is an administrator
     */
    public function isAdministrator(): bool
    {
        return $this->role === StaffRole::ADMINISTRATOR;
    }

    /**
     * Check if staff is a hotel manager
     */
    public function isHotelManager(): bool
    {
        return $this->role === StaffRole::HOTEL_MANAGER;
    }

    /**
     * Check if staff is a ferry operator
     */
    public function isFerryOperator(): bool
    {
        return $this->role === StaffRole::FERRY_OPERATOR;
    }

    /**
     * Check if staff is theme park staff
     */
    public function isThemeParkStaff(): bool
    {
        return $this->role === StaffRole::THEME_PARK_STAFF;
    }

    /**
     * Get the dashboard route based on staff role
     */
    public function getDashboardRoute(): string
    {
        return $this->role->dashboardRoute();
    }

    /**
     * Generate a unique staff ID
     */
    public static function generateStaffId(): string
    {
        $prefix = 'SID';

        // Get the last staff ID
        $lastStaff = self::orderByRaw('CAST(SUBSTRING(staff_id, 5) AS UNSIGNED) DESC')
            ->first();

        if ($lastStaff && $lastStaff->staff_id) {
            // Extract the number from the last staff ID and increment
            $number = (int) substr($lastStaff->staff_id, 4); // Skip 'SID-'
            $newNumber = $number + 1;
        } else {
            // First staff member
            $newNumber = 1;
        }

        // Format: SID-001, SID-002, etc.
        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to auto-generate staff ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            // Auto-generate staff ID if not provided
            if (empty($staff->staff_id)) {
                $staff->staff_id = self::generateStaffId();
            }
        });
    }
}
