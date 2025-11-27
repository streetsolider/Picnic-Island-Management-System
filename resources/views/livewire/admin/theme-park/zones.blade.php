<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Theme Park Management
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Create Zone Modal -->
        @if($showCreateModal)
            <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);" wire:click="closeModals"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[600px] max-w-[700px] max-h-[90vh] overflow-y-auto" style="z-index: 100000;" @click.stop>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Create New Theme Park Zone</h3>
                <form wire:submit="createZone">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zone Name</label>
                        <input wire:model="name" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g., Adventure Zone 1, Water Park North">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zone Type</label>
                        <select wire:model="zone_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">Select Zone Type</option>
                            @foreach($zoneTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('zone_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Optional description for this zone..."></textarea>
                        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Theme Park Staff (Optional)</label>
                        <select wire:model="assigned_staff_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">None - Assign later</option>
                            @foreach($themeParkStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Capacity and operating hours will be managed by the assigned staff member</p>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <x-admin.button.secondary wire:click="closeModals">Cancel</x-admin.button.secondary>
                        <x-admin.button.primary type="submit">Create Zone</x-admin.button.primary>
                    </div>
                </form>
            </div>
        @endif

        <!-- Edit Zone Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);" wire:click="closeModals"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[600px] max-w-[700px] max-h-[90vh] overflow-y-auto" style="z-index: 100000;" @click.stop>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Edit Theme Park Zone</h3>
                <form wire:submit="updateZone">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zone Name</label>
                        <input wire:model="name" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g., Adventure Zone 1, Water Park North">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zone Type</label>
                        <select wire:model="zone_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">Select Zone Type</option>
                            @foreach($zoneTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('zone_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Optional description for this zone..."></textarea>
                        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Theme Park Staff (Optional)</label>
                        <select wire:model="assigned_staff_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">None - Assign later</option>
                            @foreach($themeParkStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Capacity and operating hours will be managed by the assigned staff member</p>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-between items-center gap-3 mt-6">
                        <div class="flex gap-3">
                            <x-admin.button.danger wire:click="openDeleteModal({{ $this->zoneId }})">Delete</x-admin.button.danger>
                        </div>
                        <div class="flex gap-3">
                            <x-admin.button.secondary wire:click="closeModals">Cancel</x-admin.button.secondary>
                            <x-admin.button.primary type="submit">Update Zone</x-admin.button.primary>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
            <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);" wire:click="closeModals"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[400px] max-w-[500px]" style="z-index: 100000;" @click.stop>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Delete Zone</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this theme park zone? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <x-admin.button.secondary wire:click="closeModals">Cancel</x-admin.button.secondary>
                    <x-admin.button.danger wire:click="deleteZone">Delete</x-admin.button.danger>
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <x-admin.alert.success class="mb-4">
                {{ session('message') }}
            </x-admin.alert.success>
        @endif

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <input wire:model.live="search" type="text" placeholder="Search by zone name or type..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div class="w-full md:w-48">
                        <select wire:model.live="statusFilter" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <x-admin.button.primary wire:click="openCreateModal" class="w-full md:w-auto font-semibold">Create Zone</x-admin.button.primary>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zones Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zone Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assigned Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($zones as $zone)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $zone->name }}</div>
                                    @if($zone->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($zone->description, 40) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $zone->zone_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($zone->assignedStaff)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $zone->assignedStaff->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $zone->assignedStaff->email }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500 italic">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $zone->id }})" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $zone->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="openEditModal({{ $zone->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No theme park zones found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $zones->links() }}
            </div>
        </div>
    </div>
</div>
