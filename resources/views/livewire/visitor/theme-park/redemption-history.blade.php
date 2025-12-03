<div class="min-h-screen bg-gradient-to-br from-brand-light via-pink-50 to-purple-100/20">
    <section class="relative py-12 overflow-hidden">
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 left-10 w-72 h-72 bg-pink-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-brand-dark mb-4">
                    Redemption <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-secondary to-purple-600">History</span>
                </h1>
                <p class="text-xl text-brand-dark/70">View your ticket redemptions and their status</p>
            </div>

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filter Buttons --}}
            <div class="mb-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6">
                <label class="block text-sm font-bold text-brand-dark mb-4">
                    📊 Filter by Status
                </label>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="$set('filter', 'all')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'all' ? 'bg-gradient-to-r from-brand-primary to-brand-secondary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        All
                    </button>
                    <button wire:click="$set('filter', 'pending')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'pending' ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ⏳ Pending
                    </button>
                    <button wire:click="$set('filter', 'validated')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'validated' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ✅ Validated
                    </button>
                    <button wire:click="$set('filter', 'cancelled')"
                        class="px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md {{ $filter === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-pink-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        ❌ Cancelled
                    </button>
                </div>
            </div>

            {{-- Redemptions List --}}
            @if($redemptions->isEmpty())
                <div class="bg-white rounded-3xl shadow-2xl p-16 text-center">
                    <div class="text-6xl mb-6">📋</div>
                    <h3 class="text-3xl font-display font-bold text-brand-dark mb-4">No Redemptions Yet</h3>
                    <p class="text-xl text-gray-600 mb-8">You haven't redeemed any tickets yet. Visit the activities page to get started!</p>
                    <a href="{{ route('visitor.theme-park.activities') }}" wire:navigate
                        class="inline-block bg-gradient-to-r from-brand-primary to-brand-secondary text-white px-8 py-3 rounded-xl font-bold hover:from-brand-secondary hover:to-brand-primary transition-all transform hover:scale-105 shadow-lg">
                        🎢 Browse Activities
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($redemptions as $redemption)
                        <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                            {{-- Card Header --}}
                            <div class="bg-gradient-to-r {{
                                $redemption->status === 'validated' ? 'from-green-500 to-emerald-600' :
                                ($redemption->status === 'cancelled' ? 'from-red-500 to-pink-600' : 'from-yellow-500 to-orange-500')
                            }} p-6 text-white">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold mb-2">{{ $redemption->activity->name }}</h3>
                                        <p class="text-sm text-white/90 font-medium">📍 {{ $redemption->activity->zone->name }} Zone</p>
                                    </div>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white/20 backdrop-blur-sm">
                                        {{
                                            $redemption->status === 'validated' ? '✅ Validated' :
                                            ($redemption->status === 'cancelled' ? '❌ Cancelled' : '⏳ Pending')
                                        }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">
                                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 mb-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Redemption Code</p>
                                        <p class="font-mono text-lg font-bold text-brand-primary">{{ $redemption->redemption_reference }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Number of Persons</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $redemption->number_of_persons }} 👥</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Tickets Used</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $redemption->tickets_redeemed }} 🎟️</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Redeemed At</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $redemption->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    @if($redemption->status === 'validated')
                                        <div class="bg-green-50 rounded-xl p-4">
                                            <p class="text-sm text-green-700 mb-1">Validated At</p>
                                            <p class="text-lg font-bold text-green-900">{{ $redemption->validated_at?->format('M d, Y h:i A') }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($redemption->status === 'validated')
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl px-6 py-4">
                                        <div class="flex items-center text-green-800">
                                            <svg class="w-6 h-6 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-bold">✅ Validated by {{ $redemption->validatedBy->name ?? 'Staff' }}</span>
                                        </div>
                                    </div>
                                @elseif($redemption->status === 'cancelled')
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl px-6 py-4">
                                        <div class="text-red-800">
                                            <p class="font-bold mb-1">❌ Cancelled</p>
                                            <p class="text-sm">{{ $redemption->cancellation_reason ?? 'No reason provided' }}</p>
                                        </div>
                                    </div>
                                @elseif($redemption->status === 'pending')
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 rounded-2xl px-6 py-4">
                                        <div class="flex items-center justify-between flex-wrap gap-4">
                                            <div class="flex items-center text-blue-900">
                                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-bold">Show code <span class="font-mono text-lg text-brand-primary">{{ $redemption->redemption_reference }}</span> to staff</span>
                                            </div>
                                            <button
                                                wire:click="cancelRedemption({{ $redemption->id }})"
                                                wire:confirm="Are you sure you want to cancel this redemption? Your tickets will be returned."
                                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg">
                                                ❌ Cancel Redemption
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($redemptions->hasPages())
                    <div class="mt-8">
                        {{ $redemptions->links() }}
                    </div>
                @endif
            @endif

            {{-- Info Box --}}
            <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 border-2 border-brand-primary/20">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-brand-primary mr-3 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-brand-dark mb-3">📚 Redemption Status Guide:</p>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <span class="mr-2">⏳</span>
                                <div>
                                    <strong class="text-gray-900">Pending:</strong>
                                    <span class="text-gray-700">Waiting for staff validation at the activity entrance</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">✅</span>
                                <div>
                                    <strong class="text-gray-900">Validated:</strong>
                                    <span class="text-gray-700">Approved by staff - you can participate in the activity</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">❌</span>
                                <div>
                                    <strong class="text-gray-900">Cancelled:</strong>
                                    <span class="text-gray-700">Redemption cancelled - tickets returned to your wallet</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
