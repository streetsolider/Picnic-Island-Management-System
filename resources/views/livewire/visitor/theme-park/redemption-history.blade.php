<div>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Redemption History</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            View your ticket redemptions and their status
        </p>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <x-admin.alert.success class="mb-4" dismissible>
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    @if (session('error'))
        <x-admin.alert.danger class="mb-4" dismissible>
            {{ session('error') }}
        </x-admin.alert.danger>
    @endif

    {{-- Filter Buttons --}}
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Filter by Status
        </label>
        <div class="flex flex-wrap gap-2">
            <button
                wire:click="$set('filter', 'all')"
                class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                All
            </button>
            <button
                wire:click="$set('filter', 'pending')"
                class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filter === 'pending' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Pending
            </button>
            <button
                wire:click="$set('filter', 'validated')"
                class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filter === 'validated' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Validated
            </button>
            <button
                wire:click="$set('filter', 'cancelled')"
                class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filter === 'cancelled' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Cancelled
            </button>
        </div>
    </div>

    {{-- Redemptions List --}}
    @if($redemptions->isEmpty())
        <x-admin.card.empty-state
            icon="📋"
            title="No Redemptions Yet"
            description="You haven't redeemed any tickets yet. Visit the activities page to get started!">
            <x-slot name="action">
                <x-admin.button.primary href="{{ route('visitor.theme-park.activities') }}" wire:navigate>
                    Browse Activities
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="space-y-4">
            @foreach($redemptions as $redemption)
                <x-admin.card.base>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $redemption->activity->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $redemption->activity->zone->name }} Zone
                                    </p>
                                </div>
                                <x-admin.badge.status :status="$redemption->status">
                                    {{ ucfirst($redemption->status) }}
                                </x-admin.badge.status>
                            </div>

                            <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-4 text-sm">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Redemption Code</p>
                                    <p class="font-mono font-semibold text-gray-900 dark:text-white">{{ $redemption->redemption_reference }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Tickets Used</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $redemption->tickets_redeemed }} ticket(s)</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Redeemed At</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $redemption->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                @if($redemption->status === 'validated')
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Validated At</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $redemption->validated_at?->format('M d, Y h:i A') }}</p>
                                    </div>
                                @endif
                            </div>

                            @if($redemption->status === 'validated')
                                <div class="mt-3">
                                    <x-admin.alert.success>
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Validated by {{ $redemption->validatedBy->name ?? 'Staff' }}
                                        </div>
                                    </x-admin.alert.success>
                                </div>
                            @elseif($redemption->status === 'cancelled')
                                <div class="mt-3">
                                    <x-admin.alert.danger>
                                        <div class="text-sm">
                                            <strong>Cancelled:</strong> {{ $redemption->cancellation_reason ?? 'No reason provided' }}
                                        </div>
                                    </x-admin.alert.danger>
                                </div>
                            @elseif($redemption->status === 'pending')
                                <div class="mt-3">
                                    <x-admin.alert.info>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>Show code <strong>{{ $redemption->redemption_reference }}</strong> to staff at the activity entrance</span>
                                            <x-admin.button.danger
                                                size="sm"
                                                wire:click="cancelRedemption({{ $redemption->id }})"
                                                wire:confirm="Are you sure you want to cancel this redemption? Your tickets will be returned.">
                                                Cancel
                                            </x-admin.button.danger>
                                        </div>
                                    </x-admin.alert.info>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-admin.card.base>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($redemptions->hasPages())
            <div class="mt-6">
                {{ $redemptions->links() }}
            </div>
        @endif
    @endif

    {{-- Info Box --}}
    <x-admin.alert.info class="mt-6">
        <div class="text-sm">
            <p class="font-medium mb-1">Redemption Status Guide:</p>
            <ul class="list-disc list-inside space-y-1">
                <li><strong>Pending:</strong> Waiting for staff validation at the activity entrance</li>
                <li><strong>Validated:</strong> Approved by staff - you can participate in the activity</li>
                <li><strong>Cancelled:</strong> Redemption cancelled - tickets returned to your wallet</li>
            </ul>
        </div>
    </x-admin.alert.info>
</div>
