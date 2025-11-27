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
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <div x-data="{ sidebarOpen: false }" class="flex h-screen">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 bg-blue-600 dark:bg-blue-800">
                        <h1 class="text-xl font-bold text-white">Hotel Manager</h1>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 overflow-y-auto py-4">
                        <a href="{{ route('hotel.dashboard') }}"
                            class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('hotel.dashboard') ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <div class="mt-4">
                            <div
                                class="px-6 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Room Management
                            </div>
                            <a href="{{ route('hotel.rooms.index') }}"
                                class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('hotel.rooms.*') ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                All Rooms
                            </a>

                            <a href="{{ route('hotel.views.manage') }}"
                                class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('hotel.views.*') ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Room Views
                            </a>
                        </div>

                        <div class="mt-4">
                            <div
                                class="px-6 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Amenities
                            </div>
                            <a href="{{ route('hotel.amenities.categories') }}"
                                class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('hotel.amenities.categories') ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Categories
                            </a>

                            <a href="{{ route('hotel.amenities.items') }}"
                                class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('hotel.amenities.items') ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Amenity Items
                            </a>
                        </div>
                    </nav>

                    <!-- User Menu -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ auth('staff')->user()->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Hotel Manager
                                </div>
                            </div>
                            <x-ui.theme.toggle />
                            <form method="POST" action="{{ route('staff.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white dark:bg-gray-800 shadow-sm">
                    <div class="flex items-center justify-between px-6 py-4">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-500 dark:text-gray-400 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        @if (isset($header))
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                {{ $header }}
                            </h2>
                        @endif
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                    <div class="container mx-auto px-6 py-8">
                        @if (session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>