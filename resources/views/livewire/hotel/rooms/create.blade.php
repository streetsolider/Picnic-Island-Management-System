<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Room') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- Capacity Information Card --}}
        <x-admin.card.base class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Hotel Capacity
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $hotel->name }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $currentRoomCount }} / {{ $hotel->room_capacity }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($remainingCapacity > 0)
                            <span class="text-green-600 dark:text-green-400">{{ $remainingCapacity }} rooms remaining</span>
                        @else
                            <span class="text-red-600 dark:text-red-400">Capacity reached</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Capacity Progress Bar --}}
            <div class="mt-4">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                         style="width: {{ $hotel->room_capacity > 0 ? ($currentRoomCount / $hotel->room_capacity * 100) : 0 }}%">
                    </div>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Room Creation Form --}}
        @if($remainingCapacity > 0)
            <x-admin.card.base>
                <x-slot:title>Room Details</x-slot:title>

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
                                    $
                                </span>
                                <input
                                    wire:model="base_price"
                                    type="number"
                                    id="base_price"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    class="w-full pl-7 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
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

                    {{-- Amenities Section --}}
                    @if($amenityCategories->isNotEmpty())
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Room Amenities
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Select the amenities available in this room. You can select all amenities in a category or pick individual items.
                            </p>

                            <div class="space-y-4">
                                @foreach($amenityCategories as $category)
                                    @if($category->amenities->isNotEmpty())
                                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                            {{-- Category Header with Select All --}}
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $category->name }}
                                                    @if($category->description)
                                                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                            - {{ $category->description }}
                                                        </span>
                                                    @endif
                                                </h4>
                                                <label class="flex items-center cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        wire:click="toggleCategory({{ $category->id }})"
                                                        @if($this->isCategorySelected($category->id)) checked @endif
                                                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700"
                                                    >
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</span>
                                                </label>
                                            </div>

                                            {{-- Amenity Items Grid --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                                @foreach($category->amenities as $amenity)
                                                    <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition-colors">
                                                        <input
                                                            type="checkbox"
                                                            wire:model="selectedAmenities"
                                                            value="{{ $amenity->id }}"
                                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700"
                                                        >
                                                        <div class="ml-3 flex-1">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $amenity->name }}
                                                            </span>
                                                            @if($amenity->description)
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                                    {{ $amenity->description }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if($amenityCategories->isEmpty() || $amenityCategories->every(fn($cat) => $cat->amenities->isEmpty()))
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No amenities available</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Create amenity categories and items first before assigning them to rooms.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('hotel.amenities.categories') }}" wire:navigate
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Manage Amenities
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

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
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                            Create Room
                        </x-admin.button.primary>
                    </div>
                </form>
            </x-admin.card.base>
        @else
            {{-- Capacity Reached Message --}}
            <x-admin.card.empty-state
                title="Hotel Capacity Reached"
                description="This hotel has reached its maximum room capacity of {{ $hotel->room_capacity }} rooms. Please contact the administrator to increase capacity."
                icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'>
                <x-slot:action>
                    <x-admin.button.secondary
                        :href="route('hotel.rooms.index')"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>'>
                        Back to Rooms
                    </x-admin.button.secondary>
                </x-slot:action>
            </x-admin.card.empty-state>
        @endif
    </div>
</div>
