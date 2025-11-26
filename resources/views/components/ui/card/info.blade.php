@props([
    'type' => 'info',  // info, success, warning, danger
    'icon' => null,
    'title' => null,
])

@php
use App\Helpers\UiHelper;

// Get color classes based on type
$colors = match($type) {
    'info' => [
        'border' => 'border-l-4 border-blue-500 dark:border-blue-400',
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'title' => 'text-blue-900 dark:text-blue-300',
        'text' => 'text-blue-700 dark:text-blue-400',
    ],
    'success' => [
        'border' => 'border-l-4 border-green-500 dark:border-green-400',
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'icon' => 'text-green-500 dark:text-green-400',
        'title' => 'text-green-900 dark:text-green-300',
        'text' => 'text-green-700 dark:text-green-400',
    ],
    'warning' => [
        'border' => 'border-l-4 border-yellow-500 dark:border-yellow-400',
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'icon' => 'text-yellow-500 dark:text-yellow-400',
        'title' => 'text-yellow-900 dark:text-yellow-300',
        'text' => 'text-yellow-700 dark:text-yellow-400',
    ],
    'danger' => [
        'border' => 'border-l-4 border-red-500 dark:border-red-400',
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'icon' => 'text-red-500 dark:text-red-400',
        'title' => 'text-red-900 dark:text-red-300',
        'text' => 'text-red-700 dark:text-red-400',
    ],
    default => [
        'border' => 'border-l-4 border-blue-500 dark:border-blue-400',
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'title' => 'text-blue-900 dark:text-blue-300',
        'text' => 'text-blue-700 dark:text-blue-400',
    ],
};

// Combine classes
$classes = trim("{$colors['border']} {$colors['bg']} rounded-lg overflow-hidden shadow-sm");

// Prepare attributes
$attributes = $attributes->merge([
    'class' => $classes,
]);
@endphp

<div {{ $attributes }}>
    <div class="p-4">
        <div class="flex">
            {{-- Icon (Optional) --}}
            @if($icon)
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 {{ $colors['icon'] }}">
                        {!! $icon !!}
                    </div>
                </div>
                <div class="ml-3 flex-1">
            @else
                <div class="flex-1">
            @endif
                    {{-- Title (Optional) --}}
                    @if($title)
                        <h3 class="text-sm font-semibold {{ $colors['title'] }} mb-1">
                            {{ $title }}
                        </h3>
                    @endif

                    {{-- Content --}}
                    <div class="text-sm {{ $colors['text'] }}">
                        {{ $slot }}
                    </div>
                </div>
            @if($icon)
                </div>
            @endif
        </div>
    </div>
</div>
