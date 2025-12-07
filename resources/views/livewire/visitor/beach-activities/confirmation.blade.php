<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Success Message --}}
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500 rounded-3xl p-8 md:p-12 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-display font-bold text-green-900 mb-3">
                Booking Confirmed!
            </h1>
            <p class="text-lg text-green-700 mb-8">
                Your beach activity has been successfully booked. Please save your booking reference.
            </p>

            {{-- Booking Reference --}}
            <div class="bg-white rounded-2xl p-8 inline-block shadow-lg">
                <p class="text-sm text-gray-600 mb-2">Booking Reference</p>
                <p class="text-5xl font-mono font-bold text-brand-primary tracking-wider">
                    {{ $booking->booking_reference }}
                </p>
            </div>
        </div>

        {{-- Booking Details --}}
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                <h2 class="text-2xl font-display font-bold text-white">Booking Details</h2>
            </div>
            <div class="p-8">
                <div class="space-y-8">
                    {{-- Activity Info --}}
                    <div class="flex items-start gap-6 pb-8 border-b border-gray-200">
                        <span class="text-6xl">{{ $booking->service->category->icon }}</span>
                        <div class="flex-1">
                            <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">
                                {{ $booking->service->name }}
                            </h3>
                            <p class="text-gray-600">
                                {{ $booking->service->category->name }}
                            </p>
                        </div>
                    </div>

                    {{-- Date & Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <p class="text-sm font-semibold text-gray-500 mb-2">Date</p>
                            <p class="text-xl font-bold text-brand-dark">
                                {{ $booking->booking_date->format('l, F j, Y') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">
                            <p class="text-sm font-semibold text-gray-500 mb-2">Time</p>
                            <p class="text-xl font-bold text-brand-dark">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                        </div>

                        @if($booking->duration_hours)
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <p class="text-sm font-semibold text-gray-500 mb-2">Duration</p>
                                <p class="text-xl font-bold text-brand-dark">
                                    {{ $booking->duration_hours }} {{ $booking->duration_hours == 1 ? 'hour' : 'hours' }}
                                </p>
                            </div>
                        @endif

                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                            <p class="text-sm font-semibold text-gray-600 mb-2">Total Amount Paid</p>
                            <p class="text-2xl font-bold text-green-600">
                                MVR {{ number_format($booking->total_price, 2) }}
                            </p>
                        </div>
                    </div>

                    {{-- Guest Info --}}
                    <div class="pt-8 border-t border-gray-200">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Guest Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Name</p>
                                <p class="font-bold text-brand-dark">{{ $booking->guest->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="font-bold text-brand-dark">{{ $booking->guest->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="pt-8 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">Status</span>
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Important Information --}}
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-6">
                <h2 class="text-2xl font-display font-bold text-white">Important Information</h2>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 bg-brand-primary/5 rounded-2xl">
                        <svg class="w-6 h-6 text-brand-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-700">Please arrive at least <strong>10 minutes before</strong> your scheduled activity time.</p>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-brand-primary/5 rounded-2xl">
                        <svg class="w-6 h-6 text-brand-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-700">Present your <strong>booking reference</strong> ({{ $booking->booking_reference }}) to the beach staff for validation.</p>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-brand-primary/5 rounded-2xl">
                        <svg class="w-6 h-6 text-brand-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-700">Cancellations must be made at least <strong>24 hours in advance</strong> for a full refund.</p>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-brand-primary/5 rounded-2xl">
                        <svg class="w-6 h-6 text-brand-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-700">Activities are subject to weather conditions and may be rescheduled for safety.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('visitor.beach-activities.my-bookings') }}" wire:navigate
                class="flex-1 bg-brand-primary hover:bg-brand-primary/90 text-white py-4 rounded-full font-semibold text-center transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                View My Bookings
            </a>

            <a href="{{ route('visitor.beach-activities.browse') }}" wire:navigate
                class="flex-1 bg-white hover:bg-gray-50 text-brand-primary py-4 rounded-full font-semibold text-center border-2 border-brand-primary transition-all">
                Book Another Activity
            </a>
        </div>
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
