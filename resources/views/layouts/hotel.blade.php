<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Hotel Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased">
    <x-admin.navigation.side-nav app-name="Hotel Manager">
        <x-slot:logo>
            <a href="{{ route('hotel.dashboard') }}" wire:navigate>
                <x-common.application-logo class="w-10 h-10 fill-current text-gray-500" />
            </a>
        </x-slot:logo>

        {{-- Navigation Links --}}
        <x-admin.navigation.nav-link :href="route('hotel.dashboard')" :active="request()->routeIs('hotel.dashboard')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'>
            {{ __('Dashboard') }}
        </x-admin.navigation.nav-link>

        {{-- Hotel Management Navigation (shown when managing a specific hotel) --}}
        @if(request()->routeIs('hotel.manage') || request()->routeIs('hotel.rooms.*') || request()->routeIs('hotel.views.*') || request()->routeIs('hotel.amenities.*') || request()->routeIs('hotel.pricing.*') || request()->routeIs('hotel.policies.*') || request()->routeIs('hotel.images.*') || request()->routeIs('hotel.bookings.*') || request()->routeIs('hotel.availability.*') || request()->routeIs('hotel.reports.*'))

            {{-- Operations Section --}}
            <div class="mt-4 px-3">
                <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('Operations') }}
                </h3>
            </div>

            {{-- Coming Soon --}}
            {{-- <x-admin.navigation.nav-link :href="route('hotel.bookings.index')" :active="request()->routeIs('hotel.bookings.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                {{ __('Bookings') }}
            </x-admin.navigation.nav-link> --}}

            {{-- <x-admin.navigation.nav-link :href="route('hotel.availability.manage')" :active="request()->routeIs('hotel.availability.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>'>
                {{ __('Room Availability') }}
            </x-admin.navigation.nav-link> --}}

            {{-- Room Management Section --}}
            <div class="mt-4 px-3">
                <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('Room Management') }}
                </h3>
            </div>

            <x-admin.navigation.nav-link :href="route('hotel.rooms.index')" :active="request()->routeIs('hotel.rooms.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'>
                {{ __('Rooms') }}
            </x-admin.navigation.nav-link>

            <x-admin.navigation.nav-link :href="route('hotel.amenities.manage')" :active="request()->routeIs('hotel.amenities.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>'>
                {{ __('Amenities') }}
            </x-admin.navigation.nav-link>

            <x-admin.navigation.nav-link :href="route('hotel.images.manage')" :active="request()->routeIs('hotel.images.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                {{ __('Room Gallery') }}
            </x-admin.navigation.nav-link>

            {{-- Pricing & Policies Section --}}
            <div class="mt-4 px-3">
                <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('Pricing & Policies') }}
                </h3>
            </div>

            <x-admin.navigation.nav-link :href="route('hotel.pricing.manage')" :active="request()->routeIs('hotel.pricing.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'>
                {{ __('Pricing') }}
            </x-admin.navigation.nav-link>

            <x-admin.navigation.nav-link :href="route('hotel.policies.manage')" :active="request()->routeIs('hotel.policies.*')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'>
                {{ __('Policies') }}
            </x-admin.navigation.nav-link>

            {{-- Reports & Analytics Section --}}
            <div class="mt-4 px-3">
                <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('Reports & Analytics') }}
                </h3>
            </div>

            {{-- Coming Soon --}}
            {{-- <x-admin.navigation.nav-link :href="route('hotel.reports.occupancy')" :active="request()->routeIs('hotel.reports.occupancy')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'>
                {{ __('Occupancy Reports') }}
            </x-admin.navigation.nav-link> --}}

            {{-- <x-admin.navigation.nav-link :href="route('hotel.reports.revenue')" :active="request()->routeIs('hotel.reports.revenue')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'>
                {{ __('Revenue Analytics') }}
            </x-admin.navigation.nav-link> --}}

            {{-- <x-admin.navigation.nav-link :href="route('hotel.reports.bookings')" :active="request()->routeIs('hotel.reports.bookings')"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>'>
                {{ __('Booking History') }}
            </x-admin.navigation.nav-link> --}}
        @endif

        {{-- Top Bar --}}
        <x-slot:topBar>
            @if (isset($header))
                {{ $header }}
            @endif
        </x-slot:topBar>

        {{-- Main Content --}}
        <x-slot:content>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <x-admin.alert.success class="mb-4">
                            {{ session('success') }}
                        </x-admin.alert.success>
                    @endif

                    {{-- Error Message --}}
                    @if (session('error'))
                        <x-admin.alert.danger class="mb-4">
                            {{ session('error') }}
                        </x-admin.alert.danger>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </x-slot:content>
    </x-admin.navigation.side-nav>
    @livewireScripts
</body>

</html>