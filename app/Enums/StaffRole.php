<?php

namespace App\Enums;

enum StaffRole: string
{
    case ADMINISTRATOR = 'administrator';
    case HOTEL_MANAGER = 'hotel_manager';
    case FERRY_OPERATOR = 'ferry_operator';
    case THEME_PARK_MANAGER = 'theme_park_manager';
    case THEME_PARK_STAFF = 'theme_park_staff';
    case BEACH_STAFF = 'beach_staff';

    /**
     * Get the label for the role
     */
    public function label(): string
    {
        return match($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::HOTEL_MANAGER => 'Hotel Manager',
            self::FERRY_OPERATOR => 'Ferry Operator',
            self::THEME_PARK_MANAGER => 'Theme Park Manager',
            self::THEME_PARK_STAFF => 'Theme Park Staff',
            self::BEACH_STAFF => 'Beach Staff',
        };
    }

    /**
     * Get all role values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all roles as options for forms
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($role) => [
            $role->value => $role->label()
        ])->toArray();
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMINISTRATOR;
    }

    /**
     * Get the dashboard route based on staff role
     */
    public function dashboardRoute(): string
    {
        return match($this) {
            self::ADMINISTRATOR => 'admin.dashboard',
            self::HOTEL_MANAGER => 'hotel.dashboard',
            self::FERRY_OPERATOR => 'ferry.dashboard',
            self::THEME_PARK_MANAGER => 'theme-park.dashboard',
            self::THEME_PARK_STAFF => 'theme-park.dashboard',
            self::BEACH_STAFF => 'beach.dashboard',
        };
    }
}
