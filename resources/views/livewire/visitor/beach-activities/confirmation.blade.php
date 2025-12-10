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
            <p class="text-xl text-gray-600">Your beach activity has been successfully booked</p>
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
                {{-- Activity --}}
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Activity</h3>
                    <div class="flex items-start gap-4">
                        <span class="text-5xl">{{ $booking->service->category->icon }}</span>
                        <div class="flex-1">
                            <p class="font-semibold text-brand-dark text-lg">{{ $booking->service->name }}</p>
                            <p class="text-gray-600">{{ $booking->service->category->name }}</p>
                        </div>
                    </div>
                </div>

                {{-- Guest Info --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Guest Details</h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-brand-dark">{{ $booking->guest->name }}</p>
                        <p class="text-gray-600">{{ $booking->guest->email }}</p>
                    </div>
                </div>

                {{-- Hotel Booking --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Hotel Booking</h3>
                    <div class="space-y-2">
                        <p class="font-mono text-brand-primary font-semibold">{{ $booking->hotelBooking->booking_reference }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ $booking->hotelBooking->check_in_date->format('M j') }} -
                            {{ $booking->hotelBooking->check_out_date->format('M j, Y') }}
                        </p>
                    </div>
                </div>

                {{-- Date --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Activity Date</h3>
                    <p class="text-2xl font-bold text-brand-dark">{{ $booking->booking_date->format('d') }}</p>
                    <p class="text-gray-600">{{ $booking->booking_date->format('F Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->booking_date->format('l') }}</p>
                </div>

                {{-- Time --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Time Slot</h3>
                    <p class="text-lg font-bold text-brand-dark">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                    </p>
                    @if($booking->duration_hours)
                        <p class="text-sm text-gray-500">{{ $booking->duration_hours }} {{ $booking->duration_hours == 1 ? 'hour' : 'hours' }}</p>
                    @endif
                </div>
            </div>

            {{-- Total Price --}}
            <div class="border-t border-gray-100 pt-6 mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        @if($booking->duration_hours)
                            <p class="text-gray-600">{{ $booking->duration_hours }} {{ $booking->duration_hours == 1 ? 'hour' : 'hours' }}</p>
                        @else
                            <p class="text-gray-600">1 slot</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-brand-primary">MVR {{ number_format($booking->total_price, 2) }}</p>
                        <p class="text-sm text-green-600 font-semibold">✓ Confirmed</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Please arrive at least <strong>10 minutes before</strong> your scheduled activity time
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Present your <strong>booking reference ({{ $booking->booking_reference }})</strong> to the beach staff for validation
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cancellations must be made at least <strong>24 hours in advance</strong>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                    Activities are subject to weather conditions and may be rescheduled for safety
                </li>
            </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('visitor.beach-activities.my-bookings') }}" wire:navigate
                class="flex-1 bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-4 rounded-xl font-semibold text-center transition-all transform hover:scale-105 shadow-lg">
                View All Bookings
            </a>
            <a href="{{ route('visitor.beach-activities.browse') }}" wire:navigate
                class="flex-1 bg-white hover:bg-gray-50 text-brand-dark border border-gray-200 px-6 py-4 rounded-xl font-semibold text-center transition-all">
                Book Another Activity
            </a>
        </div>
    </div>
</div>
