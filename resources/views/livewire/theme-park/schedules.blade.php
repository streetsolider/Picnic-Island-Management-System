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
        {{-- Tab Switcher --}}
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button
                        wire:click="switchView('shows')"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $viewMode === 'shows' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        📅 Scheduled Shows
                    </button>
                    <button
                        wire:click="switchView('hours')"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $viewMode === 'hours' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        🕒 Operating Hours
                    </button>
                </nav>
            </div>
        </div>

        {{-- Scheduled Shows Section --}}
        @if($viewMode === 'shows')
            {{-- Filter and Actions --}}
            <x-admin.card.base class="mb-6">
                <div class="p-4 flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter by Activity
                        </label>
                        <select wire:model.live="selectedActivity" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">All My Scheduled Shows</option>
                            @foreach($activities as $activity)
                                @if($activity->activity_type === 'scheduled')
                                    <option value="{{ $activity->id }}">{{ $activity->name }} ({{ $activity->zone->name }})</option>
                                @endif
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
                    description="Create your first show schedule for your scheduled activities.">
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
                                <x-admin.table.header>Show Time</x-admin.table.header>
                                <x-admin.table.header>Capacity</x-admin.table.header>
                                <x-admin.table.header>Tickets Sold</x-admin.table.header>
                                <x-admin.table.header>Status</x-admin.table.header>
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
                                            {{ $schedule->show_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $schedule->show_date->format('l') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $schedule->venue_capacity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($schedule->tickets_sold > 0)
                                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $schedule->tickets_sold }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-admin.badge.status :status="$schedule->status">
                                            {{ ucfirst($schedule->status) }}
                                        </x-admin.badge.status>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            @if($schedule->status === 'scheduled')
                                                <x-admin.button.secondary size="sm" wire:click="edit({{ $schedule->id }})">
                                                    Edit
                                                </x-admin.button.secondary>
                                                @if($schedule->tickets_sold == 0)
                                                    <x-admin.button.danger size="sm" wire:click="confirmDelete({{ $schedule->id }})">
                                                        Delete
                                                    </x-admin.button.danger>
                                                @else
                                                    <x-admin.button.warning size="sm" wire:click="confirmCancel({{ $schedule->id }})">
                                                        Cancel
                                                    </x-admin.button.warning>
                                                @endif
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
        @endif

        {{-- Operating Hours Section --}}
        @if($viewMode === 'hours')
            @if($continuousActivities->isEmpty())
                <x-admin.card.empty-state
                    icon="🕒"
                    title="No Continuous Rides"
                    description="You don't have any continuous ride activities assigned yet.">
                </x-admin.card.empty-state>
            @else
                <x-admin.card.base>
                    <x-admin.table.wrapper hoverable>
                        <thead>
                            <tr>
                                <x-admin.table.header>Activity</x-admin.table.header>
                                <x-admin.table.header>Zone</x-admin.table.header>
                                <x-admin.table.header>Operating Hours</x-admin.table.header>
                                <x-admin.table.header>Actions</x-admin.table.header>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($continuousActivities as $activity)
                                <x-admin.table.row>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $activity->name }}</div>
                                        @if($activity->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($activity->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $activity->zone->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($activity->operating_hours_start && $activity->operating_hours_end)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($activity->operating_hours_start)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($activity->operating_hours_end)->format('g:i A') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Custom hours set</div>
                                        @else
                                            <div class="text-sm text-gray-500 dark:text-gray-400 italic">
                                                Using zone hours
                                            </div>
                                            @if($activity->zone->operating_hours_start && $activity->zone->operating_hours_end)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    ({{ \Carbon\Carbon::parse($activity->zone->operating_hours_start)->format('g:i A') }} -
                                                    {{ \Carbon\Carbon::parse($activity->zone->operating_hours_end)->format('g:i A') }})
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <x-admin.button.secondary size="sm" wire:click="editHours({{ $activity->id }})">
                                                Set Hours
                                            </x-admin.button.secondary>
                                            @if($activity->operating_hours_start && $activity->operating_hours_end)
                                                <x-admin.button.danger size="sm" wire:click="clearHours({{ $activity->id }})">
                                                    Clear
                                                </x-admin.button.danger>
                                            @endif
                                        </div>
                                    </td>
                                </x-admin.table.row>
                            @endforeach
                        </tbody>
                    </x-admin.table.wrapper>
                </x-admin.card.base>
            @endif
        @endif

        {{-- Schedule Form Modal --}}
        <x-overlays.modal name="schedule-form" maxWidth="2xl" focusable>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $editMode ? 'Edit Show Schedule' : 'Create Show Schedule' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    {{-- Activity Selection --}}
                    <div>
                        <label for="activity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Scheduled Show <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="activity_id" id="activity_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Select a scheduled show</option>
                            @foreach($activities as $activity)
                                @if($activity->activity_type === 'scheduled')
                                    <option value="{{ $activity->id }}">{{ $activity->name }} ({{ $activity->zone->name }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('activity_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Show Date --}}
                    <div>
                        <label for="show_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Show Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="show_date" wire:model="show_date" min="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        @error('show_date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Show Time --}}
                    <div>
                        <label for="show_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Show Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="show_time" wire:model="show_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        @error('show_time') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Venue Capacity --}}
                    <div>
                        <label for="venue_capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Venue Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="venue_capacity" wire:model="venue_capacity" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum number of visitors for this show</p>
                        @error('venue_capacity') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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

        {{-- Operating Hours Form Modal --}}
        <x-overlays.modal name="hours-form" maxWidth="lg" focusable>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Set Operating Hours
                </h2>

                <form wire:submit.prevent="saveHours" class="space-y-4">
                    {{-- Opening Time --}}
                    <div>
                        <label for="hours_opening_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Opening Time
                        </label>
                        <input type="time" id="hours_opening_time" wire:model="hours_opening_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to use zone hours</p>
                        @error('hours_opening_time') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Closing Time --}}
                    <div>
                        <label for="hours_closing_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Closing Time
                        </label>
                        <input type="time" id="hours_closing_time" wire:model="hours_closing_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to use zone hours</p>
                        @error('hours_closing_time') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Info Note --}}
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Set specific operating hours for this activity, or leave empty to use the zone's default hours.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" x-on:click="$dispatch('close-modal', 'hours-form')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveHours" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="saveHours">Save Hours</span>
                            <span wire:loading wire:target="saveHours">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-overlays.modal>

        {{-- Delete Confirmation Modal --}}
        <div x-data="{ show: false }"
             @confirm-delete.window="show = true"
             x-show="show"
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="show = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Delete Schedule
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete this schedule? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button"
                                wire:click="delete"
                                @click="show = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button"
                                @click="show = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Confirmation Modal --}}
        <div x-data="{ show: false }"
             @confirm-cancel.window="show = true"
             x-show="show"
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="show = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Cancel Schedule
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to cancel this schedule? Guests with tickets will be notified.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button"
                                wire:click="cancelSchedule"
                                @click="show = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel Schedule
                        </button>
                        <button type="button"
                                @click="show = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
