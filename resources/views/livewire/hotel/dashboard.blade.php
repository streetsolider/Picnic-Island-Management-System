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
                            :href="route('hotel.manage', $hotel)"
                            size="md"
                            class="w-full"
                            icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'>
                            Manage Hotel
                        </x-admin.button.primary>
                    </div>
                </div>
            </x-admin.card.base>
        @endforeach
    </div>
</div>
