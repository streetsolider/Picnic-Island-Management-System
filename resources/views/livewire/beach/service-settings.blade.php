<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Service Settings
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(!$service)
            <!-- No Service Assigned -->
            <x-admin.card.empty-state
                icon="🏖️"
                title="No Service Assigned"
                description="You don't have any beach service assigned to you yet. Please contact your administrator.">
            </x-admin.card.empty-state>
        @else
            <!-- Flash Messages -->
            @if (session()->has('success'))
                <x-admin.alert.success dismissible>
                    {{ session('success') }}
                </x-admin.alert.success>
            @endif

            @if (session()->has('error'))
                <x-admin.alert.danger dismissible>
                    {{ session('error') }}
                </x-admin.alert.danger>
            @endif

            <!-- Service Info (Read-only) -->
            <x-admin.card.base>
                <x-slot name="title">Service Information</x-slot>
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-4xl">{{ $service->category->icon ?? '🏖️' }}</span>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $service->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $service->category->name ?? 'Beach Service' }}</p>
                    </div>
                </div>
                @if($service->description)
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $service->description }}</p>
                @endif
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Service Type</span>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $service->service_type }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Booking Type</span>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $service->booking_type === 'fixed_slot' ? 'Fixed Time Slots' : 'Flexible Duration' }}</p>
                    </div>
                    @if($service->booking_type === 'fixed_slot')
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Slot Duration</span>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $service->slot_duration_minutes }} minutes</p>
                        </div>
                    @endif
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Total Capacity</span>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $service->capacity_limit }}</p>
                    </div>
                </div>
            </x-admin.card.base>

            <!-- Editable Settings Form -->
            <x-admin.card.base>
                <x-slot name="title">Manage Settings</x-slot>
                <form wire:submit.prevent="saveSettings" class="space-y-6">
                    <!-- Operating Hours -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opening Time *</label>
                            <input wire:model="opening_time" type="time" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @error('opening_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Closing Time *</label>
                            <input wire:model="closing_time" type="time" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @error('closing_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Pricing -->
                    @if($service->booking_type === 'fixed_slot')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slot Price (MVR) *</label>
                            <input wire:model="slot_price" type="number" min="0" step="0.01" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @error('slot_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Price per {{ $service->slot_duration_minutes }} minute slot</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price Per Hour (MVR) *</label>
                            <input wire:model="price_per_hour" type="number" min="0" step="0.01" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @error('price_per_hour') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hourly rental rate</p>
                        </div>
                    @endif

                    <!-- Concurrent Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Concurrent Capacity *</label>
                        <input wire:model="concurrent_capacity" type="number" min="1" max="{{ $service->capacity_limit }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('concurrent_capacity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum simultaneous bookings allowed (max: {{ $service->capacity_limit }})</p>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Service Active</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">When inactive, no new bookings can be made</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('beach.dashboard') }}" wire:navigate>
                            <x-admin.button.secondary>
                                Cancel
                            </x-admin.button.secondary>
                        </a>
                        <x-admin.button.primary type="submit">
                            Save Settings
                        </x-admin.button.primary>
                    </div>
                </form>
            </x-admin.card.base>
        @endif
    </div>
</div>
