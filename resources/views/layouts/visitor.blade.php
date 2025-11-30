<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Book Your Stay</title>

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

<body class="font-sans antialiased bg-brand-light">
    <div class="min-h-screen">
        {{-- Navigation --}}
        <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-white/95 backdrop-blur-md shadow-md': scrolled, 'bg-white shadow-sm': !scrolled }"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2" wire:navigate>
                            <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span class="text-xl font-display font-bold text-brand-dark">Kabohera Fun Island</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}"
                            class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium"
                            wire:navigate>Home</a>
                        @auth
                            {{-- <a href="{{ route('my-bookings') }}"
                                class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium"
                                wire:navigate>My Bookings</a> --}}
                            {{-- <a href="{{ route('profile') }}"
                                class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium"
                                wire:navigate>Profile</a> --}}
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">Login</a>
                            <a href="{{ route('register') }}"
                                class="bg-brand-secondary hover:bg-brand-secondary/90 text-white px-6 py-2 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30">
                                Register
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile menu button --}}
                    <button class="md:hidden p-2 text-brand-dark">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="pt-16">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-brand-dark border-t border-white/10 pt-12 pb-6 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span class="text-lg font-display font-bold">Kabohera Fun Island</span>
                        </div>
                        <p class="text-gray-400 max-w-md text-sm leading-relaxed">
                            Your ultimate destination for relaxation and adventure. Experience the magic of island life.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-brand-primary transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-brand-primary transition-colors">Our Services</a></li>
                            <li><a href="#" class="hover:text-brand-primary transition-colors">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Contact</h4>
                        <p class="text-gray-400 text-sm">hello@kabohera.com</p>
                        <p class="text-gray-400 text-sm">+1 (555) 123-4567</p>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 text-center text-gray-500 text-xs">
                    <p>&copy; 2024 Kabohera Fun Island. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
