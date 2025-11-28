<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Room') }} - {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- Room Information Card --}}
        <x-admin.card.base class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Room Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $hotel->name }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Status:
                        @if($room->is_available)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                Unavailable
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Room Edit Form --}}
        <x-admin.card.base>
            <x-slot:title>Edit Room Details</x-slot:title>

            <form wire:submit.prevent="save" class="space-y-6">
                {{-- Room Number & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Room Number --}}
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Room Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="room_number"
                            type="text"
                            id="room_number"
                            placeholder="e.g., 101, A-205"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                        @error('room_number')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Room Type --}}
                    <div>
                        <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Room Type <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="room_type"
                            id="room_type"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($roomTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('room_type')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Bed Configuration --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Bed Size --}}
                    <div>
                        <label for="bed_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bed Size <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="bed_size"
                            id="bed_size"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($bedSizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('bed_size')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Bed Count --}}
                    <div>
                        <label for="bed_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bed Configuration <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="bed_count"
                            id="bed_count"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            @foreach($bedCounts as $count)
                                <option value="{{ $count }}">{{ $count }} ({{ $count == 'Single' ? '1' : ($count == 'Double' ? '2' : ($count == 'Triple' ? '3' : '4')) }} bed{{ $count == 'Single' ? '' : 's' }})</option>
                            @endforeach
                        </select>
                        @error('bed_count')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- View & Floor --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- View --}}
                    <div>
                        <label for="view" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            View
                        </label>
                        <select
                            wire:model="view"
                            id="view"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                            <option value="">Select a view</option>
                            @foreach($views as $viewOption)
                                <option value="{{ $viewOption }}">{{ $viewOption }} View</option>
                            @endforeach
                        </select>
                        @error('view')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Floor Number --}}
                    <div>
                        <label for="floor_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Floor Number
                        </label>
                        <input
                            wire:model="floor_number"
                            type="number"
                            id="floor_number"
                            min="1"
                            placeholder="e.g., 1, 2, 3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                        @error('floor_number')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Pricing & Occupancy --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Base Price --}}
                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Base Price (per night) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">
                                MVR
                            </span>
                            <input
                                wire:model="base_price"
                                type="number"
                                id="base_price"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full pl-12 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            >
                        </div>
                        @error('base_price')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Max Occupancy --}}
                    <div>
                        <label for="max_occupancy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Maximum Occupancy <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="max_occupancy"
                            type="number"
                            id="max_occupancy"
                            min="1"
                            max="10"
                            placeholder="e.g., 2, 4"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        >
                        @error('max_occupancy')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-admin.button.secondary
                        :href="route('hotel.rooms.index')"
                        size="md">
                        Cancel
                    </x-admin.button.secondary>

                    <x-admin.button.primary
                        type="submit"
                        size="md"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'>
                        Update Room
                    </x-admin.button.primary>
                </div>
            </form>
        </x-admin.card.base>
    </div>
</div>
