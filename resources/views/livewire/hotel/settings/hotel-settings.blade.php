<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hotel Settings</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Configure general hotel settings and preferences for {{ $hotel->name }}
        </p>
    </div>

    <div key="{{ $refreshKey }}">
    {{-- Hotel Selector (if multiple hotels) --}}
        @if($assignedHotels->count() > 1)
            <x-admin.card.base class="mb-6">
                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Hotel to Manage
                    </label>
                    <select
                        wire:model.live="selectedHotelId"
                        wire:change="selectHotel($event.target.value)"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($assignedHotels as $assignedHotel)
                            <option value="{{ $assignedHotel->id }}">
                                {{ $assignedHotel->name }} ({{ $assignedHotel->star_rating_display }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </x-admin.card.base>
        @endif

        <!-- Flash Messages -->
        @if(session()->has('message'))
            <x-admin.alert.success dismissible class="mb-6">
                {{ session('message') }}
            </x-admin.alert.success>
        @endif

        @if(session()->has('error'))
            <x-admin.alert.danger dismissible class="mb-6">
                {{ session('error') }}
            </x-admin.alert.danger>
        @endif

        @if(session()->has('success'))
            <x-admin.alert.success dismissible class="mb-6">
                {{ session('success') }}
            </x-admin.alert.success>
        @endif

        <!-- Hotel Information Card -->
        <x-admin.card.base class="mb-6">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Hotel Information</span>
                </div>
            </x-slot>

            <div class="space-y-4">
                <!-- Hotel Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $hotel->name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $hotel->star_rating }} Star Hotel
                    </p>
                </div>

                <!-- Description -->
                @if($hotel->description)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $hotel->description }}
                        </p>
                    </div>
                @endif
            </div>
        </x-admin.card.base>

        <!-- Check-in & Check-out Times Card -->
        <x-admin.card.base>
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Check-in & Check-out Times</span>
                    <x-admin.button.primary wire:click="openEditModal" size="sm">
                        Edit Times
                    </x-admin.button.primary>
                </div>
            </x-slot>

            <div class="space-y-6">
                <!-- Time Display Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Check-in Time -->
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Check-in Time
                                </p>
                            </div>
                            <div class="text-gray-400 dark:text-gray-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $hotel->formatted_checkin_time }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Guests can check-in from this time
                        </p>
                    </div>

                    <!-- Check-out Time -->
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Check-out Time
                                </p>
                            </div>
                            <div class="text-gray-400 dark:text-gray-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $hotel->formatted_checkout_time }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Guests must check-out by this time
                        </p>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <x-admin.alert.info>
                        <div class="space-y-3">
                            <p class="font-semibold text-sm">About Check-in & Check-out Times:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="font-medium text-blue-700 dark:text-blue-400 mb-1">Check-in Time:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li>Allowed range: 12:00 PM to 4:00 PM</li>
                                        <li>Guests can check-in from this time onwards</li>
                                        <li>Early arrivals may request early check-in</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-medium text-orange-700 dark:text-orange-400 mb-1">Check-out Time:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li>Allowed range: 10:00 AM to 2:00 PM</li>
                                        <li>Guests must vacate rooms by this time</li>
                                        <li>Late checkout available (up to 6:00 PM)</li>
                                        <li>Late checkout requests are FREE of charge</li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 border-t border-blue-200 dark:border-blue-800 pt-2 mt-2">
                                <strong>Note:</strong> Check-in time must be after check-out time to ensure proper room turnover and housekeeping.
                            </p>
                        </div>
                    </x-admin.alert.info>
                </div>
            </div>
        </x-admin.card.base>

    <!-- Hotel Gallery Section -->
    <x-admin.card.base class="mt-6">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <div>
                    <span>Hotel Images Gallery</span>
                    <p class="text-sm font-normal text-gray-600 dark:text-gray-400 mt-1">
                        Manage images for {{ $hotel->name }} - these images will be displayed to visitors when browsing hotels
                    </p>
                </div>
                <x-admin.button.primary wire:click="openHotelGalleryUploadModal" size="sm">
                    Upload Images
                </x-admin.button.primary>
            </div>
        </x-slot>

        {{-- Hotel Gallery Images --}}
        @if(count($hotelGalleryImages) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" key="{{ $refreshKey }}">
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
    </div>

    <!-- Edit Modal -->
    <x-admin.modal.form
        wire:key="edit-modal-{{ $hotel->id }}"
        name="edit-hotel-times"
        :show="$showEditModal"
        title="Edit Check-in & Check-out Times"
        submitText="Save Changes"
        wire:submit="save"
        :loading="'save'"
        maxWidth="2xl"
        x-on:close="$wire.closeEditModal()">

        <!-- Time Inputs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Check-in Time -->
            <div>
                <label for="checkinTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Check-in Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="checkinTime"
                    wire:model="checkinTime"
                    min="12:00"
                    max="16:00"
                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                @error('checkinTime')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Between 12:00 PM and 4:00 PM
                </p>
            </div>

            <!-- Check-out Time -->
            <div>
                <label for="checkoutTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Check-out Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="checkoutTime"
                    wire:model="checkoutTime"
                    min="10:00"
                    max="14:00"
                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                @error('checkoutTime')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Between 10:00 AM and 2:00 PM
                </p>
            </div>
        </div>

        <!-- Info Alert -->
        <x-admin.alert.warning>
            <p class="text-sm">
                <strong>Important:</strong> These changes will apply to all future bookings. Check-in time must be after check-out time to allow for proper room preparation.
            </p>
        </x-admin.alert.warning>
    </x-admin.modal.form>

    <!-- Upload Hotel Gallery Images Modal -->
    <x-admin.modal.form
        name="upload-hotel-gallery-images"
        :show="$showUploadModal"
        title="Upload Hotel Images"
        submitText="Upload"
        wire:submit="uploadHotelGalleryImages"
        :loading="'uploadHotelGalleryImages'"
        x-on:close="$wire.closeUploadModal()">

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
    </x-admin.modal.form>
</div>
