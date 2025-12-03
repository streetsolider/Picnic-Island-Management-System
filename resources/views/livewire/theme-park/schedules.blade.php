<div>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Activity Schedules
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage operating hours and schedules for your assigned activities
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <x-admin.alert.success class="mb-4" dismissible>
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    @if (session('error'))
        <x-admin.alert.danger class="mb-4" dismissible>
            {{ session('error') }}
        </x-admin.alert.danger>
    @endif

    {{-- Check if staff has assigned activities --}}
    @if($myActivities->isEmpty())
        <x-admin.card.empty-state
            icon="🎢"
            title="No Activities Assigned"
            description="You don't have any activities assigned to you yet. Contact your manager to get activities assigned.">
        </x-admin.card.empty-state>
    @else
        {{-- Filter and Actions --}}
        <x-admin.card.base class="mb-6">
            <div class="p-4 flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Activity
                    </label>
                    <select wire:model.live="selectedActivity" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">All My Activities</option>
                        @foreach($myActivities as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }} ({{ $activity->zone->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-admin.button.primary wire:click="openForm">
                        Create Schedule
                    </x-admin.button.primary>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Schedules Table --}}
        @if($schedules->isEmpty())
            <x-admin.card.empty-state
                icon="📅"
                title="No Schedules Yet"
                description="Create your first schedule to set operating hours for your activities.">
                <x-slot name="action">
                    <x-admin.button.primary wire:click="openForm">
                        Create Schedule
                    </x-admin.button.primary>
                </x-slot>
            </x-admin.card.empty-state>
        @else
            <x-admin.card.base>
                <x-admin.table.wrapper hoverable>
                    <thead>
                        <tr>
                            <x-admin.table.header>Activity</x-admin.table.header>
                            <x-admin.table.header>Date</x-admin.table.header>
                            <x-admin.table.header>Time</x-admin.table.header>
                            <x-admin.table.header>Available Slots</x-admin.table.header>
                            <x-admin.table.header>Booked Slots</x-admin.table.header>
                            <x-admin.table.header>Remaining</x-admin.table.header>
                            <x-admin.table.header>Actions</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <x-admin.table.row>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $schedule->activity->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->activity->zone->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $schedule->schedule_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $schedule->schedule_date->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $schedule->available_slots }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($schedule->booked_slots > 0)
                                        <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $schedule->booked_slots }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $remaining = $schedule->getRemainingSlots();
                                        $percentage = ($remaining / $schedule->available_slots) * 100;
                                    @endphp
                                    <span class="font-medium {{ $percentage > 50 ? 'text-green-600 dark:text-green-400' : ($percentage > 20 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $remaining }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <x-admin.button.secondary size="sm" wire:click="edit({{ $schedule->id }})">
                                            Edit
                                        </x-admin.button.secondary>
                                        @if($schedule->booked_slots == 0)
                                            <x-admin.button.danger size="sm" wire:click="confirmDelete({{ $schedule->id }})">
                                                Delete
                                            </x-admin.button.danger>
                                        @endif
                                    </div>
                                </td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>

                {{-- Pagination --}}
                @if($schedules->hasPages())
                    <div class="mt-4 px-6 pb-4">
                        {{ $schedules->links() }}
                    </div>
                @endif
            </x-admin.card.base>
        @endif

        {{-- Schedule Form Modal --}}
        <x-overlays.modal name="schedule-form" maxWidth="2xl" focusable>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $editMode ? 'Edit Schedule' : 'Create Schedule' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    {{-- Activity Selection --}}
                    <div>
                        <label for="activity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Activity <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="activity_id" id="activity_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Select an activity</option>
                            @foreach($myActivities as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }} ({{ $activity->zone->name }})</option>
                            @endforeach
                        </select>
                        @error('activity_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Schedule Date --}}
                    <div>
                        <label for="schedule_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="schedule_date" wire:model="schedule_date" min="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        @error('schedule_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Time Range --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="start_time" wire:model="start_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            @error('start_time') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="end_time" wire:model="end_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            @error('end_time') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Available Slots --}}
                    <div>
                        <label for="available_slots" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Available Slots <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="available_slots" wire:model="available_slots" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum number of visitors for this schedule</p>
                        @error('available_slots') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" x-on:click="$dispatch('close-modal', 'schedule-form')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-overlays.modal>
    @endif
</div>
