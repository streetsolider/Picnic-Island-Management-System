<div>
    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <!-- Search -->
                <div class="flex-1">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search vessels..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-48">
                    <select wire:model.live="statusFilter"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Create Button -->
                <div>
                    <button type="button" wire:click="openCreateModal"
                        class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm">
                        Add New Vessel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Vessels Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Registration Number
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Capacity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Operator
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($vessels as $vessel)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $vessel->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vessel->registration_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vessel->vessel_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vessel->capacity }} passengers
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $vessel->operator ? $vessel->operator->name : 'Not Assigned' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $vessel->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $vessel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $vessel->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openEditModal({{ $vessel->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                    Edit
                                </button>
                                <button wire:click="openDeleteModal({{ $vessel->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No vessels found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $vessels->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);"
            wire:click="closeModals"></div>

        <!-- Modal Content -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[500px] max-w-[600px]"
            style="z-index: 100000;" @click.stop>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $showEditModal ? 'Edit Vessel' : 'Add New Vessel' }}
            </h3>

            <form wire:submit="{{ $showEditModal ? 'updateVessel' : 'createVessel' }}">
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vessel
                        Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Registration Number -->
                <div class="mb-4">
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Registration Number</label>
                    <input type="text" wire:model="registration_number" id="registration_number"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    @error('registration_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Vessel Type -->
                <div class="mb-4">
                    <label for="vessel_type"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vessel Type</label>
                    <select wire:model="vessel_type" id="vessel_type"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        <option value="Ferry">Ferry</option>
                        <option value="Speed Boat">Speed Boat</option>
                        <option value="Boat">Boat</option>
                    </select>
                    @error('vessel_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Capacity -->
                <div class="mb-4">
                    <label for="capacity"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity</label>
                    <input type="number" wire:model="capacity" id="capacity"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Ferry Operator -->
                <div class="mb-4">
                    <label for="operator_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Ferry Operator</label>
                    <select wire:model="operator_id" id="operator_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        <option value="">-- No Operator --</option>
                        @foreach($ferryOperators as $operator)
                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                        @endforeach
                    </select>
                    @error('operator_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Active Status -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active"
                            class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeModals"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        {{ $showEditModal ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);"
            wire:click="closeModals"></div>

        <!-- Modal Content -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[400px] max-w-[500px]"
            style="z-index: 100000;" @click.stop>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Delete Vessel</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete this vessel? This action cannot be undone.
            </p>

            <div class="flex justify-end gap-3">
                <button type="button" wire:click="closeModals"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded-md">
                    Cancel
                </button>
                <button type="button" wire:click="deleteVessel"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                    Delete
                </button>
            </div>
        </div>
    @endif
</div>