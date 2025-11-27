@props([
    'name',
    'show' => false,
    'title' => null,
    'maxWidth' => '2xl',
    'submitText' => 'Save',
    'cancelText' => 'Cancel',
    'submitColor' => 'primary', // primary, danger, warning, success
    'loading' => null, // Target for wire:loading
])

<x-ui.modal.base
    :name="$name"
    :show="$show"
    :title="$title"
    :maxWidth="$maxWidth"
    {{ $attributes->whereStartsWith('x-') }}
>
    <form {{ $attributes->whereDoesntStartWith('x-') }}>
        <div class="space-y-6">
            {{ $slot }}
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-ui.button.secondary
                type="button"
                x-on:click="$dispatch('close')"
                wire:loading.attr="disabled"
                :wire:target="$loading"
            >
                {{ $cancelText }}
            </x-ui.button.secondary>

            @if($submitColor === 'danger')
                <x-ui.button.danger
                    type="submit"
                    wire:loading.attr="disabled"
                    :wire:target="$loading"
                >
                    {{ $submitText }}
                </x-ui.button.danger>
            @elseif($submitColor === 'warning')
                <x-ui.button.warning
                    type="submit"
                    wire:loading.attr="disabled"
                    :wire:target="$loading"
                >
                    {{ $submitText }}
                </x-ui.button.warning>
            @elseif($submitColor === 'success')
                <x-ui.button.success
                    type="submit"
                    wire:loading.attr="disabled"
                    :wire:target="$loading"
                >
                    {{ $submitText }}
                </x-ui.button.success>
            @else
                <x-ui.button.primary
                    type="submit"
                    wire:loading.attr="disabled"
                    :wire:target="$loading"
                >
                    {{ $submitText }}
                </x-ui.button.primary>
            @endif
        </div>
    </form>
</x-ui.modal.base>
