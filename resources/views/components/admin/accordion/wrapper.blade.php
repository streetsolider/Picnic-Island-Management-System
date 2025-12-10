@props([
    'allowMultiple' => false, // Allow multiple items to be open at once
])

@php
$classes = 'space-y-2';

$attributes = $attributes->merge([
    'class' => $classes,
]);
@endphp

<div {{ $attributes }} x-data="{
    openItems: [],
    allowMultiple: {{ $allowMultiple ? 'true' : 'false' }},
    toggle(id) {
        if (this.allowMultiple) {
            // Toggle item in array
            if (this.openItems.includes(id)) {
                this.openItems = this.openItems.filter(item => item !== id);
            } else {
                this.openItems.push(id);
            }
        } else {
            // Only allow one item open at a time
            this.openItems = this.openItems.includes(id) ? [] : [id];
        }
    },
    isOpen(id) {
        return this.openItems.includes(id);
    }
}">
    {{ $slot }}
</div>
