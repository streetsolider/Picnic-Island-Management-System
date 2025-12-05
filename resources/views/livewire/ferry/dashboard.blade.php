<div>
    {{-- Vessel Selection --}}
    @if($vessels->count() > 1)
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Vessel
            </label>
            <select
                wire:change="selectVessel($event.target.value)"
                class="w-full md:w-96 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @foreach($vessels as $vessel)
                    <option value="{{ $vessel->id }}" {{ $selectedVesselId == $vessel->id ? 'selected' : '' }}>
                        {{ $vessel->name }} - {{ $vessel->registration_number }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ferry Operations Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Managing: <span class="font-semibold">{{ $selectedVessel->name }}</span> ({{ $selectedVessel->vessel_type }})
        </p>
    </div>

    {{-- Vessel Information Card --}}
    <x-admin.card.base class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Vessel Name</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedVessel->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Registration Number</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedVessel->registration_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Vessel Type</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedVessel->vessel_type }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Capacity</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedVessel->capacity }} passengers</p>
            </div>
        </div>
    </x-admin.card.base>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Today's Passengers --}}
        <x-admin.card.stat
            title="Today's Passengers"
            :value="$stats['today_passengers']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            color="indigo" />

        {{-- Upcoming Trips --}}
        <x-admin.card.stat
            title="Upcoming Trips"
            :value="$stats['upcoming_trips']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
            color="blue" />

        {{-- Total Tickets (This Month) --}}
        <x-admin.card.stat
            title="Tickets This Month"
            :value="$stats['total_tickets_month']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>'
            color="green" />

        {{-- Total Revenue (This Month) --}}
        <x-admin.card.stat
            title="Revenue This Month"
            value="MVR {{ number_format($stats['total_revenue_month'], 2) }}"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            color="yellow" />
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Manage Routes --}}
        <x-admin.card.base>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Routes</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Create and manage ferry routes for your vessel</p>
                <x-admin.button.primary wire:navigate href="{{ route('ferry.routes.index') }}">
                    Go to Routes
                </x-admin.button.primary>
            </div>
        </x-admin.card.base>

        {{-- Manage Schedules --}}
        <x-admin.card.base>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Schedules</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Set departure and arrival times for routes</p>
                <x-admin.button.primary wire:navigate href="{{ route('ferry.schedules.index') }}">
                    Go to Schedules
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
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Check and validate passenger ferry tickets</p>
                <x-admin.button.primary wire:navigate href="{{ route('ferry.tickets.validate') }}">
                    Validate Tickets
                </x-admin.button.primary>
            </div>
        </x-admin.card.base>
    </div>
</div>
