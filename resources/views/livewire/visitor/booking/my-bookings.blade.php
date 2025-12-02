<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Bookings</span>
            </h1>
            <p class="text-gray-600">View and manage all your bookings</p>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl flex items-start gap-3"
                x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-start gap-3"
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

        {{-- Booking Type Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm p-2 mb-6 inline-flex gap-2">
            <button wire:click="$set('bookingType', 'hotel')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $bookingType === 'hotel' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                🏨 Hotel Bookings
            </button>
            <button wire:click="$set('bookingType', 'ferry')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $bookingType === 'ferry' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                🚢 Ferry Tickets
            </button>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm p-2 mb-6 inline-flex gap-2">
            <button wire:click="$set('activeTab', 'upcoming')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $activeTab === 'upcoming' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Upcoming
            </button>
            <button wire:click="$set('activeTab', 'past')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $activeTab === 'past' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Past
            </button>
            <button wire:click="$set('activeTab', 'cancelled')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $activeTab === 'cancelled' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Cancelled
            </button>
        </div>

        {{-- Bookings List --}}
        @if ($bookings->isEmpty())
            <div class="bg-white rounded-3xl shadow-lg p-16 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">
                    No {{ $activeTab === 'upcoming' ? 'Upcoming' : ($activeTab === 'past' ? 'Past' : 'Cancelled') }} {{ $bookingType === 'hotel' ? 'Bookings' : 'Tickets' }}
                </h3>
                <p class="text-gray-600 mb-6">You don't have any {{ $activeTab }} {{ $bookingType === 'hotel' ? 'hotel bookings' : 'ferry tickets' }} at the moment.</p>
                <a href="{{ $bookingType === 'hotel' ? route('booking.search') : route('ferry-tickets.browse') }}" wire:navigate
                    class="inline-block bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                    {{ $bookingType === 'hotel' ? 'Book a Room' : 'Book Ferry Ticket' }}
                </a>
            </div>
        @elseif($bookingType === 'hotel')
            <div class="grid gap-6">
                @foreach ($bookings as $booking)
                    <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="grid md:grid-cols-4 gap-6 p-6">
                            {{-- Hotel & Room Image --}}
                            <div class="md:col-span-1">
                                @php
                                    $primaryImage = $booking->room->getPrimaryImage();
                                @endphp
                                @if ($primaryImage)
                                    <img src="{{ Storage::url($primaryImage->image_path) }}"
                                        alt="{{ $booking->room->full_description }}"
                                        class="w-full h-32 md:h-full rounded-2xl object-cover">
                                @else
                                    <div class="w-full h-32 md:h-full bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 rounded-2xl flex items-center justify-center">
                                        <svg class="w-12 h-12 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Booking Details --}}
                            <div class="md:col-span-2">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-display font-bold text-brand-dark mb-1">
                                            {{ $booking->hotel->name }}
                                        </h3>
                                        <p class="text-gray-600">{{ $booking->room->full_description }}</p>
                                        <div class="flex items-center gap-1 mt-1 text-brand-accent text-sm">
                                            {{ str_repeat('⭐', $booking->hotel->star_rating) }}
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-blue-100 text-blue-600' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 mb-1">Check-in</p>
                                        <p class="font-semibold text-brand-dark">{{ $booking->check_in_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->check_in_date->format('l') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Check-out</p>
                                        <p class="font-semibold text-brand-dark">{{ $booking->check_out_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->check_out_date->format('l') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Guests</p>
                                        <p class="font-semibold text-brand-dark">{{ $booking->number_of_guests }} {{ Str::plural('Guest', $booking->number_of_guests) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Duration</p>
                                        <p class="font-semibold text-brand-dark">{{ $booking->number_of_nights }} {{ Str::plural('Night', $booking->number_of_nights) }}</p>
                                    </div>
                                </div>

                                @if ($booking->special_requests)
                                    <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                                        <p class="text-xs text-gray-500 mb-1">Special Requests</p>
                                        <p class="text-sm text-gray-700">{{ $booking->special_requests }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Price & Actions --}}
                            <div class="md:col-span-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                                    <p class="text-2xl font-bold text-brand-primary mb-1">MVR {{ number_format($booking->total_price, 2) }}</p>
                                    <p class="text-xs {{ $booking->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-semibold">
                                        {{ $booking->payment_status === 'paid' ? '✓ Paid' : '⏱ Pending' }}
                                    </p>
                                </div>

                                <div class="space-y-2 mt-4">
                                    <p class="text-xs text-gray-500">Booking Ref:</p>
                                    <p class="font-mono text-sm font-semibold text-brand-dark">{{ $booking->booking_reference }}</p>

                                    {{-- Actions based on status --}}
                                    @if ($booking->status === 'confirmed' && $booking->check_in_date->isFuture())
                                        <div x-data="{ showCancelModal: false }">
                                            <button @click="showCancelModal = true"
                                                class="w-full text-sm text-red-600 hover:text-red-700 font-semibold py-2 px-4 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                                Cancel Booking
                                            </button>

                                            {{-- Cancel Confirmation Modal --}}
                                            <div x-show="showCancelModal"
                                                x-cloak
                                                class="fixed inset-0 z-50 overflow-y-auto"
                                                aria-labelledby="modal-title"
                                                role="dialog"
                                                aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    {{-- Background overlay --}}
                                                    <div x-show="showCancelModal"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                        @click="showCancelModal = false"
                                                        aria-hidden="true"></div>

                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                    {{-- Modal panel --}}
                                                    <div x-show="showCancelModal"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                        class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                                                    Cancel Booking
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500">
                                                                        Are you sure you want to cancel this booking? This action cannot be undone. Your refund will be processed within 5-7 business days.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                                            <button type="button"
                                                                wire:click="cancelBooking({{ $booking->id }})"
                                                                @click="showCancelModal = false"
                                                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                                Yes, Cancel Booking
                                                            </button>
                                                            <button type="button"
                                                                @click="showCancelModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                                Keep Booking
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Ferry Tickets List --}}
            <div class="grid gap-6">
                @foreach ($bookings as $ticket)
                    <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="grid md:grid-cols-4 gap-6 p-6">
                            {{-- Ferry Icon --}}
                            <div class="md:col-span-1">
                                <div class="w-full h-32 md:h-full bg-gradient-to-br from-blue-100 to-cyan-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- Ticket Details --}}
                            <div class="md:col-span-2">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-display font-bold text-brand-dark mb-1">
                                            {{ $ticket->schedule->route->origin }} → {{ $ticket->schedule->route->destination }}
                                        </h3>
                                        <p class="text-gray-600">{{ $ticket->schedule->route->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $ticket->schedule->vessel->name }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $ticket->status === 'confirmed' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $ticket->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}
                                        {{ $ticket->status === 'used' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $ticket->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 mb-1">Travel Date</p>
                                        <p class="font-semibold text-brand-dark">{{ $ticket->travel_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $ticket->travel_date->format('l') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Departure Time</p>
                                        <p class="font-semibold text-brand-dark">{{ $ticket->schedule->departure_time->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500">{{ $ticket->schedule->arrival_time->format('H:i') }} arrival</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Passengers</p>
                                        <p class="font-semibold text-brand-dark">{{ $ticket->number_of_passengers }} {{ Str::plural('Passenger', $ticket->number_of_passengers) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Ferry Service</p>
                                        <p class="font-semibold text-green-600">FREE</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Price & Actions --}}
                            <div class="md:col-span-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                                    <p class="text-2xl font-bold text-green-600 mb-1">FREE</p>
                                    <p class="text-xs text-green-600 font-semibold">✓ No Charge</p>
                                </div>

                                <div class="space-y-2 mt-4">
                                    <p class="text-xs text-gray-500">Ticket Ref:</p>
                                    <p class="font-mono text-sm font-semibold text-brand-dark">{{ $ticket->ticket_reference }}</p>

                                    {{-- Actions based on status --}}
                                    @if ($ticket->status === 'confirmed' && $ticket->travel_date->isFuture())
                                        <div x-data="{ showCancelModal: false }">
                                            <button @click="showCancelModal = true"
                                                class="w-full text-sm text-red-600 hover:text-red-700 font-semibold py-2 px-4 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                                Cancel Ticket
                                            </button>

                                            {{-- Cancel Confirmation Modal --}}
                                            <div x-show="showCancelModal"
                                                x-cloak
                                                class="fixed inset-0 z-50 overflow-y-auto"
                                                aria-labelledby="modal-title"
                                                role="dialog"
                                                aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    {{-- Background overlay --}}
                                                    <div x-show="showCancelModal"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                        @click="showCancelModal = false"
                                                        aria-hidden="true"></div>

                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                    {{-- Modal panel --}}
                                                    <div x-show="showCancelModal"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                        class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                                                    Cancel Ferry Ticket
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500">
                                                                        Are you sure you want to cancel this ferry ticket? This action cannot be undone.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                                            <button type="button"
                                                                wire:click="cancelFerryTicket({{ $ticket->id }})"
                                                                @click="showCancelModal = false"
                                                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                                Yes, Cancel Ticket
                                                            </button>
                                                            <button type="button"
                                                                @click="showCancelModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                                Keep Ticket
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- View Details Button --}}
                                    <a href="{{ route('ferry-tickets.show', $ticket) }}" wire:navigate
                                        class="block w-full text-center text-sm text-brand-primary hover:text-brand-primary/80 font-semibold py-2 px-4 border border-brand-primary/20 rounded-lg hover:bg-brand-primary/5 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
