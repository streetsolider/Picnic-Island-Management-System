<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kabohera Fun Island - Book Your Stay</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

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
        <nav x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-white/95 backdrop-blur-md shadow-md': scrolled, 'bg-white shadow-sm': !scrolled }"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3" wire:navigate>
                            <img src="{{ asset('images/kabohera-logo.png') }}" alt="Kabohera Fun Island" class="h-10 w-auto">
                            <span class="text-xl font-display font-bold text-brand-dark hidden sm:inline">Kabohera Fun Island</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}"
                            class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium"
                            wire:navigate>Home</a>
                        @auth
                            <a href="{{ route('my-bookings') }}"
                                class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium"
                                wire:navigate>My Bookings</a>

                            {{-- Theme Park Menu --}}
                            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                                <button @click="open = !open"
                                    class="flex items-center text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">
                                    Theme Park
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                    <a href="{{ route('visitor.theme-park.wallet') }}" wire:navigate
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Wallet & Tickets
                                    </a>
                                    <a href="{{ route('visitor.theme-park.activities') }}" wire:navigate
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Browse Activities
                                    </a>
                                    <a href="{{ route('visitor.theme-park.redemptions') }}" wire:navigate
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        My Redemptions
                                    </a>
                                </div>
                            </div>

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
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-brand-dark relative z-50">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     @click.away="mobileMenuOpen = false"
                     class="md:hidden border-t border-gray-100 bg-white">
                    <div class="px-4 py-4 space-y-3">
                        <a href="{{ route('home') }}" wire:navigate
                            class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                            Home
                        </a>
                        @auth
                            <a href="{{ route('my-bookings') }}" wire:navigate
                                class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                My Bookings
                            </a>

                            {{-- Theme Park Mobile Menu --}}
                            <div class="space-y-1">
                                <div class="px-4 py-2 text-sm font-semibold text-gray-500">Theme Park</div>
                                <a href="{{ route('visitor.theme-park.wallet') }}" wire:navigate
                                    class="block px-4 py-2 pl-8 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg transition-colors">
                                    Wallet & Tickets
                                </a>
                                <a href="{{ route('visitor.theme-park.activities') }}" wire:navigate
                                    class="block px-4 py-2 pl-8 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg transition-colors">
                                    Browse Activities
                                </a>
                                <a href="{{ route('visitor.theme-park.redemptions') }}" wire:navigate
                                    class="block px-4 py-2 pl-8 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg transition-colors">
                                    My Redemptions
                                </a>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" wire:navigate
                                class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" wire:navigate
                                class="block px-4 py-2 bg-brand-secondary text-white hover:bg-brand-secondary/90 rounded-lg font-semibold text-center transition-colors">
                                Register
                            </a>
                        @endauth
                    </div>
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
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('images/kabohera-logo.png') }}" alt="Kabohera Fun Island" class="h-8 w-auto">
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
