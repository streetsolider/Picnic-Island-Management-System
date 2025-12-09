<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('booking.search', array_filter([
                    'checkIn' => $checkIn,
                    'checkOut' => $checkOut,
                    'guests' => $guests,
                    'roomType' => $roomType ?: null,
                    'view' => $view ?: null,
                    'bedSize' => $bedSize ?: null,
                    'bedCount' => $bedCount ?: null,
                    'minPrice' => $minPrice ?: null,
                    'maxPrice' => $maxPrice ?: null,
                    'sortBy' => $sortBy !== 'price_asc' ? $sortBy : null,
                ])) }}"
                wire:navigate
                class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-primary/80 font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Search Results
            </a>
        </div>

        {{-- Hotel Header --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Hotel Image Carousel --}}
                <div class="md:col-span-1">
                    @if ($hotel->hotelGallery && $hotel->hotelGallery->images->isNotEmpty())
                        <div
                            x-data="{
                                currentSlide: 0,
                                totalSlides: {{ $hotel->hotelGallery->images->count() }},
                                startX: 0,
                                currentX: 0,
                                isDragging: false,
                                nextSlide() {
                                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                                },
                                prevSlide() {
                                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                                },
                                handleStart(e) {
                                    this.startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
                                    this.isDragging = true;
                                },
                                handleMove(e) {
                                    if (!this.isDragging) return;
                                    this.currentX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
                                },
                                handleEnd() {
                                    if (!this.isDragging) return;
                                    const diff = this.startX - this.currentX;
                                    if (Math.abs(diff) > 50) {
                                        if (diff > 0) {
                                            this.nextSlide();
                                        } else {
                                            this.prevSlide();
                                        }
                                    }
                                    this.isDragging = false;
                                    this.startX = 0;
                                    this.currentX = 0;
                                }
                            }"
                            class="relative w-full h-64 md:h-full overflow-hidden bg-gray-900 cursor-grab active:cursor-grabbing select-none"
                            @touchstart="handleStart"
                            @touchmove="handleMove"
                            @touchend="handleEnd"
                            @mousedown="handleStart"
                            @mousemove="handleMove"
                            @mouseup="handleEnd"
                            @mouseleave="handleEnd">

                            {{-- Image Slides --}}
                            @foreach ($hotel->hotelGallery->images as $index => $image)
                                <div
                                    x-show="currentSlide === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 w-full h-full">
                                    <img
                                        src="{{ Storage::url($image->image_path) }}"
                                        alt="{{ $hotel->name }} - Image {{ $index + 1 }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endforeach

                            {{-- Slide Indicators --}}
                            @if ($hotel->hotelGallery->images->count() > 1)
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                    @foreach ($hotel->hotelGallery->images as $index => $image)
                                        <button
                                            @click="currentSlide = {{ $index }}"
                                            class="w-2 h-2 rounded-full transition-all duration-300"
                                            :class="currentSlide === {{ $index }} ? 'bg-white w-6' : 'bg-white/50'">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="w-full h-64 md:h-full bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                            <svg class="w-24 h-24 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Hotel Info --}}
                <div class="md:col-span-2 p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">{{ $hotel->name }}</h1>
                            <div class="flex items-center gap-2 text-brand-accent mb-3">
                                <span class="text-xl">{{ str_repeat('⭐', $hotel->star_rating) }}</span>
                                <span class="text-sm text-gray-600">{{ $hotel->star_rating }}-Star Hotel</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-6">{{ $hotel->description }}</p>

                    {{-- Booking Details --}}
                    <div class="grid grid-cols-3 gap-4 p-4 bg-brand-light/30 rounded-xl">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Check-in</p>
                            <p class="font-semibold text-brand-dark">{{ Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Check-out</p>
                            <p class="font-semibold text-brand-dark">{{ Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Guests</p>
                            <p class="font-semibold text-brand-dark">{{ $guests }} {{ Str::plural('Guest', $guests) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Room Selection Header --}}
        <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-brand-dark mb-1">
                    Available Rooms
                </h2>
                <p class="text-gray-600">{{ count($roomTypes) }} room {{ Str::plural('type', count($roomTypes)) }} available for your dates</p>
            </div>

            @if (count($roomTypes) > 0)
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select wire:model.live="sortBy"
                        class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="type_asc">Room Type</option>
                    </select>
                </div>
            @endif
        </div>

        {{-- Room Types Grid --}}
        @if (count($roomTypes) > 0)
            <div class="space-y-6">
                @foreach ($roomTypes as $roomTypeItem)
                    @php
                        $room = $roomTypeItem['room'];
                        $pricing = $roomTypeItem['pricing'];
                        $availableCount = $roomTypeItem['available_count'];
                        $roomImage = $room->getPrimaryImage();
                    @endphp

                    <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="grid md:grid-cols-3 gap-6">
                            {{-- Room Image --}}
                            <div class="relative h-64 md:h-auto overflow-hidden bg-gray-200">
                                @if ($roomImage)
                                    <img src="{{ Storage::url($roomImage->image_path) }}" alt="{{ $room->full_description }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Availability Badge --}}
                                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full">
                                    <span class="text-sm font-semibold text-brand-primary">{{ $availableCount }} available</span>
                                </div>
                            </div>

                            {{-- Room Details --}}
                            <div class="md:col-span-2 p-6 flex flex-col">
                                <div class="flex-grow">
                                    <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">
                                        {{ $room->full_description }}
                                    </h3>

                                    <div class="flex flex-wrap gap-4 mb-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <span>Up to {{ $room->max_occupancy }} guests</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                </path>
                                            </svg>
                                            <span>{{ ucfirst($room->bed_count) }} {{ ucfirst($room->bed_size) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            <span>{{ $room->view }} View</span>
                                        </div>
                                    </div>

                                    {{-- Amenities Preview --}}
                                    @if ($room->amenities->count() > 0)
                                        <div class="mb-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-2">Amenities:</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($room->amenities->take(6) as $amenity)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-brand-light/50 text-brand-dark">
                                                        {{ $amenity->name }}
                                                    </span>
                                                @endforeach
                                                @if ($room->amenities->count() > 6)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                        +{{ $room->amenities->count() - 6 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Pricing & Action --}}
                                <div class="flex items-end justify-between pt-4 border-t border-gray-100">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Total for {{ $pricing['number_of_nights'] }} {{ Str::plural('night', $pricing['number_of_nights']) }}</p>
                                        <p class="text-3xl font-bold text-brand-primary mb-1">MVR {{ number_format($pricing['total_price'], 2) }}</p>
                                        <p class="text-sm text-gray-500">MVR {{ number_format($pricing['average_price_per_night'], 2) }} per night</p>
                                    </div>

                                    <a href="{{ route('booking.room.details', array_filter([
                                            'room' => $room->id,
                                            'checkIn' => $checkIn,
                                            'checkOut' => $checkOut,
                                            'guests' => $guests,
                                            'roomType' => $roomType ?: null,
                                            'view' => $view ?: null,
                                            'bedSize' => $bedSize ?: null,
                                            'bedCount' => $bedCount ?: null,
                                            'minPrice' => $minPrice ?: null,
                                            'maxPrice' => $maxPrice ?: null,
                                            'sortBy' => $sortBy !== 'price_asc' ? $sortBy : null,
                                        ])) }}"
                                        wire:navigate
                                        class="bg-brand-secondary hover:bg-brand-secondary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30 whitespace-nowrap">
                                        Select Room
                                    </a>
                                </div>
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
                <p class="text-gray-600 mb-6">Unfortunately, there are no rooms available at this hotel for your selected dates and guest count.</p>
                <a href="{{ route('booking.search', array_filter([
                        'checkIn' => $checkIn,
                        'checkOut' => $checkOut,
                        'guests' => $guests,
                        'roomType' => $roomType ?: null,
                        'view' => $view ?: null,
                        'bedSize' => $bedSize ?: null,
                        'bedCount' => $bedCount ?: null,
                        'minPrice' => $minPrice ?: null,
                        'maxPrice' => $maxPrice ?: null,
                        'sortBy' => $sortBy !== 'price_asc' ? $sortBy : null,
                    ])) }}"
                    wire:navigate
                    class="inline-block bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                    Back to Search
                </a>
            </div>
        @endif
    </div>
</div>
