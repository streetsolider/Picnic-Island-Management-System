<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Room Images') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Room Images Management</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Upload and manage images for room types and individual rooms
            </p>
        </div>

        {{-- Tabs Navigation (following pricing pattern) --}}
        <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" role="tablist">
            <button wire:click="setActiveTab('room_types')" type="button"
                class="@if($activeTab === 'room_types') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Room Type Images
            </button>
            <button wire:click="setActiveTab('room_specific')" type="button"
                class="@if($activeTab === 'room_specific') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Room-Specific Images
            </button>
        </nav>
        </div>

        {{-- Tab Content --}}
        <div key="{{ $refreshKey }}">
        @if($activeTab === 'room_types')
            @include('livewire.hotel.images.partials.room-type-images')
        @else
            @include('livewire.hotel.images.partials.room-specific-images')
        @endif
        </div>
    </div>

    {{-- Upload Modal (shared) --}}
    @include('livewire.hotel.images.partials.upload-modal')

    {{-- Delete Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-image-modal"
        title="Delete Image?"
        description="Are you sure you want to delete this image? This action cannot be undone."
        method="deleteImage"
        confirmText="Yes, Delete Image"
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
