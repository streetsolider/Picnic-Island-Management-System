@props([
    'type' => 'info',  // info, success, warning, danger
    'title' => null,
    'message' => '',
    'duration' => 5000, // 5 seconds default
])

@php
// Get color classes based on type
$colors = match($type) {
    'info' => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/90',
        'border' => 'border-blue-400 dark:border-blue-500',
        'icon' => 'text-blue-500 dark:text-blue-400',
        'title' => 'text-blue-900 dark:text-blue-100',
        'text' => 'text-blue-700 dark:text-blue-300',
        'button' => 'text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200',
        'iconSvg' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
    ],
    'success' => [
        'bg' => 'bg-green-50 dark:bg-green-900/90',
        'border' => 'border-green-400 dark:border-green-500',
        'icon' => 'text-green-500 dark:text-green-400',
        'title' => 'text-green-900 dark:text-green-100',
        'text' => 'text-green-700 dark:text-green-300',
        'button' => 'text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200',
        'iconSvg' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ],
    'warning' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/90',
        'border' => 'border-yellow-400 dark:border-yellow-500',
        'icon' => 'text-yellow-500 dark:text-yellow-400',
        'title' => 'text-yellow-900 dark:text-yellow-100',
        'text' => 'text-yellow-700 dark:text-yellow-300',
        'button' => 'text-yellow-500 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-200',
        'iconSvg' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>',
    ],
    'danger' => [
        'bg' => 'bg-red-50 dark:bg-red-900/90',
        'border' => 'border-red-400 dark:border-red-500',
        'icon' => 'text-red-500 dark:text-red-400',
        'title' => 'text-red-900 dark:text-red-100',
        'text' => 'text-red-700 dark:text-red-300',
        'button' => 'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200',
        'iconSvg' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ],
    default => [
        'bg' => 'bg-gray-50 dark:bg-gray-800',
        'border' => 'border-gray-400 dark:border-gray-500',
        'icon' => 'text-gray-500 dark:text-gray-400',
        'title' => 'text-gray-900 dark:text-gray-100',
        'text' => 'text-gray-700 dark:text-gray-300',
        'button' => 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200',
        'iconSvg' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
    ],
};
@endphp

<div
    x-data="{ show: false }"
    x-init="
        $nextTick(() => {
            show = true;
            setTimeout(() => { 
                show = false;
                setTimeout(() => {
                    $wire.set('showToast', null);
                }, 300);
            }, {{ $duration }});
        });
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    class="max-w-sm w-full {{ $colors['bg'] }} {{ $colors['border'] }} border-l-4 shadow-lg rounded-lg pointer-events-auto overflow-hidden"
    role="alert"
>
    <div class="p-4">
        <div class="flex items-start">
            {{-- Icon --}}
            <div class="flex-shrink-0 {{ $colors['icon'] }}">
                {!! $colors['iconSvg'] !!}
            </div>

            {{-- Content --}}
            <div class="ml-3 flex-1 pt-0.5">
                @if($title)
                    <p class="text-sm font-semibold {{ $colors['title'] }}">
                        {{ $title }}
                    </p>
                @endif
                <p class="text-sm {{ $colors['text'] }} {{ $title ? 'mt-1' : '' }}">
                    {{ $message }}
                    {{ $slot }}
                </p>
            </div>

            {{-- Close Button --}}
            <div class="ml-4 flex-shrink-0 flex">
                <button
                    @click="show = false; setTimeout(() => { $wire.set('showToast', null); }, 300);"
                    class="{{ $colors['button'] }} rounded-md inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

