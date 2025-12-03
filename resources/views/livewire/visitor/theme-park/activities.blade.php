<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10">
    <section class="relative py-12 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 right-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-40 left-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-4 mb-4 flex-wrap">
                    <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark">
                        Theme Park <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Activities</span>
                    </h1>
                    <div class="bg-gradient-to-br from-purple-600 to-brand-secondary rounded-2xl px-6 py-3 text-white shadow-xl transform hover:scale-105 transition-all">
                        <p class="text-sm font-medium">Your Tickets</p>
                        <p class="text-3xl font-bold">{{ $wallet->ticket_balance }}</p>
                    </div>
                </div>
                <p class="text-xl text-brand-dark/70">Browse and redeem tickets for exciting activities</p>
            </div>

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="max-w-6xl mx-auto mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{!! session('success') !!}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-6xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Zone Filter --}}
            @if($zones->isNotEmpty())
                <div class="max-w-6xl mx-auto mb-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6">
                    <label class="block text-sm font-bold text-brand-dark mb-4">
                        🎯 Filter by Zone
                    </label>
                    <div class="flex flex-wrap gap-3">
                        <button wire:click="$set('selectedZone', null)"
                            class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $selectedZone === null ? 'bg-gradient-to-r from-brand-primary to-brand-secondary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            All Zones
                        </button>
                        @foreach($zones as $zone)
                            <button wire:click="$set('selectedZone', {{ $zone->id }})"
                                class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $selectedZone == $zone->id ? 'bg-gradient-to-r from-brand-primary to-brand-secondary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                {{ $zone->name }}
                                <span class="ml-2 px-2 py-1 rounded-full text-xs {{ $selectedZone == $zone->id ? 'bg-white/20' : 'bg-gray-100' }}">
                                    {{ $zone->activities->where('is_active', true)->count() }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Activities Grid --}}
            @if($activities->isEmpty())
                <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-2xl p-16 text-center">
                    <div class="text-6xl mb-6">🎢</div>
                    <h3 class="text-3xl font-display font-bold text-brand-dark mb-4">No Activities Available</h3>
                    <p class="text-xl text-gray-600 mb-2">There are currently no active activities in the selected zone.</p>
                    <p class="text-gray-500">Check back later for exciting new activities!</p>
                </div>
            @else
                <div class="max-w-6xl mx-auto grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
                    @foreach($activities as $activity)
                        <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl flex flex-col">
                            {{-- Activity Header --}}
                            <div class="bg-gradient-to-br from-brand-primary to-brand-secondary p-6 text-white">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-xl font-bold flex-1">{{ $activity->name }}</h3>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-white/20 backdrop-blur-sm">
                                        {{ $activity->ticket_cost }} 🎟️
                                    </span>
                                </div>
                                <p class="text-sm text-white/90 font-medium">📍 {{ $activity->zone->name }} Zone</p>
                            </div>

                            {{-- Activity Body --}}
                            <div class="p-6 flex flex-col flex-1">
                                @if($activity->description)
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        {{ Str::limit($activity->description, 100) }}
                                    </p>
                                @endif

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $activity->duration_minutes }} minutes</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $activity->capacity_per_session }} per session</span>
                                    </div>
                                    @if($activity->min_age || $activity->max_age)
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium">{{ $activity->min_age ?? 'Any' }} - {{ $activity->max_age ?? 'Any' }} years</span>
                                        </div>
                                    @endif
                                    @if($activity->height_requirement_cm)
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                            <span class="font-medium">Min height: {{ $activity->height_requirement_cm }} cm</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Available Schedules --}}
                                @if($activity->schedules->isNotEmpty())
                                    <div class="mb-4 pt-4 border-t-2 border-gray-100">
                                        <p class="text-xs font-bold text-gray-700 mb-3 uppercase">📅 Available Schedules:</p>
                                        <div class="space-y-2 max-h-32 overflow-y-auto">
                                            @foreach($activity->schedules->take(3) as $schedule)
                                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg px-3 py-2 border border-blue-200">
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="font-bold text-brand-dark">
                                                            {{ $schedule->schedule_date->format('M d') }}
                                                        </span>
                                                        <span class="text-gray-600 font-medium">
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        ✓ {{ $schedule->getRemainingSlots() }} slots available
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($activity->schedules->count() > 3)
                                                <p class="text-xs text-gray-500 text-center py-1 font-medium">
                                                    +{{ $activity->schedules->count() - 3 }} more schedule(s)
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Redeem Button --}}
                                <button
                                    wire:click="selectActivity({{ $activity->id }})"
                                    {{ $wallet->ticket_balance < $activity->ticket_cost ? 'disabled' : '' }}
                                    class="w-full mt-auto px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg {{ $wallet->ticket_balance < $activity->ticket_cost ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-brand-secondary to-pink-600 text-white hover:from-pink-600 hover:to-brand-secondary' }}">
                                    @if($wallet->ticket_balance < $activity->ticket_cost)
                                        ❌ Insufficient Tickets
                                    @else
                                        🎟️ Redeem {{ $activity->ticket_cost }} Tickets
                                    @endif
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($activities->hasPages())
                    <div class="max-w-6xl mx-auto mb-8">
                        {{ $activities->links() }}
                    </div>
                @endif
            @endif

            {{-- Low Balance Warning --}}
            @if($wallet->ticket_balance < 5)
                <div class="max-w-6xl mx-auto bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-2xl px-6 py-4 shadow-lg">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-yellow-900 font-bold">⚠️ Your ticket balance is low! Top up your wallet to purchase more tickets.</span>
                        </div>
                        <a href="{{ route('visitor.theme-park.wallet') }}" wire:navigate
                            class="bg-gradient-to-r from-brand-primary to-brand-secondary text-white px-6 py-2.5 rounded-xl font-bold hover:from-brand-secondary hover:to-brand-primary transition-all transform hover:scale-105 shadow-lg">
                            Go to Wallet 💰
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Redeem Confirmation Modal --}}
    @if($selectedActivity)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="cancelRedemption">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all relative" @click.stop>
                <div class="bg-gradient-to-r from-brand-secondary to-pink-600 p-6 text-white">
                    <h3 class="text-2xl font-display font-bold">🎟️ Confirm Ticket Redemption</h3>
                    <p class="text-white/90 text-sm mt-1">You're about to redeem tickets for this activity</p>
                </div>

                <form wire:submit="redeemTickets" class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Activity</p>
                        <p class="text-xl font-bold text-brand-dark">{{ $selectedActivity->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Zone</p>
                        <p class="text-lg font-semibold text-gray-900">📍 {{ $selectedActivity->zone->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Ticket Cost</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $selectedActivity->ticket_cost }} ticket(s)</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 border-2 border-purple-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Current Balance:</span>
                            <span class="font-bold text-gray-900">{{ $wallet->ticket_balance }} tickets</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-purple-200">
                            <span class="text-sm font-bold text-gray-700">Balance After:</span>
                            <span class="text-2xl font-bold text-purple-600">{{ $wallet->ticket_balance - $selectedActivity->ticket_cost }} tickets</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-2xl p-4 border-2 border-blue-200">
                        <p class="text-sm text-blue-900">
                            💡 After redeeming, you'll receive a redemption code. Show this code to the staff at the activity entrance for validation.
                        </p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="redeemTickets"
                            class="flex-1 bg-gradient-to-r from-brand-secondary to-pink-600 text-white px-6 py-3 rounded-xl font-bold hover:from-pink-600 hover:to-brand-secondary transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="redeemTickets">🎟️ Redeem Tickets</span>
                            <span wire:loading wire:target="redeemTickets">Processing...</span>
                        </button>
                        <button type="button" wire:click="cancelRedemption"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
