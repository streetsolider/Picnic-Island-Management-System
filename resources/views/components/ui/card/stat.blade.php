@props([
    'title' => '',
    'value' => '',
    'subtitle' => null,
    'icon' => null,
    'color' => 'blue',
    'badgeStyle' => false,  // false = side icon layout, true = badge layout
])

@php
use App\Helpers\UiHelper;

// Get color classes for the stat card
$colorClasses = UiHelper::getStatCardColorClasses($color);

// Icon classes based on layout
if ($badgeStyle) {
    // Badge Style: Icon in colored circle on the right
    $iconWrapperClasses = "p-3 rounded-full {$colorClasses['icon-bg']}";
    $iconClasses = "w-8 h-8 {$colorClasses['icon']}";
} else {
    // Side Icon Style: Large icon on the left
    $iconWrapperClasses = "flex-shrink-0";
    $iconClasses = "h-12 w-12 {$colorClasses['icon']}";
}
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
    <div class="p-6 text-gray-700 dark:text-gray-300">
        @if($badgeStyle)
            {{-- Badge Style Layout: Text on left, Icon badge on right --}}
            <div class="flex items-center">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                        {{ $title }}
                    </h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ $value }}
                    </p>
                    @if($subtitle)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
                @if($icon)
                    <div class="{{ $iconWrapperClasses }}">
                        <div class="{{ $iconClasses }}">
                            {!! $icon !!}
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- Side Icon Style: Icon on left, Text on right --}}
            <div class="flex items-center">
                @if($icon)
                    <div class="{{ $iconWrapperClasses }}">
                        <div class="{{ $iconClasses }}">
                            {!! $icon !!}
                        </div>
                    </div>
                @endif
                <div class="{{ $icon ? 'ml-5 w-0 flex-1' : 'flex-1' }}">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            {{ $title }}
                        </dt>
                        <dd class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $value }}
                        </dd>
                        @if($subtitle)
                            <dd class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $subtitle }}
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        @endif
    </div>
</div>
