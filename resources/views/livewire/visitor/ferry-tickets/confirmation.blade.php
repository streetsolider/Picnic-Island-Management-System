<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Success Message --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Booking <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Confirmed!</span>
            </h1>
            <p class="text-gray-600">Your ferry ticket has been successfully booked</p>
        </div>

        {{-- Ticket Reference Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
            <div class="text-center mb-8">
                <p class="text-sm text-gray-600 mb-2">Your Ticket Reference</p>
                <div class="inline-block bg-brand-primary/10 px-8 py-4 rounded-xl">
                    <p class="text-3xl font-mono font-bold text-brand-primary tracking-wider">{{ $ticket->ticket_reference }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-3">Please save this reference number for boarding</p>
            </div>

            {{-- Ticket Details --}}
            <div class="border-t border-b border-gray-100 py-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Route</span>
                    <span class="font-semibold text-brand-dark">{{ $ticket->schedule->route->origin }} → {{ $ticket->schedule->route->destination }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Travel Date</span>
                    <span class="font-semibold text-brand-dark">{{ $ticket->travel_date->format('l, M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Departure Time</span>
                    <span class="font-semibold text-brand-dark">{{ $ticket->schedule->departure_time->format('H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Passengers</span>
                    <span class="font-semibold text-brand-dark">{{ $ticket->number_of_passengers }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ferry Vessel</span>
                    <span class="font-semibold text-brand-dark">{{ $ticket->schedule->vessel->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ferry Service</span>
                    <span class="text-xl font-bold text-green-600">FREE</span>
                </div>
            </div>

            {{-- Important Information --}}
            <div class="mt-6">
                <h3 class="font-semibold text-brand-dark mb-3">Important Information</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Please arrive at the ferry terminal 30 minutes before departure
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Present your ticket reference ({{ $ticket->ticket_reference }}) and valid ID at check-in
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cancellations must be made at least 24 hours before departure
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Maximum luggage: 20kg per passenger
                    </li>
                </ul>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('ferry-tickets.show', $ticket) }}"
                class="flex-1 bg-brand-primary hover:bg-brand-primary/90 text-white text-center px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                View Ticket Details
            </a>
            <a href="{{ route('ferry-tickets.my-tickets') }}"
                class="flex-1 bg-white hover:bg-gray-50 text-brand-dark text-center px-6 py-3 rounded-xl font-semibold border-2 border-gray-200 transition-all">
                View All My Tickets
            </a>
        </div>
    </div>
</div>
