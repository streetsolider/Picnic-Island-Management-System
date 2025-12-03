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
                        <x-admin.table.header>Activity Name</x-admin.table.header>
                        @if($isManager)
                            <x-admin.table.header>Zone</x-admin.table.header>
                            <x-admin.table.header>Assigned Staff</x-admin.table.header>
                        @endif
                        <x-admin.table.header>Ticket Cost</x-admin.table.header>
                        <x-admin.table.header>Capacity</x-admin.table.header>
                        <x-admin.table.header>Duration</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        @if($isManager)
                            <x-admin.table.header>Actions</x-admin.table.header>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $activity->name }}</div>
                                @if($activity->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($activity->description, 50) }}</div>
                                @endif
                            </td>
                            @if($isManager)
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $activity->zone->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($activity->assignedStaff)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $activity->assignedStaff->name }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500 italic">Not assigned</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4">{{ $activity->ticket_cost }} tickets</td>
                            <td class="px-6 py-4">{{ $activity->capacity_per_session }}</td>
                            <td class="px-6 py-4">{{ $activity->duration_minutes }} min</td>
                            <td class="px-6 py-4">
                                <x-admin.badge.status :status="$activity->is_active ? 'active' : 'inactive'">
                                    {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge.status>
                            </td>
                            @if($isManager)
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <x-admin.button.secondary size="sm" wire:click="edit({{ $activity->id }})">
                                            Edit
                                        </x-admin.button.secondary>
                                        <x-admin.button.warning size="sm" wire:click="toggleActive({{ $activity->id }})">
                                            {{ $activity->is_active ? 'Deactivate' : 'Activate' }}
                                        </x-admin.button.warning>
                                        <x-admin.button.danger size="sm" wire:click="confirmDelete({{ $activity->id }})">
                                            Delete
                                        </x-admin.button.danger>
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

                {{-- Assign Staff (Manager Only) --}}
                @if($isManager)
                    <div>
                        <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assign Staff (Optional)
                        </label>
                        <select wire:model="assigned_staff_id" id="assigned_staff_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">None - Assign later</option>
                            @foreach($availableStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Ticket Cost --}}
                <div>
                    <label for="ticket_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ticket Cost <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="ticket_cost" wire:model="ticket_cost" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Number of tickets required to participate in this activity</p>
                    @error('ticket_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Capacity & Duration --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="capacity_per_session" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Capacity per Session <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="capacity_per_session" wire:model="capacity_per_session" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        @error('capacity_per_session') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Duration (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="duration_minutes" wire:model="duration_minutes" min="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        @error('duration_minutes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

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
</div>
