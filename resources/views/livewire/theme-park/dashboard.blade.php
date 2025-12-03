<div>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Theme Park Staff Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if($zone)
                Managing: <span class="font-semibold">{{ $zone->name }}</span> zone
            @else
                Theme Park Staff Dashboard
            @endif
        </p>
    </div>

    @if(!$zone)
        {{-- No Zone Assigned --}}
        <x-admin.card.empty-state
            icon="⚠️"
            title="No Zone Assigned"
            description="You don't have any zone assigned to you yet. Please contact an administrator.">
        </x-admin.card.empty-state>
    @else
        {{-- Statistics Grid --}}
        <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
            {{-- Total Activities --}}
            <x-admin.card.stat
                title="Total Activities"
                :value="$stats['total_activities'] ?? 0"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'
                color="indigo">
                <x-slot name="footer">
                    <span class="text-green-600 dark:text-green-400">{{ $stats['active_activities'] ?? 0 }}</span> active
                </x-slot>
            </x-admin.card.stat>

            {{-- Pending Redemptions --}}
            <x-admin.card.stat
                title="Pending Validation"
                :value="$stats['pending_redemptions'] ?? 0"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                color="yellow">
                <x-slot name="footer">
                    Awaiting validation
                </x-slot>
            </x-admin.card.stat>

            {{-- Validated Today --}}
            <x-admin.card.stat
                title="Validated Tickets"
                :value="$stats['validated_redemptions'] ?? 0"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                color="green">
                <x-slot name="footer">
                    All time
                </x-slot>
            </x-admin.card.stat>

            {{-- Total Redemptions --}}
            <x-admin.card.stat
                title="Total Redemptions"
                :value="$stats['total_redemptions'] ?? 0"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>'
                color="purple">
                <x-slot name="footer">
                    All statuses
                </x-slot>
            </x-admin.card.stat>
        </div>

        {{-- Zone Information --}}
        <x-admin.card.base class="mb-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Zone Name</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $zone->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Opening Hours</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $zone->opening_time }} - {{ $zone->closing_time }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Capacity Limit</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($zone->capacity_limit) }} visitors</p>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Manage Activities --}}
            <x-admin.card.base>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Activities</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Create and manage activities in your zone</p>
                    <x-admin.button.primary wire:navigate href="{{ route('theme-park.activities.index') }}">
                        Go to Activities
                    </x-admin.button.primary>
                </div>
            </x-admin.card.base>

            {{-- Validate Tickets --}}
            <x-admin.card.base>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Validate Tickets</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Check and validate visitor ticket redemptions</p>
                    <x-admin.button.primary wire:navigate href="{{ route('theme-park.validate') }}">
                        Validate Tickets
                    </x-admin.button.primary>
                </div>
            </x-admin.card.base>
        </div>

        {{-- Recent Redemptions --}}
        <x-admin.card.base>
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Recent Redemptions</span>
                    <x-admin.button.link href="{{ route('theme-park.validate') }}" wire:navigate>
                        View All
                    </x-admin.button.link>
                </div>
            </x-slot>

            @if($recentRedemptions->isEmpty())
                <x-admin.card.empty-state
                    icon="📋"
                    title="No Redemptions Yet"
                    description="No visitors have redeemed tickets for activities in your zone yet.">
                </x-admin.card.empty-state>
            @else
                <x-admin.table.wrapper hoverable>
                    <thead>
                        <tr>
                            <x-admin.table.header>Reference</x-admin.table.header>
                            <x-admin.table.header>Visitor</x-admin.table.header>
                            <x-admin.table.header>Activity</x-admin.table.header>
                            <x-admin.table.header>Tickets</x-admin.table.header>
                            <x-admin.table.header>Status</x-admin.table.header>
                            <x-admin.table.header>Date</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRedemptions as $redemption)
                            <x-admin.table.row>
                                <td class="px-6 py-4 font-mono text-sm">{{ $redemption->redemption_reference }}</td>
                                <td class="px-6 py-4">{{ $redemption->user->name }}</td>
                                <td class="px-6 py-4">{{ $redemption->activity->name }}</td>
                                <td class="px-6 py-4">{{ $redemption->tickets_redeemed }}</td>
                                <td class="px-6 py-4">
                                    <x-admin.badge.status :status="$redemption->status">
                                        {{ ucfirst($redemption->status) }}
                                    </x-admin.badge.status>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $redemption->created_at->diffForHumans() }}
                                </td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>
            @endif
        </x-admin.card.base>
    @endif
</div>
