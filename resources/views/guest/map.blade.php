<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Island Map - Kabohera Fun Island</title>

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
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <img src="{{ asset('images/kabohera-logo.png') }}" alt="Kabohera Fun Island"
                                class="h-10 w-auto">
                            <span class="text-xl font-display font-bold text-brand-dark hidden sm:inline">Kabohera Fun
                                Island</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}"
                            class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">Home</a>
                        <a href="{{ route('map') }}" class="text-brand-primary font-semibold">Map</a>
                        @auth
                            <a href="{{ route('my-bookings') }}"
                                class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">My
                                Bookings</a>

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
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 text-brand-dark relative z-50">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" @click.away="mobileMenuOpen = false"
                    class="md:hidden border-t border-gray-100 bg-white">
                    <div class="px-4 py-4 space-y-3">
                        <a href="{{ route('home') }}"
                            class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                            Home
                        </a>
                        <a href="{{ route('map') }}"
                            class="block px-4 py-2 bg-brand-primary/5 text-brand-primary rounded-lg font-semibold">
                            Map
                        </a>
                        @auth
                            <a href="{{ route('my-bookings') }}"
                                class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                My Bookings
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 text-brand-dark hover:bg-brand-primary/5 hover:text-brand-primary rounded-lg font-medium transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
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
            <div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
                {{-- Hero Section --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                            Explore <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Kabohera
                                Island</span>
                        </h1>
                        <p class="text-xl text-gray-600">Discover hotels, theme parks, and beach activities across the
                            island</p>
                    </div>
                </div>

                {{-- Map Container --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-brand-primary/10" x-data="{
                activeMarkerId: null,
                scale: 1,
                mobileActiveMarker: null,

                toggleMarker(markerId) {
                    this.activeMarkerId = this.activeMarkerId === markerId ? null : markerId;
                },

                triggerMobileCard(data, id) {
                    this.mobileActiveMarker = data;
                    window.dispatchEvent(new CustomEvent('set-active-marker', { detail: id }));
                },

                closeMobileCard() {
                    this.mobileActiveMarker = null;
                    window.dispatchEvent(new CustomEvent('set-active-marker', { detail: null }));
                },

                init() {
                    const elem = this.$refs.mapContainer;
                    this.panzoom = window.Panzoom(elem, {
                        maxScale: 4,
                        minScale: 1,
                        contain: 'outside',
                        startScale: 1
                    });
                    
                    elem.addEventListener('panzoomchange', (e) => {
                        this.scale = e.detail.scale;
                    });

                    elem.parentElement.addEventListener('wheel', this.panzoom.zoomWithWheel);
                },
                zoomIn() { this.panzoom.zoomIn() },
                zoomOut() { this.panzoom.zoomOut() },
                reset() { this.panzoom.reset() }
            }"
            @open-mobile-card.stop="triggerMobileCard($event.detail.data, $event.detail.id)">
                        {{-- Mobile Bottom Sheet --}}
                        <div x-show="mobileActiveMarker" 
                            x-cloak
                            class="fixed inset-0 z-[200] md:hidden"
                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            
                            {{-- Backdrop --}}
                            <div x-show="mobileActiveMarker"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-gray-500/75 transition-opacity" 
                                @click="closeMobileCard()"></div>

                            {{-- Panel --}}
                            <div class="fixed inset-x-0 bottom-0 z-10 w-full overflow-y-auto bg-white rounded-t-3xl shadow-xl p-6 sm:p-8 transform transition-all"
                                x-show="mobileActiveMarker"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="translate-y-full"
                                x-transition:enter-end="translate-y-0"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="translate-y-0"
                                x-transition:leave-end="translate-y-full">
                                
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <img :src="mobileActiveMarker?.image" class="w-10 h-10 rounded-full border border-gray-200" alt="">
                                        <h3 class="text-xl font-bold text-gray-900" x-text="mobileActiveMarker?.name"></h3>
                                    </div>
                                    <button @click="closeMobileCard()" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <p class="text-gray-600 mb-6 leading-relaxed" x-text="mobileActiveMarker?.description"></p>
                                
                                <template x-if="mobileActiveMarker?.type === 'App\\Models\\Hotel'">
                                    <template x-if="mobileActiveMarker?.isActive">
                                        <a href="{{ route('booking.search') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-brand-primary active:bg-brand-primary/90 text-white rounded-xl font-bold text-lg transition-all shadow-lg">
                                            Book Hotel
                                        </a>
                                    </template>
                                    <template x-if="!mobileActiveMarker?.isActive">
                                        <div class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-red-50 text-red-600 rounded-xl font-bold text-lg">
                                            Currently Unavailable
                                        </div>
                                    </template>
                                </template>
                                
                                <template x-if="mobileActiveMarker?.type !== 'App\\Models\\Hotel'">
                                    <div class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold text-lg">
                                        View Details
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Controls --}}
                        <div class="absolute bottom-6 right-6 z-20 flex flex-col gap-2">
                            <button @click="zoomIn()"
                                class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-brand-dark hover:bg-gray-50 focus:outline-none transition-transform active:scale-95"
                                type="button" aria-label="Zoom In">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            <button @click="zoomOut()"
                                class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-brand-dark hover:bg-gray-50 focus:outline-none transition-transform active:scale-95"
                                type="button" aria-label="Zoom Out">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>
                            <button @click="reset()"
                                class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-brand-dark hover:bg-gray-50 focus:outline-none transition-transform active:scale-95"
                                type="button" aria-label="Reset Zoom">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        {{-- Legend --}}
                        <div
                            class="bg-gradient-to-r from-brand-primary/5 to-brand-secondary/5 p-4 border-b border-gray-100">
                            <div class="flex flex-wrap justify-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/map/hotel_pin.png') }}"
                                        class="w-6 h-6 rounded-full border-2 border-white shadow-sm" alt="Hotel">
                                    <span class="font-semibold text-brand-dark">Hotels</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/map/themepark_pin.png') }}"
                                        class="w-6 h-6 rounded-full border-2 border-white shadow-sm" alt="Theme Park">
                                    <span class="font-semibold text-brand-dark">Theme Parks</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/map/beach_pin.png') }}"
                                        class="w-6 h-6 rounded-full border-2 border-white shadow-sm" alt="Beach">
                                    <span class="font-semibold text-brand-dark">Beach Activities</span>
                                </div>
                            </div>
                        </div>

                        {{-- Map --}}
                        <div
                            class="p-4 sm:p-6 lg:p-8 relative bg-gradient-to-br from-blue-50 to-brand-primary/5 overflow-hidden rounded-2xl">
                            <div x-ref="mapContainer" class="relative origin-top-left">
                                {{-- Map Image --}}
                                <img src="{{ str_starts_with($mapImagePath, 'images/') ? asset($mapImagePath) : asset('storage/' . $mapImagePath) }}"
                                    class="w-full h-auto object-cover rounded-2xl shadow-xl">

                                {{-- Markers --}}
                                @foreach($markers as $marker)
                                    <div class="absolute hover:z-50 origin-center"
                                        :class="{ 'z-50': showTooltip || isActive, 'z-10': !showTooltip && !isActive }"
                                        :style="`left: {{ $marker->x_position }}%; top: {{ $marker->y_position }}%; transform: translate(-50%, -50%) scale(${1/scale})`"
                                        x-data="{
                                            showTooltip: false,
                                            isActive: false, // Local active state for mobile z-index
                                            markerId: {{ $marker->id }},
                                            // Data for mobile card
                                            markerData: {
                                                name: '{{ addslashes($marker->mappable->name ?? 'Location') }}',
                                                description: '{{ addslashes($marker->mappable->description ?? 'Explore this amazing location on Kabohera Island') }}',
                                                type: '{{ $marker->mappable_type }}',
                                                isActive: {{ $marker->mappable->is_active ?? 'true' }},
                                                image: '{{ $marker->mappable_type === 'App\Models\Hotel' ? asset('images/map/hotel_pin.png') : ($marker->mappable_type === 'App\Models\ThemeParkActivity' ? asset('images/map/themepark_pin.png') : asset('images/map/beach_pin.png')) }}'
                                            },
                                            toggle() {
                                                if (window.matchMedia('(hover: none)').matches) {
                                                    $dispatch('open-mobile-card', { data: this.markerData, id: this.markerId });
                                                }
                                            },
                                            hoverOn() { if (window.matchMedia('(hover: hover)').matches) this.showTooltip = true },
                                            hoverOff() { if (window.matchMedia('(hover: hover)').matches) this.showTooltip = false },
                                            handleActiveChange(e) {
                                                this.isActive = (e.detail === this.markerId);
                                            }
                                        }"
                                        @mouseenter="hoverOn"
                                        @mouseleave="hoverOff"
                                        @click.outside="showTooltip = false"
                                        @set-active-marker.window="handleActiveChange($event)">

                                        <button @click="toggle" class="transition-all duration-200 focus:outline-none group relative">

                                            @if($marker->mappable_type === 'App\Models\Hotel')
                                                <img src="{{ asset('images/map/hotel_pin.png') }}"
                                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 border-3 border-white rounded-full object-cover shadow-lg group-hover:scale-125 group-hover:shadow-xl transition-all">
                                            @elseif($marker->mappable_type === 'App\Models\ThemeParkActivity')
                                                <img src="{{ asset('images/map/themepark_pin.png') }}"
                                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 border-3 border-white rounded-full object-cover shadow-lg group-hover:scale-125 group-hover:shadow-xl transition-all">
                                            @elseif($marker->mappable_type === 'App\Models\BeachActivity')
                                                <img src="{{ asset('images/map/beach_pin.png') }}"
                                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 border-3 border-white rounded-full object-cover shadow-lg group-hover:scale-125 group-hover:shadow-xl transition-all">
                                            @endif

                                            <span class="sr-only">{{ $marker->mappable->name ?? 'Marker' }}</span>
                                        </button>

                                        {{-- Card Tooltip (Desktop Only) --}}
                                        <div x-show="showTooltip"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-2"
                                            class="hidden md:block absolute bottom-full left-1/2 -translate-x-1/2 pb-4 z-[100] origin-bottom"
                                            style="width: max-content">

                                            <div class="bg-white rounded-2xl shadow-2xl p-5 w-72 border border-gray-100 relative">
                                                {{-- Location Name --}}
                                                <h4 class="text-lg font-bold text-brand-dark mb-2 pr-6">
                                                    {{ $marker->mappable->name ?? 'Location' }}
                                                </h4>

                                                {{-- Description --}}
                                                <p class="text-sm text-gray-600 mb-4 leading-relaxed line-clamp-3">
                                                    {{ $marker->mappable->description ?? 'Explore this amazing location on Kabohera Island' }}
                                                </p>

                                                {{-- Action Button --}}
                                                @if($marker->mappable_type === 'App\Models\Hotel')
                                                    @if($marker->mappable->is_active ?? true)
                                                        <a href="{{ route('booking.search') }}"
                                                            class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl font-semibold text-sm transition-all shadow-md hover:shadow-lg">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Book Hotel
                                                        </a>
                                                    @else
                                                        <div class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-semibold text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Currently Unavailable
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        View Details
                                                    </div>
                                                @endif

                                                {{-- Arrow pointer --}}
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-px text-white drop-shadow-sm">
                                                     <svg width="24" height="12" viewBox="0 0 24 12" fill="currentColor">
                                                        <path d="M0 0L12 12L24 0H0Z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        {{-- Empty State --}}
                        @if($markers->isEmpty())
                            <div class="p-12 text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                    </path>
                                </svg>
                                <h3 class="text-xl font-display font-bold text-brand-dark mb-2">
                                    Map Coming Soon
                                </h3>
                                <p class="text-gray-600">
                                    We're currently placing locations on the map. Check back soon!
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info Section --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-display font-bold text-brand-dark mb-2">How to Use the Map</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Click on any marker to view details about hotels, theme parks, and beach
                                    activities.
                                    You can book directly from the location popup or browse our full selection of
                                    accommodations and activities.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-brand-dark border-t border-white/10 pt-12 pb-6 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('images/kabohera-logo.png') }}" alt="Kabohera Fun Island"
                                class="h-8 w-auto">
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
</body>

</html>