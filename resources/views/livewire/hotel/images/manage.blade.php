<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Image Gallery Management') }}
        </h2>
    </x-slot>

    <div class="space-y-6" key="{{ $refreshKey }}">
        {{-- Hotel Selector (if multiple hotels) --}}
        @if($assignedHotels->count() > 1)
            <x-admin.card.base>
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

        {{-- Hotel Gallery Section --}}
        @include('livewire.hotel.images.partials.hotel-gallery')

        {{-- Room Galleries Section --}}
        @include('livewire.hotel.images.partials.galleries')
    </div>

    {{-- Assign Rooms Modal --}}
    @include('livewire.hotel.images.partials.assign-rooms-modal')

    {{-- Delete Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-image-modal"
        title="Delete Image?"
        description="Are you sure you want to delete this image? This action cannot be undone."
        method="deleteImage"
        confirmText="Yes, Delete Image"
    />

    {{-- Delete Gallery Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-gallery-modal"
        title="Delete Gallery?"
        description="Are you sure you want to delete this gallery? All images in this gallery will be permanently deleted."
        method="deleteGallery"
        confirmText="Yes, Delete Gallery"
    />

    {{-- Toast Notifications --}}
    <x-admin.toast.toast-container>
        @if($showToast)
            <x-admin.toast.toast
                wire:key="toast-{{ $showToast }}"
                :type="$toastType"
                :title="$toastTitle"
                :message="$toastMessage"
            />
        @endif
    </x-admin.toast.toast-container>
</div>
