@props([
    'id' => null, // Unique ID for this accordion item
    'title' => '',
    'badge' => null, // Optional badge text/count
    'defaultOpen' => false, // Start expanded
])

@php
// Generate unique ID if not provided
$itemId = $id ?? 'accordion-' . uniqid();

// Initialize open state in parent if default open
$initScript = $defaultOpen ? "if (!openItems.includes('{$itemId}')) { openItems.push('{$itemId}'); }" : '';
@endphp

<div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800"
     x-init="{{ $initScript }}">

    {{-- Accordion Header --}}
    <button @click="toggle('{{ $itemId }}')" type="button"
        class="w-full px-6 py-4 flex items-center justify-between text-left bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        :class="{ 'border-b border-gray-200 dark:border-gray-700': isOpen('{{ $itemId }}') }">

        <div class="flex items-center gap-3 flex-1">
            {{-- Expand/Collapse Icon --}}
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                 :class="{ 'rotate-90': isOpen('{{ $itemId }}') }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>

            {{-- Title Slot --}}
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
            @endif

            {{-- Badge (if provided) --}}
            @if($badge)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                    {{ $badge }}
                </span>
            @endif
        </div>

        {{-- Actions Slot (Optional) --}}
        @isset($actions)
            <div class="flex items-center gap-2 ml-4" @click.stop>
                {{ $actions }}
            </div>
        @endisset
    </button>

    {{-- Accordion Content --}}
    <div x-show="isOpen('{{ $itemId }}')"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         style="display: none;">
        <div class="px-6 py-4 bg-white dark:bg-gray-800">
            {{ $slot }}
        </div>
    </div>
</div>
