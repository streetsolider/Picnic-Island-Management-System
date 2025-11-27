<div>
    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <!-- Search -->
                <div class="flex-1">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search schedules..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                </div>

                <!-- Create Button -->
                <div>
                    <x-admin.button.primary wire:click="openCreateModal" class="w-full md:w-auto font-semibold">
                        Add New Schedule
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

    <!-- Schedules Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Route</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Vessel</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Departure</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Arrival</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Days</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $schedule->route->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->route->origin }} ->
                                    {{ $schedule->route->destination }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $schedule->vessel->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($schedule->days_of_week as $day)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ substr($day, 0, 3) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openEditModal({{ $schedule->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                <button wire:click="openDeleteModal({{ $schedule->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No schedules found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $schedules->links() }}
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
                {{ $showEditModal ? 'Edit Schedule' : 'Add New Schedule' }}
            </h3>

            <form wire:submit="{{ $showEditModal ? 'updateSchedule' : 'createSchedule' }}">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Route & Vessel -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ferry_route_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Route</label>
                            <select wire:model="ferry_route_id" id="ferry_route_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }} ({{ $route->origin }} ->
                                        {{ $route->destination }})</option>
                                @endforeach
                            </select>
                            @error('ferry_route_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="ferry_vessel_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vessel</label>
                            <select wire:model="ferry_vessel_id" id="ferry_vessel_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="">Select Vessel</option>
                                @foreach($vessels as $vessel)
                                    <option value="{{ $vessel->id }}">{{ $vessel->name }}</option>
                                @endforeach
                            </select>
                            @error('ferry_vessel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Times -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="departure_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departure
                                Time</label>
                            <input type="time" wire:model="departure_time" id="departure_time"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('departure_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="arrival_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arrival Time</label>
                            <input type="time" wire:model="arrival_time" id="arrival_time"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            @error('arrival_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Days of Week -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Days of
                            Operation</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="days_of_week" value="{{ $day }}"
                                        class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ substr($day, 0, 3) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('days_of_week') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
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
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Delete Schedule</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete this schedule? This action cannot be undone.
            </p>

            <div class="flex justify-end gap-3">
                <x-admin.button.secondary wire:click="closeModals">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger wire:click="deleteSchedule">
                    Delete
                </x-admin.button.danger>
            </div>
        </div>
    @endif
</div>