<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Complete Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Booking</span>
            </h1>
            <p class="text-gray-600">Review your reservation details and confirm your booking</p>
        </div>

        {{-- Error Message --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-600 font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Booking Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h2 class="text-2xl font-display font-bold text-brand-dark mb-6">Guest Information</h2>

                    <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold text-brand-dark">{{ auth()->user()->name }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Special Requests (Optional)
                        </label>
                        <textarea wire:model="specialRequests"
                            rows="4"
                            placeholder="Any special requests? (e.g., early check-in, room preferences, dietary requirements)"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Your requests are subject to availability and may incur additional charges.</p>
                    </div>

                    {{-- Hotel Policies --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-xl font-display font-bold text-brand-dark mb-4">Hotel Policies</h3>

                        {{-- Cancellation Policy --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-brand-dark mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Cancellation Policy
                            </h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Free cancellation up to 48 hours before check-in
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    50% refund for cancellations within 24-48 hours of check-in
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    No refund for cancellations within 24 hours of check-in
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Refunds will be processed within 5-7 business days
                                </li>
                            </ul>
                        </div>

                        {{-- Check-in/Check-out --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-brand-dark mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check-in & Check-out
                            </h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Check-in from {{ $room->hotel->formatted_checkin_time }} onwards
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Check-out by {{ $room->hotel->formatted_checkout_time }}
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Valid government-issued photo ID required at check-in
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Late check-out requests subject to availability (additional charges may apply)
                                </li>
                            </ul>
                        </div>

                        {{-- House Rules --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-brand-dark mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                House Rules
                            </h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    No smoking in rooms (designated smoking areas available)
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    No pets allowed (except certified service animals)
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Quiet hours from 10:00 PM to 7:00 AM
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Maximum occupancy: {{ $room->max_occupancy }} guests per room
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Guests are responsible for any damages to the room or property
                                </li>
                            </ul>
                        </div>

                        {{-- Payment Terms --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-brand-dark mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment Terms
                            </h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Full payment required at time of booking
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    All prices are in Maldivian Rufiyaa (MVR)
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Credit card authorization may be held until check-out
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Additional charges for room service, minibar, etc. will be billed separately
                                </li>
                            </ul>
                        </div>

                        {{-- Important Notice --}}
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-blue-800">
                                    <strong class="font-semibold">Important:</strong> By confirming this booking, you acknowledge that you have read and agree to abide by all hotel policies, including cancellation terms, house rules, and payment conditions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-6 sticky top-8">
                    <h2 class="text-xl font-display font-bold text-brand-dark mb-4">Booking Summary</h2>

                    {{-- Room Info --}}
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <div class="flex gap-3 mb-3">
                            @php
                                $primaryImage = $room->getPrimaryImage();
                            @endphp
                            @if ($primaryImage)
                                <img src="{{ Storage::url($primaryImage->image_path) }}"
                                    alt="{{ $room->full_description }}"
                                    class="w-20 h-20 rounded-xl object-cover">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-xl"></div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-brand-dark text-sm">{{ $room->full_description }}</h3>
                                <p class="text-xs text-gray-600">{{ $room->hotel->name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dates & Guests --}}
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-in</span>
                            <span class="font-semibold text-brand-dark">{{ Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-out</span>
                            <span class="font-semibold text-brand-dark">{{ Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Guests</span>
                            <span class="font-semibold text-brand-dark">{{ $guests }} {{ Str::plural('Guest', $guests) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nights</span>
                            <span class="font-semibold text-brand-dark">{{ $pricing['number_of_nights'] }} {{ Str::plural('Night', $pricing['number_of_nights']) }}</span>
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="border-t border-gray-100 pt-4 mb-6 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Room rate</span>
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
                        <div class="flex justify-between font-bold text-brand-dark text-lg pt-2 border-t border-gray-100">
                            <span>Total</span>
                            <span class="text-brand-primary">MVR {{ number_format($pricing['total_price'], 2) }}</span>
                        </div>
                    </div>

                    {{-- Confirm Button --}}
                    <button wire:click="confirmBooking" wire:loading.attr="disabled"
                        class="w-full bg-brand-secondary hover:bg-brand-secondary/90 text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-brand-secondary/30 disabled:opacity-50">
                        <span wire:loading.remove>Confirm Booking</span>
                        <span wire:loading>Processing...</span>
                    </button>

                    <p class="text-xs text-center text-gray-500 mt-4">
                        By confirming, you agree to our terms and conditions
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
