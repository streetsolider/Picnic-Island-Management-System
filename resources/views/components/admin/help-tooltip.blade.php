@props(['title', 'buttonText' => 'Help'])

<div x-data="{ open: false }">
    {{-- Help Button with Text --}}
    <button
        type="button"
        @click="open = true"
        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ $buttonText }}</span>
    </button>

    {{-- Full Modal --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="open = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm"></div>

        {{-- Modal Content --}}
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-4xl max-h-[85vh] overflow-y-auto">

                {{-- Header --}}
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
                    @if($title)
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $title }}
                        </h3>
                    @endif

                    {{-- Close Button --}}
                    <button
                        @click="open = false"
                        type="button"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="px-6 py-6 text-gray-600 dark:text-gray-300">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end">
                    <x-admin.button.secondary @click="open = false">
                        Close
                    </x-admin.button.secondary>
                </div>
            </div>
        </div>
    </div>
</div>
