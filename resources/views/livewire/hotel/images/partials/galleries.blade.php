<x-admin.card.base>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Room Image Galleries</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Create reusable image galleries and assign them to multiple rooms
                </p>
            </div>
            <x-admin.button.primary wire:click="openGalleryForm">
                Create Gallery
            </x-admin.button.primary>
        </div>
    </div>

    {{-- Galleries Grid --}}
    @if($this->galleries->isEmpty())
        <x-admin.card.empty-state
            title="No galleries yet"
            description="Create your first gallery to organize room images. You can then assign galleries to multiple rooms."
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($this->galleries as $gallery)
                <x-admin.card.base
                    class="cursor-pointer transition-all {{ $selectedGalleryId == $gallery->id ? 'ring-2 ring-indigo-500 border-indigo-500' : 'hover:shadow-lg' }}"
                    wire:click="selectGallery({{ $gallery->id }})">
                    {{-- Gallery Header --}}
                    <div class="mb-4">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $gallery->name }}
                        </h4>
                        @if($gallery->description)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $gallery->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300 dark:ring-1 dark:ring-blue-500/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $gallery->images_count ?? 0 }} {{ Str::plural('image', $gallery->images_count ?? 0) }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300 dark:ring-1 dark:ring-purple-500/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $gallery->rooms_count ?? 0 }} {{ Str::plural('room', $gallery->rooms_count ?? 0) }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-admin.button.secondary
                            wire:click.stop="openGalleryForm({{ $gallery->id }})"
                            size="sm">
                            Edit
                        </x-admin.button.secondary>
                        <button
                            wire:click.stop="confirmDeleteGallery({{ $gallery->id }})"
                            x-data x-on:click="$dispatch('open-modal', 'delete-gallery-modal')"
                            type="button"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-md bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </x-admin.card.base>
            @endforeach
        </div>

        {{-- Selected Gallery Images --}}
        @if($selectedGalleryId)
            @php
                $selectedGallery = $this->galleries->firstWhere('id', $selectedGalleryId);
            @endphp

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedGallery?->name ?? 'Gallery' }} - Images
                    </h4>
                    <div class="flex gap-2">
                        <x-admin.button.secondary
                            wire:click="openAssignRoomsModal"
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'>
                            Assign to Rooms
                        </x-admin.button.secondary>
                        <x-admin.button.primary
                            wire:click="openGalleryUploadModal"
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                            Upload Images
                        </x-admin.button.primary>
                    </div>
                </div>

                @if(count($galleryImages) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($galleryImages as $image)
                            <x-admin.card.base
                                wire:key="gallery-image-{{ $image->id }}"
                                padding="p-0"
                                class="relative group overflow-hidden {{ $image->is_primary ? 'border-2 border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : '' }}">

                                {{-- Reorder Buttons --}}
                                <div class="absolute top-2 right-2 z-10 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if(!$loop->first)
                                        <button wire:click="moveGalleryImageUp({{ $image->id }})"
                                            class="bg-white dark:bg-gray-800 rounded p-1 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    @if(!$loop->last)
                                        <button wire:click="moveGalleryImageDown({{ $image->id }})"
                                            class="bg-white dark:bg-gray-800 rounded p-1 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                {{-- Primary Badge --}}
                                @if($image->is_primary)
                                    <span class="absolute top-3 left-3 z-20 mt-1 ml-1 px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        Primary
                                    </span>
                                @endif

                                {{-- Image Container --}}
                                <img src="{{ $image->image_url }}"
                                     alt="{{ $image->alt_text ?? 'Gallery image' }}"
                                     class="w-full h-48 object-cover">

                                {{-- Action Buttons at Bottom --}}
                                <div class="p-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between gap-2">
                                        @if(!$image->is_primary)
                                            <x-admin.button.secondary
                                                wire:click="setGalleryPrimaryImage({{ $image->id }})"
                                                size="sm"
                                                class="flex-1">
                                                Set Primary
                                            </x-admin.button.secondary>
                                        @else
                                            <span class="flex-1 text-xs text-gray-500 dark:text-gray-400 px-2">Primary Image</span>
                                        @endif
                                        <button
                                            wire:click="confirmDeleteImage({{ $image->id }}, 'gallery')"
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
                        title="No images yet"
                        description="Upload images to this gallery using the button above."
                        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'>
                    </x-admin.card.empty-state>
                @endif
            </div>
        @endif
    @endif
</x-admin.card.base>

{{-- Gallery Form Modal --}}
<x-admin.modal.form
    name="gallery-form"
    :show="$showGalleryForm"
    :title="$editingGalleryId ? 'Edit Gallery' : 'Create Gallery'"
    submitText="Save"
    wire:submit="saveGallery"
    :loading="'saveGallery'"
    maxWidth="2xl">

    <div class="space-y-6">
        {{-- Gallery Details --}}
        <div class="space-y-4">
            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gallery Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       wire:model="galleryName"
                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                @error('galleryName')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea
                    wire:model="galleryDescription"
                    rows="3"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                @error('galleryDescription')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Assigned Rooms Section (only when editing) --}}
        @if($editingGalleryId && !empty($assignedRooms))
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Assigned Rooms
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Uncheck rooms to remove gallery assignment
                    </p>
                </div>

                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto max-h-64">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keep
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
                                @foreach($assignedRooms as $room)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="checkbox"
                                                checked
                                                wire:click="toggleRoomRemoval({{ $room->id }})"
                                                @if(in_array($room->id, $removingRoomIds)) unchecked @endif
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

                    {{-- Removal Count --}}
                    @if(!empty($removingRoomIds))
                        <div class="px-6 py-3 bg-yellow-50 dark:bg-yellow-900/20 border-t border-yellow-200 dark:border-yellow-700">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <strong>{{ count($removingRoomIds) }}</strong> room(s) will be removed from this gallery
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.modal.form>

{{-- Gallery Upload Modal --}}
<x-overlays.modal name="upload-gallery-images" maxWidth="2xl" focusable>
    <div class="p-6">
        @php
            $selectedGallery = $selectedGalleryId ? $this->galleries->firstWhere('id', $selectedGalleryId) : null;
        @endphp
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Upload Images to {{ $selectedGallery?->name ?? 'Gallery' }}
        </h2>

        <form wire:submit.prevent="uploadGalleryImages" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Images
                </label>
                <input type="file"
                       wire:model="uploadingGalleryImages"
                       multiple
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    JPEG, PNG, JPG, WEBP (Max 5MB each)
                </p>
                @error('uploadingGalleryImages.*')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Live Preview --}}
            @if($uploadingGalleryImages)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($uploadingGalleryImages as $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="uploadingGalleryImages" class="text-sm text-gray-600 dark:text-gray-400">
                Processing images...
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button.secondary
                    type="button"
                    x-on:click="$dispatch('close-modal', 'upload-gallery-images')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.primary
                    type="submit"
                    :disabled="!$uploadingGalleryImages"
                    wire:loading.attr="disabled"
                    wire:target="uploadGalleryImages">
                    Upload Images
                </x-admin.button.primary>
            </div>
        </form>
    </div>
</x-overlays.modal>

{{-- Delete Gallery Confirmation Modal --}}
<x-admin.modal.confirmation
    name="delete-gallery-modal"
    title="Delete Gallery?"
    description="Are you sure you want to delete this gallery? All images in this gallery will be permanently deleted. This action cannot be undone."
    method="deleteGallery"
    confirmText="Yes, Delete Gallery"
/>
