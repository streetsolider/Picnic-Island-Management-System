<div>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Validate Tickets</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Validate visitor redemption codes for your assigned activities
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

    @if(!$hasAssignedActivities)
        {{-- No Activities Assigned --}}
        <x-admin.card.empty-state
            icon="⚠️"
            title="No Activities Assigned"
            description="You don't have any activities assigned to you yet. Please contact your manager to get activities assigned.">
        </x-admin.card.empty-state>
    @else
        {{-- Search Form --}}
        <x-admin.card.base class="mb-6">
            <form wire:submit="searchRedemption">
                <div class="flex items-end space-x-4">
                    <div class="flex-1">
                        <label for="searchCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter Redemption Code
                        </label>
                        <input
                            type="text"
                            id="searchCode"
                            wire:model="searchCode"
                            placeholder="TPR-XXXXXXXX"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg font-mono"
                            autofocus
                        />
                        @error('searchCode') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex space-x-2">
                        <x-admin.button.primary type="submit" :loading="'searchRedemption'">
                            Validate
                        </x-admin.button.primary>
                        @if($searchPerformed)
                            <x-admin.button.secondary type="button" wire:click="resetSearch">
                                Clear
                            </x-admin.button.secondary>
                        @endif
                    </div>
                </div>
            </form>
        </x-admin.card.base>

        {{-- Redemption Details --}}
        @if($redemption)
            <x-admin.card.base>
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <span>Redemption Details</span>
                        <x-admin.badge.status :status="$redemption->status">
                            {{ ucfirst($redemption->status) }}
                        </x-admin.badge.status>
                    </div>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Redemption Information --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Redemption Code</p>
                            <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">{{ $redemption->redemption_reference }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Activity</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->activity->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Credits Spent</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->tickets_redeemed }} credit(s)</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Purchased At</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    {{-- Visitor Information --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor Email</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->user->email }}</p>
                        </div>
                        @if($redemption->status === 'validated')
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Validated By</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->validatedBy->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Validated At</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->validated_at?->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        @if($redemption->status === 'cancelled' && $redemption->cancellation_reason)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Cancellation Reason</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $redemption->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($redemption->status === 'validated')
                    <x-admin.alert.success class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>This redemption has already been validated and the visitor can participate in the activity.</span>
                        </div>
                    </x-admin.alert.success>
                @elseif($redemption->status === 'cancelled')
                    <x-admin.alert.danger class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span>This redemption has been cancelled and cannot be used.</span>
                        </div>
                    </x-admin.alert.danger>
                @endif
            </x-admin.card.base>
        @elseif($searchPerformed)
            <x-admin.card.empty-state
                icon="🔍"
                title="No Results"
                description="No redemption found with this code. Please check and try again.">
            </x-admin.card.empty-state>
        @else
            {{-- Instructions --}}
            <x-admin.card.base>
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">How to Validate Tickets</h3>
                    <div class="max-w-md mx-auto text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p>1. Ask the visitor for their redemption code (format: TPR-XXXXXXXX)</p>
                        <p>2. Enter the code in the search box above</p>
                        <p>3. Click "Validate" to verify and approve entry</p>
                        <p>4. If valid and pending, the system will mark it as validated</p>
                    </div>
                </div>
            </x-admin.card.base>
        @endif
    @endif
</div>
