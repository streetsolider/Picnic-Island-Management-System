<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Ferry Operations</title>

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
    <x-admin.navigation.side-nav app-name="Ferry Operator">
        <x-slot:logo>
            <a href="{{ route('ferry.dashboard') }}" wire:navigate>
                <x-common.application-logo class="w-10 h-10 fill-current text-gray-500" />
            </a>
        </x-slot:logo>

        {{-- Navigation Links --}}
        <x-admin.navigation.nav-link :href="route('ferry.dashboard')" :active="request()->routeIs('ferry.dashboard')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'>
            {{ __('Dashboard') }}
        </x-admin.navigation.nav-link>

        {{-- Operations Section --}}
        <div class="mt-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ __('Operations') }}
            </h3>
        </div>

        <x-admin.navigation.nav-link :href="route('ferry.routes.index')" :active="request()->routeIs('ferry.routes.*')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>'>
            {{ __('Routes') }}
        </x-admin.navigation.nav-link>

        <x-admin.navigation.nav-link :href="route('ferry.schedules.index')" :active="request()->routeIs('ferry.schedules.*')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
            {{ __('Schedules') }}
        </x-admin.navigation.nav-link>

        {{-- Ticket Management Section --}}
        <div class="mt-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ __('Ticket Management') }}
            </h3>
        </div>

        <x-admin.navigation.nav-link :href="route('ferry.tickets.validate')" :active="request()->routeIs('ferry.tickets.validate')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'>
            {{ __('Validate Tickets') }}
        </x-admin.navigation.nav-link>

        <x-admin.navigation.nav-link :href="route('ferry.tickets.passengers')" :active="request()->routeIs('ferry.tickets.passengers')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'>
            {{ __('Passenger Lists') }}
        </x-admin.navigation.nav-link>

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
