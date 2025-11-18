<?php

namespace App\Enums;

enum UserRole: string
{
    case VISITOR = 'visitor';
    case HOTEL_MANAGER = 'hotel_manager';
    case FERRY_OPERATOR = 'ferry_operator';
    case THEME_PARK_STAFF = 'theme_park_staff';
    case ADMINISTRATOR = 'administrator';

    /**
     * Get the label for the role
     */
    public function label(): string
    {
        return match($this) {
            self::VISITOR => 'Visitor/Customer',
            self::HOTEL_MANAGER => 'Hotel Manager',
            self::FERRY_OPERATOR => 'Ferry Operator',
            self::THEME_PARK_STAFF => 'Theme Park Staff',
            self::ADMINISTRATOR => 'Administrator',
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
     * Check if role is staff (any staff role)
     */
    public function isStaff(): bool
    {
        return in_array($this, [
            self::HOTEL_MANAGER,
            self::FERRY_OPERATOR,
            self::THEME_PARK_STAFF,
            self::ADMINISTRATOR,
        ]);
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMINISTRATOR;
    }
}
