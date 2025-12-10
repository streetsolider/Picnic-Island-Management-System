<?php

namespace App\Helpers;

use App\Enums\StaffRole;

/**
 * UI Component Helper Functions
 *
 * Provides utility functions for UI components to resolve colors,
 * sizes, icons, and other design system properties.
 */
class UiHelper
{
    /**
     * Button size classes mapping
     */
    public static function getButtonSizeClasses(string $size = 'md'): string
    {
        return match ($size) {
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-6 py-3 text-base',
            default => 'px-4 py-2 text-sm',
        };
    }

    /**
     * Get button color classes based on variant
     */
    public static function getButtonColorClasses(string $color = 'primary'): array
    {
        return match ($color) {
            'primary' => [
                'bg' => 'bg-indigo-600 dark:bg-indigo-500',
                'hover' => 'hover:bg-indigo-700 dark:hover:bg-indigo-600',
                'focus' => 'focus:ring-indigo-500',
                'text' => 'text-white',
            ],
            'secondary' => [
                'bg' => 'bg-gray-600 dark:bg-gray-500',
                'hover' => 'hover:bg-gray-700 dark:hover:bg-gray-600',
                'focus' => 'focus:ring-gray-500',
                'text' => 'text-white',
            ],
            'success' => [
                'bg' => 'bg-green-600 dark:bg-green-500',
                'hover' => 'hover:bg-green-700 dark:hover:bg-green-600',
                'focus' => 'focus:ring-green-500',
                'text' => 'text-white',
            ],
            'danger' => [
                'bg' => 'bg-red-600 dark:bg-red-500',
                'hover' => 'hover:bg-red-700 dark:hover:bg-red-600',
                'focus' => 'focus:ring-red-500',
                'text' => 'text-white',
            ],
            'warning' => [
                'bg' => 'bg-orange-600 dark:bg-orange-500',
                'hover' => 'hover:bg-orange-700 dark:hover:bg-orange-600',
                'focus' => 'focus:ring-orange-500',
                'text' => 'text-white',
            ],
            default => [
                'bg' => 'bg-indigo-600 dark:bg-indigo-500',
                'hover' => 'hover:bg-indigo-700 dark:hover:bg-indigo-600',
                'focus' => 'focus:ring-indigo-500',
                'text' => 'text-white',
            ],
        };
    }

    /**
     * Get role-based badge color classes
     */
    public static function getRoleBadgeClasses(StaffRole|string $role): array
    {
        $roleValue = $role instanceof StaffRole ? $role->value : $role;

        return match ($roleValue) {
            'administrator' => [
                'bg' => 'bg-red-100 dark:bg-red-900',
                'text' => 'text-red-800 dark:text-red-300',
            ],
            'hotel_manager' => [
                'bg' => 'bg-purple-100 dark:bg-purple-900',
                'text' => 'text-purple-800 dark:text-purple-300',
            ],
            'ferry_operator' => [
                'bg' => 'bg-cyan-100 dark:bg-cyan-900',
                'text' => 'text-cyan-800 dark:text-cyan-300',
            ],
            'theme_park_staff' => [
                'bg' => 'bg-pink-100 dark:bg-pink-900',
                'text' => 'text-pink-800 dark:text-pink-300',
            ],
            'beach_staff' => [
                'bg' => 'bg-teal-100 dark:bg-teal-900',
                'text' => 'text-teal-800 dark:text-teal-300',
            ],
            default => [
                'bg' => 'bg-gray-100 dark:bg-gray-900',
                'text' => 'text-gray-800 dark:text-gray-300',
            ],
        };
    }

    /**
     * Get status badge color classes
     */
    public static function getStatusBadgeClasses(bool $active): array
    {
        return $active
            ? [
                'bg' => 'bg-green-100 dark:bg-green-900',
                'text' => 'text-green-800 dark:text-green-300',
            ]
            : [
                'bg' => 'bg-red-100 dark:bg-red-900',
                'text' => 'text-red-800 dark:text-red-300',
            ];
    }

