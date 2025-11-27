@props([
    'name',
    'show' => false,
    'title' => 'Confirm Action',
    'description' => 'Are you sure you want to perform this action? This cannot be undone.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmColor' => 'danger', // danger, warning, primary, success
    'icon' => null, // SVG or null for default warning
    'method' => null, // Livewire method to call
])

@php
$buttonAttributes = $method ? ['wire:click' => $method] : [];
@endphp

<x-ui.modal.base
    :name="$name"
    :show="$show"
    maxWidth="md"
    {{ $attributes->whereStartsWith('x-') }}
>
    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
            @if($icon)
                {!! $icon !!}
            @else
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @endif
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $description }}
                </p>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-3">
            <x-ui.button.secondary
                x-on:click="$dispatch('close')"
                wire:loading.attr="disabled"
            >
                {{ $cancelText }}
            </x-ui.button.secondary>

            @if($confirmColor === 'danger')
                <x-ui.button.danger
                    x-on:click="$dispatch('close')"
                    :attributes="new \Illuminate\View\ComponentAttributeBag($buttonAttributes)"
                    wire:loading.attr="disabled"
                >
                    {{ $confirmText }}
                </x-ui.button.danger>
            @elseif($confirmColor === 'warning')
                <x-ui.button.warning
                    x-on:click="$dispatch('close')"
                    :attributes="new \Illuminate\View\ComponentAttributeBag($buttonAttributes)"
                    wire:loading.attr="disabled"
                >
                    {{ $confirmText }}
                </x-ui.button.warning>
            @else
                <x-ui.button.primary
                    x-on:click="$dispatch('close')"
                    :attributes="new \Illuminate\View\ComponentAttributeBag($buttonAttributes)"
                    wire:loading.attr="disabled"
                >
                    {{ $confirmText }}
                </x-ui.button.primary>
            @endif
        </div>
    </x-slot>
</x-ui.modal.base>
