@props([
    'type' => 'button',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
])

@php
use App\Helpers\UiHelper;

// Determine if this should be a link or button
$isLink = !is_null($href);
$tag = $isLink ? 'a' : 'button';

// Size classes
$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};

// Base button classes
$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800';

// Color classes (Orange warning)
$colorClasses = 'bg-orange-600 dark:bg-orange-500 text-white hover:bg-orange-700 dark:hover:bg-orange-600 focus:ring-orange-500';

// Disabled/Loading state classes
$stateClasses = '';
if ($disabled || $loading) {
    $stateClasses = 'opacity-50 cursor-not-allowed';
    $colorClasses = 'bg-orange-600 dark:bg-orange-500 text-white'; // Remove hover on disabled
}

// Icon spacing
$iconSpacing = match($size) {
    'sm' => $iconPosition === 'left' ? 'mr-1.5' : 'ml-1.5',
    'md' => $iconPosition === 'left' ? 'mr-2' : 'ml-2',
    'lg' => $iconPosition === 'left' ? 'mr-2.5' : 'ml-2.5',
    default => $iconPosition === 'left' ? 'mr-2' : 'ml-2',
};

// Icon size
$iconSize = match($size) {
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-5 h-5',
    default => 'w-5 h-5',
};

// Spinner size for loading state
$spinnerSize = match($size) {
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    default => 'w-5 h-5',
};

// Merge all classes
$classes = trim("{$baseClasses} {$colorClasses} {$sizeClasses} {$stateClasses}");

// Prepare attributes
$attributes = $attributes->merge([
    'class' => $classes,
]);

// Add type attribute for buttons
if (!$isLink) {
    $attributes = $attributes->merge(['type' => $type]);
}

// Add href for links
if ($isLink) {
    $attributes = $attributes->merge(['href' => $href]);
}

// Add disabled attribute
if ($disabled && !$isLink) {
    $attributes = $attributes->merge(['disabled' => true]);
}

// Prevent clicks when loading
if ($loading) {
    $attributes = $attributes->merge(['disabled' => true]);
}
@endphp

<{{ $tag }} {{ $attributes }}>
    {{-- Loading Spinner --}}
    @if($loading)
        <svg class="{{ $spinnerSize }} animate-spin {{ $iconPosition === 'left' ? 'mr-2' : 'ml-2' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    {{-- Icon (Left Position) --}}
    @if($icon && $iconPosition === 'left' && !$loading)
        <span class="{{ $iconSize }} {{ $iconSpacing }}">
            {!! $icon !!}
        </span>
    @endif

    {{-- Button Text/Content --}}
    <span>{{ $slot }}</span>

    {{-- Icon (Right Position) --}}
    @if($icon && $iconPosition === 'right' && !$loading)
        <span class="{{ $iconSize }} {{ $iconSpacing }}">
            {!! $icon !!}
        </span>
    @endif
</{{ $tag }}>
