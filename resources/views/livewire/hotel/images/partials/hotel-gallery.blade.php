{{-- Hotel Gallery Section --}}
<x-admin.card.base class="mb-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Hotel Gallery</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Manage images for {{ $hotel->name }} - these images will be displayed to visitors when browsing hotels
                </p>
            </div>
            <x-admin.button.primary wire:click="openHotelGalleryUploadModal">
                Upload Hotel Images
            </x-admin.button.primary>
        </div>
    </div>

    {{-- Hotel Gallery Images --}}
    @if(count($hotelGalleryImages) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($hotelGalleryImages as $image)
                <x-admin.card.base
                    wire:key="hotel-gallery-image-{{ $image->id }}"
                    padding="p-0"
                    class="relative group overflow-hidden {{ $image->is_primary ? 'border-2 border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : '' }}">

                    {{-- Reorder Buttons --}}
                    <div class="absolute top-2 right-2 z-10 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        @if(!$loop->first)
                            <button wire:click="moveHotelGalleryImageUp({{ $image->id }})"
                                class="bg-white dark:bg-gray-800 rounded p-1 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        @endif
                        @if(!$loop->last)
                            <button wire:click="moveHotelGalleryImageDown({{ $image->id }})"
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
                         alt="{{ $image->alt_text ?? 'Hotel image' }}"
                         class="w-full h-48 object-cover">

                    {{-- Action Buttons at Bottom --}}
                    <div class="p-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-1">
                            @if(!$image->is_primary)
                                <x-admin.button.secondary
                                    wire:click="setHotelGalleryPrimaryImage({{ $image->id }})"
                                    size="sm"
                                    class="flex-1">
                                    Set Primary
                                </x-admin.button.secondary>
                            @endif
                            <button
                                wire:click="deleteHotelGalleryImage({{ $image->id }})"
                                type="button"
                                wire:confirm="Are you sure you want to delete this image?"
                                class="inline-flex items-center justify-center px-2 py-1.5 text-sm rounded-md bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
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
            title="No hotel images yet"
            description="Upload images to showcase {{ $hotel->name }} to potential guests. These images will appear in search results."
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' />
    @endif
</x-admin.card.base>

{{-- Upload Hotel Gallery Images Modal --}}
<x-admin.modal.form
    name="upload-hotel-gallery-images"
    title="Upload Hotel Images"
    submitText="Upload"
    wire:submit="uploadHotelGalleryImages"
    :loading="'uploadHotelGalleryImages'">

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Images
            </label>
            <input
                type="file"
                wire:model="uploadingHotelGalleryImages"
                multiple
                accept="image/jpeg,image/png,image/jpg,image/webp"
                class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                JPEG, PNG, JPG or WEBP. Max 5MB per image.
            </p>
            @error('uploadingHotelGalleryImages.*')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        {{-- Image Preview --}}
        @if($uploadingHotelGalleryImages)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Preview
                </label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($uploadingHotelGalleryImages as $image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-24 w-full object-cover rounded">
                    @endforeach
                </div>
            </div>
        @endif

        <div wire:loading wire:target="uploadingHotelGalleryImages" class="text-sm text-indigo-600 dark:text-indigo-400">
            Loading preview...
        </div>
    </div>
</x-admin.modal.form>
