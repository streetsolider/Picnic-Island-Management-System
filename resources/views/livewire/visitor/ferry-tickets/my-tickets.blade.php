<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Ferry Tickets</span>
            </h1>
            <p class="text-gray-600">View and manage your ferry ticket bookings</p>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white rounded-2xl shadow-lg p-2 mb-6 inline-flex gap-2">
            <button wire:click="$set('filter', 'upcoming')"
                class="px-6 py-2 rounded-xl font-semibold transition-all {{ $filter === 'upcoming' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Upcoming
            </button>
            <button wire:click="$set('filter', 'past')"
                class="px-6 py-2 rounded-xl font-semibold transition-all {{ $filter === 'past' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Past
            </button>
            <button wire:click="$set('filter', 'cancelled')"
                class="px-6 py-2 rounded-xl font-semibold transition-all {{ $filter === 'cancelled' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Cancelled
            </button>
            <button wire:click="$set('filter', 'all')"
                class="px-6 py-2 rounded-xl font-semibold transition-all {{ $filter === 'all' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                All
            </button>
        </div>

        {{-- Tickets List --}}
        @if($tickets->isEmpty())
            <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Tickets Found</h3>
                <p class="text-gray-600 mb-6">You don't have any {{ $filter !== 'all' ? $filter : '' }} ferry tickets.</p>
                <a href="{{ route('ferry-tickets.browse') }}"
                    class="inline-block bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                    Book a Ferry Ticket
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($tickets as $ticket)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow p-6">
                        <div class="grid md:grid-cols-5 gap-4 items-center">
                            {{-- Ticket Reference --}}
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Ticket Reference</p>
                                <p class="font-mono font-bold text-brand-primary">{{ $ticket->ticket_reference }}</p>
                            </div>

                            {{-- Route --}}
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Route</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->schedule->route->origin }} → {{ $ticket->schedule->route->destination }}</p>
                            </div>

                            {{-- Travel Date & Time --}}
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Travel Date</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->travel_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $ticket->schedule->departure_time->format('H:i') }}</p>
                            </div>

                            {{-- Passengers & Price --}}
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Passengers / Total</p>
                                <p class="font-semibold text-gray-900">{{ $ticket->number_of_passengers }} / MVR {{ number_format($ticket->total_price, 2) }}</p>
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    {{ $ticket->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $ticket->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $ticket->status === 'used' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $ticket->status === 'expired' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('ferry-tickets.show', $ticket) }}"
                                    class="bg-brand-primary hover:bg-brand-primary/90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
