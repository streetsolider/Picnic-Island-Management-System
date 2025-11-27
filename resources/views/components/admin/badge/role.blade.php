@props([
    'role',
])

@php
    // Normalize role to string if it's an enum
    $roleValue = is_object($role) && method_exists($role, 'value') ? $role->value : $role;
    $roleLabel = is_object($role) && method_exists($role, 'label') ? $role->label() : ucwords(str_replace('_', ' ', $roleValue));

    // Color mapping for each role
    $colors = match (strtolower($roleValue)) {
        'administrator', 'admin' => [
            'bg' => 'bg-red-100 dark:bg-red-900/20',
            'text' => 'text-red-800 dark:text-red-300',
            'ring' => 'dark:ring-1 dark:ring-red-500/30',
        ],
        'hotel_manager', 'hotel manager' => [
            'bg' => 'bg-purple-100 dark:bg-purple-900/20',
            'text' => 'text-purple-800 dark:text-purple-300',
            'ring' => 'dark:ring-1 dark:ring-purple-500/30',
        ],
        'ferry_operator', 'ferry operator' => [
            'bg' => 'bg-cyan-100 dark:bg-cyan-900/20',
            'text' => 'text-cyan-800 dark:text-cyan-300',
            'ring' => 'dark:ring-1 dark:ring-cyan-500/30',
        ],
        'theme_park_staff', 'theme park staff' => [
            'bg' => 'bg-pink-100 dark:bg-pink-900/20',
            'text' => 'text-pink-800 dark:text-pink-300',
            'ring' => 'dark:ring-1 dark:ring-pink-500/30',
        ],
        'beach_staff', 'beach staff' => [
            'bg' => 'bg-teal-100 dark:bg-teal-900/20',
            'text' => 'text-teal-800 dark:text-teal-300',
            'ring' => 'dark:ring-1 dark:ring-teal-500/30',
        ],
        default => [
            'bg' => 'bg-gray-100 dark:bg-gray-700',
            'text' => 'text-gray-800 dark:text-gray-300',
            'ring' => 'dark:ring-1 dark:ring-gray-500/30',
        ],
    };

    $classes = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colors['bg']} {$colors['text']} {$colors['ring']}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $roleLabel }}
</span>
