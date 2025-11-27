{{--
    UI Component Library - Color Theme Configuration

    This file defines the color system for all UI components.
    Colors are based on Tailwind CSS v4 color palette.

    Usage:
    - Import this file in components that need color mapping
    - Use @php directive to access color arrays
    - All colors support dark mode variants
--}}

@php
/**
 * Primary Component Colors
 * Used for buttons, links, focus states, etc.
 */
$primaryColors = [
    'primary' => [
        'bg' => 'bg-indigo-600 dark:bg-indigo-500',
        'bg-hover' => 'hover:bg-indigo-700 dark:hover:bg-indigo-600',
        'text' => 'text-indigo-600 dark:text-indigo-400',
        'text-hover' => 'hover:text-indigo-700 dark:hover:text-indigo-300',
        'border' => 'border-indigo-600 dark:border-indigo-500',
        'ring' => 'focus:ring-indigo-500',
    ],
    'secondary' => [
        'bg' => 'bg-gray-600 dark:bg-gray-500',
        'bg-hover' => 'hover:bg-gray-700 dark:hover:bg-gray-600',
        'text' => 'text-gray-600 dark:text-gray-400',
        'text-hover' => 'hover:text-gray-700 dark:hover:text-gray-300',
        'border' => 'border-gray-600 dark:border-gray-500',
        'ring' => 'focus:ring-gray-500',
    ],
    'success' => [
        'bg' => 'bg-green-600 dark:bg-green-500',
        'bg-hover' => 'hover:bg-green-700 dark:hover:bg-green-600',
        'text' => 'text-green-600 dark:text-green-400',
        'text-hover' => 'hover:text-green-700 dark:hover:text-green-300',
        'border' => 'border-green-600 dark:border-green-500',
        'ring' => 'focus:ring-green-500',
    ],
    'danger' => [
        'bg' => 'bg-red-600 dark:bg-red-500',
        'bg-hover' => 'hover:bg-red-700 dark:hover:bg-red-600',
        'text' => 'text-red-600 dark:text-red-400',
        'text-hover' => 'hover:text-red-700 dark:hover:text-red-300',
        'border' => 'border-red-600 dark:border-red-500',
        'ring' => 'focus:ring-red-500',
    ],
    'warning' => [
        'bg' => 'bg-orange-600 dark:bg-orange-500',
        'bg-hover' => 'hover:bg-orange-700 dark:hover:bg-orange-600',
        'text' => 'text-orange-600 dark:text-orange-400',
        'text-hover' => 'hover:text-orange-700 dark:hover:text-orange-300',
        'border' => 'border-orange-600 dark:border-orange-500',
        'ring' => 'focus:ring-orange-500',
    ],
    'info' => [
        'bg' => 'bg-blue-600 dark:bg-blue-500',
        'bg-hover' => 'hover:bg-blue-700 dark:hover:bg-blue-600',
        'text' => 'text-blue-600 dark:text-blue-400',
        'text-hover' => 'hover:text-blue-700 dark:hover:text-blue-300',
        'border' => 'border-blue-600 dark:border-blue-500',
        'ring' => 'focus:ring-blue-500',
    ],
];

/**
 * Role-Based Colors
 * Used for role badges, staff identification, etc.
 * Matches the color scheme from existing admin views
 */
$roleColors = [
    'administrator' => [
        'badge-bg' => 'bg-red-100 dark:bg-red-900',
        'badge-text' => 'text-red-800 dark:text-red-300',
        'icon' => 'text-red-600 dark:text-red-400',
        'icon-bg' => 'bg-red-100 dark:bg-red-900',
    ],
    'hotel_manager' => [
        'badge-bg' => 'bg-purple-100 dark:bg-purple-900',
        'badge-text' => 'text-purple-800 dark:text-purple-300',
        'icon' => 'text-purple-600 dark:text-purple-400',
        'icon-bg' => 'bg-purple-100 dark:bg-purple-900',
    ],
    'ferry_operator' => [
        'badge-bg' => 'bg-cyan-100 dark:bg-cyan-900',
        'badge-text' => 'text-cyan-800 dark:text-cyan-300',
        'icon' => 'text-cyan-600 dark:text-cyan-400',
        'icon-bg' => 'bg-cyan-100 dark:bg-cyan-900',
    ],
    'theme_park_staff' => [
        'badge-bg' => 'bg-pink-100 dark:bg-pink-900',
        'badge-text' => 'text-pink-800 dark:text-pink-300',
        'icon' => 'text-pink-600 dark:text-pink-400',
        'icon-bg' => 'bg-pink-100 dark:bg-pink-900',
    ],
    'beach_staff' => [
        'badge-bg' => 'bg-teal-100 dark:bg-teal-900',
        'badge-text' => 'text-teal-800 dark:text-teal-300',
        'icon' => 'text-teal-600 dark:text-teal-400',
        'icon-bg' => 'bg-teal-100 dark:bg-teal-900',
    ],
];

/**
 * Status Colors
 * Used for status badges, alerts, notifications, etc.
 */
$statusColors = [
    'active' => [
        'badge-bg' => 'bg-green-100 dark:bg-green-900',
        'badge-text' => 'text-green-800 dark:text-green-300',
    ],
    'inactive' => [
        'badge-bg' => 'bg-red-100 dark:bg-red-900',
        'badge-text' => 'text-red-800 dark:text-red-300',
    ],
    'pending' => [
        'badge-bg' => 'bg-yellow-100 dark:bg-yellow-900',
        'badge-text' => 'text-yellow-800 dark:text-yellow-300',
    ],
    'processing' => [
        'badge-bg' => 'bg-blue-100 dark:bg-blue-900',
        'badge-text' => 'text-blue-800 dark:text-blue-300',
    ],
];

/**
 * Alert/Flash Message Colors
 * Used for alerts, notifications, flash messages
 */
$alertColors = [
    'success' => [
        'bg' => 'bg-green-100 dark:bg-green-900/30',
        'border' => 'border-green-400 dark:border-green-700',
        'text' => 'text-green-700 dark:text-green-300',
        'icon' => 'text-green-600 dark:text-green-400',
    ],
    'error' => [
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
];

/**
 * Stat Card Icon Colors
 * Used for stat cards in dashboards
 * Matches colors from existing admin and hotel dashboards
 */
$statCardColors = [
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
];

/**
 * Helper function to get color classes
 *
 * @param array $colors Color array (e.g., $primaryColors)
 * @param string $variant Color variant (e.g., 'primary', 'success')
 * @param string $property Color property (e.g., 'bg', 'text', 'bg-hover')
 * @return string Tailwind CSS classes
 */
function getColorClasses($colors, $variant, $property) {
    return $colors[$variant][$property] ?? '';
}
@endphp

{{--
    USAGE EXAMPLES:

    1. In Button Component:
    @php $btnColors = $primaryColors[$color] ?? $primaryColors['primary']; @endphp
    <button class="{{ $btnColors['bg'] }} {{ $btnColors['bg-hover'] }} {{ $btnColors['ring'] }}">

    2. In Badge Component:
    @php $badgeColors = $statusColors[$active ? 'active' : 'inactive']; @endphp
    <span class="{{ $badgeColors['badge-bg'] }} {{ $badgeColors['badge-text'] }}">

    3. In Alert Component:
    @php $alertClasses = $alertColors[$type]; @endphp
    <div class="{{ $alertClasses['bg'] }} {{ $alertClasses['border'] }} {{ $alertClasses['text'] }}">

    4. Direct Function Usage:
    {{ getColorClasses($primaryColors, 'success', 'bg') }}
--}}
