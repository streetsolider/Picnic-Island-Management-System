<div class="min-h-screen bg-gradient-to-br from-brand-light via-purple-50 to-brand-secondary/10">
    <section class="relative py-12 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 left-10 w-72 h-72 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-brand-secondary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Theme Park <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-brand-secondary">Wallet</span>
                </h1>
                <p class="text-xl text-brand-dark/70">Manage your balance and purchase credits for exciting activities</p>
            </div>

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="max-w-4xl mx-auto mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-4xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Cancellation Notifications --}}
            <div class="max-w-4xl mx-auto">
                <livewire:visitor.theme-park.cancellation-notifications />
            </div>

            {{-- Balance Cards --}}
            <div class="max-w-4xl mx-auto grid gap-6 mb-8 md:grid-cols-2">
                {{-- MVR Balance Card --}}
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300">
                    <div class="p-8 text-white">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-green-100 text-sm font-medium mb-2">MVR Balance</p>
                                <p class="text-5xl font-bold">{{ number_format($stats['current_balance_mvr'], 2) }}</p>
                                <p class="text-green-100 text-xs mt-2">Maldivian Rufiyaa</p>
                            </div>
                            <div class="p-4 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button wire:click="openTopUpForm" class="w-full bg-white text-green-600 px-6 py-3 rounded-xl font-bold hover:bg-green-50 transition-all transform hover:scale-105 shadow-lg">
                            💰 Top Up Wallet
                        </button>
                    </div>
                </div>

                {{-- Credit Balance Card --}}
                <div class="bg-gradient-to-br from-purple-600 to-brand-secondary rounded-3xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300">
                    <div class="p-8 text-white">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-purple-100 text-sm font-medium mb-2">Credit Balance</p>
                                <p class="text-5xl font-bold">{{ number_format($stats['current_credit_balance']) }}</p>
                                <p class="text-purple-100 text-xs mt-2">Available Credits</p>
                            </div>
                            <div class="p-4 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                        </div>
                        <button wire:click="openPurchaseForm" class="w-full bg-white text-purple-600 px-6 py-3 rounded-xl font-bold hover:bg-purple-50 transition-all transform hover:scale-105 shadow-lg">
                            💳 Purchase Credits
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pricing Info --}}
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl px-6 py-4 flex items-center shadow-lg">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-blue-800 font-medium">Current credit price: <strong class="text-2xl">MVR {{ number_format($creditPrice, 2) }}</strong> per credit</span>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="max-w-4xl mx-auto grid gap-6 mb-8 md:grid-cols-3">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all">
                    <p class="text-gray-600 text-sm mb-1">Total Topped Up</p>
                    <p class="text-3xl font-bold text-green-600">MVR {{ number_format($stats['total_topped_up_mvr'], 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-all">
                    <p class="text-gray-600 text-sm mb-1">Credits Purchased</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_credits_purchased']) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-brand-secondary transform hover:scale-105 transition-all">
                    <p class="text-gray-600 text-sm mb-1">Credits Redeemed</p>
                    <p class="text-3xl font-bold text-brand-secondary">{{ number_format($stats['total_credits_redeemed']) }}</p>
                </div>
            </div>

            {{-- Transaction History --}}
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6 text-white">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <h2 class="text-2xl font-display font-bold">Transaction History</h2>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'all' ? 'bg-white text-brand-primary' : 'bg-white/20 text-white hover:bg-white/30' }} transition-all">
                                All
                            </button>
                            <button wire:click="$set('filter', 'top_up')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'top_up' ? 'bg-white text-brand-primary' : 'bg-white/20 text-white hover:bg-white/30' }} transition-all">
                                💰 Top-ups
                            </button>
                            <button wire:click="$set('filter', 'ticket_purchase')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'ticket_purchase' ? 'bg-white text-brand-primary' : 'bg-white/20 text-white hover:bg-white/30' }} transition-all">
                                💳 Buy Credits
                            </button>
                            <button wire:click="$set('filter', 'activity_ticket_purchase')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'activity_ticket_purchase' ? 'bg-white text-brand-primary' : 'bg-white/20 text-white hover:bg-white/30' }} transition-all">
                                🎟️ Tickets
                            </button>
                            <button wire:click="$set('filter', 'credit_refund')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'credit_refund' ? 'bg-white text-brand-primary' : 'bg-white/20 text-white hover:bg-white/30' }} transition-all">
                                ↩️ Refunds
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($transactions->isEmpty())
                        <div class="text-center py-16">
                            <div class="text-6xl mb-4">📋</div>
                            <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">No Transactions Yet</h3>
                            <p class="text-gray-600">Your transaction history will appear here once you top up or purchase credits.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reference</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Balance After</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 font-mono text-sm">{{ $transaction->transaction_reference }}</td>
                                            <td class="px-4 py-4">
                                                @if($transaction->transaction_type === 'top_up')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                        💰 Top Up
                                                    </span>
                                                @elseif($transaction->transaction_type === 'ticket_purchase')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                                        💳 Buy Credits
                                                    </span>
                                                @elseif($transaction->transaction_type === 'activity_ticket_purchase')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                        🎟️ Ticket Purchase
                                                    </span>
                                                @elseif($transaction->transaction_type === 'credit_refund')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                                        ↩️ Refund
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @if($transaction->transaction_type === 'top_up')
                                                    <span class="text-green-600 font-bold">+MVR {{ number_format($transaction->amount_mvr, 2) }}</span>
                                                @elseif($transaction->transaction_type === 'ticket_purchase')
                                                    <span class="text-purple-600 font-bold">+{{ $transaction->credits_amount }} credits</span>
                                                    <span class="text-sm text-gray-500">(MVR {{ number_format($transaction->amount_mvr, 2) }})</span>
                                                @elseif($transaction->transaction_type === 'activity_ticket_purchase')
                                                    <span class="text-red-600 font-bold">-{{ $transaction->credits_amount }} credits</span>
                                                @elseif($transaction->transaction_type === 'credit_refund')
                                                    <span class="text-green-600 font-bold">+{{ $transaction->credits_amount }} credits</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="font-semibold text-gray-900">MVR {{ number_format($transaction->balance_after_mvr, 2) }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->balance_after_tickets }} credits</div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($transactions->hasPages())
                            <div class="mt-6">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Top Up Modal --}}
    @if($showTopUpForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="$set('showTopUpForm', false)">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white">
                    <h3 class="text-2xl font-display font-bold">💰 Top Up Wallet</h3>
                    <p class="text-green-100 text-sm mt-1">Add funds to your wallet balance</p>
                </div>

                <form wire:submit.prevent="topUp" class="p-6">
                    <div class="mb-6">
                        <label for="topUpAmount" class="block text-sm font-bold text-gray-700 mb-2">
                            Amount (MVR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="topUpAmount" wire:model="topUpAmount" step="0.01" min="10" max="10000"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg font-semibold"
                            placeholder="Enter amount" />
                        @error('topUpAmount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Min: MVR 10.00 | Max: MVR 10,000.00
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" wire:loading.attr="disabled" wire:target="topUp"
                            class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:from-green-600 hover:to-emerald-700 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="topUp">Top Up</span>
                            <span wire:loading wire:target="topUp">Processing...</span>
                        </button>
                        <button type="button" wire:click="$set('showTopUpForm', false)"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Purchase Credits Modal --}}
    @if($showPurchaseForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="$set('showPurchaseForm', false)">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
                <div class="bg-gradient-to-r from-purple-600 to-brand-secondary p-6 text-white">
                    <h3 class="text-2xl font-display font-bold">🎟️ Purchase Credits</h3>
                    <p class="text-purple-100 text-sm mt-1">Buy credits for theme park activities</p>
                </div>

                <form wire:submit.prevent="purchaseCredits" class="p-6 space-y-6">
                    <div>
                        <label for="creditCount" class="block text-sm font-bold text-gray-700 mb-2">
                            Number of Credits <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="creditCount" wire:model.live="creditCount" min="1"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg font-semibold" />
                        @error('creditCount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 border-2 border-purple-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Price per credit:</span>
                            <span class="font-bold text-gray-900">MVR {{ number_format($creditPrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-purple-200">
                            <span class="text-sm font-bold text-gray-700">Total Cost:</span>
                            <span class="text-2xl font-bold text-purple-600">MVR {{ number_format($creditPrice * ($creditCount ?: 1), 2) }}</span>
                        </div>
                    </div>

                    <div class="rounded-2xl p-4 border-2 {{ $this->hasInsufficientBalance() ? 'bg-red-50 border-red-300' : 'bg-blue-50 border-blue-200' }}">
                        <p class="text-sm text-gray-700 mb-1">
                            Current MVR Balance: <strong class="text-gray-900">MVR {{ number_format($stats['current_balance_mvr'], 2) }}</strong>
                        </p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <span>Balance After Purchase:</span>
                            <strong class="{{ $this->hasInsufficientBalance() ? 'text-red-600' : 'text-gray-900' }}">
                                MVR {{ number_format($this->getBalanceAfterPurchase(), 2) }}
                            </strong>
                            @if($this->hasInsufficientBalance())
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </p>
                    </div>

                    @if($this->hasInsufficientBalance())
                        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-bold text-red-800">Insufficient Wallet Balance</p>
                                    <p class="text-sm text-red-700 mt-1">
                                        You need <strong>MVR {{ number_format(abs($this->getBalanceAfterPurchase()), 2) }}</strong> more to complete this purchase. Please top up your wallet first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="purchaseCredits"
                            @if($this->hasInsufficientBalance()) disabled @endif
                            class="flex-1 bg-gradient-to-r from-purple-600 to-brand-secondary text-white px-6 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-pink-600 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            @if($this->hasInsufficientBalance())
                                <span>Insufficient Balance</span>
                            @else
                                <span wire:loading.remove wire:target="purchaseCredits">Purchase</span>
                                <span wire:loading wire:target="purchaseCredits">Processing...</span>
                            @endif
                        </button>
                        <button type="button" wire:click="$set('showPurchaseForm', false)"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
