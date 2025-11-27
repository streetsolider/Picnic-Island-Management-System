@props([
    'type' => 'info',  // info, success, warning, danger
    'icon' => null,
    'title' => null,
    'dismissible' => false,
])

@php
use App\Helpers\UiHelper;

// Get color classes based on type
$colors = match($type) {
    'info' => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'border' => 'border-blue-200 dark:border-blue-800',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'title' => 'text-blue-900 dark:text-blue-300',
        'text' => 'text-blue-700 dark:text-blue-400',
        'button' => 'text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300',
    ],
    'success' => [
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'border' => 'border-green-200 dark:border-green-800',
        'icon' => 'text-green-500 dark:text-green-400',
        'title' => 'text-green-900 dark:text-green-300',
        'text' => 'text-green-700 dark:text-green-400',
        'button' => 'text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300',
    ],
    'warning' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'border' => 'border-yellow-200 dark:border-yellow-800',
        'icon' => 'text-yellow-500 dark:text-yellow-400',
        'title' => 'text-yellow-900 dark:text-yellow-300',
        'text' => 'text-yellow-700 dark:text-yellow-400',
        'button' => 'text-yellow-500 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300',
    ],
    'danger' => [
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'border' => 'border-red-200 dark:border-red-800',
        'icon' => 'text-red-500 dark:text-red-400',
        'title' => 'text-red-900 dark:text-red-300',
        'text' => 'text-red-700 dark:text-red-400',
        'button' => 'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300',
    ],
    default => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'border' => 'border-blue-200 dark:border-blue-800',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'title' => 'text-blue-900 dark:text-blue-300',
        'text' => 'text-blue-700 dark:text-blue-400',
        'button' => 'text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300',
    ],
};

// Combine classes
$classes = trim("{$colors['bg']} {$colors['border']} border rounded-lg overflow-hidden shadow-sm");

// Prepare attributes
$attributes = $attributes->merge([
    'class' => $classes,
]);
@endphp

<div {{ $attributes }} role="alert">
    <div class="p-4">
        <div class="flex items-start">
            {{-- Icon (Optional) --}}
            @if($icon)
                <div class="flex-shrink-0">
                    <div class="w-5 h-5 {{ $colors['icon'] }}">
                        {!! $icon !!}
                    </div>
                </div>
            @endif

            {{-- Content --}}
            <div class="{{ $icon ? 'ml-3' : '' }} flex-1">
                {{-- Title (Optional) --}}
                @if($title)
                    <h3 class="text-sm font-semibold {{ $colors['title'] }} mb-1">
                        {{ $title }}
                    </h3>
                @endif

                {{-- Message Content --}}
                <div class="text-sm {{ $colors['text'] }}">
                    {{ $slot }}
                </div>
            </div>

            {{-- Dismiss Button (Optional) --}}
            @if($dismissible)
                <div class="ml-4 flex-shrink-0">
                    <button
                        type="button"
                        class="{{ $colors['button'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-md p-1 transition-colors"
                        @click="$el.closest('[role=alert]').remove()"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
