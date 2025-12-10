<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Complete Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Ferry Booking</span>
            </h1>
            <p class="text-gray-600">Review your ferry ticket details and confirm your booking</p>
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
                    <h2 class="text-2xl font-display font-bold text-brand-dark mb-6">Passenger Information</h2>

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

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Hotel Booking Confirmed</h4>
                                <p class="text-sm text-blue-800">
                                    Booking: {{ $hotelBooking->booking_reference }}<br>
                                    Hotel Stay: {{ $hotelBooking->check_in_date->format('M d, Y') }} - {{ $hotelBooking->check_out_date->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-semibold text-brand-dark mb-4">Important Information</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Please arrive at the ferry terminal 30 minutes before departure
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bring your ticket reference and valid ID
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Cancellations must be made at least 24 hours before departure
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Maximum luggage: 20kg per passenger
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Booking Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-6 sticky top-8">
                    <h2 class="text-xl font-display font-bold text-brand-dark mb-4">Ferry Ticket Summary</h2>

                    {{-- Ferry Route Info --}}
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <div class="mb-4">
                            <h3 class="font-semibold text-brand-dark text-lg">{{ $schedule->route->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($travelDate)->format('l, M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Departs: {{ $schedule->departure_time->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- Passengers & Pricing --}}
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Passengers</span>
                            <span class="font-semibold">{{ $passengers }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ferry Service</span>
                            <span class="font-semibold text-green-600">FREE</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ferry Vessel</span>
                            <span class="font-semibold text-xs">{{ $schedule->vessel->name }}</span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-brand-dark">Total Amount</span>
                            <span class="text-2xl font-bold text-green-600">FREE</span>
                        </div>
                    </div>

                    {{-- Confirm Button --}}
                    <button wire:click="confirmBooking" wire:loading.attr="disabled"
                        class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white px-6 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30 flex items-center justify-center gap-2">
                        <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Confirm Booking</span>
                        <span wire:loading>Processing...</span>
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        By confirming, you agree to our terms and conditions
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
