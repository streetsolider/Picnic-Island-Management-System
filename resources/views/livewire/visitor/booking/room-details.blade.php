<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('booking.hotel.rooms', array_filter([
    'hotel' => $room->hotel_id,
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
])) }}" wire:navigate
                class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-primary/80 font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Room Selection
            </a>
        </div>

        {{-- Capacity Warning --}}
        @if (request()->has('guests') && request('guests') > $room->max_occupancy)
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Notice:</strong> You searched for {{ request('guests') }} guests, but this room can
                                accommodate a maximum of {{ $room->max_occupancy }} guests.
                                The guest count has been adjusted automatically.
                                <a href="{{ route('booking.search', array_filter([
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'guests' => request('guests'),
                'roomType' => $roomType ?: null,
                'view' => $view ?: null,
                'bedSize' => $bedSize ?: null,
                'bedCount' => $bedCount ?: null,
                'minPrice' => $minPrice ?: null,
                'maxPrice' => $maxPrice ?: null,
                'sortBy' => $sortBy !== 'price_asc' ? $sortBy : null,
            ])) }}" wire:navigate class="font-semibold underline hover:text-yellow-800">
                                    Search for rooms that can accommodate {{ request('guests') }} guests
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
        @endif

        {{-- Flash Messages --}}
        @if (session()->has('warning'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8 min-w-0">
            {{-- Left Column: Room Details --}}
            <div class="lg:col-span-2 space-y-6 min-w-0">
                {{-- Room Images Gallery --}}
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg" x-data="{
                        currentImage: '{{ Storage::url($room->getPrimaryImage()?->image_path ?? $room->getAllImages()->first()?->image_path ?? '') }}',
                        images: {{ $room->getAllImages()->map(fn($img) => Storage::url($img->image_path))->toJson() }},
                        setImage(path) {
                            this.currentImage = path;
                        }
                    }">
                    @php
                        $images = $room->getAllImages();
                        $primaryImage = $room->getPrimaryImage();
                    @endphp

                    @if ($images->isNotEmpty())
                        {{-- MOBILE/TABLET: Full-width image carousel (replaces big image) --}}
                        <div class="block lg:hidden relative" x-data="{
                                        swiper: null,
                                        currentSlide: 0,
                                        totalSlides: {{ $images->count() }},
                                        init() {
                                            const self = this;
                                            this.swiper = new Swiper(this.$el.querySelector('.swiper'), {
                                                slidesPerView: 1,
                                                spaceBetween: 0,
                                                grabCursor: true,
                                                observer: true,
                                                observeParents: true,
                                                on: {
                                                    slideChange: function() {
                                                        self.currentSlide = this.activeIndex;
                                                    }
                                                }
                                            });
                                        }
                                    }">
                            <div class="swiper roomGallerySwiper" x-cloak style="display: none;"
                                x-init="$el.style.display = 'block'">
                                <div class="swiper-wrapper">
                                    @foreach ($images as $image)
                                        <div class="swiper-slide !h-auto">
                                            <div class="h-72 sm:h-80 md:h-96 overflow-hidden">
                                                <img src="{{ Storage::url($image->image_path) }}" alt="Room view"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Custom Pagination Dots --}}
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center items-center gap-2 z-10">
                                @foreach ($images as $index => $image)
                                    <button type="button" @click="swiper.slideTo({{ $index }})"
                                        class="h-2.5 rounded-full transition-all duration-300 focus:outline-none"
                                        :class="currentSlide === {{ $index }} ? 'w-8 bg-white' : 'w-3 bg-white bg-opacity-60 hover:bg-white'">
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- DESKTOP: Big image + thumbnail carousel --}}
                        <div class="hidden lg:block">
                            {{-- Main big image --}}
                            <div class="relative h-96 bg-gray-200">
                                <img :src="currentImage" alt="{{ $room->full_description }}"
                                    class="w-full h-full object-cover">

                                @if ($images->count() > 1)
                                    <div
                                        class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">
                                        {{ $images->count() }} Photos
                                    </div>
                                @endif
                            </div>

                            {{-- Thumbnail carousel --}}
                            @if ($images->count() > 1)
                                <div class="p-4">
                                    @if ($images->count() <= 4)
                                        {{-- Grid for 4 or fewer images --}}
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach ($images as $image)
                                                <div class="aspect-square rounded-lg overflow-hidden cursor-pointer"
                                                    @click="setImage('{{ Storage::url($image->image_path) }}')">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Room view"
                                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                                        :class="currentImage === '{{ Storage::url($image->image_path) }}' ? 'ring-4 ring-brand-primary' : ''">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{-- Carousel for more than 4 images --}}
                                        <div class="swiper roomGallerySwiper overflow-hidden" x-data="{
                                                                        swiper: null,
                                                                        isBeginning: true,
                                                                        isEnd: false,
                                                                        init() {
                                                                            this.swiper = new Swiper(this.$el, {
                                                                                slidesPerView: 4,
                                                                                spaceBetween: 10,
                                                                                grabCursor: true,
                                                                                watchOverflow: true,
                                                                                observer: true,
                                                                                observeParents: true,
                                                                                navigation: {
                                                                                    nextEl: this.$el.querySelector('.swiper-button-next'),
                                                                                    prevEl: this.$el.querySelector('.swiper-button-prev'),
                                                                                },
                                                                            });
                                                                            this.isEnd = this.swiper.isEnd;
                                                                            this.swiper.on('slideChange', () => {
                                                                                this.isBeginning = this.swiper.isBeginning;
                                                                                this.isEnd = this.swiper.isEnd;
                                                                            });
                                                                        }
                                                                    }">
                                            <div class="swiper-wrapper">
                                                @foreach ($images as $image)
                                                    <div class="swiper-slide">
                                                        <div class="aspect-square rounded-lg overflow-hidden cursor-pointer"
                                                            @click="setImage('{{ Storage::url($image->image_path) }}')">
                                                            <img src="{{ Storage::url($image->image_path) }}" alt="Room view"
                                                                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                                                :class="currentImage === '{{ Storage::url($image->image_path) }}' ? 'ring-4 ring-brand-primary' : ''">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            {{-- Arrow indicators --}}
                                            <div class="swiper-button-next transition-opacity duration-300"
                                                :class="isEnd ? '!opacity-0 !pointer-events-none' : 'opacity-100'"></div>
                                            <div class="swiper-button-prev transition-opacity duration-300"
                                                :class="isBeginning ? '!opacity-0 !pointer-events-none' : 'opacity-100'"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div
                            class="h-96 bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                            <svg class="w-32 h-32 text-brand-primary/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Room Info Card --}}
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-display font-bold text-brand-dark mb-2">
                                {{ $room->full_description }}
                            </h1>
                            <div class="flex items-center gap-4 text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    {{ $room->hotel->name }}
                                </span>
                                <span class="text-brand-accent">
                                    {{ str_repeat('⭐', $room->hotel->star_rating) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Room Features --}}
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Room Type</p>
                                <p class="font-semibold text-brand-dark">{{ ucfirst($room->room_type) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-brand-secondary/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-secondary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Max Occupancy</p>
                                <p class="font-semibold text-brand-dark">{{ $room->max_occupancy }} Guests</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-brand-accent/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">View</p>
                                <p class="font-semibold text-brand-dark">{{ $room->view }} View</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Bed Configuration</p>
                                <p class="font-semibold text-brand-dark">{{ $room->bed_count }}
                                    {{ ucfirst($room->bed_size) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Amenities --}}
                    @if ($room->amenities->isNotEmpty())
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="text-xl font-display font-bold text-brand-dark mb-4">Room Amenities</h3>

                            @php
                                $groupedAmenities = $room->amenities->groupBy('category.name');
                            @endphp

                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach ($groupedAmenities as $categoryName => $amenities)
                                    <div>
                                        <h4 class="font-semibold text-brand-dark mb-3">{{ $categoryName }}</h4>
                                        <ul class="space-y-2">
                                            @foreach ($amenities as $amenity)
                                                <li class="flex items-center gap-2 text-gray-600">
                                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    {{ $amenity->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Hotel Policies Highlights --}}
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="text-xl font-display font-bold text-brand-dark mb-4">Important Information</h3>

                        <div class="grid md:grid-cols-3 gap-4">
                            {{-- Check-in --}}
                            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark mb-1">Check-in</p>
                                    <p class="text-sm text-gray-600">From {{ $room->hotel->formatted_checkin_time }}</p>
                                </div>
                            </div>

                            {{-- Check-out --}}
                            <div class="flex items-start gap-3 p-4 bg-orange-50 rounded-xl">
                                <div
                                    class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark mb-1">Check-out</p>
                                    <p class="text-sm text-gray-600">Until {{ $room->hotel->formatted_checkout_time }}
                                    </p>
                                </div>
                            </div>

                            {{-- Cancellation --}}
                            <div class="flex items-start gap-3 p-4 bg-green-50 rounded-xl">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark mb-1">Cancellation</p>
                                    <p class="text-sm text-gray-600">Free cancellation available</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-brand-dark">Note:</span> Full hotel policies including
                                payment terms, house rules, and cancellation details will be shown during checkout.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Booking Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-6 sticky top-8">
                    <div class="mb-6">
                        @if ($pricingError)
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                <p class="text-red-600 font-semibold">Invalid Dates</p>
                                <p class="text-red-500 text-sm">{{ $pricingError }}</p>
                            </div>
                        @elseif ($pricing)
                            <div class="mb-2">
                                <span class="text-3xl font-bold text-brand-primary">MVR
                                    {{ number_format($pricing['total_price'], 2) }}</span>
                                <span class="text-gray-500 text-sm">total</span>
                            </div>
                            <p class="text-sm text-gray-600">MVR {{ number_format($pricing['average_price_per_night'], 2) }}
                                per night × {{ $pricing['number_of_nights'] }}
                                {{ Str::plural('night', $pricing['number_of_nights']) }}
                            </p>
                        @elseif (!$isAvailable)
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                <p class="text-red-600 font-semibold">Room Not Available</p>
                                <p class="text-red-500 text-sm">This room is not available for the selected dates.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Date Selection --}}
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Check-in</label>
                            <input type="date" wire:model.live="checkIn"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Check-out</label>
                            <input type="date" wire:model.live="checkOut"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Guests</label>
                            <select wire:model.live="guests"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                @for ($i = 1; $i <= $room->max_occupancy; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ Str::plural('Guest', $i) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    @if ($pricing && $isAvailable)
                        <div class="border-t border-gray-100 pt-4 mb-6 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Base price ({{ $pricing['number_of_nights'] }}
                                    {{ Str::plural('night', $pricing['number_of_nights']) }})</span>
                                <span>MVR {{ number_format($pricing['subtotal_before_discount'], 2) }}</span>
                            </div>
                            @if (isset($pricing['view_adjustment']) && $pricing['view_adjustment'] != 0)
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ $room->view }} View</span>
                                    <span>+ MVR {{ number_format($pricing['view_adjustment'], 2) }}</span>
                                </div>
                            @endif
                            @if (isset($pricing['discount_amount']) && $pricing['discount_amount'] > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount</span>
                                    <span>- MVR {{ number_format($pricing['discount_amount'], 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-semibold text-brand-dark pt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span>MVR {{ number_format($pricing['total_price'], 2) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Book Now Button --}}
                    <button wire:click="bookNow" @if(!$isAvailable || $pricingError) disabled @endif
                        class="w-full bg-brand-secondary hover:bg-brand-secondary/90 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30 disabled:shadow-none disabled:transform-none">
                        @if ($pricingError)
                            Invalid Dates
                        @elseif ($isAvailable)
                            Book Now
                        @else
                            Unavailable
                        @endif
                    </button>

                    @guest
                        <p class="text-xs text-center text-gray-500 mt-4">You'll be asked to log in to complete your booking
                        </p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>