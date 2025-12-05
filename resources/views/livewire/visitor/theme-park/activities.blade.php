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
                        <p class="text-sm font-medium">Your Credits</p>
                        <p class="text-3xl font-bold">{{ $wallet->credit_balance }}</p>
                    </div>
                </div>
                <p class="text-xl text-brand-dark/70">Browse and purchase activity tickets with your credits</p>
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
                                        {{ $activity->credit_cost }} 💳
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="font-medium">{{ ucfirst($activity->activity_type) }}</span>
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
                                @if($activity->showSchedules->isNotEmpty())
                                    <div class="mb-4 pt-4 border-t-2 border-gray-100">
                                        <p class="text-xs font-bold text-gray-700 mb-3 uppercase">📅 Available Schedules:</p>
                                        <div class="space-y-2 max-h-32 overflow-y-auto">
                                            @foreach($activity->showSchedules->take(3) as $schedule)
                                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg px-3 py-2 border border-blue-200">
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="font-bold text-brand-dark">
                                                            {{ $schedule->show_date->format('M d') }}
                                                        </span>
                                                        <span class="text-gray-600 font-medium">
                                                            {{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        ✓ {{ $schedule->getRemainingSeats() }} seats available
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($activity->showSchedules->count() > 3)
                                                <p class="text-xs text-gray-500 text-center py-1 font-medium">
                                                    +{{ $activity->showSchedules->count() - 3 }} more schedule(s)
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Purchase Button --}}
                                <button
                                    wire:click="selectActivity({{ $activity->id }})"
                                    {{ $wallet->credit_balance < $activity->credit_cost ? 'disabled' : '' }}
                                    class="w-full mt-auto px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg {{ $wallet->credit_balance < $activity->credit_cost ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-brand-secondary to-pink-600 text-white hover:from-pink-600 hover:to-brand-secondary' }}">
                                    @if($wallet->credit_balance < $activity->credit_cost)
                                        ❌ Insufficient Credits
                                    @else
                                        🎟️ Purchase for {{ $activity->credit_cost }} Credits
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
            @if($wallet->credit_balance < 5)
                <div class="max-w-6xl mx-auto bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-2xl px-6 py-4 shadow-lg">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-yellow-900 font-bold">⚠️ Your credit balance is low! Top up your wallet to purchase more credits.</span>
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

    {{-- Purchase Confirmation Modal --}}
    @if($selectedActivity)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="cancelRedemption">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all relative" @click.stop>
                <div class="bg-gradient-to-r from-brand-secondary to-pink-600 p-4 text-white">
                    <h3 class="text-xl font-display font-bold">🎟️ Confirm Activity Purchase</h3>
                    <p class="text-white/90 text-xs mt-1">{{ $selectedActivity->name }} • 📍 {{ $selectedActivity->zone->name }}</p>
                </div>

                <form wire:submit.prevent="purchaseTicket" class="p-4 space-y-3">
                    {{-- Schedule Selection (for scheduled shows only) --}}
                    @if($selectedActivity->isScheduled())
                        <div>
                            <label for="selectedSchedule" class="block text-sm font-bold text-gray-700 mb-1">
                                Select Show Time <span class="text-red-500">*</span>
                            </label>
                            <select id="selectedSchedule" wire:model.live="selectedSchedule"
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-semibold">
                                <option value="">-- Select a schedule --</option>
                                @foreach($selectedActivity->showSchedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ $schedule->show_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}
                                        ({{ $schedule->getRemainingSeats() }} seats left)
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedSchedule')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="numberOfPersons" class="block text-sm font-bold text-gray-700 mb-1">
                            Number of Persons <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="numberOfPersons" wire:model.live="numberOfPersons" min="1" max="50"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-semibold"
                            placeholder="Enter number" />
                        @error('numberOfPersons')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                        $totalCost = $selectedActivity->credit_cost * $numberOfPersons;
                        $balanceAfter = $wallet->credit_balance - $totalCost;
                        $hasInsufficient = $balanceAfter < 0;
                    @endphp

                    <div class="rounded-xl p-3 border-2 {{ $hasInsufficient ? 'bg-red-50 border-red-300' : 'bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200' }}">
                        <div class="grid grid-cols-2 gap-2 text-sm mb-2">
                            <span class="text-gray-600">Cost per person:</span>
                            <span class="font-bold text-gray-900 text-right">{{ $selectedActivity->credit_cost }} credits</span>
                            <span class="text-gray-600">Number of persons:</span>
                            <span class="font-bold text-gray-900 text-right">{{ $numberOfPersons }}</span>
                        </div>
                        <div class="pt-2 border-t-2 {{ $hasInsufficient ? 'border-red-200' : 'border-purple-200' }}">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-gray-700">Total Cost:</span>
                                <span class="text-lg font-bold text-purple-600">{{ $totalCost }} credits</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Current Balance:</span>
                                <span class="font-semibold text-gray-900">{{ $wallet->credit_balance }} credits</span>
                            </div>
                            <div class="flex justify-between items-center mt-1 pt-1 border-t {{ $hasInsufficient ? 'border-red-200' : 'border-purple-200' }}">
                                <span class="text-xs font-bold text-gray-700">Balance After:</span>
                                <span class="text-base font-bold flex items-center gap-1 {{ $hasInsufficient ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $balanceAfter }} credits
                                    @if($hasInsufficient)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($hasInsufficient)
                        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-bold text-red-800">Insufficient Credits</p>
                                    <p class="text-xs text-red-700 mt-0.5">
                                        You need <strong>{{ abs($balanceAfter) }} more credits</strong>. Please purchase credits first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="purchaseTicket"
                            @if($hasInsufficient) disabled @endif
                            class="flex-1 bg-gradient-to-r from-brand-secondary to-pink-600 text-white px-4 py-2.5 rounded-xl font-bold hover:from-pink-600 hover:to-brand-secondary transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-sm">
                            @if($hasInsufficient)
                                <span>Insufficient Credits</span>
                            @else
                                <span wire:loading.remove wire:target="purchaseTicket">🎟️ Purchase</span>
                                <span wire:loading wire:target="purchaseTicket">Processing...</span>
                            @endif
                        </button>
                        <button type="button" wire:click="cancelRedemption"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
