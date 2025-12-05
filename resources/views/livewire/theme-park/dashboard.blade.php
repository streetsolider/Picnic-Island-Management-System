<div>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    @if($isManager)
                        Theme Park Manager Dashboard
                    @else
                        Theme Park Staff Dashboard
                    @endif
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @if($isManager)
                        Overview of all zones and activities
                    @else
                        Manage your assigned activities and schedules
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    @if($isManager)
        {{-- ===== MANAGER DASHBOARD ===== --}}

        {{-- Statistics Grid --}}
        <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
            {{-- Total Zones --}}
            <x-admin.card.stat
                title="Total Zones"
                :value="$totalZones"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'
                color="indigo">
                <x-slot name="footer">
                    <span class="text-green-600 dark:text-green-400">{{ $activeZones }}</span> active
                </x-slot>
            </x-admin.card.stat>

            {{-- Total Activities --}}
            <x-admin.card.stat
                title="Total Activities"
                :value="$totalActivities"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>'
                color="purple">
                <x-slot name="footer">
                    <span class="text-green-600 dark:text-green-400">{{ $activeActivities }}</span> active
                </x-slot>
            </x-admin.card.stat>

            {{-- Credit Price --}}
            <x-admin.card.stat
                title="Credit Price"
                :value="'MVR ' . number_format($creditPrice, 2)"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                color="green">
                <x-slot name="footer">
                    Per credit
                </x-slot>
            </x-admin.card.stat>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Manage Zones --}}
            <x-admin.card.base>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Zones</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Create and organize theme park zones</p>
                    <x-admin.button.primary wire:navigate href="{{ route('theme-park.zones') }}">
                        Go to Zones
                    </x-admin.button.primary>
                </div>
            </x-admin.card.base>

            {{-- Manage Activities --}}
            <x-admin.card.base>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Activities</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Create activities and assign staff</p>
                    <x-admin.button.primary wire:navigate href="{{ route('theme-park.activities.index') }}">
                        Go to Activities
                    </x-admin.button.primary>
                </div>
            </x-admin.card.base>

            {{-- Credit Settings --}}
            <x-admin.card.base>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Credit Settings</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure global credit pricing</p>
                    <x-admin.button.primary wire:navigate href="{{ route('theme-park.settings') }}">
                        Go to Settings
                    </x-admin.button.primary>
                </div>
            </x-admin.card.base>
        </div>

        {{-- Zones Overview --}}
        <x-admin.card.base class="mb-6">
            <x-slot name="title">Zones Overview</x-slot>

            @if($zones->isEmpty())
                <x-admin.card.empty-state
                    icon="🏝️"
                    title="No Zones Yet"
                    description="Create your first zone to get started.">
                    <x-slot name="action">
                        <x-admin.button.primary wire:navigate href="{{ route('theme-park.zones') }}">
                            Create Zone
                        </x-admin.button.primary>
                    </x-slot>
                </x-admin.card.empty-state>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($zones as $zone)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $zone->name }}</h4>
                                <x-admin.badge.status :status="$zone->is_active ? 'active' : 'inactive'">
                                    {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge.status>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $zone->zone_type }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-medium">{{ $zone->activities_count }}</span> activities
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-admin.card.base>

        {{-- Recent Activities --}}
        @if($recentActivities->isNotEmpty())
            <x-admin.card.base>
                <x-slot name="title">Recently Created Activities</x-slot>

                <x-admin.table.wrapper>
                    <thead>
                        <tr>
                            <x-admin.table.header>Activity Name</x-admin.table.header>
                            <x-admin.table.header>Zone</x-admin.table.header>
                            <x-admin.table.header>Assigned Staff</x-admin.table.header>
                            <x-admin.table.header>Status</x-admin.table.header>
                            <x-admin.table.header>Created</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                            <x-admin.table.row>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $activity->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $activity->zone->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($activity->assignedStaff)
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $activity->assignedStaff->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500 italic">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <x-admin.badge.status :status="$activity->is_active ? 'active' : 'inactive'">
                                        {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge.status>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>
            </x-admin.card.base>
        @endif

    @else
        {{-- ===== STAFF DASHBOARD ===== --}}

        {{-- Check if staff has assigned activities --}}
        @if($myActivities->isEmpty())
            <x-admin.card.empty-state
                icon="🎢"
                title="No Activities Assigned"
                description="You don't have any activities assigned to you yet. Contact your manager to get activities assigned.">
            </x-admin.card.empty-state>
        @else
            {{-- Statistics --}}
            <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- My Activities --}}
                <x-admin.card.stat
                    title="My Activities"
                    :value="$totalActivities"
                    icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>'
                    color="indigo">
                    <x-slot name="footer">
                        Assigned to you
                    </x-slot>
                </x-admin.card.stat>

                {{-- Today's Schedules --}}
                <x-admin.card.stat
                    title="Today's Schedules"
                    :value="$todaySchedules->count()"
                    icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
                    color="purple">
                    <x-slot name="footer">
                        Operating today
                    </x-slot>
                </x-admin.card.stat>

                {{-- Credit Price --}}
                <x-admin.card.stat
                    title="Credit Price"
                    :value="'MVR ' . number_format($creditPrice, 2)"
                    icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    color="green">
                    <x-slot name="footer">
                        Per credit
                    </x-slot>
                </x-admin.card.stat>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- My Activities --}}
                <x-admin.card.base>
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">My Activities</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">View your assigned activities</p>
                        <x-admin.button.primary wire:navigate href="{{ route('theme-park.activities.index') }}">
                            View Activities
                        </x-admin.button.primary>
                    </div>
                </x-admin.card.base>

                {{-- Manage Schedules --}}
                <x-admin.card.base>
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Schedules</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Set operating hours and days</p>
                        <x-admin.button.primary wire:navigate href="{{ route('theme-park.schedules') }}">
                            Manage Schedules
                        </x-admin.button.primary>
                    </div>
                </x-admin.card.base>

                {{-- Validate Redemptions --}}
                <x-admin.card.base>
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Validate Redemptions</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Check visitor redemptions</p>
                        <x-admin.button.primary wire:navigate href="{{ route('theme-park.validate') }}">
                            Validate Redemptions
                        </x-admin.button.primary>
                    </div>
                </x-admin.card.base>
            </div>

            {{-- Today's Schedules --}}
            @if($todaySchedules->isNotEmpty())
                <x-admin.card.base class="mb-6">
                    <x-slot name="title">Today's Schedules - {{ today()->format('l, M d, Y') }}</x-slot>

                    <x-admin.table.wrapper>
                        <thead>
                            <tr>
                                <x-admin.table.header>Activity</x-admin.table.header>
                                <x-admin.table.header>Zone</x-admin.table.header>
                                <x-admin.table.header>Time</x-admin.table.header>
                                <x-admin.table.header>Capacity</x-admin.table.header>
                                <x-admin.table.header>Attendees</x-admin.table.header>
                                <x-admin.table.header>Remaining</x-admin.table.header>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaySchedules as $schedule)
                                <x-admin.table.row>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $schedule->activity->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $schedule->activity->zone->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $schedule->venue_capacity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($schedule->tickets_sold > 0)
                                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $schedule->tickets_sold }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $remaining = $schedule->venue_capacity - $schedule->tickets_sold;
                                            $percentage = ($remaining / $schedule->venue_capacity) * 100;
                                        @endphp
                                        <span class="font-medium {{ $percentage > 50 ? 'text-green-600 dark:text-green-400' : ($percentage > 20 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                            {{ $remaining }}
                                        </span>
                                    </td>
                                </x-admin.table.row>
                            @endforeach
                        </tbody>
                    </x-admin.table.wrapper>
                </x-admin.card.base>
            @endif

            {{-- Upcoming Schedules --}}
            @if($upcomingSchedules->isNotEmpty())
                <x-admin.card.base>
                    <x-slot name="title">Upcoming Schedules (Next 7 Days)</x-slot>

                    <x-admin.table.wrapper>
                        <thead>
                            <tr>
                                <x-admin.table.header>Activity</x-admin.table.header>
                                <x-admin.table.header>Date</x-admin.table.header>
                                <x-admin.table.header>Time</x-admin.table.header>
                                <x-admin.table.header>Capacity</x-admin.table.header>
                                <x-admin.table.header>Attendees</x-admin.table.header>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingSchedules as $schedule)
                                <x-admin.table.row>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $schedule->activity->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->activity->zone->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $schedule->show_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $schedule->show_date->format('l') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($schedule->show_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $schedule->venue_capacity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($schedule->tickets_sold > 0)
                                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $schedule->tickets_sold }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                </x-admin.table.row>
                            @endforeach
                        </tbody>
                    </x-admin.table.wrapper>
                </x-admin.card.base>
            @endif
        @endif
    @endif
</div>
