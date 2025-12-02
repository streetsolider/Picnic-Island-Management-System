<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Passenger List</h2>

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
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Total Passengers: {{ count($passengers) }}
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
                        <x-admin.table.row>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $passenger->ticket_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $passenger->guest->email }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                                {{ $passenger->number_of_passengers }}
                            </td>
                            <td class="px-6 py-4">
                                <x-admin.badge.status :status="$passenger->status">
                                    {{ ucfirst($passenger->status) }}
                                </x-admin.badge.status>
                            </td>
                        </x-admin.table.row>
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
