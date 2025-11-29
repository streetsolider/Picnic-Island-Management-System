<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Room Gallery') }}
        </h2>
    </x-slot>

    <div class="space-y-6" key="{{ $refreshKey }}">
        {{-- Gallery Management Content --}}
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
