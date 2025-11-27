@props([
    'icon' => null,
    'title' => '',
    'description' => '',
])

@php
// Base card classes
$classes = 'bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg';

// Prepare attributes
$attributes = $attributes->merge([
    'class' => $classes,
]);
@endphp

<div {{ $attributes }}>
    <div class="p-6 text-center text-gray-700 dark:text-gray-300">
        {{-- Icon --}}
        @if($icon)
            <div class="mx-auto h-12 w-12 text-gray-400">
                {!! $icon !!}
            </div>
        @endif

        {{-- Title --}}
        @if($title)
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h3>
        @endif

        {{-- Description --}}
        @if($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $description }}
            </p>
        @endif

        {{-- Action Button Slot --}}
        @isset($action)
            <div class="mt-6">
                {{ $action }}
            </div>
        @endisset
    </div>
</div>
