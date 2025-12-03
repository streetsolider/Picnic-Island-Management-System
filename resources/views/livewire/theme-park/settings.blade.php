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
                </div>
            </div>

            {{-- Info Box --}}
            <x-admin.alert.info class="mt-6">
                <div class="text-sm">
                    <p class="font-medium">How Theme Park Tickets Work</p>
                    <p class="mt-1">
                        <strong>1 Ticket = {{ number_format($ticketPrice, 2) }} MVR</strong>
                    </p>
                    <p class="mt-2">
                        Visitors purchase tickets at this rate. Each activity requires a specific number of tickets to participate. For example, if an activity costs 5 tickets and the ticket price is MVR 10, visitors pay MVR 50 for that activity.
                    </p>
                </div>
            </x-admin.alert.info>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-md bg-indigo-600 dark:bg-indigo-500 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150 ease-in-out">
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="w-5 h-5 animate-spin mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </x-admin.card.base>
</div>
