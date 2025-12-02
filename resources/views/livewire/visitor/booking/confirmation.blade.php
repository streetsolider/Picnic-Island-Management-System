<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Success Icon Animation --}}
        <div class="text-center mb-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6"
                x-show="show"
                x-transition:enter="transition ease-out duration-500 transform"
                x-transition:enter-start="scale-0"
                x-transition:enter-end="scale-100">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Booking <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Confirmed!</span>
            </h1>
            <p class="text-xl text-gray-600">Your reservation has been successfully confirmed</p>
        </div>

        {{-- Booking Reference Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
            <div class="text-center pb-6 border-b border-gray-100">
                <p class="text-sm text-gray-500 mb-2">Booking Reference</p>
                <p class="text-3xl font-bold text-brand-primary font-mono">{{ $booking->booking_reference }}</p>
                <p class="text-sm text-gray-500 mt-2">Please save this reference number for your records</p>
            </div>

            {{-- Booking Details --}}
            <div class="grid md:grid-cols-2 gap-6 py-6">
                {{-- Hotel & Room --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Accommodation</h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-brand-dark">{{ $booking->hotel->name }}</p>
                        <p class="text-gray-600">{{ $booking->room->full_description }}</p>
                        <div class="flex items-center gap-1 text-brand-accent">
                            {{ str_repeat('⭐', $booking->hotel->star_rating) }}
                        </div>
                    </div>
                </div>

                {{-- Guest Info --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Guest Details</h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-brand-dark">{{ $booking->guest->name }}</p>
                        <p class="text-gray-600">{{ $booking->guest->email }}</p>
                        <p class="text-gray-600">{{ $booking->number_of_guests }} {{ Str::plural('Guest', $booking->number_of_guests) }}</p>
                    </div>
                </div>

                {{-- Check-in --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Check-in</h3>
                    <p class="text-2xl font-bold text-brand-dark">{{ $booking->check_in_date->format('d') }}</p>
                    <p class="text-gray-600">{{ $booking->check_in_date->format('F Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->check_in_date->format('l') }}</p>
                </div>

                {{-- Check-out --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Check-out</h3>
                    <p class="text-2xl font-bold text-brand-dark">{{ $booking->check_out_date->format('d') }}</p>
                    <p class="text-gray-600">{{ $booking->check_out_date->format('F Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->check_out_date->format('l') }}</p>
                </div>
            </div>

            {{-- Special Requests --}}
            @if ($booking->special_requests)
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Special Requests</h3>
                    <p class="text-gray-600">{{ $booking->special_requests }}</p>
                </div>
            @endif

            {{-- Total Price --}}
            <div class="border-t border-gray-100 pt-6 mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-gray-600">{{ $booking->number_of_nights }} {{ Str::plural('Night', $booking->number_of_nights) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-brand-primary">MVR {{ number_format($booking->total_price, 2) }}</p>
                        <p class="text-sm text-green-600 font-semibold">✓ Paid</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- What's Next --}}
        <div class="bg-brand-primary/5 border border-brand-primary/20 rounded-2xl p-6 mb-6">
            <h3 class="font-semibold text-brand-dark mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                What's Next?
            </h3>
            <ul class="space-y-3 text-sm text-gray-700">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    You'll receive a confirmation email with all booking details
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Check-in time is 2:00 PM • Check-out time is 12:00 PM
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    You can now book ferry tickets with your confirmed hotel reservation
                </li>
            </ul>
        </div>

        {{-- Ferry Booking CTA --}}
        <div class="bg-gradient-to-r from-brand-secondary to-brand-primary rounded-2xl p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Need Ferry Tickets?</h3>
                    <p class="text-white/90">Book your ferry ride to the island now with your confirmed hotel reservation!</p>
                </div>
                <a href="{{ route('ferry-tickets.browse') }}" wire:navigate
                    class="bg-white text-brand-primary hover:bg-gray-50 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg whitespace-nowrap">
                    Book Ferry →
                </a>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('my-bookings') }}" wire:navigate
                class="flex-1 bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-4 rounded-xl font-semibold text-center transition-all transform hover:scale-105 shadow-lg">
                View All Bookings
            </a>
            <a href="{{ route('home') }}" wire:navigate
                class="flex-1 bg-white hover:bg-gray-50 text-brand-dark border border-gray-200 px-6 py-4 rounded-xl font-semibold text-center transition-all">
                Return to Home
            </a>
        </div>
    </div>
</div>
