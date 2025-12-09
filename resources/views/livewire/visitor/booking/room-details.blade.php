<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('booking.hotel.rooms', ['hotel' => $room->hotel_id, 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'guests' => $guests]) }}"
                wire:navigate
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
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Notice:</strong> You searched for {{ request('guests') }} guests, but this room can accommodate a maximum of {{ $room->max_occupancy }} guests.
                            The guest count has been adjusted automatically.
                            <a href="{{ route('booking.search', ['checkIn' => $checkIn, 'checkOut' => $checkOut, 'guests' => request('guests')]) }}"
                               wire:navigate
                               class="font-semibold underline hover:text-yellow-800">
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
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Left Column: Room Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Room Images Gallery --}}
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg">
                    @php
                        $images = $room->getAllImages();
                        $primaryImage = $room->getPrimaryImage();
                    @endphp

                    @if ($images->isNotEmpty())
                        <div class="relative h-96 bg-gray-200">
                            <img src="{{ Storage::url($primaryImage->image_path ?? $images->first()->image_path) }}"
                                alt="{{ $room->full_description }}"
                                class="w-full h-full object-cover">

                            @if ($images->count() > 1)
                                <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">
                                    {{ $images->count() }} Photos
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail Grid --}}
                        @if ($images->count() > 1)
                            <div class="grid grid-cols-4 gap-2 p-4">
                                @foreach ($images->take(4) as $image)
                                    <div class="aspect-square rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($image->image_path) }}"
                                            alt="Room view"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300 cursor-pointer">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="h-96 bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 flex items-center justify-center">
                            <svg class="w-32 h-32 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-6 h-6 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-6 h-6 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Bed Configuration</p>
                                <p class="font-semibold text-brand-dark">{{ $room->bed_count }} {{ ucfirst($room->bed_size) }}</p>
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
                                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark mb-1">Check-in</p>
                                    <p class="text-sm text-gray-600">From {{ $room->hotel->formatted_checkin_time }}</p>
                                </div>
                            </div>

                            {{-- Check-out --}}
                            <div class="flex items-start gap-3 p-4 bg-orange-50 rounded-xl">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark mb-1">Check-out</p>
                                    <p class="text-sm text-gray-600">Until {{ $room->hotel->formatted_checkout_time }}</p>
                                </div>
                            </div>

                            {{-- Cancellation --}}
                            <div class="flex items-start gap-3 p-4 bg-green-50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                                <span class="font-semibold text-brand-dark">Note:</span> Full hotel policies including payment terms, house rules, and cancellation details will be shown during checkout.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Booking Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-6 sticky top-8">
                    <div class="mb-6">
                        @if ($pricing)
                            <div class="mb-2">
                                <span class="text-3xl font-bold text-brand-primary">MVR {{ number_format($pricing['total_price'], 2) }}</span>
                                <span class="text-gray-500 text-sm">total</span>
                            </div>
                            <p class="text-sm text-gray-600">MVR {{ number_format($pricing['average_price_per_night'], 2) }} per night × {{ $pricing['number_of_nights'] }} {{ Str::plural('night', $pricing['number_of_nights']) }}</p>
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
                                <span>Base price ({{ $pricing['number_of_nights'] }} {{ Str::plural('night', $pricing['number_of_nights']) }})</span>
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
                    <button wire:click="bookNow"
                        @if(!$isAvailable) disabled @endif
                        class="w-full bg-brand-secondary hover:bg-brand-secondary/90 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30 disabled:shadow-none disabled:transform-none">
                        {{ $isAvailable ? 'Book Now' : 'Unavailable' }}
                    </button>

                    @guest
                        <p class="text-xs text-center text-gray-500 mt-4">You'll be asked to log in to complete your booking</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
