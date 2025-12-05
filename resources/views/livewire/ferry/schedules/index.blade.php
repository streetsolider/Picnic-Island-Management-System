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

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ferry Schedules</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage schedules for {{ $selectedVessel->name }} ({{ $selectedVessel->registration_number }})
            </p>
        </div>
        <x-admin.button.primary wire:click="openForm">
            Add Schedule
        </x-admin.button.primary>
    </div>

    {{-- Schedules Table --}}
    @if($schedules->isEmpty())
        <x-admin.card.empty-state
            icon="📅"
            title="No schedules yet"
            description="Create your first ferry schedule to get started.">
            <x-slot name="action">
                <x-admin.button.primary wire:click="openForm">
                    Add Schedule
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <x-admin.card.base>
            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Route</x-admin.table.header>
                        <x-admin.table.header>Departure</x-admin.table.header>
                        <x-admin.table.header>Arrival</x-admin.table.header>
                        <x-admin.table.header>Days of Week</x-admin.table.header>
                        <x-admin.table.header>Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $schedule->route->name }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $schedule->departure_time->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $schedule->arrival_time->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($schedule->days_of_week as $day)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                            {{ substr($day, 0, 3) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <x-admin.button.secondary size="sm" wire:click="edit({{ $schedule->id }})">
                                        Edit
                                    </x-admin.button.secondary>
                                    <x-admin.button.danger size="sm" wire:click="deleteSchedule({{ $schedule->id }})" wire:confirm="Are you sure you want to delete this schedule?">
                                        Delete
                                    </x-admin.button.danger>
                                </div>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </x-admin.card.base>
    @endif

    {{-- Create/Edit Modal --}}
    <x-overlays.modal name="schedule-form" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $editingScheduleId ? 'Edit Schedule' : 'Create Schedule' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
            {{-- Route Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ferry Route <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="ferry_route_id"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Select a route</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}">{{ $route->name }} ({{ $route->origin }} → {{ $route->destination }})</option>
                    @endforeach
                </select>
                @error('ferry_route_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Departure Time --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Departure Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    wire:model="departure_time"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('departure_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Arrival Time --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Arrival Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    wire:model="arrival_time"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('arrival_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Days of Week --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Days of Week <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($availableDays as $day)
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                wire:model="days_of_week"
                                value="{{ $day }}"
                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
                @error('days_of_week') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button.secondary
                    type="button"
                    x-on:click="$dispatch('close-modal', 'schedule-form')"
                    size="md">
                    Cancel
                </x-admin.button.secondary>

                <x-admin.button.primary
                    type="submit"
                    size="md"
                    wire:loading.attr="disabled"
                    wire:target="save">
                    Save
                </x-admin.button.primary>
            </div>
        </form>
        </div>
    </x-overlays.modal>
</div>
