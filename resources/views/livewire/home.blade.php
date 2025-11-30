<div class="min-h-screen bg-brand-light font-sans text-brand-dark">
    {{-- Navigation --}}
    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-white/90 backdrop-blur-md shadow-sm': scrolled, 'bg-transparent': !scrolled }"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="text-2xl font-display font-bold text-brand-dark">Kabohera Fun Island</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home"
                        class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">Home</a>
                    <a href="#services"
                        class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">Services</a>
                    <a href="#activities"
                        class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">Activities</a>
                    <a href="#about"
                        class="text-brand-dark/80 hover:text-brand-primary transition-colors font-medium">About</a>
                    <a href="{{ route('booking.search') }}" wire:navigate
                        class="bg-brand-secondary hover:bg-brand-secondary/90 text-white px-6 py-2.5 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30">
                        Book Now
                    </a>
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

    {{-- Hero Section --}}
    <section id="home" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        {{-- Background Gradient/Image --}}
        <div class="absolute inset-0 bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 -z-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-brand-primary/5 to-transparent -z-10">
        </div>

        {{-- Decorative Blobs --}}
        <div
            class="absolute top-20 left-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute top-40 right-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-brand-secondary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 text-center lg:text-left" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 300)">

                    <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-brand-primary/20 rounded-full px-4 py-1.5 shadow-sm"
                        x-show="show" x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <span class="w-2 h-2 rounded-full bg-brand-secondary animate-pulse"></span>
                        <span class="text-sm font-medium text-brand-dark/70">No. 1 Island Destination</span>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-display font-bold leading-tight text-brand-dark" x-show="show"
                        x-transition:enter="transition ease-out duration-700 delay-200"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        Discover Your <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Perfect
                            Escape</span>
                    </h1>

                    <p class="text-xl text-brand-dark/70 max-w-2xl mx-auto lg:mx-0 leading-relaxed" x-show="show"
                        x-transition:enter="transition ease-out duration-700 delay-400"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        Immerse yourself in the beauty of Kabohera Fun Island. Where turquoise waters meet golden sands,
                        and adventure awaits at every turn.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4"
                        x-show="show" x-transition:enter="transition ease-out duration-700 delay-600"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <a href="{{ route('booking.search') }}" wire:navigate
                            class="w-full sm:w-auto bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-4 rounded-full font-semibold text-lg transition-all transform hover:scale-105 shadow-xl shadow-brand-primary/30 flex items-center justify-center gap-2">
                            Start Your Journey
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <button
                            class="w-full sm:w-auto bg-white hover:bg-gray-50 text-brand-dark border border-gray-200 px-8 py-4 rounded-full font-semibold text-lg transition-all hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-brand-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Watch Video
                        </button>
                    </div>
                </div>

                <div class="relative hidden lg:block" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 600)">
                    <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl shadow-brand-primary/20 transform rotate-2 hover:rotate-0 transition-all duration-500"
                        x-show="show" x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0 translate-x-12"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="aspect-[4/5] bg-gray-200 relative group">
                            {{-- Placeholder for Hero Image --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10"></div>
                            <img src="https://images.unsplash.com/photo-1540206351-d6465b3ac5c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                alt="Island Resort"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                            <div class="absolute bottom-8 left-8 z-20 text-white">
                                <p class="text-sm font-medium uppercase tracking-wider mb-2">Featured Resort</p>
                                <h3 class="text-2xl font-display font-bold">Ocean View Paradise</h3>
                                <div class="flex items-center mt-2 space-x-1">
                                    <svg class="w-5 h-5 text-brand-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <span class="font-bold">4.9</span>
                                    <span class="text-white/80">(2.5k reviews)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Cards --}}
                    <div class="absolute -bottom-10 -left-10 bg-white p-4 rounded-2xl shadow-xl z-20 animate-bounce-slow"
                        x-show="show" x-transition:enter="transition ease-out duration-700 delay-500"
                        x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-brand-secondary/10 flex items-center justify-center text-brand-secondary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Limited Offer</p>
                                <p class="text-brand-dark font-bold">30% Off Today</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="services" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" x-data="{ shown: false }"
                x-intersect.threshold.50="shown = true">
                <h2 class="text-brand-secondary font-semibold tracking-wide uppercase mb-3"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="transition duration-700 ease-out">Our Services</h2>
                <h3 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-6"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="transition duration-700 delay-100 ease-out">Experience Luxury & Adventure</h3>
                <p class="text-xl text-gray-600"
                    :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="transition duration-700 delay-200 ease-out">We provide everything you need for an
                    unforgettable stay, from world-class accommodation to thrilling activities.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $services = [
                        ['title' => 'Luxury Hotels', 'desc' => 'Stay in our premium beachfront villas with private pools and stunning views.', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'bg-blue-50 text-blue-600'],
                        ['title' => 'Ferry Services', 'desc' => 'Seamless transport between islands with our modern fleet of high-speed ferries.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'color' => 'bg-orange-50 text-orange-600'],
                        ['title' => 'Theme Park', 'desc' => 'Endless fun for the whole family with rollercoasters, water rides, and shows.', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-yellow-50 text-yellow-600'],
                    ];
                @endphp

                @foreach($services as $index => $service)
                    <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300"
                        x-data="{ shown: false }" x-intersect.threshold.20="shown = true"
                        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        class="transition duration-700 ease-out" style="transition-delay: {{ $index * 150 }}ms">
                        <div
                            class="w-16 h-16 {{ $service['color'] }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $service['icon'] }}"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-display font-bold text-brand-dark mb-4">{{ $service['title'] }}</h4>
                        <p class="text-gray-600 mb-6 leading-relaxed">{{ $service['desc'] }}</p>
                        <a href="#"
                            class="inline-flex items-center text-brand-primary font-semibold group-hover:translate-x-2 transition-transform">
                            Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Color Theme Display Section --}}
    <section class="py-20 bg-brand-dark text-white relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div x-data="{ shown: false }" x-intersect.threshold.50="shown = true">
                    <h2 class="text-3xl md:text-4xl font-display font-bold mb-6"
                        :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                        class="transition duration-700 ease-out">Designed for Paradise</h2>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed"
                        :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                        class="transition duration-700 delay-100 ease-out">
                        Our visual identity reflects the natural beauty of the island. From the deep blues of the ocean
                        to the warm hues of the sunset, every color tells a story.
                    </p>

                    <div class="space-y-6" :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                        class="transition duration-700 delay-200 ease-out">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-brand-primary shadow-lg shadow-brand-primary/20"></div>
                            <div>
                                <p class="font-bold text-lg">Ocean Blue</p>
                                <p class="text-gray-500 font-mono text-sm">#0EA5E9</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-brand-secondary shadow-lg shadow-brand-secondary/20">
                            </div>
                            <div>
                                <p class="font-bold text-lg">Sunset Orange</p>
                                <p class="text-gray-500 font-mono text-sm">#F97316</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-brand-accent shadow-lg shadow-brand-accent/20"></div>
                            <div>
                                <p class="font-bold text-lg">Golden Sand</p>
                                <p class="text-gray-500 font-mono text-sm">#FCD34D</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ shown: false }" x-intersect.threshold.50="shown = true">
                    <div class="grid grid-cols-2 gap-4" :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                        class="transition duration-1000 ease-out">
                        <div class="space-y-4 mt-8">
                            <div class="h-40 bg-brand-light/10 rounded-2xl backdrop-blur-sm p-4 border border-white/10">
                            </div>
                            <div class="h-56 bg-brand-primary rounded-2xl p-6 flex flex-col justify-end">
                                <p class="font-display font-bold text-2xl">Vibrant</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-56 bg-brand-secondary rounded-2xl p-6 flex flex-col justify-end">
                                <p class="font-display font-bold text-2xl">Warm</p>
                            </div>
                            <div class="h-40 bg-brand-light/10 rounded-2xl backdrop-blur-sm p-4 border border-white/10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-brand-dark border-t border-white/10 pt-16 pb-8 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span class="text-2xl font-display font-bold">Kabohera Fun Island</span>
                    </div>
                    <p class="text-gray-400 max-w-md leading-relaxed">
                        Your ultimate destination for relaxation and adventure. Experience the magic of island life with
                        our world-class amenities and services.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-6">Quick Links</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-brand-primary transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-brand-primary transition-colors">Our Services</a></li>
                        <li><a href="#" class="hover:text-brand-primary transition-colors">Destinations</a></li>
                        <li><a href="#" class="hover:text-brand-primary transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-6">Contact</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            hello@kabohera.com
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            +1 (555) 123-4567
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                <p>&copy; 2024 Kabohera Fun Island. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>