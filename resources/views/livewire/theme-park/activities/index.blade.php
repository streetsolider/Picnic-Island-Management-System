<div>
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Activities</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @if($zone)
                    {{ $zone->name }} zone
                @endif
            </p>
        </div>
        @if($zone)
            <x-admin.button.primary wire:click="openForm">
                Add Activity
            </x-admin.button.primary>
        @endif
    </div>

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

    @if(!$zone)
        {{-- No Zone Assigned --}}
        <x-admin.card.empty-state
            icon="⚠️"
            title="No Zone Assigned"
            description="You don't have any zone assigned to you yet. Please contact an administrator.">
        </x-admin.card.empty-state>
    @else
        {{-- Activities Table --}}
        @if($activities->isEmpty())
            <x-admin.card.empty-state
                icon="🎢"
                title="No Activities Yet"
                description="Start by creating your first activity for visitors to enjoy.">
                <x-slot name="action">
                    <x-admin.button.primary wire:click="openForm">
                        Add Activity
                    </x-admin.button.primary>
                </x-slot>
            </x-admin.card.empty-state>
        @else
            <x-admin.card.base>
                <x-admin.table.wrapper hoverable>
                    <thead>
                        <tr>
                            <x-admin.table.header>Activity Name</x-admin.table.header>
                            <x-admin.table.header>Ticket Cost</x-admin.table.header>
                            <x-admin.table.header>Capacity</x-admin.table.header>
                            <x-admin.table.header>Duration</x-admin.table.header>
                            <x-admin.table.header>Status</x-admin.table.header>
                            <x-admin.table.header>Actions</x-admin.table.header>
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
                                <td class="px-6 py-4">{{ $activity->ticket_cost }} tickets</td>
                                <td class="px-6 py-4">{{ $activity->capacity_per_session }} per session</td>
                                <td class="px-6 py-4">{{ $activity->duration_minutes }} min</td>
                                <td class="px-6 py-4">
                                    <x-admin.badge.status :status="$activity->is_active ? 'active' : 'inactive'">
                                        {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge.status>
                                </td>
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
    @endif

    {{-- Activity Form Modal --}}
    <x-admin.modal.form
        name="activity-form"
        :show="$showForm"
        :title="$editMode ? 'Edit Activity' : 'Add Activity'"
        submitText="{{ $editMode ? 'Update' : 'Create' }}"
        wire:submit="save"
        :loading="'save'"
        maxWidth="2xl">

        <div class="grid gap-4">
            {{-- Activity Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Activity Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="e.g., Roller Coaster Ride"
                />
                @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="Describe the activity..."></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Ticket Cost & Capacity --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="ticket_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ticket Cost <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="ticket_cost"
                        wire:model="ticket_cost"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                    @error('ticket_cost') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="capacity_per_session" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Capacity per Session <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="capacity_per_session"
                        wire:model="capacity_per_session"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                    @error('capacity_per_session') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Duration --}}
            <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Duration (minutes) <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="duration_minutes"
                    wire:model="duration_minutes"
                    min="5"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
                @error('duration_minutes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Age Requirements --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="min_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Minimum Age
                    </label>
                    <input
                        type="number"
                        id="min_age"
                        wire:model="min_age"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Optional"
                    />
                    @error('min_age') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="max_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Maximum Age
                    </label>
                    <input
                        type="number"
                        id="max_age"
                        wire:model="max_age"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Optional"
                    />
                    @error('max_age') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Height Requirement --}}
            <div>
                <label for="height_requirement_cm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Height Requirement (cm)
                </label>
                <input
                    type="number"
                    id="height_requirement_cm"
                    wire:model="height_requirement_cm"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="Optional"
                />
                @error('height_requirement_cm') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>
    </x-admin.modal.form>
</div>
