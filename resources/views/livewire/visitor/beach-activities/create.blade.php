<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Complete <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Booking</span>
            </h1>
            <p class="text-gray-600">Review your booking details and confirm</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-start gap-3"
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

        <form wire:submit="confirmBooking" class="space-y-6">
            {{-- Activity Summary --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                    <h2 class="text-2xl font-display font-bold text-white">Activity Details</h2>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        {{-- Service Info --}}
                        <div class="flex items-start gap-4 pb-6 border-b border-gray-200">
                            <span class="text-5xl">{{ $service->category->icon }}</span>
                            <div class="flex-1">
                                <h3 class="text-2xl font-display font-bold text-brand-dark">{{ $service->name }}</h3>
                                <p class="text-gray-600 mt-1">{{ $service->category->name }}</p>
                            </div>
                        </div>

                        {{-- Booking Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Date</p>
                                <p class="text-lg font-bold text-brand-dark">
                                    {{ $bookingDate->format('l, F j, Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Time</p>
                                <p class="text-lg font-bold text-brand-dark">
                                    {{ \Carbon\Carbon::parse($startTime)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($endTime)->format('g:i A') }}
                                </p>
                            </div>
                        </div>

                        {{-- Pricing Breakdown --}}
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-gray-700">{{ $pricing['breakdown'] }}</span>
                                <span class="text-xl font-bold text-gray-900">
                                    MVR {{ number_format($pricing['total_price'], 2) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-green-300">
                                <span class="text-lg font-bold text-gray-900">Total Amount</span>
                                <span class="text-3xl font-bold text-green-600">
                                    MVR {{ number_format($pricing['total_price'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Guest Information --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                    <h2 class="text-2xl font-display font-bold text-white">Guest Information</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 mb-1">Full Name</p>
                            <p class="text-lg font-bold text-brand-dark">{{ $guest->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-500 mb-1">Email</p>
                            <p class="text-lg font-bold text-brand-dark">{{ $guest->email }}</p>
                        </div>

                        @if($guest->phone)
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Phone</p>
                                <p class="text-lg font-bold text-brand-dark">{{ $guest->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Hotel Booking Info --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                    <h2 class="text-2xl font-display font-bold text-white">Associated Hotel Booking</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 mb-1">Booking Reference</p>
                            <p class="text-lg font-mono font-bold text-brand-primary">{{ $hotelBooking->booking_reference }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-500 mb-1">Hotel Stay Dates</p>
                            <p class="text-lg font-bold text-brand-dark">
                                {{ $hotelBooking->check_in_date->format('M j') }} - {{ $hotelBooking->check_out_date->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                    <h2 class="text-2xl font-display font-bold text-white">Payment Method</h2>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <label class="flex items-start p-6 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-brand-primary hover:bg-brand-primary/5 transition-all">
                            <input
                                wire:model="paymentMethod"
                                type="radio"
                                value="card"
                                class="mt-1 text-brand-primary focus:ring-brand-primary">
                            <div class="ml-4 flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="font-bold text-gray-900">Credit / Debit Card</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Pay securely with your card</p>
                            </div>
                        </label>

                        <label class="flex items-start p-6 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-brand-primary hover:bg-brand-primary/5 transition-all">
                            <input
                                wire:model="paymentMethod"
                                type="radio"
                                value="room_charge"
                                class="mt-1 text-brand-primary focus:ring-brand-primary">
                            <div class="ml-4 flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="font-bold text-gray-900">Charge to Room</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Add to your hotel bill</p>
                            </div>
                        </label>

                        @error('paymentMethod')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Terms & Conditions --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <label class="flex items-start cursor-pointer group">
                        <input
                            wire:model="termsAccepted"
                            type="checkbox"
                            class="mt-1 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        <span class="ml-4 text-gray-700 group-hover:text-brand-dark transition-colors">
                            I agree to the <a href="#" class="text-brand-primary hover:text-brand-primary/80 font-semibold">terms and conditions</a> and
                            <a href="#" class="text-brand-primary hover:text-brand-primary/80 font-semibold">cancellation policy</a>.
                            I understand that this booking is subject to availability and confirmation.
                        </span>
                    </label>

                    @error('termsAccepted')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <button
                    type="button"
                    wire:click="$dispatch('navigate', { url: '{{ route('visitor.beach-activities.details', $service) }}' })"
                    class="w-full sm:w-auto text-gray-600 hover:text-brand-primary font-semibold py-3 px-6 border-2 border-gray-300 rounded-full hover:border-brand-primary transition-all">
                    ← Back to Activity
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-4 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                    Confirm & Pay MVR {{ number_format($pricing['total_price'], 2) }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('navigate', (event) => {
            window.location.href = event.url;
        });
    });
</script>
@endpush
