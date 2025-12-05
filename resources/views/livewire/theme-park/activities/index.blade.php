<div>
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Activities</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @if($isManager)
                    Create and manage activities across all zones
                @else
                    Manage your assigned activities
                @endif
            </p>
        </div>
        @if($isManager)
            <x-admin.button.primary wire:click="openForm">
                Add Activity
            </x-admin.button.primary>
        @endif
    </div>

    {{-- Zone Filter (Manager Only) --}}
    @if($isManager)
        <x-admin.card.base class="mb-6">
            <div class="p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Zone</label>
                <select wire:model.live="selectedZoneFilter" class="w-full md:w-1/3 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">All Zones</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->zone_type }})</option>
                    @endforeach
                </select>
            </div>
        </x-admin.card.base>
    @endif

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

    {{-- Activities Table --}}
    @if($activities->isEmpty())
        <x-admin.card.empty-state
            icon="🎢"
            title="No Activities Assigned"
            description="{{ $isManager ? 'Start by creating your first activity for visitors to enjoy.' : 'You don\'t have any activities assigned to you yet. Contact your manager.' }}">
            @if($isManager)
                <x-slot name="action">
                    <x-admin.button.primary wire:click="openForm">
                        Add Activity
                    </x-admin.button.primary>
                </x-slot>
            @endif
        </x-admin.card.empty-state>
    @else
        <x-admin.card.base>
            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Activity Details</x-admin.table.header>
                        @if($isManager)
                            <x-admin.table.header>Zone</x-admin.table.header>
                        @endif
                        <x-admin.table.header class="text-center">Info</x-admin.table.header>
                        <x-admin.table.header>Assigned Staff</x-admin.table.header>
                        <x-admin.table.header class="text-center">Status</x-admin.table.header>
                        @if($isManager)
                            <x-admin.table.header class="text-right">Actions</x-admin.table.header>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <x-admin.table.row>
                            {{-- Activity Details --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $activity->name }}</div>
                                @if($activity->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($activity->description, 60) }}</div>
                                @endif
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded {{ $activity->activity_type === 'continuous' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-200' }}">
                                        {{ $activity->activity_type === 'continuous' ? '🎢 Continuous' : '🎭 Scheduled' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Zone (Manager Only) --}}
                            @if($isManager)
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $activity->zone->name }}
                                    </span>
                                </td>
                            @endif

                            {{-- Info: Credit Cost & Duration --}}
                            <td class="px-6 py-4">
                                <div class="text-sm space-y-1">
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold mr-1">💳</span>
                                        <span>{{ $activity->credit_cost }} credits</span>
                                    </div>
                                    @if($activity->duration_minutes)
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <span class="font-semibold mr-1">⏱️</span>
                                            <span>{{ $activity->duration_minutes }} min</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Assigned Staff --}}
                            <td class="px-6 py-4">
                                @if($activity->assignedStaff)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-semibold text-sm">
                                            {{ strtoupper(substr($activity->assignedStaff->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->assignedStaff->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->assignedStaff->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Not assigned</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                <x-admin.badge.status :status="$activity->is_active ? 'active' : 'inactive'">
                                    {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge.status>
                            </td>

                            {{-- Actions (Manager Only) --}}
                            @if($isManager)
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="edit({{ $activity->id }})" class="p-2 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="openStaffAssignment({{ $activity->id }})" class="p-2 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors" title="Assign Staff">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="toggleActive({{ $activity->id }})" class="p-2 text-gray-600 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400 transition-colors" title="{{ $activity->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($activity->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                        <button wire:click="confirmDelete({{ $activity->id }})" class="p-2 text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>

            {{-- Pagination --}}
            @if($activities->hasPages())
                <div class="mt-4">
                    {{ $activities->links() }}
                </div>
            @endif
        </x-admin.card.base>
    @endif

    {{-- Activity Form Modal --}}
    <x-overlays.modal name="activity-form" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $editMode ? 'Edit Activity' : 'Create Activity' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-4">
                {{-- Zone Selection (Manager Only) --}}
                @if($isManager)
                    <div>
                        <label for="theme_park_zone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Zone <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="theme_park_zone_id" id="theme_park_zone_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Select a zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->zone_type }})</option>
                            @endforeach
                        </select>
                        @error('theme_park_zone_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Activity Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Activity Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" wire:model="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="e.g., Roller Coaster Ride" />
                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea id="description" wire:model="description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Describe the activity..."></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Activity Type --}}
                <div>
                    <label for="activity_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Activity Type <span class="text-red-500">*</span>
                    </label>
                    <select id="activity_type" wire:model="activity_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="continuous">Continuous Ride (Walk-up access)</option>
                        <option value="scheduled">Scheduled Show (Pre-booking required)</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Continuous rides allow walk-up access, scheduled shows require advance booking</p>
                    @error('activity_type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Credit Cost & Capacity --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="credit_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Credit Cost <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="credit_cost" wire:model="credit_cost" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Credits required per person</p>
                        @error('credit_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Capacity
                        </label>
                        <input type="number" id="capacity" wire:model="capacity" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Venue capacity (required for shows)</p>
                        @error('capacity') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Duration (for scheduled shows) or Duration & Operating Hours (for continuous rides) --}}
                @if($activity_type === 'scheduled')
                    {{-- Scheduled shows: Only show duration --}}
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Show Duration (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="duration_minutes" wire:model="duration_minutes" min="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="e.g., 45" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            How long does the show last?
                        </p>
                        @error('duration_minutes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- Continuous rides: Show duration and operating hours --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ride Duration (minutes)
                            </label>
                            <input type="number" id="duration_minutes" wire:model="duration_minutes" min="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Average ride duration
                            </p>
                            @error('duration_minutes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="operating_hours_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Opens At
                            </label>
                            <input type="time" id="operating_hours_start" wire:model="operating_hours_start" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Leave empty to use zone hours
                            </p>
                            @error('operating_hours_start') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="operating_hours_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Closes At
                            </label>
                            <input type="time" id="operating_hours_end" wire:model="operating_hours_end" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Leave empty to use zone hours
                            </p>
                            @error('operating_hours_end') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Age Requirements --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="min_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Age
                        </label>
                        <input type="number" id="min_age" wire:model="min_age" min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional" />
                        @error('min_age') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="max_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Maximum Age
                        </label>
                        <input type="number" id="max_age" wire:model="max_age" min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional" />
                        @error('max_age') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Height Requirement --}}
                <div>
                    <label for="height_requirement_cm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Height Requirement (cm)
                    </label>
                    <input type="number" id="height_requirement_cm" wire:model="height_requirement_cm" min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional" />
                    @error('height_requirement_cm') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" x-on:click="$dispatch('close-modal', 'activity-form')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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

    {{-- Staff Assignment Modal --}}
    @if($showStaffAssignmentModal && $activityToAssign)
        <x-overlays.modal name="staff-assignment-modal" maxWidth="md" focusable show>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Assign Staff to Activity
                </h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>Activity:</strong> {{ $activityToAssign->name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Zone:</strong> {{ $activityToAssign->zone->name }}
                    </p>
                </div>

                <form wire:submit.prevent="assignStaff" class="space-y-4">
                    <div>
                        <label for="selectedStaffId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Staff Member
                        </label>
                        <select wire:model="selectedStaffId" id="selectedStaffId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Unassign Staff --</option>
                            @foreach($themeParkStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select a staff member or leave blank to unassign</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeStaffAssignmentModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="assignStaff" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="assignStaff">Assign</span>
                            <span wire:loading wire:target="assignStaff">Assigning...</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-overlays.modal>
    @endif
</div>
