{{-- Assign Rooms to Gallery Modal --}}
<x-admin.modal.form
    name="assign-rooms-modal"
    :show="$showAssignRoomsModal"
    :title="'Assign Rooms to ' . ($this->galleries->firstWhere('id', $selectedGalleryId)?->name ?? 'Gallery')"
    submitText="Assign to Selected Rooms"
    wire:submit="assignRoomsToGallery"
    :loading="'assignRoomsToGallery'"
    maxWidth="2xl">

    <div class="space-y-4">
        {{-- Search and Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label for="roomSearchTerm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Search Room Number
                </label>
                <input
                    type="text"
                    id="roomSearchTerm"
                    wire:model.live.debounce.300ms="roomSearchTerm"
                    placeholder="e.g., 101"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                />
            </div>

            {{-- Room Type Filter --}}
            <div>
                <label for="filterRoomType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Room Type
                </label>
                <select
                    id="filterRoomType"
                    wire:model.live="filterRoomType"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- View Filter --}}
            <div>
                <label for="filterView" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    View
                </label>
                <select
                    id="filterView"
                    wire:model.live="filterView"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Views</option>
                    <option value="Garden View">Garden View</option>
                    <option value="Beach View">Beach View</option>
                </select>
            </div>
        </div>

        {{-- Rooms Table --}}
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            @if(empty($assignableRooms) || count($assignableRooms) === 0)
                <div class="p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">
                        @if($roomSearchTerm || $filterRoomType || $filterView)
                            No rooms found matching your filters.
                        @else
                            All rooms are already assigned to this gallery.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input
                                        type="checkbox"
                                        wire:click="$set('selectedRoomIds', {{ empty($assignableRooms) ? '[]' : json_encode(collect($assignableRooms)->pluck('id')->toArray()) }})"
                                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500"
                                    />
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Room #
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Bed Config
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    View
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($assignableRooms as $room)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input
                                            type="checkbox"
                                            value="{{ $room->id }}"
                                            wire:model.live="selectedRoomIds"
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500"
                                        />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $room->room_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $room->room_type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ ucfirst($room->bed_size) }} ({{ $room->bed_count }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $room->view ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Selected Count --}}
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Selected: <span class="font-semibold text-gray-900 dark:text-white">{{ count($selectedRoomIds) }}</span> room(s)
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-admin.modal.form>
