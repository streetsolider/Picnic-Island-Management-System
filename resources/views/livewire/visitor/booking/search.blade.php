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

                        {{-- Guests --}}
                        <div>
                            <label class="block text-sm font-semibold text-brand-dark mb-2">
                                Guests
                            </label>
                            <select wire:model="guests"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}
                                        {{ Str::plural('Guest', $i) }}</option>
                                @endfor
                            </select>
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
                            {{ $showFilters ? 'Hide' : 'Show' }} Filters
                        </button>

                        @if ($showFilters)
                            <div class="grid md:grid-cols-3 gap-4 mt-6">
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
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Results Section --}}
    @if ($searched)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="mb-8">
                <h2 class="text-2xl font-display font-bold text-brand-dark mb-2">
                    {{ count($results) > 0 ? count($results) . ' ' . Str::plural('Hotel', count($results)) . ' Found' : 'No Hotels Found' }}
                </h2>
                @if (count($results) > 0)
                    <p class="text-gray-600">{{ $checkIn }} - {{ $checkOut }} • {{ $guests }}
                        {{ Str::plural('Guest', $guests) }}</p>
                @endif
            </div>

            @if (count($results) > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($results as $result)
                        <div
                            class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                            {{-- Room Image --}}
                            <div class="relative h-64 overflow-hidden bg-gray-200">
                                @php
                                    $roomImage = $result['cheapest_room']->getPrimaryImage();
                                @endphp
                                @if ($roomImage)
                                    <img src="{{ Storage::url($roomImage->image_path) }}" alt="{{ $result['hotel']->name }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Hotel Rating Badge --}}
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <span class="text-brand-accent font-bold text-sm">
                                        {{ str_repeat('⭐', $result['hotel']->star_rating) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Hotel Info --}}
                            <div class="p-6">
                                <h3 class="text-xl font-display font-bold text-brand-dark mb-2">
                                    {{ $result['hotel']->name }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $result['cheapest_room']->full_description }}
                                </p>

                                <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                    <span>{{ $result['available_rooms_count'] }} room{{ $result['available_rooms_count'] > 1 ? 's' : '' }} available</span>
                                    <span>{{ $result['nights'] }} night{{ $result['nights'] > 1 ? 's' : '' }}</span>
                                </div>

                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500">Starting from</p>
                                        <p class="text-2xl font-bold text-brand-primary">MVR {{ number_format($result['starting_price'], 2) }}</p>
                                        <p class="text-xs text-gray-500">MVR {{ number_format($result['price_per_night'], 2) }}/night</p>
                                    </div>

                                    <a href="{{ route('booking.room.details', ['room' => $result['cheapest_room']->id, 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'guests' => $guests]) }}"
                                        wire:navigate
                                        class="bg-brand-secondary hover:bg-brand-secondary/90 text-white px-6 py-2.5 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30">
                                        View Details
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
                    <p class="text-gray-600 mb-6">Unfortunately, there are no rooms available for your selected dates. Try adjusting your search criteria.</p>
                    <button wire:click="$set('searched', false)"
                        class="bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                        Search Again
                    </button>
                </div>
            @endif
        </section>
    @endif
</div>
