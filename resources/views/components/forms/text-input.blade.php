@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-transparent focus:ring-2 focus:ring-brand-primary rounded-xl shadow-sm px-4 py-3 transition-all outline-none']) }}>
