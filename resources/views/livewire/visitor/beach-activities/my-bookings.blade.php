<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                My Beach <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Bookings</span>
            </h1>
            <p class="text-gray-600">View and manage your beach activity bookings</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl flex items-start gap-3"
                x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-start gap-3"
                x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm p-2 mb-6 inline-flex gap-2">
            <button
                wire:click="switchTab('upcoming')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $tab === 'upcoming' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Upcoming
            </button>
            <button
                wire:click="switchTab('past')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $tab === 'past' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Past
            </button>
            <button
                wire:click="switchTab('cancelled')"
                class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $tab === 'cancelled' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Cancelled
            </button>
        </div>

        {{-- Bookings List --}}
        @if($bookings->isEmpty())
            <div class="bg-white rounded-3xl shadow-lg p-16 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">
                    No Bookings Found
                </h3>
                <p class="text-gray-600 mb-6">You don't have any {{ $tab }} beach activity bookings.</p>
                <a href="{{ route('visitor.beach-activities.browse') }}" wire:navigate
                    class="inline-block bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                    Browse Activities
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center gap-6">
                                {{-- Activity Icon & Info --}}
                                <div class="flex items-start gap-4 flex-1">
                                    <span class="text-5xl">{{ $booking->service->category->icon }}</span>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-display font-bold text-brand-dark mb-1">
                                            {{ $booking->service->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ $booking->service->category->name }}
                                        </p>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                            <div class="flex items-center gap-2 text-gray-700">
                                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $booking->booking_date->format('M j, Y') }}</span>
                                            </div>

                                            <div class="flex items-center gap-2 text-gray-700">
                                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-semibold">
                                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2 text-gray-700">
                                                <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                                <span class="font-mono font-semibold">{{ $booking->booking_reference }}</span>
                                            </div>

                                            <div class="flex items-center gap-2 font-bold text-brand-primary">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                MVR {{ number_format($booking->total_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status & Actions --}}
                                <div class="flex flex-col items-end gap-4 md:min-w-[200px]">
                                    {{-- Status Badge --}}
                                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full
                                        @if($booking->isRedeemed()) bg-green-100 text-green-800
                                        @elseif($booking->isConfirmed()) bg-blue-100 text-blue-800
                                        @elseif($booking->isCancelled()) bg-red-100 text-red-800
                                        @elseif($booking->isExpired()) bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-col w-full gap-2">
                                        @if($booking->isConfirmed() && $booking->booking_date->greaterThanOrEqualTo(now()->addHours(24)))
                                            <button
                                                wire:click="openCancelModal({{ $booking->id }})"
                                                class="w-full text-sm text-red-600 hover:text-red-700 font-semibold py-2 px-4 border-2 border-red-200 rounded-full hover:bg-red-50 transition-all">
                                                Cancel
                                            </button>
                                        @endif

                                        <a
                                            href="{{ route('visitor.beach-activities.confirmation', $booking) }}"
                                            wire:navigate
                                            class="w-full text-center text-sm text-brand-primary hover:text-brand-primary/80 font-semibold py-2 px-4 border-2 border-brand-primary/20 rounded-full hover:bg-brand-primary/5 transition-all">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Cancel Confirmation Modal --}}
@if($showCancelModal && $selectedBooking)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeCancelModal">
        <div class="bg-white rounded-3xl shadow-xl max-w-md w-full" @click.stop>
            <div class="p-8">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-6">
                    <h3 class="text-2xl font-display font-bold text-brand-dark">Cancel Booking</h3>
                    <button wire:click="closeCancelModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Warning --}}
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        Are you sure you want to cancel this booking?
                    </p>
                    <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-4 mb-4">
                        <p class="text-sm text-yellow-800">
                            <strong>{{ $selectedBooking->service->name }}</strong><br>
                            {{ $selectedBooking->booking_date->format('M j, Y') }} at {{ \Carbon\Carbon::parse($selectedBooking->start_time)->format('g:i A') }}
                        </p>
                    </div>
                </div>

                {{-- Cancellation Reason (Optional) --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Reason for cancellation (optional)
                    </label>
                    <textarea
                        wire:model="cancellationReason"
                        rows="3"
                        class="w-full rounded-xl border-gray-300 focus:border-brand-primary focus:ring-brand-primary"
                        placeholder="Let us know why you're cancelling..."></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button
                        wire:click="closeCancelModal"
                        class="flex-1 bg-white hover:bg-gray-50 text-gray-700 py-3 rounded-full font-semibold border-2 border-gray-300 transition-all">
                        Keep Booking
                    </button>
                    <button
                        wire:click="confirmCancel"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-full font-semibold transition-all">
                        Confirm Cancellation
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('navigate', (event) => {
            window.location.href = event.url;
        });
    });
</script>
@endpush
