@props([
    'clickable' => false,
    'selected' => false,
])

@php
    $classes = 'bg-white dark:bg-gray-800';

    if ($selected) {
        $classes = 'bg-indigo-50 dark:bg-indigo-900/20';
    }

    if ($clickable) {
        $classes .= ' cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
    }
@endphp

<tr {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</tr>
