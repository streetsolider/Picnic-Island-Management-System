<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header with Back Button --}}
        <div class="mb-8">
            <a href="{{ route('ferry-tickets.my-tickets') }}" class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-primary/80 font-semibold mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to My Tickets
            </a>
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Ferry Ticket <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Details</span>
            </h1>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <p class="text-green-600 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-600 font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Ticket Status Banner --}}
        @if($ticket->status === 'cancelled')
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-900">This ticket has been cancelled</p>
                        @if($ticket->cancellation_reason)
                            <p class="text-sm text-red-700">Reason: {{ $ticket->cancellation_reason }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($ticket->status === 'used')
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">This ticket has been used</p>
                        <p class="text-sm text-gray-700">Validated on {{ $ticket->validated_at?->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Main Ticket Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Ticket Reference Card --}}
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-600 mb-2">Ticket Reference</p>
                        <div class="inline-block bg-brand-primary/10 px-8 py-4 rounded-xl">
                            <p class="text-3xl font-mono font-bold text-brand-primary tracking-wider">{{ $ticket->ticket_reference }}</p>
                        </div>
                    </div>

                    {{-- Ferry Details --}}
                    <div class="border-t border-gray-100 pt-6 space-y-4">
                        <h3 class="font-semibold text-brand-dark text-lg mb-4">Ferry Details</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Route</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->schedule->route->name }}</p>
                                <p class="text-sm text-gray-600">{{ $ticket->schedule->route->origin }} → {{ $ticket->schedule->route->destination }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">Ferry Vessel</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->schedule->vessel->name }}</p>
                                <p class="text-sm text-gray-600">{{ $ticket->schedule->vessel->vessel_type }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">Travel Date</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->travel_date->format('l, M d, Y') }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">Departure Time</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->schedule->departure_time->format('H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">Number of Passengers</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->number_of_passengers }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                <span class="inline-block px-3 py-1 rounded-lg text-sm font-semibold
                                    {{ $ticket->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $ticket->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $ticket->status === 'used' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $ticket->status === 'expired' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hotel Booking Info --}}
                <div class="bg-white rounded-3xl shadow-lg p-6">
                    <h3 class="font-semibold text-brand-dark mb-4">Associated Hotel Booking</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="font-semibold text-blue-900 mb-1">{{ $ticket->hotelBooking->booking_reference }}</p>
                        <p class="text-sm text-blue-800">
                            {{ $ticket->hotelBooking->check_in_date->format('M d, Y') }} - {{ $ticket->hotelBooking->check_out_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Pricing Summary --}}
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-6">
                    <h3 class="font-semibold text-brand-dark mb-4">Payment Summary</h3>
                    <div class="space-y-3 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Price per passenger</span>
                            <span class="font-semibold">MVR {{ number_format($ticket->price_per_passenger, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Number of passengers</span>
                            <span class="font-semibold">{{ $ticket->number_of_passengers }}</span>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-dark">Total Paid</span>
                            <span class="text-2xl font-bold text-brand-primary">MVR {{ number_format($ticket->total_price, 2) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Payment Method: {{ ucfirst($ticket->payment_method) }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                @if($ticket->canBeCancelled())
                    <div class="bg-white rounded-3xl shadow-lg p-6">
                        <h3 class="font-semibold text-brand-dark mb-4">Actions</h3>
                        <button wire:click="confirmCancel"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-red-500/30">
                            Cancel Ticket
                        </button>
                        <p class="text-xs text-gray-500 mt-3 text-center">Cancellations must be made at least 24 hours before departure</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Cancel Confirmation Modal --}}
    <x-visitor.modal.confirmation
        wire:model="showCancelModal"
        title="Cancel Ferry Ticket"
        message="Are you sure you want to cancel this ferry ticket? This action cannot be undone."
        confirmText="Yes, Cancel Ticket"
        cancelText="Keep Ticket"
        confirmAction="cancelTicket"
        type="danger">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mt-3">
            <p class="text-sm text-yellow-800">
                <strong>Note:</strong> Cancellations must be made at least 24 hours before departure.
            </p>
        </div>
    </x-visitor.modal.confirmation>
</div>
