<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Theme Park Staff</title>

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
    <x-admin.navigation.side-nav app-name="Theme Park Staff">
        <x-slot:logo>
            <a href="{{ route('theme-park.dashboard') }}" wire:navigate>
                <x-common.application-logo class="w-10 h-10 fill-current text-gray-500" />
            </a>
        </x-slot:logo>

        {{-- Navigation Links --}}
        <x-admin.navigation.nav-link :href="route('theme-park.dashboard')" :active="request()->routeIs('theme-park.dashboard')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'>
            {{ __('Dashboard') }}
        </x-admin.navigation.nav-link>

        {{-- Operations Section --}}
        <div class="mt-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ __('Operations') }}
            </h3>
        </div>

        <x-admin.navigation.nav-link :href="route('theme-park.activities.index')" :active="request()->routeIs('theme-park.activities.*')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'>
            {{ __('Activities') }}
        </x-admin.navigation.nav-link>

        {{-- Ticket Management Section --}}
        <div class="mt-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ __('Ticket Management') }}
            </h3>
        </div>

        <x-admin.navigation.nav-link :href="route('theme-park.validate')" :active="request()->routeIs('theme-park.validate')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'>
            {{ __('Validate Tickets') }}
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
