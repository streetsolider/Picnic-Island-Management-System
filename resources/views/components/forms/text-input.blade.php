@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'focus:ring-2 focus:ring-brand-primary rounded-xl shadow-lg px-4 py-3 transition-all outline-none bg-gray-50 dark:bg-gray-900 text-white dark:text-white placeholder-gray-400 dark:placeholder-gray-500']) }}>
