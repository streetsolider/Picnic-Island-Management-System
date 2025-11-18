<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
            'role' => UserRole::class,
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(UserRole|string $role): bool
    {
        if (is_string($role)) {
            return $this->role->value === $role;
        }

        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
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
     * Check if user is a visitor
     */
    public function isVisitor(): bool
    {
        return $this->role === UserRole::VISITOR;
    }

    /**
     * Check if user is a hotel manager
     */
    public function isHotelManager(): bool
    {
        return $this->role === UserRole::HOTEL_MANAGER;
    }

    /**
     * Check if user is a ferry operator
     */
    public function isFerryOperator(): bool
    {
        return $this->role === UserRole::FERRY_OPERATOR;
    }

    /**
     * Check if user is theme park staff
     */
    public function isThemeParkStaff(): bool
    {
        return $this->role === UserRole::THEME_PARK_STAFF;
    }

    /**
     * Check if user is an administrator
     */
    public function isAdministrator(): bool
    {
        return $this->role === UserRole::ADMINISTRATOR;
    }

    /**
     * Check if user is any type of staff
     */
    public function isStaff(): bool
    {
        return $this->role->isStaff();
    }

    /**
     * Get the dashboard route based on user role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            UserRole::VISITOR => 'visitor.dashboard',
            UserRole::HOTEL_MANAGER => 'hotel.dashboard',
            UserRole::FERRY_OPERATOR => 'ferry.dashboard',
            UserRole::THEME_PARK_STAFF => 'theme-park.dashboard',
            UserRole::ADMINISTRATOR => 'admin.dashboard',
        };
    }
}
