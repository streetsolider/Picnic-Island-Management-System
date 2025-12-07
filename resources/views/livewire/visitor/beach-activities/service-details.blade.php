<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Back Button & Header --}}
        <div class="mb-6">
            <a href="{{ route('visitor.beach-activities.browse') }}" wire:navigate
                class="inline-flex items-center text-gray-600 hover:text-brand-primary transition-colors mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Activities
            </a>
            <h1 class="text-4xl font-display font-bold text-brand-dark">
                {{ $service->name }}
            </h1>
        </div>

        {{-- Alert Messages --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-start gap-3"
                x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Service Details Card --}}
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="flex items-start gap-6">
                    <span class="text-6xl">{{ $service->category->icon }}</span>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h2 class="text-3xl font-display font-bold text-brand-dark">
                                {{ $service->name }}
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-brand-primary/10 text-brand-primary">
                                {{ $service->category->name }}
                            </span>
                        </div>

                        @if($service->description)
                            <p class="text-gray-700 mb-6">
                                {{ $service->description }}
                            </p>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="flex items-center gap-3 text-gray-600">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Operating Hours</p>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($service->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($service->closing_time)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-600">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Capacity</p>
                                    <p class="font-semibold">{{ $service->concurrent_capacity }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-600">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Price</p>
                                    <p class="font-semibold text-brand-primary">
                                        MVR {{ number_format($service->getPricePerUnit(), 2) }}
                                        @if($service->isFixedSlot())
                                            / {{ $service->slot_duration_minutes }} min
                                        @else
                                            / hour
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Form --}}
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="p-8">
                <h3 class="text-2xl font-display font-bold text-brand-dark mb-6">Book This Activity</h3>

                {{-- Login Required --}}
                @if(!auth()->check())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Login Required</h3>
                                <p class="text-yellow-800">You must be logged in with a valid hotel booking to book beach activities.</p>
                                <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="inline-block mt-3 text-brand-primary font-semibold hover:underline">Login Now →</a>
                            </div>
                        </div>
                    </div>

                {{-- Hotel Booking Required --}}
                @elseif(!$hotelBooking)
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900 mb-2">Valid Hotel Booking Required</h3>
                                <p class="text-red-800">You must have a confirmed hotel booking to book beach activities.</p>
                                <a href="{{ route('booking.search') }}" class="inline-block mt-3 text-brand-primary font-semibold hover:underline">Book a Hotel Room →</a>
                            </div>
                        </div>
                    </div>

                {{-- Check-in Gate Message --}}
                @elseif(!$isCheckedIn)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">Hotel Check-in Required</h4>
                                <p class="text-gray-700 mb-3">
                                    Beach activities can only be booked after you've checked into your hotel.
                                </p>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-1">Your hotel stay:</p>
                                    <p class="font-semibold text-brand-dark">
                                        {{ $hotelBooking->check_in_date->format('M j, Y') }} to {{ $hotelBooking->check_out_date->format('M j, Y') }}
                                    </p>
                                    @if($hotelBooking->check_in_date->isFuture())
                                        <p class="text-sm text-brand-primary mt-2">
                                            You can book activities starting from <strong>{{ $hotelBooking->check_in_date->format('M j, Y') }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- Booking Form (only shown when checked in) --}}
                @else
                    <div class="space-y-6">
                        {{-- Date Selection --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Select Date
                            </label>
                            <input
                                wire:model.live="selectedDate"
                                type="date"
                                min="{{ $minDate }}"
                                max="{{ $maxDate }}"
                                class="w-full sm:w-auto rounded-xl border-gray-300 focus:border-brand-primary focus:ring-brand-primary px-4 py-3">
                            <p class="text-sm text-gray-500 mt-2">
                                Available during your hotel stay ({{ \Carbon\Carbon::parse($minDate)->format('M j') }} - {{ \Carbon\Carbon::parse($maxDate)->format('M j, Y') }})
                            </p>
                        </div>

                    @if($service->isFixedSlot())
                        {{-- Fixed Slot Selection --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Available Time Slots ({{ $service->slot_duration_minutes }} minutes each)
                            </label>

                            @if(empty($availableSlots))
                                <div class="bg-gray-50 rounded-2xl p-12 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="font-bold text-gray-700 mb-1">No Slots Available</h4>
                                    <p class="text-sm text-gray-500">No time slots are available for the selected date. Please try another date.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach($availableSlots as $slot)
                                        <button
                                            wire:click="selectSlot('{{ $slot['time'] }}')"
                                            @if(!$slot['available']) disabled @endif
                                            class="px-4 py-4 rounded-xl font-semibold transition-all
                                                @if($selectedSlot === $slot['time'])
                                                    bg-brand-primary text-white shadow-lg shadow-brand-primary/30
                                                @elseif(!$slot['available'])
                                                    bg-gray-100 text-gray-400 cursor-not-allowed
                                                @else
                                                    bg-white border-2 border-gray-200 text-gray-700 hover:border-brand-primary hover:text-brand-primary
                                                @endif">
                                            {{ $slot['time'] }}
                                            @if(!$slot['available'])
                                                <br><span class="text-xs">Full</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Flexible Duration Selection --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Start Time --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Start Time
                                </label>
                                <select
                                    wire:model.live="selectedStartTime"
                                    class="w-full rounded-xl border-gray-300 focus:border-brand-primary focus:ring-brand-primary px-4 py-3">
                                    <option value="">Select start time</option>
                                    @foreach($availableStartTimes as $time)
                                        <option value="{{ $time }}">{{ \Carbon\Carbon::parse($time)->format('g:i A') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Duration --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Duration: {{ $durationHours }} {{ $durationHours == 1 ? 'hour' : 'hours' }}
                                </label>
                                <input
                                    wire:model.live="durationHours"
                                    type="range"
                                    min="1"
                                    max="8"
                                    step="1"
                                    class="w-full h-3 bg-gray-200 rounded-full appearance-none cursor-pointer accent-brand-primary">
                                <div class="flex justify-between text-sm text-gray-500 mt-2">
                                    <span>1 hr</span>
                                    <span>4 hrs</span>
                                    <span>8 hrs</span>
                                </div>
                            </div>
                        </div>

                        @if($selectedStartTime && $selectedEndTime)
                            <div class="bg-brand-primary/10 rounded-2xl p-4 border-2 border-brand-primary/20">
                                <p class="text-brand-primary font-semibold">
                                    <strong>Selected Time:</strong>
                                    {{ \Carbon\Carbon::parse($selectedStartTime)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($selectedEndTime)->format('g:i A') }}
                                </p>
                            </div>
                        @endif
                    @endif

                    {{-- Price Display --}}
                    @if($this->calculatedPrice)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="bg-green-50 rounded-2xl p-6 border-2 border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">{{ $this->calculatedPrice['breakdown'] }}</p>
                                        <p class="text-3xl font-bold text-green-600">
                                            MVR {{ number_format($this->calculatedPrice['total_price'], 2) }}
                                        </p>
                                    </div>
                                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Proceed Button --}}
                    <div class="flex justify-end pt-6">
                        <button
                            wire:click="proceedToBooking"
                            @disabled(!$this->calculatedPrice)
                            class="bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-4 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                            Proceed to Booking
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
