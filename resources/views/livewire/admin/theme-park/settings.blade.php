<div>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Theme Park Settings
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Configure global theme park system settings
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Success Message --}}
    @if (session('success'))
        <x-admin.alert.success class="mb-4" dismissible>
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    {{-- Ticket Price Settings Card --}}
    <x-admin.card.base>
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Ticket Pricing
            </div>
        </x-slot>

        <form wire:submit="save">
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Current Price Display --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Ticket Price
                    </label>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($currentPrice, 2) }}
                        </span>
                        <span class="ml-2 text-lg text-gray-600 dark:text-gray-400">MVR</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Per ticket
                    </p>
                </div>

                {{-- New Price Input --}}
                <div>
                    <label for="ticketPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Ticket Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            id="ticketPrice"
                            wire:model="ticketPrice"
                            step="0.01"
                            min="5"
                            max="1000"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent"
                            placeholder="Enter new price"
                        />
                        <span class="absolute right-3 top-2.5 text-gray-500 dark:text-gray-400">
                            MVR
                        </span>
                    </div>
                    @error('ticketPrice')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Valid range: MVR 5.00 - MVR 1,000.00
                    </p>
                </div>
            </div>

            {{-- Info Box --}}
            <x-admin.alert.info class="mt-6">
                <div class="flex">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm">
                        <p class="font-medium">Important Information</p>
                        <p class="mt-1">
                            This setting controls the global price for theme park tickets. Visitors will exchange MVR for tickets at this rate, then redeem tickets to participate in activities.
                        </p>
                    </div>
                </div>
            </x-admin.alert.info>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end space-x-3">
                <x-admin.button.secondary type="button" wire:click="mount">
                    Reset
                </x-admin.button.secondary>
                <x-admin.button.primary type="submit" :loading="'save'">
                    Save Changes
                </x-admin.button.primary>
            </div>
        </form>
    </x-admin.card.base>
</div>
