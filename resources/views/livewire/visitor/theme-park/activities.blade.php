<div>
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Theme Park Activities</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Browse and redeem tickets for activities
            </p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600 dark:text-gray-400">Your Ticket Balance</p>
            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $wallet->ticket_balance }} tickets</p>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <x-admin.alert.success class="mb-4" dismissible>
            {!! session('success') !!}
        </x-admin.alert.success>
    @endif

    @if (session('error'))
        <x-admin.alert.danger class="mb-4" dismissible>
            {{ session('error') }}
        </x-admin.alert.danger>
    @endif

    {{-- Zone Filter --}}
    @if($zones->isNotEmpty())
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Filter by Zone
            </label>
            <div class="flex flex-wrap gap-2">
                <button
                    wire:click="$set('selectedZone', null)"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $selectedZone === null ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    All Zones
                </button>
                @foreach($zones as $zone)
                    <button
                        wire:click="$set('selectedZone', {{ $zone->id }})"
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $selectedZone == $zone->id ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $zone->name }} ({{ $zone->activities->where('is_active', true)->count() }})
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Activities Grid --}}
    @if($activities->isEmpty())
        <x-admin.card.empty-state
            icon="🎢"
            title="No Activities Available"
            description="There are currently no active activities in the selected zone. Check back later!">
        </x-admin.card.empty-state>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-6">
            @foreach($activities as $activity)
                <x-admin.card.base>
                    <div class="h-full flex flex-col">
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $activity->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ $activity->ticket_cost }} tickets
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ $activity->zone->name }} Zone
                            </p>

                            @if($activity->description)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                    {{ Str::limit($activity->description, 100) }}
                                </p>
                            @endif

                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Duration: {{ $activity->duration_minutes }} minutes
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Capacity: {{ $activity->capacity_per_session }} per session
                                </div>
                                @if($activity->min_age || $activity->max_age)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Age: {{ $activity->min_age ?? 'Any' }} - {{ $activity->max_age ?? 'Any' }} years
                                    </div>
                                @endif
                                @if($activity->height_requirement_cm)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                        Min height: {{ $activity->height_requirement_cm }} cm
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <x-admin.button.primary
                                wire:click="selectActivity({{ $activity->id }})"
                                class="w-full"
                                :disabled="$wallet->ticket_balance < $activity->ticket_cost">
                                @if($wallet->ticket_balance < $activity->ticket_cost)
                                    Insufficient Tickets
                                @else
                                    Redeem Tickets
                                @endif
                            </x-admin.button.primary>
                        </div>
                    </div>
                </x-admin.card.base>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
            <div class="mb-6">
                {{ $activities->links() }}
            </div>
        @endif
    @endif

    {{-- Redeem Confirmation Modal --}}
    @if($selectedActivity)
        <x-admin.modal.form
            name="redeem-modal"
            :show="true"
            title="Confirm Ticket Redemption"
            submitText="Redeem Tickets"
            wire:submit="redeemTickets"
            :loading="'redeemTickets'">

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Activity</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Zone</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->zone->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ticket Cost</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->ticket_cost }} ticket(s)</p>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $wallet->ticket_balance }} tickets</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Balance After:</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $wallet->ticket_balance - $selectedActivity->ticket_cost }} tickets</span>
                    </div>
                </div>

                <x-admin.alert.info>
                    After redeeming, you'll receive a redemption code. Show this code to the staff at the activity entrance for validation.
                </x-admin.alert.info>
            </div>
        </x-admin.modal.form>
    @endif

    {{-- Low Balance Warning --}}
    @if($wallet->ticket_balance < 5)
        <x-admin.alert.warning class="mt-6">
            <div class="flex items-center justify-between">
                <span>Your ticket balance is low. Top up your wallet to purchase more tickets!</span>
                <x-admin.button.link href="{{ route('visitor.theme-park.wallet') }}" wire:navigate>
                    Go to Wallet
                </x-admin.button.link>
            </div>
        </x-admin.alert.warning>
    @endif
</div>
