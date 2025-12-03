<div>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Theme Park Wallet</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Manage your wallet balance and purchase tickets
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

    {{-- Balance Cards --}}
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        {{-- MVR Balance Card --}}
        <x-admin.card.base>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">MVR Balance</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($stats['current_balance_mvr'], 2) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maldivian Rufiyaa</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <x-admin.button.success wire:click="openTopUpForm" class="w-full">
                    Top Up Wallet
                </x-admin.button.success>
            </div>
        </x-admin.card.base>

        {{-- Ticket Balance Card --}}
        <x-admin.card.base>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ticket Balance</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($stats['current_ticket_balance']) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Available Tickets</p>
                </div>
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <x-admin.button.primary wire:click="openPurchaseForm" class="w-full">
                    Purchase Tickets
                </x-admin.button.primary>
            </div>
        </x-admin.card.base>
    </div>

    {{-- Pricing Info --}}
    <x-admin.alert.info class="mb-8">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <span>Current ticket price: <strong>MVR {{ number_format($ticketPrice, 2) }}</strong> per ticket</span>
        </div>
    </x-admin.alert.info>

    {{-- Statistics --}}
    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <x-admin.card.stat
            title="Total Topped Up"
            value="MVR {{ number_format($stats['total_topped_up_mvr'], 2) }}"
            color="green" />
        <x-admin.card.stat
            title="Total Tickets Purchased"
            :value="number_format($stats['total_tickets_purchased'])"
            color="indigo" />
        <x-admin.card.stat
            title="Total Tickets Redeemed"
            :value="number_format($stats['total_tickets_redeemed'])"
            color="purple" />
    </div>

    {{-- Transaction History --}}
    <x-admin.card.base>
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <span>Transaction History</span>
                <div class="flex space-x-2">
                    <button
                        wire:click="$set('filter', 'all')"
                        class="px-3 py-1 text-sm rounded-lg {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        All
                    </button>
                    <button
                        wire:click="$set('filter', 'top_up')"
                        class="px-3 py-1 text-sm rounded-lg {{ $filter === 'top_up' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Top-ups
                    </button>
                    <button
                        wire:click="$set('filter', 'ticket_purchase')"
                        class="px-3 py-1 text-sm rounded-lg {{ $filter === 'ticket_purchase' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Purchases
                    </button>
                </div>
            </div>
        </x-slot>

        @if($transactions->isEmpty())
            <x-admin.card.empty-state
                icon="📋"
                title="No Transactions Yet"
                description="Your transaction history will appear here once you top up or purchase tickets.">
            </x-admin.card.empty-state>
        @else
            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Reference</x-admin.table.header>
                        <x-admin.table.header>Type</x-admin.table.header>
                        <x-admin.table.header>Amount</x-admin.table.header>
                        <x-admin.table.header>Balance After</x-admin.table.header>
                        <x-admin.table.header>Date</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <x-admin.table.row>
                            <td class="px-6 py-4 font-mono text-sm">{{ $transaction->transaction_reference }}</td>
                            <td class="px-6 py-4">
                                @if($transaction->transaction_type === 'top_up')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Top Up
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        Ticket Purchase
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->transaction_type === 'top_up')
                                    <span class="text-green-600 dark:text-green-400">+MVR {{ number_format($transaction->amount_mvr, 2) }}</span>
                                @else
                                    <span class="text-indigo-600 dark:text-indigo-400">{{ $transaction->tickets_amount }} tickets</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">(MVR {{ number_format($transaction->amount_mvr, 2) }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>MVR {{ number_format($transaction->balance_after_mvr, 2) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->balance_after_tickets }} tickets</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </x-admin.card.base>

    {{-- Top Up Modal --}}
    <x-admin.modal.form
        name="top-up-form"
        :show="$showTopUpForm"
        title="Top Up Wallet"
        submitText="Top Up"
        wire:submit="topUp"
        :loading="'topUp'">

        <div>
            <label for="topUpAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Amount (MVR) <span class="text-red-500">*</span>
            </label>
            <input
                type="number"
                id="topUpAmount"
                wire:model="topUpAmount"
                step="0.01"
                min="10"
                max="10000"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="Enter amount"
            />
            @error('topUpAmount') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Min: MVR 10.00 | Max: MVR 10,000.00
            </p>
        </div>
    </x-admin.modal.form>

    {{-- Purchase Tickets Modal --}}
    <x-admin.modal.form
        name="purchase-form"
        :show="$showPurchaseForm"
        title="Purchase Tickets"
        submitText="Purchase"
        wire:submit="purchaseTickets"
        :loading="'purchaseTickets'">

        <div class="space-y-4">
            <div>
                <label for="ticketCount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Number of Tickets <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="ticketCount"
                    wire:model.live="ticketCount"
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
                @error('ticketCount') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Price per ticket:</span>
                    <span class="font-medium text-gray-900 dark:text-white">MVR {{ number_format($ticketPrice, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Cost:</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">MVR {{ number_format($ticketPrice * ($ticketCount ?: 1), 2) }}</span>
                </div>
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Current MVR Balance: <strong class="text-gray-900 dark:text-white">MVR {{ number_format($stats['current_balance_mvr'], 2) }}</strong>
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Balance After Purchase: <strong class="text-gray-900 dark:text-white">MVR {{ number_format(max(0, $stats['current_balance_mvr'] - ($ticketPrice * ($ticketCount ?: 1))), 2) }}</strong>
                </p>
            </div>
        </div>
    </x-admin.modal.form>
</div>
