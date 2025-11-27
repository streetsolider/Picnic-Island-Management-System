@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
])

@php
$classes = $active
    ? 'flex items-center px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg'
    : 'flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="mr-3">
            {!! $icon !!}
        </span>
    @endif
    {{ $slot }}
</a>
