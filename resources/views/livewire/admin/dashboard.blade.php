<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Administrator Dashboard') }}
    </h2>
</x-slot>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Total Staff -->
    <x-admin.card.stat label="Total Staff" value="{{ $stats['total_staff'] }}" icon="users" color="blue">
        <x-slot:icon>
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>

    <!-- Total Guests -->
    <x-admin.card.stat label="Total Guests" value="{{ $stats['total_guests'] }}" color="green">
        <x-slot:icon>
            <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>

    <!-- Total Hotels -->
    <x-admin.card.stat label="Total Hotels" value="{{ $stats['total_hotels'] }}" color="orange">
        <x-slot:icon>
            <svg class="w-8 h-8 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </x-slot:icon>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ $stats['active_hotels'] }} active
        </div>
    </x-admin.card.stat>

    <!-- Theme Park Zones -->
    <x-admin.card.stat label="Theme Park Zones" value="{{ $stats['total_zones'] }}" color="indigo">
        <x-slot:icon>
            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-slot:icon>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ $stats['active_zones'] }} active
        </div>
    </x-admin.card.stat>

    <!-- Beach Services -->
    <x-admin.card.stat label="Beach Services" value="{{ $stats['total_beach_services'] }}" color="teal">
        <x-slot:icon>
            <svg class="w-8 h-8 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
        </x-slot:icon>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ $stats['active_beach_services'] }} active
        </div>
    </x-admin.card.stat>

    <!-- Hotel Managers -->
    <x-admin.card.stat label="Hotel Managers" value="{{ $stats['hotel_managers'] }}" color="purple">
        <x-slot:icon>
            <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>

    <!-- Ferry Operators -->
    <x-admin.card.stat label="Ferry Operators" value="{{ $stats['ferry_operators'] }}" color="cyan">
        <x-slot:icon>
            <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>

    <!-- Theme Park Staff -->
    <x-admin.card.stat label="Theme Park Staff" value="{{ $stats['theme_park_staff'] }}" color="pink">
        <x-slot:icon>
            <svg class="w-8 h-8 text-pink-600 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>

    <!-- Administrators -->
    <x-admin.card.stat label="Administrators" value="{{ $stats['administrators'] }}" color="red">
        <x-slot:icon>
            <svg class="w-8 h-8 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                </path>
            </svg>
        </x-slot:icon>
    </x-admin.card.stat>
</div>