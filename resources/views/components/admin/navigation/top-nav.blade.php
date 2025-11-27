@props([
    'logo' => null,
    'appName' => config('app.name', 'Laravel'),
    'logoUrl' => '#',
])

<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Left Side: Logo & Navigation --}}
            <div class="flex">
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                    @if($logo)
                        {{ $logo }}
                    @else
                        <a href="{{ $logoUrl }}" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $appName }}
                        </a>
                    @endif
                </div>

                {{-- Primary Navigation --}}
                @isset($navigation)
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        {{ $navigation }}
                    </div>
                @endisset
            </div>

            {{-- Right Side: Actions --}}
            @isset($actions)
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    {{ $actions }}
                </div>
            @endisset

            {{-- Mobile Menu Button --}}
            <div class="flex items-center sm:hidden">
                <button 
                    x-data 
                    @click="$dispatch('toggle-mobile-menu')"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    @isset($mobileMenu)
        <div 
            x-data="{ open: false }"
            @toggle-mobile-menu.window="open = !open"
            x-show="open"
            x-transition
            class="sm:hidden"
        >
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-200 dark:border-gray-700">
                {{ $mobileMenu }}
            </div>
        </div>
    @endisset
</nav>
