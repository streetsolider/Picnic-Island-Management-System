<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Validate Ferry Ticket</h2>

    <x-admin.card.base>
        <div class="max-w-2xl mx-auto">
            {{-- Input Form --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Ticket Reference
                </label>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        wire:model="ticketReference"
                        wire:keydown.enter="validateTicket"
                        placeholder="Enter ticket reference (e.g., FT-XXXXXXXX)"
                        class="flex-1 px-4 py-3 text-lg border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <x-admin.button.primary wire:click="validateTicket" size="lg">
                        Validate
                    </x-admin.button.primary>
                </div>
            </div>

            {{-- Validation Result --}}
            @if($ticket)
                <div class="mt-6 p-6 {{ $validationResult['success'] ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }} rounded-lg">
                    <h3 class="text-lg font-semibold {{ $validationResult['success'] ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }} mb-4">
                        {{ $validationResult['message'] }}
                    </h3>

                    @if($ticket)
                        <div class="space-y-2 text-sm">
                            <div><strong>Ticket:</strong> {{ $ticket->ticket_reference }}</div>
                            <div><strong>Passenger:</strong> {{ $ticket->guest->name }}</div>
                            <div><strong>Route:</strong> {{ $ticket->route->origin }} → {{ $ticket->route->destination }}</div>
                            <div><strong>Travel Date:</strong> {{ $ticket->travel_date->format('M d, Y') }}</div>
                            <div><strong>Passengers:</strong> {{ $ticket->number_of_passengers }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($ticket->status) }}</div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <x-admin.button.secondary wire:click="resetForm">
                            Validate Another Ticket
                        </x-admin.button.secondary>
                    </div>
                </div>
            @endif
        </div>
    </x-admin.card.base>
</div>
