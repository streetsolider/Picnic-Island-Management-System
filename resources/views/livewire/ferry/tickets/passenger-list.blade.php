<div>
    {{-- Vessel Selection --}}
    @if($vessels->count() > 1)
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Vessel
            </label>
            <select
                wire:change="selectVessel($event.target.value)"
                class="w-full md:w-96 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @foreach($vessels as $vessel)
                    <option value="{{ $vessel->id }}" {{ $selectedVesselId == $vessel->id ? 'selected' : '' }}>
                        {{ $vessel->name }} - {{ $vessel->registration_number }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Passenger List</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $selectedVessel->name }} ({{ $selectedVessel->registration_number }})
            </p>
        </div>
    </div>

    <x-admin.card.base>
        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Schedule Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ferry Schedule
                </label>
                <select
                    wire:model="selectedSchedule"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Select a schedule</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                            ({{ $schedule->departure_time->format('H:i') }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Travel Date
                </label>
                <input
                    type="date"
                    wire:model="selectedDate"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            {{-- Load Button --}}
            <div class="flex items-end">
                <x-admin.button.primary wire:click="loadPassengers" class="w-full">
                    Load Passengers
                </x-admin.button.primary>
            </div>
        </div>

        {{-- Passenger List Table --}}
        @if(!empty($passengers))
            {{-- Summary Stats --}}
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Passengers</div>
                    <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ count($passengers) }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-green-600 dark:text-green-400">Validated (Showed Up)</div>
                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                        {{ $passengers->where('status', 'used')->count() }}
                    </div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Unvalidated (Pending)</div>
                    <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                        {{ $passengers->where('status', 'confirmed')->count() }}
                    </div>
                </div>
            </div>

            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Ticket Reference</x-admin.table.header>
                        <x-admin.table.header>Passenger Name</x-admin.table.header>
                        <x-admin.table.header>Email</x-admin.table.header>
                        <x-admin.table.header>Number of Passengers</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($passengers as $passenger)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $passenger->status === 'used' ? 'bg-green-50/50 dark:bg-green-900/10' : 'bg-yellow-50/30 dark:bg-yellow-900/5' }}">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $passenger->ticket_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->guest->display_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->guest->email }}
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
        @elseif($selectedSchedule && $selectedDate)
            <x-admin.card.empty-state
                icon="👥"
                title="No passengers found"
                description="No passengers have booked tickets for this schedule and date.">
            </x-admin.card.empty-state>
        @else
            <x-admin.card.empty-state
                icon="🔍"
                title="Select schedule and date"
                description="Choose a ferry schedule and travel date to view the passenger list.">
            </x-admin.card.empty-state>
        @endif
    </x-admin.card.base>
</div>
