<div>
    {{-- Activity Selection Dropdown --}}
    @if($assignedActivities->count() > 1)
        <x-admin.card.base class="mb-6" padding="p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Activity
            </label>
            <select
                wire:change="selectActivity($event.target.value)"
                class="w-full md:w-96 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @foreach($assignedActivities as $activity)
                    <option value="{{ $activity->id }}" {{ $selectedActivityId == $activity->id ? 'selected' : '' }}>
                        {{ $activity->name }} - {{ $activity->zone->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </x-admin.card.base>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Validate Activity Tickets</h2>
        @if($selectedActivity)
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Managing: <span class="font-semibold">{{ $selectedActivity->name }}</span> ({{ $selectedActivity->zone->name ?? 'N/A' }})
            </p>
        @else
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Select your activity and validate visitor tickets
            </p>
        @endif
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <x-admin.alert.success class="mb-4" dismissible>
            {!! session('success') !!}
        </x-admin.alert.success>
    @endif

    @if (session('error'))
        <x-admin.alert.danger class="mb-4" dismissible>
            {!! session('error') !!}
        </x-admin.alert.danger>
    @endif

    @if (session('info'))
        <x-admin.alert.info class="mb-4" dismissible>
            {{ session('info') }}
        </x-admin.alert.info>
    @endif

    @if(!$hasAssignedActivities)
        {{-- No Activities Assigned --}}
        <x-admin.card.empty-state
            icon="⚠️"
            title="No Activities Assigned"
            description="You don't have any activities assigned to you yet. Please contact your manager to get activities assigned.">
        </x-admin.card.empty-state>
    @elseif($selectedActivity)
        {{-- Activity Information Card --}}
        <x-admin.card.base class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Activity Name</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Zone</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->zone->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Activity Type</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $selectedActivity->activity_type === 'continuous_ride' ? 'Continuous Ride' : 'Scheduled Show' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Credit Cost</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedActivity->credit_cost }} {{ \Illuminate\Support\Str::plural('credit', $selectedActivity->credit_cost) }}</p>
                </div>
            </div>
            @if($selectedActivity->activity_type === 'continuous_ride' && $selectedActivity->opening_time && $selectedActivity->closing_time)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Operating Hours</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($selectedActivity->opening_time)->format('g:i A') }} -
                        {{ \Carbon\Carbon::parse($selectedActivity->closing_time)->format('g:i A') }}
                    </p>
                </div>
            @endif
        </x-admin.card.base>

        {{-- Ticket Validation Form --}}
        <x-admin.card.base class="mb-6">
            <form wire:submit="searchRedemption">
                <div class="flex items-end space-x-4">
                    <div class="flex-1">
                        <label for="searchCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter Ticket Reference Code
                        </label>
                        <input
                            type="text"
                            id="searchCode"
                            wire:model="searchCode"
                            placeholder="TPT-XXXXXXXX"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg font-mono"
                            autofocus
                        />
                        @error('searchCode') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex space-x-2">
                        <x-admin.button.primary type="submit">
                            Validate
                        </x-admin.button.primary>
                        @if($searchPerformed)
                            <x-admin.button.secondary type="button" wire:click="resetSearch">
                                Clear
                            </x-admin.button.secondary>
                        @endif
                    </div>
                </div>
            </form>
        </x-admin.card.base>

        {{-- Ticket Details --}}
        @if($ticket)
            <x-admin.card.base>
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <span>Ticket Details</span>
                        <x-admin.badge.status :status="$ticket->status">
                            {{ ucfirst($ticket->status) }}
                        </x-admin.badge.status>
                    </div>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Ticket Information --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Ticket Reference</p>
                            <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">{{ $ticket->ticket_reference }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Activity</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->activity->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->activity->zone->name ?? 'N/A' }} Zone</p>
                        </div>
                        @if($ticket->showSchedule)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Show Schedule</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $ticket->showSchedule->show_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($ticket->showSchedule->show_time)->format('g:i A') }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Number of Persons</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->quantity }} {{ \Illuminate\Support\Str::plural('person', $ticket->quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Credits Spent</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->credits_spent }} {{ \Illuminate\Support\Str::plural('credit', $ticket->credits_spent) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Purchased At</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->purchase_datetime->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($ticket->valid_until)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Valid Until</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->valid_until->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Visitor Information --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->guest->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor Email</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->guest->email ?? 'N/A' }}</p>
                        </div>
                        @if($ticket->status === 'redeemed')
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Validated By</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->redeemedByStaff->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Validated At</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->redeemed_at?->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        @if($ticket->status === 'cancelled' && $ticket->cancellation_reason)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Cancellation Reason</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Status Alerts --}}
                @if($ticket->status === 'redeemed')
                    <x-admin.alert.success class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>This ticket has already been validated and the visitor can participate in the activity.</span>
                        </div>
                    </x-admin.alert.success>
                @elseif($ticket->status === 'cancelled')
                    <x-admin.alert.danger class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span>This ticket has been cancelled and cannot be used.</span>
                        </div>
                    </x-admin.alert.danger>
                @elseif($ticket->status === 'expired')
                    <x-admin.alert.warning class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span>This ticket has expired and can no longer be used.</span>
                        </div>
                    </x-admin.alert.warning>
                @endif
            </x-admin.card.base>
        @elseif($searchPerformed)
            <x-admin.card.empty-state
                icon="🔍"
                title="No Ticket Found"
                description="No ticket found with this reference code. Please check and try again.">
            </x-admin.card.empty-state>
        @else
            {{-- Instructions --}}
            <x-admin.card.base>
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">How to Validate Tickets</h3>
                    <div class="max-w-md mx-auto text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p>1. Ask the visitor for their ticket reference code (format: TPT-XXXXXXXX)</p>
                        <p>2. Enter the code in the search box above</p>
                        <p>3. Click "Validate" to verify and approve entry</p>
                        <p>4. If valid, the system will mark it as redeemed and grant access</p>
                    </div>
                </div>
            </x-admin.card.base>
        @endif
    @else
        {{-- No Activity Selected --}}
        <x-admin.card.base>
            <div class="text-center py-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Select an Activity</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Please select an activity from the dropdown above to start validating tickets.
                </p>
            </div>
        </x-admin.card.base>
    @endif
</div>
