@props([
    'padding' => 'p-6',
    'shadow' => 'shadow-sm',
    'rounded' => 'rounded-lg',
])

@php
use App\Helpers\UiHelper;

// Base card classes
$baseClasses = 'bg-white dark:bg-gray-800 overflow-hidden';

// Combine all classes
$classes = trim("{$baseClasses} {$shadow} {$rounded}");

// Prepare attributes
$attributes = $attributes->merge([
    'class' => $classes,
]);
@endphp

<div {{ $attributes }}>
    {{-- Card Header/Title (Optional) --}}
    @isset($title)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h3>
        </div>
    @endisset

    {{-- Card Body/Content --}}
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    {{-- Card Footer (Optional) --}}
    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            {{ $footer }}
        </div>
    @endisset
</div>
