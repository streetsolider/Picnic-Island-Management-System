@props([
    'active' => true,
    'activeText' => 'Active',
    'inactiveText' => 'Inactive',
    'clickable' => false,
])

@php
$baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';

$stateClasses = $active 
    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300 dark:ring-1 dark:ring-green-500/30'
    : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 dark:ring-1 dark:ring-red-500/30';

$clickableClasses = $clickable 
    ? 'cursor-pointer hover:opacity-80 transition-opacity' 
    : '';

$classes = trim("{$baseClasses} {$stateClasses} {$clickableClasses}");
@endphp

<span 
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($active)
        <svg class="mr-1.5 h-2 w-2 fill-current" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @else
        <svg class="mr-1.5 h-2 w-2 fill-current" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif
    {{ $active ? $activeText : $inactiveText }}
</span>
