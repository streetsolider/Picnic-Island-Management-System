<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hotel Management Dashboard') }}
        </h2>
    </x-slot>

    <!-- Hotels Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($hotels as $hotel)
            <x-admin.card.base class="hover:shadow-lg transition-shadow duration-200">
                <div class="space-y-4">
                    <!-- Hotel Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $hotel->name }}
                            </h3>
                            <div class="mt-1 flex items-center">
                                <span class="text-yellow-500">{{ str_repeat('⭐', $hotel->star_rating) }}</span>
                            </div>
                        </div>
                        <x-admin.badge.status
                            :active="$hotel->is_active"
                            activeText="Active"
                            inactiveText="Inactive" />
                    </div>

                    <!-- Hotel Description -->
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                        {{ $hotel->description }}
                    </p>

                    <!-- Hotel Stats -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $hotel->rooms_count }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Total Rooms
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $hotel->amenities_count }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Amenities
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-admin.button.primary
                            href="{{ route('hotel.rooms.index') }}"
                            size="md"
                            class="w-full"
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'>
                            Manage Hotel
                        </x-admin.button.primary>
                    </div>
                </div>
            </x-admin.card.base>
        @endforeach
    </div>
</div>
