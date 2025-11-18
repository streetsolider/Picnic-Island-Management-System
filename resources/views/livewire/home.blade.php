<div class="min-h-screen bg-white">
    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xl font-display font-bold text-gray-900">Kabohera Fun Island</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services" class="text-gray-700 hover:text-primary-600 transition-colors">Services</a>
                    <a href="#hotels" class="text-gray-700 hover:text-primary-600 transition-colors">Hotels</a>
                    <a href="#activities" class="text-gray-700 hover:text-primary-600 transition-colors">Activities</a>
                    <a href="#contact" class="text-gray-700 hover:text-primary-600 transition-colors">Contact</a>
                    <button class="btn btn-primary">Book Now</button>
                </div>

                {{-- Mobile menu button --}}
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="pt-24 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center space-x-2 bg-primary-50 text-primary-700 px-4 py-2 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span>Premier Island Destination</span>
                    </div>

                    <h1 class="text-5xl md:text-6xl font-display font-bold text-gray-900 leading-tight">
                        Welcome to<br>
                        <span class="text-primary-600">Kabohera Fun Island</span>
                    </h1>

                    <p class="text-xl text-gray-600 leading-relaxed">
                        Experience the ultimate island getaway with luxurious hotels, thrilling theme park adventures, pristine beaches, and unforgettable memories.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <button class="btn btn-primary text-lg px-8 py-3">
                            Start Your Adventure
                        </button>
                        <button class="btn btn-outline text-lg px-8 py-3">
                            Explore Activities
                        </button>
                    </div>

                    <div class="flex items-center space-x-8 pt-4">
                        <div>
                            <div class="text-3xl font-bold text-gray-900">500+</div>
                            <div class="text-sm text-gray-600">Happy Guests</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">50+</div>
                            <div class="text-sm text-gray-600">Activities</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">4.9</div>
                            <div class="text-sm text-gray-600">Rating</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-square rounded-2xl bg-gradient-to-br from-primary-100 to-secondary-100 flex items-center justify-center">
                        <svg class="w-64 h-64 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-lg p-4 border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Special Offers</div>
                                <div class="text-sm text-gray-600">Up to 30% off</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="services" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Everything you need for a perfect island vacation</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Hotel Service --}}
                <div class="card card-hover group">
                    <div class="p-6 space-y-4">
                        <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                            <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Luxury Hotels</h3>
                        <p class="text-gray-600">Beachfront accommodations with stunning ocean views and world-class amenities.</p>
                        <a href="#hotels" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Learn more
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Ferry Service --}}
                <div class="card card-hover group">
                    <div class="p-6 space-y-4">
                        <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center group-hover:bg-secondary-500 transition-colors">
                            <svg class="w-7 h-7 text-secondary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Ferry Service</h3>
                        <p class="text-gray-600">Comfortable and scenic ferry rides connecting you to paradise.</p>
                        <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Learn more
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Theme Park --}}
                <div class="card card-hover group">
                    <div class="p-6 space-y-4">
                        <div class="w-14 h-14 bg-accent-100 rounded-xl flex items-center justify-center group-hover:bg-accent-500 transition-colors">
                            <svg class="w-7 h-7 text-accent-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Theme Park</h3>
                        <p class="text-gray-600">Thrilling rides, exciting shows, and entertainment for all ages.</p>
                        <a href="#activities" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Learn more
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Beach Activities --}}
                <div class="card card-hover group">
                    <div class="p-6 space-y-4">
                        <div class="w-14 h-14 bg-warning-100 rounded-xl flex items-center justify-center group-hover:bg-warning-500 transition-colors">
                            <svg class="w-7 h-7 text-warning-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Beach Activities</h3>
                        <p class="text-gray-600">Snorkeling, diving, water sports, and relaxing beach experiences.</p>
                        <a href="#activities" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Learn more
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Hotels --}}
    <section id="hotels" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">Featured Hotels</h2>
                    <p class="text-xl text-gray-600">Luxurious beachfront accommodations</p>
                </div>
                <button class="btn btn-outline hidden md:inline-flex">View All Hotels</button>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                <div class="card card-hover">
                    <div class="h-56 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-semibold text-gray-900">Ocean View Resort {{ $i }}</h3>
                            <span class="badge badge-success">Available</span>
                        </div>
                        <p class="text-gray-600 mb-4">Luxurious beachfront rooms with breathtaking sunset views and premium amenities.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-sm text-gray-500">Starting from</span>
                                <div class="text-2xl font-bold text-primary-600">$299<span class="text-sm text-gray-500 font-normal">/night</span></div>
                            </div>
                            <button class="btn btn-primary">Book Now</button>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Activities Section --}}
    <section id="activities" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-gray-900 mb-4">Popular Activities</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Endless adventures await you</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Activity cards --}}
                @php
                $activities = [
                    ['name' => 'Water Rides', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'color' => 'primary'],
                    ['name' => 'Beach Volleyball', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2', 'color' => 'secondary'],
                    ['name' => 'Snorkeling', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'color' => 'accent'],
                    ['name' => 'Live Shows', 'icon' => 'M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z', 'color' => 'warning'],
                    ['name' => 'Island Tours', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'info'],
                    ['name' => 'Adventure Rides', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'error'],
                ]
                @endphp

                @foreach($activities as $activity)
                <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-{{ $activity['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activity['icon'] }}"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $activity['name'] }}</h4>
                            <p class="text-gray-600 text-sm mb-3">Experience the thrill and excitement</p>
                            <button class="text-primary-600 hover:text-primary-700 font-medium text-sm inline-flex items-center">
                                Book Activity
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-primary-500 to-primary-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">Ready for Your Island Adventure?</h2>
            <p class="text-xl text-primary-100 mb-8">Book your stay today and create unforgettable memories at Kabohera Fun Island</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="btn bg-white text-primary-600 hover:bg-gray-50 text-lg px-8 py-3">
                    Book Your Stay
                </button>
                <button class="btn bg-primary-600 text-white hover:bg-primary-800 border-2 border-white text-lg px-8 py-3">
                    Contact Us
                </button>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer id="contact" class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-lg font-display font-bold">Kabohera Fun Island</span>
                    </div>
                    <p class="text-gray-400 text-sm">Your paradise destination for unforgettable experiences.</p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#hotels" class="hover:text-white transition-colors">Hotels</a></li>
                        <li><a href="#activities" class="hover:text-white transition-colors">Activities</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Hotel Booking</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Ferry Tickets</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Theme Park</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Beach Events</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>Email: info@kabohera.com</li>
                        <li>Phone: +123 456 7890</li>
                        <li>Address: Kabohera Island</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">&copy; 2024 Kabohera Fun Island. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>
