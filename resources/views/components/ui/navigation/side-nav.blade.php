@props([
    'logo' => null,
    'appName' => config('app.name', 'Laravel'),
    'width' => '64', // w-64 by default
])

<div 
    x-data="{ open: true }"
    class="flex h-screen bg-gray-100 dark:bg-gray-900"
>
    {{-- Sidebar --}}
    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-{{ $width }} bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
    >
        <div class="flex flex-col h-full">
            {{-- Logo/Header --}}
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                @if($logo)
                    {{ $logo }}
                @else
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $appName }}</span>
                @endif
                
                {{-- Close button for mobile --}}
                <button 
                    @click="open = false"
                    class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
                {{ $slot }}
            </nav>

            {{-- Footer (optional) --}}
            @isset($footer)
                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </aside>

    {{-- Overlay for mobile --}}
    <div 
        x-show="open"
        @click="open = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"
    ></div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar (optional) --}}
        @isset($topBar)
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Mobile menu button --}}
                    <button 
                        @click="open = true"
                        class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{ $topBar }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        @isset($content)
            <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900">
                {{ $content }}
            </main>
        @endisset
    </div>
</div>
