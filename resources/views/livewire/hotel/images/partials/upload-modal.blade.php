{{-- Room Type Upload Modal --}}
<x-overlays.modal name="upload-room-type-images" maxWidth="2xl" focusable>
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Upload Images for {{ $selectedRoomType }} Rooms
        </h2>

        <form wire:submit.prevent="uploadRoomTypeImages" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Images
                </label>
                <input type="file"
                       wire:model="uploadingRoomTypeImages"
                       multiple
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    JPEG, PNG, JPG, WEBP (Max 5MB each)
                </p>
                @error('uploadingRoomTypeImages.*')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Live Preview --}}
            @if($uploadingRoomTypeImages)
                <div class="grid grid-cols-3 gap-4">
                    @foreach($uploadingRoomTypeImages as $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="uploadingRoomTypeImages" class="text-sm text-gray-600 dark:text-gray-400">
                Processing images...
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button.secondary
                    type="button"
                    x-on:click="$dispatch('close-modal', 'upload-room-type-images')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.primary
                    type="submit"
                    :disabled="!$uploadingRoomTypeImages"
                    wire:loading.attr="disabled"
                    wire:target="uploadRoomTypeImages">
                    Upload Images
                </x-admin.button.primary>
            </div>
        </form>
    </div>
</x-overlays.modal>

{{-- Room-Specific Upload Modal --}}
<x-overlays.modal name="upload-room-specific-images" maxWidth="2xl" focusable>
    <div class="p-6">
        @php
            $selectedRoom = $selectedRoomId ? $rooms->firstWhere('id', $selectedRoomId) : null;
        @endphp
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Upload Images for Room {{ $selectedRoom?->room_number ?? '' }}
        </h2>

        <form wire:submit.prevent="uploadRoomSpecificImages" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Images
                </label>
                <input type="file"
                       wire:model="uploadingRoomSpecificImages"
                       multiple
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    JPEG, PNG, JPG, WEBP (Max 5MB each)
                </p>
                @error('uploadingRoomSpecificImages.*')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Live Preview --}}
            @if($uploadingRoomSpecificImages)
                <div class="grid grid-cols-3 gap-4">
                    @foreach($uploadingRoomSpecificImages as $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="uploadingRoomSpecificImages" class="text-sm text-gray-600 dark:text-gray-400">
                Processing images...
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button.secondary
                    type="button"
                    x-on:click="$dispatch('close-modal', 'upload-room-specific-images')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.primary
                    type="submit"
                    :disabled="!$uploadingRoomSpecificImages"
                    wire:loading.attr="disabled"
                    wire:target="uploadRoomSpecificImages">
                    Upload Images
                </x-admin.button.primary>
            </div>
        </form>
    </div>
</x-overlays.modal>
