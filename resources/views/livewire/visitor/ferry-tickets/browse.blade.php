<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10">
    {{-- Hero Search Section --}}
    <section class="relative py-16 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 left-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Page Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Book Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Ferry Ticket</span>
                </h1>
                <p class="text-xl text-brand-dark/70">Travel to Kabohera Fun Island in style</p>
            </div>

            {{-- Hotel Booking Alert --}}
            @if(!auth()->check())
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 max-w-3xl mx-auto">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Login Required</h3>
                            <p class="text-yellow-800">You must be logged in with a valid hotel booking to purchase ferry tickets.</p>
                            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="inline-block mt-3 text-brand-primary font-semibold hover:underline">Login Now →</a>
                        </div>
                    </div>
                </div>
            @elseif(!$hasValidBooking)
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 max-w-3xl mx-auto">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Valid Hotel Booking Required</h3>
                            <p class="text-red-800">You must have a confirmed hotel booking to purchase ferry tickets.</p>
                            <a href="{{ route('booking.search') }}" class="inline-block mt-3 text-brand-primary font-semibold hover:underline">Book a Hotel Room →</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 max-w-3xl mx-auto">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-900 mb-2">Hotel Booking Found</h3>
                            <p class="text-green-800">Your hotel stay: {{ $hotelBooking->check_in_date->format('M d, Y') }} - {{ $hotelBooking->check_out_date->format('M d, Y') }}</p>
                            <p class="text-sm text-green-700 mt-1">Room: {{ $hotelBooking->room->full_description }}</p>
                            <p class="text-sm text-green-700 mt-1">
                                <strong>Maximum {{ $maxPassengers }} {{ $maxPassengers == 1 ? 'passenger' : 'passengers' }}</strong> (based on room occupancy)
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Search Form Card --}}
            <div class="bg-white rounded-3xl shadow-2xl shadow-brand-primary/10 p-8 max-w-5xl mx-auto">
                <form wire:submit.prevent="search">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- Travel Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Travel Date
                            </label>
                            <input type="date" wire:model="travelDate"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                required
                                @if(!auth()->check() || !$hasValidBooking) disabled @endif>
                            @error('travelDate')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Route Selection --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Route
                            </label>
                            <select wire:model="routeId"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                @if(!auth()->check() || !$hasValidBooking) disabled @endif>
                                <option value="">All Routes</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->origin }} → {{ $route->destination }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Passengers --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Passengers
                                @if($hasValidBooking)
                                    <span class="text-xs text-gray-500 font-normal">(max: {{ $maxPassengers }})</span>
                                @endif
                            </label>
                            <input type="number" wire:model="passengers" min="1" max="{{ $maxPassengers }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                required
                                @if(!auth()->check() || !$hasValidBooking) disabled @endif>
                            @error('passengers')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Search Button --}}
                        <div class="flex items-end">
                            <button type="submit" wire:loading.attr="disabled"
                                @if(!auth()->check() || !$hasValidBooking) disabled @endif
                                class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove>Search</span>
                                <span wire:loading>Searching...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Search Results --}}
    @if(!empty($schedules))
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <h2 class="text-3xl font-bold text-brand-dark mb-8">Available Ferry Schedules</h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($schedules as $schedule)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow p-6">
                        {{-- Route Info --}}
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-brand-dark mb-2">
                                {{ $schedule['route']->origin }} → {{ $schedule['route']->destination }}
                            </h3>
                            <p class="text-sm text-gray-600">{{ $schedule['route']->name }}</p>
                        </div>

                        {{-- Schedule Details --}}
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Departs: {{ $schedule['departure_time'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Arrives: {{ $schedule['arrival_time'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ $schedule['available_seats'] }} seats available</span>
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="border-t border-gray-200 pt-4 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Ferry Service</span>
                                <span class="text-2xl font-bold text-green-600">FREE</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-gray-600">{{ $passengers }} {{ $passengers == 1 ? 'passenger' : 'passengers' }}</span>
                                <span class="text-lg font-semibold text-green-600">FREE</span>
                            </div>
                        </div>

                        {{-- Book Button --}}
                        <a href="{{ route('ferry-tickets.create', ['schedule' => $schedule['id'], 'date' => $travelDate, 'passengers' => $passengers]) }}"
                            class="block w-full bg-brand-primary hover:bg-brand-primary/90 text-white text-center px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                            Book Now
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @elseif(count($schedules) === 0 && !empty($travelDate))
        <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Schedules Available</h3>
                <p class="text-gray-600">No ferry schedules match your search criteria. Try adjusting your search parameters.</p>
            </div>
        </section>
    @endif
</div>
