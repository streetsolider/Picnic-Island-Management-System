<div>
    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <!-- Search -->
                <div class="flex-1">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search routes..."
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
                    <x-admin.button.primary wire:click="openCreateModal" class="w-full md:w-auto font-semibold">
                        Add New Route
                    </x-admin.button.primary>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-admin.alert.success class="mb-4">
            {{ session('message') }}
        </x-admin.alert.success>
    @endif

    <!-- Routes Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Origin</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Destination</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Duration</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Price</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($routes as $route)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $route->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->origin }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->destination }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->duration_minutes }} mins
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($route->base_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $route->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $route->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $route->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openEditModal({{ $route->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                <button wire:click="openDeleteModal({{ $route->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No routes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $routes->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);"
            wire:click="closeModals"></div>

        <!-- Modal Content -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[600px] max-w-[700px]"
            style="z-index: 100000;" @click.stop>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $showEditModal ? 'Edit Route' : 'Add New Route' }}
            </h3>

            <form wire:submit="{{ $showEditModal ? 'updateRoute' : 'createRoute' }}">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Route
                            Name</label>
                        <input type="text" wire:model="name" id="name"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Origin & Destination -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="origin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Origin</label>
                            <input type="text" wire:model="origin" id="origin"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('origin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="destination"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destination</label>
                            <input type="text" wire:model="destination" id="destination"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('destination') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Duration & Price -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="duration_minutes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration
                                (mins)</label>
                            <input type="number" wire:model="duration_minutes" id="duration_minutes"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="base_price"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Price
                                ($)</label>
                            <input type="number" step="0.01" wire:model="base_price" id="base_price"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('base_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_active" id="is_active"
                                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <x-admin.button.secondary wire:click="closeModals">
                        Cancel
                    </x-admin.button.secondary>
                    <x-admin.button.primary type="submit">
                        {{ $showEditModal ? 'Update' : 'Create' }}
                    </x-admin.button.primary>
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
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Delete Route</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete this route? This action cannot be undone.
            </p>

            <div class="flex justify-end gap-3">
                <x-admin.button.secondary wire:click="closeModals">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger wire:click="deleteRoute">
                    Delete
                </x-admin.button.danger>
            </div>
        </div>
    @endif
</div>