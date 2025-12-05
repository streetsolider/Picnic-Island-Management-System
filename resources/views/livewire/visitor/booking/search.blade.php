<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10">
    {{-- Hero Search Section --}}
    <section class="relative py-16 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div
            class="absolute top-0 left-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute top-20 right-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Page Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Find Your Perfect <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Stay</span>
                </h1>
                <p class="text-xl text-brand-dark/70">Discover luxury accommodations on Kabohera Fun Island</p>
            </div>

            {{-- Search Form Card --}}
            <div class="bg-white rounded-3xl shadow-2xl shadow-brand-primary/10 p-8 max-w-5xl mx-auto">
                <form wire:submit.prevent="search">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        {{-- Check-in Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Check-in
                            </label>
                            <input type="date" wire:model="checkIn"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                required>
                            @error('checkIn')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Check-out Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Check-out
                            </label>
                            <input type="date" wire:model="checkOut"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                required>
                            @error('checkOut')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Guests (Number Input) --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Guests
                            </label>
                            <input type="number" wire:model="guests" min="1" max="20"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"
                                required>
                            @error('guests')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Search Button --}}
                        <div class="flex items-end">
                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30 flex items-center justify-center gap-2">
                                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <svg wire:loading
                                    class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span wire:loading.remove>Search</span>
                                <span wire:loading>Searching...</span>
                            </button>
                        </div>
                    </div>

                    {{-- Advanced Filters Toggle --}}
                    <div class="border-t border-gray-100 pt-6">
                        <button type="button" wire:click="$toggle('showFilters')"
                            class="flex items-center gap-2 text-brand-primary font-semibold hover:text-brand-primary/80 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                            {{ $showFilters ? 'Hide' : 'Show' }} Advanced Filters
                        </button>

                        @if ($showFilters)
                            <div class="mt-6 space-y-6">
                                {{-- Filter Row 1: Room & View --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    {{-- Room Type Filter --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                                        <select wire:model="roomType"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            <option value="">All Types</option>
                                            <option value="standard">Standard</option>
                                            <option value="superior">Superior</option>
                                            <option value="deluxe">Deluxe</option>
                                            <option value="suite">Suite</option>
                                            <option value="family">Family</option>
                                        </select>
                                    </div>

                                    {{-- View Filter --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">View</label>
                                        <select wire:model="view"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            <option value="">All Views</option>
                                            <option value="Garden">Garden View</option>
                                            <option value="Beach">Beach View</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Row 2: Bed Configuration --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    {{-- Bed Size Filter --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Bed Size</label>
                                        <select wire:model="bedSize"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            <option value="">Any Size</option>
                                            <option value="twin">Twin</option>
                                            <option value="queen">Queen</option>
                                            <option value="king">King</option>
                                        </select>
                                    </div>

                                    {{-- Bed Count Filter --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Beds</label>
                                        <select wire:model="bedCount"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            <option value="">Any</option>
                                            <option value="single">1 Bed</option>
                                            <option value="double">2 Beds</option>
                                            <option value="triple">3 Beds</option>
                                            <option value="quad">4 Beds</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Row 3: Price Range --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (MVR)</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="number" wire:model="minPrice" placeholder="Min price"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            @error('minPrice')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <input type="number" wire:model="maxPrice" placeholder="Max price"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                            @error('maxPrice')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter Actions --}}
                                <div class="flex items-center gap-3">
                                    <button type="submit"
                                        class="bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-2 rounded-lg font-semibold transition-all">
                                        Apply Filters
                                    </button>
                                    <button type="button" wire:click="clearFilters"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold transition-all">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Results Section --}}
    @if ($searched)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            {{-- Results Header with Sort --}}
            <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl font-display font-bold text-brand-dark mb-2">
                        {{ count($results) > 0 ? count($results) . ' ' . Str::plural('Hotel', count($results)) . ' Found' : 'No Hotels Found' }}
                    </h2>
                    @if (count($results) > 0)
                        <p class="text-gray-600">{{ $checkIn }} - {{ $checkOut }} • {{ $guests }}
                            {{ Str::plural('Guest', $guests) }}</p>
                    @endif
                </div>

                @if (count($results) > 0)
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select wire:model.live="sortBy" wire:change="applySorting"
                            class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="rating_desc">Rating: High to Low</option>
                        </select>
                    </div>
                @endif
            </div>

            @if (count($results) > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($results as $result)
                        <div
                            class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                            {{-- Hotel Image --}}
                            <div class="relative h-64 overflow-hidden bg-gray-200">
                                <div class="w-full h-full bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>

                                {{-- Hotel Rating Badge --}}
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <span class="text-brand-accent font-bold text-sm">
                                        {{ str_repeat('⭐', $result['hotel']->star_rating) }}
                                    </span>
                                </div>

                                {{-- Room Types Badge --}}
                                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">{{ $result['room_types_count'] }} {{ Str::plural('type', $result['room_types_count']) }}</span>
                                </div>
                            </div>

                            {{-- Hotel Info --}}
                            <div class="p-6">
                                <h3 class="text-xl font-display font-bold text-brand-dark mb-2">
                                    {{ $result['hotel']->name }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($result['hotel']->description, 100) }}
                                </p>

                                <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                    <span>{{ $result['room_types_count'] }} room {{ Str::plural('type', $result['room_types_count']) }}</span>
                                    <span>{{ $result['nights'] }} {{ Str::plural('night', $result['nights']) }}</span>
                                </div>

                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500">Starting from</p>
                                        <p class="text-2xl font-bold text-brand-primary">MVR {{ number_format($result['starting_price'], 2) }}</p>
                                        <p class="text-xs text-gray-500">MVR {{ number_format($result['price_per_night'], 2) }}/night</p>
                                    </div>

                                    <a href="{{ route('booking.hotel.rooms', array_filter([
                                        'hotel' => $result['hotel']->id,
                                        'checkIn' => $checkIn,
                                        'checkOut' => $checkOut,
                                        'guests' => $guests,
                                        'roomType' => $roomType ?: null,
                                        'view' => $view ?: null,
                                        'bedSize' => $bedSize ?: null,
                                        'bedCount' => $bedCount ?: null,
                                    ])) }}"
                                        wire:navigate
                                        class="bg-brand-secondary hover:bg-brand-secondary/90 text-white px-6 py-2.5 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30">
                                        View Rooms
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-3xl shadow-lg p-16 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">No Rooms Available</h3>
                    <p class="text-gray-600 mb-2">Unfortunately, there are no rooms available for your search criteria.</p>
                    @if ($guests > 2)
                        <p class="text-sm text-gray-500 mb-6">Tip: Try adjusting the number of guests or selecting multiple rooms.</p>
                    @else
                        <p class="text-sm text-gray-500 mb-6">Try adjusting your dates or filters to see more options.</p>
                    @endif
                    <div class="flex gap-3 justify-center">
                        <button wire:click="$set('searched', false)"
                            class="bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                            New Search
                        </button>
                        @if ($showFilters || $roomType || $view || $bedSize || $bedCount || $minPrice || $maxPrice)
                            <button wire:click="clearFilters"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold transition-all">
                                Clear Filters
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    @endif
</div>
