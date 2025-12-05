<div class="min-h-screen bg-gradient-to-br from-brand-light via-pink-50 to-purple-100/20">
    <section class="relative py-12 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 left-10 w-72 h-72 bg-pink-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Activity Ticket <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-secondary to-purple-600">History</span>
                </h1>
                <p class="text-xl text-brand-dark/70">View your activity ticket purchases and their status</p>
            </div>

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filter Buttons --}}
            <div class="mb-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6">
                <label class="block text-sm font-bold text-brand-dark mb-4">
                    📊 Filter by Status
                </label>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="$set('filter', 'all')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'all' ? 'bg-gradient-to-r from-brand-primary to-brand-secondary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        All
                    </button>
                    <button wire:click="$set('filter', 'valid')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'valid' ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ⏳ Valid
                    </button>
                    <button wire:click="$set('filter', 'redeemed')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'redeemed' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ✅ Redeemed
                    </button>
                    <button wire:click="$set('filter', 'cancelled')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-pink-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ❌ Cancelled
                    </button>
                    <button wire:click="$set('filter', 'expired')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'expired' ? 'bg-gradient-to-r from-gray-500 to-gray-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ⏰ Expired
                    </button>
                </div>
            </div>

            {{-- Tickets List --}}
            @if($tickets->isEmpty())
                <div class="bg-white rounded-3xl shadow-2xl p-16 text-center">
                    <div class="text-6xl mb-6">📋</div>
                    <h3 class="text-3xl font-display font-bold text-brand-dark mb-4">No Activity Tickets Yet</h3>
                    <p class="text-xl text-gray-600 mb-8">You haven't purchased any activity tickets yet. Visit the activities page to get started!</p>
                    <a href="{{ route('visitor.theme-park.activities') }}" wire:navigate
                        class="inline-block bg-gradient-to-r from-brand-primary to-brand-secondary text-white px-8 py-3 rounded-xl font-bold hover:from-brand-secondary hover:to-brand-primary transition-all transform hover:scale-105 shadow-lg">
                        🎢 Browse Activities
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($tickets as $ticket)
                        <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                            {{-- Card Header --}}
                            <div class="bg-gradient-to-r {{
                                $ticket->status === 'redeemed' ? 'from-green-500 to-emerald-600' :
                                ($ticket->status === 'cancelled' ? 'from-red-500 to-pink-600' :
                                ($ticket->status === 'expired' ? 'from-gray-500 to-gray-600' : 'from-blue-500 to-purple-500'))
                            }} p-6 text-white">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold mb-2">{{ $ticket->activity->name }}</h3>
                                        <p class="text-sm text-white/90 font-medium">📍 {{ $ticket->activity->zone->name }} Zone</p>
                                        @if($ticket->showSchedule)
                                            <p class="text-sm text-white/90 font-medium mt-1">
                                                📅 {{ $ticket->showSchedule->show_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($ticket->showSchedule->show_time)->format('g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white/20 backdrop-blur-sm">
                                        {{
                                            $ticket->status === 'redeemed' ? '✅ Redeemed' :
                                            ($ticket->status === 'cancelled' ? '❌ Cancelled' :
                                            ($ticket->status === 'expired' ? '⏰ Expired' : '⏳ Valid'))
                                        }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">
                                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 mb-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Ticket Reference</p>
                                        <p class="font-mono text-lg font-bold text-brand-primary">{{ $ticket->ticket_reference }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Number of Persons</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $ticket->quantity }} 👥</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Credits Spent</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $ticket->credits_spent }} 💳</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Purchased At</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $ticket->purchase_datetime->format('M d, Y h:i A') }}</p>
                                    </div>
                                    @if($ticket->status === 'redeemed')
                                        <div class="bg-green-50 rounded-xl p-4">
                                            <p class="text-sm text-green-700 mb-1">Redeemed At</p>
                                            <p class="text-lg font-bold text-green-900">{{ $ticket->redeemed_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                                        </div>
                                    @endif
                                    @if($ticket->valid_until)
                                        <div class="bg-blue-50 rounded-xl p-4">
                                            <p class="text-sm text-blue-700 mb-1">Valid Until</p>
                                            <p class="text-lg font-bold text-blue-900">{{ $ticket->valid_until->format('M d, Y h:i A') }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($ticket->status === 'redeemed')
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl px-6 py-4">
                                        <div class="flex items-center text-green-800">
                                            <svg class="w-6 h-6 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-bold">✅ Redeemed by staff member</span>
                                        </div>
                                    </div>
                                @elseif($ticket->status === 'cancelled')
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl px-6 py-4">
                                        <div class="text-red-800">
                                            <p class="font-bold mb-1">❌ Cancelled</p>
                                            <p class="text-sm">{{ $ticket->cancellation_reason ?? 'No reason provided' }}</p>
                                        </div>
                                    </div>
                                @elseif($ticket->status === 'expired')
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl px-6 py-4">
                                        <div class="text-gray-800">
                                            <p class="font-bold mb-1">⏰ Expired</p>
                                            <p class="text-sm">This ticket has expired and can no longer be used.</p>
                                        </div>
                                    </div>
                                @elseif($ticket->status === 'valid')
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 rounded-2xl px-6 py-4">
                                        <div class="flex items-center text-blue-900">
                                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-bold">Show code <span class="font-mono text-lg text-brand-primary">{{ $ticket->ticket_reference }}</span> to staff at the activity entrance</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($tickets->hasPages())
                    <div class="mt-8">
                        {{ $tickets->links() }}
                    </div>
                @endif
            @endif

            {{-- Info Box --}}
            <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 border-2 border-brand-primary/20">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-brand-primary mr-3 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-brand-dark mb-3">📚 Ticket Status Guide:</p>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <span class="mr-2">⏳</span>
                                <div>
                                    <strong class="text-gray-900">Valid:</strong>
                                    <span class="text-gray-700">Ready to use - show your ticket reference to staff at the activity entrance</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">✅</span>
                                <div>
                                    <strong class="text-gray-900">Redeemed:</strong>
                                    <span class="text-gray-700">Validated by staff - you've participated in the activity</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">❌</span>
                                <div>
                                    <strong class="text-gray-900">Cancelled:</strong>
                                    <span class="text-gray-700">Ticket cancelled - credits returned to your wallet</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">⏰</span>
                                <div>
                                    <strong class="text-gray-900">Expired:</strong>
                                    <span class="text-gray-700">Ticket validity period has passed - can no longer be used</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
