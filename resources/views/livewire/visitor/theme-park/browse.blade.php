<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10">
    <section class="relative py-12 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 right-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-40 left-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Theme Park <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Activities</span>
                </h1>
                <p class="text-xl text-brand-dark/70">Explore exciting rides and shows at our theme park</p>
                @guest
                    <p class="text-sm text-gray-600 mt-2">
                        <a href="{{ route('login') }}" class="text-brand-primary font-semibold hover:underline">Log in</a>
                        to book activities and enjoy the park!
                    </p>
                @endguest
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

            @if (session('info'))
                <div class="max-w-6xl mx-auto mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            @endif

            {{-- Zone Filter --}}
            @if($zones->isNotEmpty())
                <div class="max-w-6xl mx-auto mb-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6">
                    <label for="zone-filter" class="block text-sm font-bold text-brand-dark mb-3">
                        🎯 Filter by Zone
                    </label>
                    <select id="zone-filter" wire:model.live="selectedZone"
                        class="w-full md:w-auto px-6 py-3 rounded-xl font-bold text-gray-700 bg-white border-2 border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition-all shadow-md hover:shadow-lg">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->name }} ({{ $zone->activities->where('is_active', true)->count() }} activities)
                            </option>
                        @endforeach
                    </select>
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

                                {{-- Current Schedule / Operating Hours --}}
                                <div class="mb-4 pt-4 border-t-2 border-gray-100">
                                    @if($activity->isContinuous())
                                        {{-- Show operating hours for continuous rides --}}
                                        <p class="text-xs font-bold text-gray-700 mb-3 uppercase">🕐 Operating Hours:</p>
                                        @if($activity->operating_hours_start && $activity->operating_hours_end)
                                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg px-3 py-2 border border-blue-200">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="font-bold text-brand-dark">Daily</span>
                                                    <span class="text-gray-600 font-medium">
                                                        {{ $activity->operating_hours_start->format('g:i A') }} - {{ $activity->operating_hours_end->format('g:i A') }}
                                                    </span>
                                                </div>
                                                <div class="text-xs mt-1">
                                                    @if($activity->isCurrentlyOpen())
                                                        <span class="text-green-600 font-bold">✓ Currently Open</span>
                                                    @else
                                                        <span class="text-red-600 font-bold">✗ Currently Closed</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 italic">Operating hours not set</p>
                                        @endif
                                    @elseif($activity->showSchedules->isNotEmpty())
                                        {{-- Show available schedules for scheduled shows --}}
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
                                                    <div class="flex items-center justify-between text-xs text-gray-600 mt-1">
                                                        <span>⏱️ {{ $schedule->getFormattedDuration() }}</span>
                                                        <span>✓ {{ $schedule->getRemainingSeats() }} seats</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($activity->showSchedules->count() > 3)
                                                <p class="text-xs text-gray-500 text-center py-1 font-medium">
                                                    +{{ $activity->showSchedules->count() - 3 }} more schedule(s)
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-500 italic">No schedules available</p>
                                    @endif
                                </div>

                                {{-- Book Activity Button --}}
                                <button
                                    wire:click="bookActivity"
                                    class="w-full mt-auto px-6 py-3 rounded-xl font-bold transition-all transform shadow-lg bg-gradient-to-r from-brand-secondary to-pink-600 text-white hover:from-pink-600 hover:to-brand-secondary hover:scale-105">
                                    @guest
                                        🔑 Log In to Book
                                    @else
                                        🎟️ Book Activity
                                    @endguest
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
        </div>
    </section>
</div>
