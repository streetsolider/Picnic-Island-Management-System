@props([
    'name',
    'show' => false,
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmAction' => null,
    'type' => 'warning' // warning, danger, info
])

<div x-data="{ show: @entangle($attributes->wire('model')) }"
    x-show="show"
    x-cloak
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">

    {{-- Background overlay --}}
    <div x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
        @click="show = false"
        aria-hidden="true"></div>

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal panel --}}
        <div x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative inline-block align-bottom bg-white rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 z-10">

            <div class="sm:flex sm:items-start">
                {{-- Icon --}}
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-12 sm:w-12
                    @if($type === 'warning') bg-yellow-100
                    @elseif($type === 'danger') bg-red-100
                    @else bg-blue-100
                    @endif">
                    @if($type === 'warning')
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @elseif($type === 'danger')
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @else
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <h3 class="text-xl font-display font-bold text-brand-dark mb-3" id="modal-title">
                        {{ $title }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-gray-600 leading-relaxed">
                            {{ $message }}
                        </p>

                        {{-- Optional slot for additional content --}}
                        @if(isset($slot) && $slot->isNotEmpty())
                            <div class="mt-4">
                                {{ $slot }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-6 sm:mt-5 sm:flex sm:flex-row-reverse gap-3">
                @if($confirmAction)
                    <button type="button"
                        wire:click="{{ $confirmAction }}"
                        @click="show = false"
                        class="w-full inline-flex justify-center rounded-xl px-6 py-3 font-semibold text-white shadow-lg transition-all transform hover:scale-105 sm:ml-3 sm:w-auto
                        @if($type === 'warning' || $type === 'danger')
                            bg-red-600 hover:bg-red-700 shadow-red-600/30
                        @else
                            bg-brand-primary hover:bg-brand-primary/90 shadow-brand-primary/30
                        @endif">
                        {{ $confirmText }}
                    </button>
                @endif

                <button type="button"
                    @click="show = false"
                    class="mt-3 w-full inline-flex justify-center rounded-xl border-2 border-gray-200 px-6 py-3 bg-white font-semibold text-gray-700 hover:bg-gray-50 transition-colors sm:mt-0 sm:w-auto">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
