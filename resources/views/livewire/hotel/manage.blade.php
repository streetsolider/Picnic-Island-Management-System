<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $hotel->name }}
        </h2>
    </x-slot>

    <!-- Hotel Info -->
    <x-admin.card.base class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $hotel->name }}</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $hotel->description }}</p>
        <div class="mt-4 flex items-center gap-4">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Rating:</span>
                <span class="ml-2 text-yellow-500">{{ str_repeat('⭐', $hotel->star_rating) }}</span>
            </div>
            <x-admin.badge.status
                :active="$hotel->is_active"
                activeText="Active"
                inactiveText="Inactive" />
        </div>
    </x-admin.card.base>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Rooms with Capacity -->
        <x-admin.card.stat
            title="Room Capacity"
            :value="$stats['total_rooms'] . ' / ' . $hotel->room_capacity"
            color="blue">
            <x-slot:icon>
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </x-slot:icon>
            <x-slot:footer>
                <div class="mt-2">
                    @php
                        $capacityPercentage = $hotel->room_capacity > 0 ? ($stats['total_rooms'] / $hotel->room_capacity * 100) : 0;
                        $remainingRooms = $hotel->room_capacity - $stats['total_rooms'];
                    @endphp
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $capacityPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        @if($remainingRooms > 0)
                            <span class="text-green-600 dark:text-green-400">{{ $remainingRooms }} rooms available</span>
                        @else
                            <span class="text-red-600 dark:text-red-400">Capacity reached</span>
                        @endif
                    </p>
                </div>
            </x-slot:footer>
        </x-admin.card.stat>

        <!-- Available Rooms -->
        <x-admin.card.stat title="Available Rooms" :value="$stats['available_rooms']" color="green">
            <x-slot:icon>
                <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-admin.card.stat>

        <!-- Room Views -->
        <x-admin.card.stat title="Room Views" :value="$stats['total_views']" color="purple">
            <x-slot:icon>
                <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </x-slot:icon>
        </x-admin.card.stat>

        <!-- Total Amenities -->
        <x-admin.card.stat title="Total Amenities" :value="$stats['total_amenities']" color="yellow">
            <x-slot:icon>
                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </x-slot:icon>
        </x-admin.card.stat>
    </div>

    <!-- Quick Actions -->
    <x-admin.card.base class="mb-6">
        <x-slot:title>Quick Actions</x-slot:title>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-admin.button.primary
                :href="route('hotel.rooms.index')"
                wire:navigate
                size="md"
                class="w-full"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                Manage Rooms
            </x-admin.button.primary>

            <x-admin.button.success
                :href="route('hotel.amenities.categories')"
                size="md"
                class="w-full"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>'>
                Manage Categories
            </x-admin.button.success>

            <x-admin.button.secondary
                :href="route('hotel.views.manage')"
                size="md"
                class="w-full"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'>
                Manage Views
            </x-admin.button.secondary>
        </div>
    </x-admin.card.base>

    <!-- Recent Rooms -->
    @if($recentRooms->count() > 0)
        <x-admin.card.base>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Recently Added Rooms</h3>
                <x-admin.button.link :href="route('hotel.rooms.index')" size="sm">
                    View All →
                </x-admin.button.link>
            </div>
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <x-admin.table.header>Room Number</x-admin.table.header>
                        <x-admin.table.header>Type</x-admin.table.header>
                        <x-admin.table.header>Bed Config</x-admin.table.header>
                        <x-admin.table.header>View</x-admin.table.header>
                        <x-admin.table.header>Price</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRooms as $room)
                        <x-admin.table.row clickable>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $room->room_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $room->room_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $room->bed_count }} {{ $room->bed_size }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $room->view?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ${{ number_format($room->base_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.badge.status
                                    :active="$room->is_available"
                                    activeText="Available"
                                    inactiveText="Unavailable" />
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </x-admin.card.base>
    @else
        <x-admin.card.empty-state
            title="No rooms yet"
            description="Get started by creating your first room for this hotel."
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'>
            <x-slot:action>
                <x-admin.button.primary
                    :href="route('hotel.rooms.create')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'>
                    Create Room
                </x-admin.button.primary>
            </x-slot:action>
        </x-admin.card.empty-state>
    @endif
</div>