    /**
     * Get alert/flash message color classes
     */
    public static function getAlertColorClasses(string $type = 'info'): array
    {
        return match ($type) {
            'success' => [
                'bg' => 'bg-green-100 dark:bg-green-900/30',
                'border' => 'border-green-400 dark:border-green-700',
                'text' => 'text-green-700 dark:text-green-300',
                'icon' => 'text-green-600 dark:text-green-400',
            ],
            'error', 'danger' => [
                'bg' => 'bg-red-100 dark:bg-red-900/30',
                'border' => 'border-red-400 dark:border-red-700',
                'text' => 'text-red-700 dark:text-red-300',
                'icon' => 'text-red-600 dark:text-red-400',
            ],
            'warning' => [
                'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                'border' => 'border-yellow-400 dark:border-yellow-700',
                'text' => 'text-yellow-700 dark:text-yellow-300',
                'icon' => 'text-yellow-600 dark:text-yellow-400',
            ],
            'info' => [
                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                'border' => 'border-blue-400 dark:border-blue-700',
                'text' => 'text-blue-700 dark:text-blue-300',
                'icon' => 'text-blue-600 dark:text-blue-400',
            ],
            default => [
                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                'border' => 'border-blue-400 dark:border-blue-700',
                'text' => 'text-blue-700 dark:text-blue-300',
                'icon' => 'text-blue-600 dark:text-blue-400',
            ],
        };
    }

    /**
     * Get stat card icon color classes
     */
    public static function getStatCardColorClasses(string $color): array
    {
        return match ($color) {
            'blue' => [
                'icon' => 'text-blue-600 dark:text-blue-300',
                'icon-bg' => 'bg-blue-100 dark:bg-blue-900',
            ],
            'green' => [
                'icon' => 'text-green-600 dark:text-green-300',
                'icon-bg' => 'bg-green-100 dark:bg-green-900',
            ],
            'purple' => [
                'icon' => 'text-purple-600 dark:text-purple-300',
                'icon-bg' => 'bg-purple-100 dark:bg-purple-900',
            ],
            'orange' => [
                'icon' => 'text-orange-600 dark:text-orange-300',
                'icon-bg' => 'bg-orange-100 dark:bg-orange-900',
            ],
            'indigo' => [
                'icon' => 'text-indigo-600 dark:text-indigo-300',
                'icon-bg' => 'bg-indigo-100 dark:bg-indigo-900',
            ],
            'teal' => [
                'icon' => 'text-teal-600 dark:text-teal-300',
                'icon-bg' => 'bg-teal-100 dark:bg-teal-900',
            ],
            'cyan' => [
                'icon' => 'text-cyan-600 dark:text-cyan-300',
                'icon-bg' => 'bg-cyan-100 dark:bg-cyan-900',
            ],
            'pink' => [
                'icon' => 'text-pink-600 dark:text-pink-300',
                'icon-bg' => 'bg-pink-100 dark:bg-pink-900',
            ],
            'red' => [
                'icon' => 'text-red-600 dark:text-red-300',
                'icon-bg' => 'bg-red-100 dark:bg-red-900',
            ],
            'yellow' => [
                'icon' => 'text-yellow-600 dark:text-yellow-300',
                'icon-bg' => 'bg-yellow-100 dark:bg-yellow-900',
            ],
            default => [
                'icon' => 'text-gray-600 dark:text-gray-300',
                'icon-bg' => 'bg-gray-100 dark:bg-gray-900',
            ],
        };
    }

    /**
     * Get modal size classes
     */
    public static function getModalSizeClasses(string $size = 'md'): string
    {
        return match ($size) {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            'full' => 'sm:max-w-full',
            default => 'sm:max-w-md',
        };
    }

    /**
     * Get rounded corner classes
     */
    public static function getRoundedClasses(string $size = 'md'): string
    {
        return match ($size) {
            'none' => '',
            'sm' => 'rounded',
            'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'xl' => 'rounded-xl',
            'full' => 'rounded-full',
            default => 'rounded-md',
        };
    }

    /**
     * Get shadow classes
     */
    public static function getShadowClasses(string $size = 'sm'): string
    {
        return match ($size) {
            'none' => '',
            'sm' => 'shadow-sm',
            'md' => 'shadow',
            'lg' => 'shadow-lg',
            'xl' => 'shadow-xl',
            '2xl' => 'shadow-2xl',
            default => 'shadow-sm',
        };
    }

    /**
     * Combine multiple class strings, removing duplicates
     */
    public static function mergeClasses(string ...$classes): string
    {
        $allClasses = [];
        foreach ($classes as $classString) {
            $allClasses = array_merge($allClasses, explode(' ', $classString));
        }
        return implode(' ', array_unique(array_filter($allClasses)));
    }
}
