@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-white dark:text-white mb-2']) }}>
    {{ $value ?? $slot }}
</label>
