<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Room Management') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Capacity Information Card --}}
        <x-admin.card.base>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $hotel->name }} - Room Capacity
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Monitor your hotel's room capacity and availability
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $currentRoomCount }} / {{ $hotel->room_capacity }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($remainingCapacity > 0)
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $remainingCapacity }} rooms available</span>
                        @else
                            <span class="text-red-600 dark:text-red-400 font-medium">Capacity reached</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Capacity Progress Bar --}}
            <div class="mt-4">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    @php
                        $capacityPercentage = $hotel->room_capacity > 0 ? ($currentRoomCount / $hotel->room_capacity * 100) : 0;
                    @endphp
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300"
                         style="width: {{ $capacityPercentage }}%">
                    </div>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Rooms List Card --}}
        <x-admin.card.base>
            {{-- Header with Create Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">All Rooms</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage all rooms for this hotel</p>
                </div>
                <x-admin.button.primary
                    wire:click="openCreateModal"
                    size="md"
                    :disabled="$remainingCapacity <= 0"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                    Create Room
                </x-admin.button.primary>
            </div>

            {{-- Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                {{-- Search --}}
                <div>
                    <input
                        wire:model.live="search"
                        type="text"
                        placeholder="Search by room number..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                </div>

                {{-- Room Type Filter --}}
                <div>
                    <select
                        wire:model.live="filterRoomType"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                        <option value="">All Room Types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Bed Size Filter --}}
                <div>
                    <select
                        wire:model.live="filterBedSize"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                        <option value="">All Bed Sizes</option>
                        @foreach($bedSizes as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Availability Filter --}}
                <div>
                    <select
                        wire:model.live="filterAvailability"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                        <option value="">All Statuses</option>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
            </div>

            {{-- Rooms Table --}}
            @if($rooms->count() > 0)
                <x-admin.table.wrapper hoverable>
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <x-admin.table.header>Room Number</x-admin.table.header>
                            <x-admin.table.header>Type</x-admin.table.header>
                            <x-admin.table.header>Bed Config</x-admin.table.header>
                            <x-admin.table.header>View</x-admin.table.header>
                            <x-admin.table.header>Price</x-admin.table.header>
                            <x-admin.table.header>Status</x-admin.table.header>
                            <x-admin.table.header>Actions</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                            <x-admin.table.row>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $room->room_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $room->room_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $room->bed_count }} {{ $room->bed_size }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $room->view ? $room->view . ' View' : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($room->base_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-admin.badge.status
                                        :active="$room->is_available"
                                        activeText="Available"
                                        inactiveText="Unavailable" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button
                                        wire:click="toggleAvailability({{ $room->id }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Toggle
                                    </button>
                                    <a
                                        href="{{ route('hotel.rooms.edit', $room) }}"
                                        wire:navigate
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Edit
                                    </a>
                                    <button
                                        wire:click="deleteRoom({{ $room->id }})"
                                        wire:confirm="Are you sure you want to delete this room?"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $rooms->links() }}
                </div>
            @else
                <x-admin.card.empty-state
                    title="No rooms found"
                    description="Get started by creating your first room for this hotel."
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'>
                    <x-slot:action>
                        <x-admin.button.primary
                            wire:click="openCreateModal"
                            :disabled="$remainingCapacity <= 0"
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                            Create Room
                        </x-admin.button.primary>
                    </x-slot:action>
                </x-admin.card.empty-state>
            @endif
        </x-admin.card.base>
    </div>

    {{-- Create Room Modal --}}
    <x-overlays.modal name="create-room" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Create New Room
            </h2>

            <form wire:submit.prevent="createRoom" class="space-y-4">
                {{-- Room Number & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Room Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="room_number"
                            type="text"
                            id="room_number"
                            placeholder="e.g., 101, A-205"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                        @error('room_number')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Room Type <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="room_type"
                            id="room_type"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($roomTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('room_type')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Bed Configuration --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bed_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bed Size <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="bed_size"
                            id="bed_size"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($bedSizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('bed_size')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="bed_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bed Configuration <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="bed_count"
                            id="bed_count"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($bedCounts as $count)
                                <option value="{{ $count }}">{{ $count }}</option>
                            @endforeach
                        </select>
                        @error('bed_count')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- View --}}
                <div>
                    <label for="view" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        View
                    </label>
                    <select
                        wire:model="view"
                        id="view"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                        <option value="">Select a view</option>
                        @foreach($views as $viewOption)
                            <option value="{{ $viewOption }}">{{ $viewOption }} View</option>
                        @endforeach
                    </select>
                    @error('view')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Maximum Occupancy --}}
                <div>
                    <label for="max_occupancy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Maximum Occupancy <span class="text-red-500">*</span>
                    </label>
                    <input
                        wire:model="max_occupancy"
                        type="number"
                        id="max_occupancy"
                        min="1"
                        max="10"
                        placeholder="e.g., 2, 4"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                    @error('max_occupancy')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Pricing will be managed through the Pricing section
                    </p>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-admin.button.secondary
                        type="button"
                        x-on:click="$dispatch('close-modal', 'create-room')"
                        size="md">
                        Cancel
                    </x-admin.button.secondary>

                    <x-admin.button.primary
                        type="submit"
                        size="md"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                        Create Room
                    </x-admin.button.primary>
                </div>
            </form>
        </div>
    </x-overlays.modal>
</div>
