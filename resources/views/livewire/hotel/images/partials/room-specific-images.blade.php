<x-admin.card.base>
    {{-- Header with Room Selector and Upload Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Room-Specific Images</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Override default images for individual rooms
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Room Selector --}}
            <select wire:model="selectedRoomId" wire:change="changeRoom($event.target.value)"
                class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <option value="">Select a room...</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">
                        Room {{ $room->room_number }} - {{ $room->room_type }} @if($room->view)({{ $room->view }} View)@endif
                    </option>
                @endforeach
            </select>

            <x-admin.button.primary
                wire:click="openRoomSpecificUploadModal"
                :disabled="!$selectedRoomId"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                Upload Images
            </x-admin.button.primary>
        </div>
    </div>

    {{-- Info Alert --}}
    @if($selectedRoomId)
        @php
            $selectedRoom = $rooms->firstWhere('id', $selectedRoomId);
        @endphp
        <x-admin.alert.info class="mb-4">
            <strong>Note:</strong> Room-specific images will override the default room type images.
            If you delete all room-specific images, the room will automatically use the default {{ $selectedRoom->room_type ?? 'room type' }} images.
        </x-admin.alert.info>
    @endif

    {{-- Images Grid or Empty State --}}
    @if($selectedRoomId)
        @if(count($roomSpecificImages) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($roomSpecificImages as $image)
                    <x-admin.card.base
                        wire:key="room-specific-{{ $image->id }}"
                        padding="p-0"
                        class="relative group overflow-hidden {{ $image->is_primary ? 'border-2 border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : '' }}">

                        {{-- Reorder Buttons --}}
                        <div class="absolute top-2 right-2 z-10 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if(!$loop->first)
                                <button wire:click="moveImageUp({{ $image->id }}, 'room_specific')"
                                    class="bg-white dark:bg-gray-800 rounded p-1 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            @endif
                            @if(!$loop->last)
                                <button wire:click="moveImageDown({{ $image->id }}, 'room_specific')"
                                    class="bg-white dark:bg-gray-800 rounded p-1 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        {{-- Primary Badge (Top Left) --}}
                        @if($image->is_primary)
                            <span class="absolute top-3 left-3 z-20 mt-1 ml-1 px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                Primary
                            </span>
                        @endif

                        {{-- Image Container --}}
                        <img src="{{ $image->image_url }}"
                             alt="{{ $image->alt_text ?? 'Room image' }}"
                             class="w-full h-48 object-cover">

                        {{-- Action Buttons at Bottom --}}
                        <div class="p-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between gap-2">
                                @if(!$image->is_primary)
                                    <x-admin.button.secondary
                                        wire:click="setPrimaryImage({{ $image->id }}, 'room_specific')"
                                        size="sm"
                                        class="flex-1">
                                        Set Primary
                                    </x-admin.button.secondary>
                                @else
                                    <span class="flex-1 text-xs text-gray-500 dark:text-gray-400 px-2">Primary Image</span>
                                @endif
                                <button
                                    wire:click="confirmDeleteImage({{ $image->id }}, 'room_specific')"
                                    x-data x-on:click="$dispatch('open-modal', 'delete-image-modal')"
                                    type="button"
                                    class="inline-flex items-center justify-center px-3 py-2 rounded-md bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </x-admin.card.base>
                @endforeach
            </div>
        @else
            <x-admin.card.empty-state
                title="No room-specific images"
                description="This room is currently using the default room type images. Upload room-specific images to override them."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                <x-slot:action>
                    <x-admin.button.primary wire:click="openRoomSpecificUploadModal">
                        Upload Images
                    </x-admin.button.primary>
                </x-slot:action>
            </x-admin.card.empty-state>
        @endif
    @else
        <x-admin.card.empty-state
            title="Select a room"
            description="Choose a room from the dropdown above to manage its images."
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'
        />
    @endif
</x-admin.card.base>
