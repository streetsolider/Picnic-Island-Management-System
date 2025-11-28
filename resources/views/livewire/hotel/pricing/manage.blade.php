<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pricing Management</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Configure dynamic pricing for {{ $hotel->name }}
        </p>
    </div>

    {{-- Success Message --}}
    @if (session()->has('success'))
        <x-admin.alert.success class="mb-6">
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    {{-- Tabs Navigation --}}
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" role="tablist">
            <button wire:click="setActiveTab('room_types')" type="button"
                class="@if($activeTab === 'room_types') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Room Types
            </button>
            <button wire:click="setActiveTab('views')" type="button"
                class="@if($activeTab === 'views') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                View Pricing
            </button>
            <button wire:click="setActiveTab('seasonal')" type="button"
                class="@if($activeTab === 'seasonal') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Seasonal Pricing
            </button>
            <button wire:click="setActiveTab('day_types')" type="button"
                class="@if($activeTab === 'day_types') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Day Type Pricing
            </button>
            <button wire:click="setActiveTab('durations')" type="button"
                class="@if($activeTab === 'durations') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Duration Discounts
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div class="mt-6" key="{{ $refreshKey }}">
        @if($activeTab === 'room_types')
            @include('livewire.hotel.pricing.partials.room-types')
        @elseif($activeTab === 'views')
            @include('livewire.hotel.pricing.partials.views')
        @elseif($activeTab === 'seasonal')
            @include('livewire.hotel.pricing.partials.seasonal')
        @elseif($activeTab === 'day_types')
            @include('livewire.hotel.pricing.partials.day-types')
        @elseif($activeTab === 'durations')
            @include('livewire.hotel.pricing.partials.durations')
        @endif
    </div>

    {{-- Delete Confirmation Modals --}}
    <x-admin.modal.confirmation
        name="delete-room-type-pricing-modal"
        title="Delete Room Type Pricing?"
        description="Are you sure you want to delete this pricing? This action cannot be undone."
        method="deleteRoomTypePricing"
        confirmText="Yes, Delete Pricing"
    />

    <x-admin.modal.confirmation
        name="delete-view-pricing-modal"
        title="Delete View Pricing?"
        description="Are you sure you want to delete this view pricing? This action cannot be undone."
        method="deleteViewPricing"
        confirmText="Yes, Delete Pricing"
    />

    <x-admin.modal.confirmation
        name="delete-seasonal-pricing-modal"
        title="Delete Seasonal Pricing?"
        description="Are you sure you want to delete this seasonal pricing? This action cannot be undone."
        method="deleteSeasonalPricing"
        confirmText="Yes, Delete Pricing"
    />

    <x-admin.modal.confirmation
        name="delete-day-type-pricing-modal"
        title="Delete Day Type Pricing?"
        description="Are you sure you want to delete this day type pricing? This action cannot be undone."
        method="deleteDayTypePricing"
        confirmText="Yes, Delete Pricing"
    />

    <x-admin.modal.confirmation
        name="delete-duration-discount-modal"
        title="Delete Duration Discount?"
        description="Are you sure you want to delete this discount? This action cannot be undone."
        method="deleteDurationDiscount"
        confirmText="Yes, Delete Discount"
    />
</div>
