<div>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ticket History
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View ticket purchase and redemption history for your assigned activities
                </p>
            </div>
        </div>
    </x-slot>

    @if($assignedActivities->isEmpty())
        <x-admin.card.empty-state
            icon="🎢"
            title="No Activities Assigned"
            description="You don't have any activities assigned to you yet. Contact your manager to get activities assigned.">
        </x-admin.card.empty-state>
    @else
        {{-- Filters --}}
        <x-admin.card.base class="mb-6">
            <div class="p-4 space-y-4">
                {{-- Activity & Time Filter Row --}}
                <div class="flex flex-col md:flex-row gap-4">
                    {{-- Activity Selector --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            📍 Select Activity
                        </label>
                        <select wire:model.live="selectedActivityId"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @foreach($assignedActivities as $activity)
                                <option value="{{ $activity->id }}">
                                    {{ $activity->name }} ({{ $activity->zone->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Time Filter --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ⏱️ Time Period
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="$set('timeFilter', '1')"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $timeFilter == '1' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Last 1hr
                            </button>
                            <button wire:click="$set('timeFilter', '3')"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $timeFilter == '3' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Last 3hrs
                            </button>
                            <button wire:click="$set('timeFilter', '6')"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $timeFilter == '6' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Last 6hrs
                            </button>
                            <button wire:click="$set('timeFilter', '12')"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $timeFilter == '12' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Last 12hrs
                            </button>
                            <button wire:click="$set('timeFilter', '24')"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $timeFilter == '24' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Last 24hrs
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-800 dark:text-green-300">Redeemed Tickets</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">{{ $redeemedCount }}</p>
                                <p class="text-xs text-green-700 dark:text-green-400 mt-1">{{ $totalRedeemedPersons }} persons</p>
                            </div>
                            <div class="text-4xl">✅</div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-300">Pending Tickets</p>
                                <p class="text-2xl font-bold text-amber-900 dark:text-amber-100 mt-1">{{ $unredeemedCount }}</p>
                                <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">{{ $totalUnredeemedPersons }} persons</p>
                            </div>
                            <div class="text-4xl">⏳</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Tab Switcher --}}
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button
                        wire:click="switchView('unredeemed')"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $viewMode === 'unredeemed' ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        ⏳ Pending Tickets ({{ $unredeemedCount }})
                    </button>
                    <button
                        wire:click="switchView('redeemed')"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $viewMode === 'redeemed' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        ✅ Redeemed Tickets ({{ $redeemedCount }})
                    </button>
                </nav>
            </div>
        </div>

        {{-- Tickets Table --}}
        @if($tickets->isEmpty())
            <x-admin.card.empty-state
                icon="{{ $viewMode === 'redeemed' ? '✅' : '⏳' }}"
                title="No {{ $viewMode === 'redeemed' ? 'Redeemed' : 'Pending' }} Tickets"
                description="No {{ $viewMode === 'redeemed' ? 'redeemed' : 'pending' }} tickets found for the selected time period.">
            </x-admin.card.empty-state>
        @else
            <x-admin.card.base>
                <x-admin.table.wrapper hoverable>
                    <thead>
                        <tr>
                            <x-admin.table.header>Guest</x-admin.table.header>
                            <x-admin.table.header>Activity</x-admin.table.header>
                            <x-admin.table.header>Quantity</x-admin.table.header>
                            @if($viewMode === 'redeemed')
                                <x-admin.table.header>Redeemed</x-admin.table.header>
                            @else
                                <x-admin.table.header>Purchased</x-admin.table.header>
                                <x-admin.table.header>Status</x-admin.table.header>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <x-admin.table.row>
                                {{-- Guest --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->guest->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->guest->email }}</div>
                                </td>

                                {{-- Activity --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticket->activity->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $ticket->activity->zone->name }}
                                        @if($ticket->showSchedule)
                                            • {{ $ticket->showSchedule->show_date->format('M d') }} at {{ \Carbon\Carbon::parse($ticket->showSchedule->show_time)->format('g:i A') }}
                                        @endif
                                    </div>
                                </td>

                                {{-- Quantity --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $ticket->quantity }} {{ Str::plural('person', $ticket->quantity) }}
                                    </span>
                                </td>

                                {{-- Date (Redeemed or Purchased) --}}
                                <td class="px-6 py-4">
                                    @if($viewMode === 'redeemed')
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $ticket->redeemed_at?->format('M d, Y') ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $ticket->redeemed_at?->format('g:i A') ?? '' }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $ticket->purchase_datetime->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $ticket->purchase_datetime->format('g:i A') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status (Pending view only) --}}
                                @if($viewMode === 'unredeemed')
                                    <td class="px-6 py-4">
                                        <x-admin.badge.status status="pending">
                                            ⏳ Pending
                                        </x-admin.badge.status>
                                    </td>
                                @endif
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>

                {{-- Pagination --}}
                @if($tickets->hasPages())
                    <div class="mt-4 px-6 pb-4">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </x-admin.card.base>
        @endif
    @endif
</div>
