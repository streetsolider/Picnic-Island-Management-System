<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Passenger List</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                View and filter passengers by vessel, schedule, and date
            </p>
        </div>
    </div>

    <x-admin.card.base>
        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Vessel Selection --}}
            @if($vessels->count() > 1)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Ferry Vessel
                    </label>
                    <select
                        wire:model.live="selectedVesselId"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all">All Vessels</option>
                        @foreach($vessels as $vessel)
                            <option value="{{ $vessel->id }}">
                                {{ $vessel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                {{-- Show vessel info when only one vessel --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Ferry Vessel
                    </label>
                    <div class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
                        {{ $selectedVessel->name }}
                    </div>
                </div>
            @endif

            {{-- Date Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Travel Date
                </label>
                <input
                    type="date"
                    wire:model.live="selectedDate"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            {{-- Schedule Selection (Filter) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Filter by Schedule <span class="text-gray-400 font-normal">(Optional)</span>
                </label>
                <select
                    wire:model.live="selectedSchedule"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">All Schedules</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            @if($selectedVesselId === 'all')
                                {{ $schedule->vessel->name }} -
                            @endif
                            {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                            ({{ $schedule->departure_time->format('H:i') }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Loading Indicator --}}
        <div wire:loading wire:target="selectedVesselId, selectedDate, selectedSchedule" class="mb-4">
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 flex items-center gap-3">
                <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Loading passengers...</span>
            </div>
        </div>

        {{-- Passenger List Table --}}
        @if($this->filteredPassengers()->isNotEmpty())
            {{-- Summary Stats --}}
            <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Tickets</div>
                    <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $this->filteredPassengers()->count() }}</div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Total Passengers</div>
                    <div class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                        {{ $this->filteredPassengers()->sum('number_of_passengers') }}
                    </div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-green-600 dark:text-green-400">Validated (Showed Up)</div>
                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                        {{ $this->filteredPassengers()->where('status', 'used')->sum('number_of_passengers') }}
                    </div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Unvalidated (Pending)</div>
                    <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                        {{ $this->filteredPassengers()->where('status', 'confirmed')->sum('number_of_passengers') }}
                    </div>
                </div>
            </div>

            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Ticket Reference</x-admin.table.header>
                        <x-admin.table.header>Passenger Name</x-admin.table.header>
                        <x-admin.table.header>Ferry Name</x-admin.table.header>
                        <x-admin.table.header>Schedule</x-admin.table.header>
                        <x-admin.table.header>Number of Passengers</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->filteredPassengers() as $passenger)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $passenger->status === 'used' ? 'bg-green-50/50 dark:bg-green-900/10' : 'bg-yellow-50/30 dark:bg-yellow-900/5' }}">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $passenger->ticket_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->guest->display_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->vessel->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="font-medium">{{ $passenger->schedule->route->origin }} → {{ $passenger->schedule->route->destination }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-500">{{ $passenger->schedule->departure_time->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                                {{ $passenger->number_of_passengers }}
                            </td>
                            <td class="px-6 py-4">
                                @if($passenger->status === 'used')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✓ Validated
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        ⏳ Unvalidated
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        @else
            <x-admin.card.empty-state
                icon="👥"
                title="No passengers found"
                description="No passengers have booked tickets for {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}.">
            </x-admin.card.empty-state>
        @endif
    </x-admin.card.base>
</div>
