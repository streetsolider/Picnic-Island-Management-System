<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('my-bookings') }}" wire:navigate
                class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-primary/80 font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to My Bookings
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Header with Status Badge --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-3xl font-display font-bold text-brand-dark">
                    Booking Details
                </h1>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $booking->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </div>
            <p class="text-gray-600">Reference: <span class="font-mono font-bold text-brand-primary">{{ $booking->booking_reference }}</span></p>
        </div>

        {{-- Main Booking Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
            {{-- Booking Details Grid --}}
            <div class="grid md:grid-cols-2 gap-8 pb-8 border-b border-gray-100">
                {{-- Hotel & Room --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Accommodation</h3>
                    <div class="space-y-2">
                        <p class="text-xl font-bold text-brand-dark">{{ $booking->hotel->name }}</p>
                        <p class="text-gray-600">{{ $booking->room->full_description }}</p>
                        <div class="flex items-center gap-1 text-brand-accent">
                            {{ str_repeat('⭐', $booking->hotel->star_rating) }}
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Room Number</p>
                            <p class="font-semibold text-brand-dark">{{ $booking->room->room_number }}</p>
                        </div>
                    </div>
                </div>

                {{-- Guest Info --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Guest Details</h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-brand-dark">{{ $booking->guest->name }}</p>
                        <p class="text-gray-600">{{ $booking->guest->email }}</p>
                        <p class="text-gray-600">{{ $booking->number_of_guests }} {{ Str::plural('Guest', $booking->number_of_guests) }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Total Rooms</p>
                            <p class="font-semibold text-brand-dark">{{ $booking->number_of_rooms }} {{ Str::plural('Room', $booking->number_of_rooms) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Check-in / Check-out --}}
            <div class="grid md:grid-cols-2 gap-8 py-8 border-b border-gray-100">
                {{-- Check-in --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Check-in</h3>
                    <div class="flex items-end gap-4">
                        <div>
                            <p class="text-4xl font-bold text-brand-dark">{{ $booking->check_in_date->format('d') }}</p>
                            <p class="text-gray-600">{{ $booking->check_in_date->format('M Y') }}</p>
                        </div>
                        <div class="mb-1">
                            <p class="text-sm text-gray-500">{{ $booking->check_in_date->format('l') }}</p>
                            <p class="text-xs text-brand-primary font-medium">⏰ 2:00 PM</p>
                        </div>
                    </div>
                </div>

                {{-- Check-out --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Check-out</h3>
                    <div class="flex items-end gap-4">
                        <div>
                            <p class="text-4xl font-bold text-brand-dark">{{ $booking->check_out_date->format('d') }}</p>
                            <p class="text-gray-600">{{ $booking->check_out_date->format('M Y') }}</p>
                        </div>
                        <div class="mb-1">
                            <p class="text-sm text-gray-500">{{ $booking->check_out_date->format('l') }}</p>
                            <p class="text-xs text-brand-primary font-medium">
                                @if($booking->hasApprovedLateCheckoutRequest())
                                    ⏰ {{ $booking->lateCheckoutRequest->formatted_requested_time }} (Late Checkout)
                                @else
                                    ⏰ {{ $booking->hotel->formatted_checkout_time }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Special Requests --}}
            @if ($booking->special_requests)
                <div class="py-6 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Special Requests</h3>
                    <p class="text-gray-600">{{ $booking->special_requests }}</p>
                </div>
            @endif

            {{-- Total Price --}}
            <div class="pt-6 mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-gray-600">{{ $booking->number_of_nights }} {{ Str::plural('Night', $booking->number_of_nights) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-brand-primary">MVR {{ number_format($booking->total_price, 2) }}</p>
                        <p class="text-sm text-green-600 font-semibold">✓ Paid</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Late Checkout Section --}}
        @if(in_array($booking->status, ['confirmed', 'checked_in']))
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-brand-dark mb-2">Late Checkout</h3>

                        @if($booking->hasApprovedLateCheckoutRequest())
                            {{-- Approved Request --}}
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-800 mb-1">Late Checkout Approved!</p>
                                        <p class="text-sm text-green-700">
                                            Your checkout time has been extended to <strong>{{ $booking->lateCheckoutRequest->formatted_requested_time }}</strong>
                                            on {{ $booking->check_out_date->format('M d, Y') }}.
                                        </p>
                                        @if($booking->lateCheckoutRequest->manager_notes)
                                            <div class="mt-2 pt-2 border-t border-green-200">
                                                <p class="text-xs text-green-600 font-semibold">Manager's Note:</p>
                                                <p class="text-sm text-green-700">{{ $booking->lateCheckoutRequest->manager_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @elseif($booking->hasPendingLateCheckoutRequest())
                            {{-- Pending Request --}}
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4" x-data="{ showCancelModal: false }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1">
                                        <svg class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-yellow-800 mb-1">Request Pending</p>
                                            <p class="text-sm text-yellow-700">
                                                You requested to checkout at <strong>{{ $booking->lateCheckoutRequest->formatted_requested_time }}</strong>.
                                                The hotel manager is reviewing your request.
                                            </p>
                                            @if($booking->lateCheckoutRequest->guest_notes)
                                                <div class="mt-2 pt-2 border-t border-yellow-200">
                                                    <p class="text-xs text-yellow-600 font-semibold">Your Note:</p>
                                                    <p class="text-sm text-yellow-700">{{ $booking->lateCheckoutRequest->guest_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <button @click="showCancelModal = true"
                                            class="flex-shrink-0 text-xs px-3 py-1.5 bg-white border border-yellow-300 text-yellow-700 rounded-lg hover:bg-yellow-50 transition-colors font-semibold">
                                        Cancel Request
                                    </button>
                                </div>

                                {{-- Cancel Request Confirmation Modal --}}
                                <div x-show="showCancelModal"
                                    x-cloak
                                    class="fixed inset-0 z-50 overflow-y-auto"
                                    aria-labelledby="modal-title"
                                    role="dialog"
                                    aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        {{-- Background overlay --}}
                                        <div x-show="showCancelModal"
                                            x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="ease-in duration-200"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-0"
                                            @click="showCancelModal = false"
                                            aria-hidden="true"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        {{-- Modal panel --}}
                                        <div x-show="showCancelModal"
                                            x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave="ease-in duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-10">

                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                                        Cancel Late Checkout Request
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            Are you sure you want to cancel your late checkout request? Your checkout time will revert to the standard time of {{ $booking->hotel->formatted_checkout_time }}.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                                <button type="button"
                                                    wire:click="cancelLateCheckoutRequest"
                                                    @click="showCancelModal = false"
                                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                    Yes, Cancel Request
                                                </button>
                                                <button type="button"
                                                    @click="showCancelModal = false"
                                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                    Keep Request
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($booking->lateCheckoutRequest && $booking->lateCheckoutRequest->status === 'rejected')
                            {{-- Rejected Request --}}
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-red-800 mb-1">Request Declined</p>
                                        <p class="text-sm text-red-700">
                                            Your late checkout request for {{ $booking->lateCheckoutRequest->formatted_requested_time }} was declined.
                                        </p>
                                        @if($booking->lateCheckoutRequest->manager_notes)
                                            <div class="mt-2 pt-2 border-t border-red-200">
                                                <p class="text-xs text-red-600 font-semibold">Reason:</p>
                                                <p class="text-sm text-red-700">{{ $booking->lateCheckoutRequest->manager_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($booking->canRequestLateCheckout())
                                <button wire:click="openLateCheckoutModal"
                                        class="px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                                    Request Again
                                </button>
                            @endif

                        @else
                            {{-- No Request Yet --}}
                            <p class="text-gray-600 mb-4">
                                Need more time? Request a late checkout for FREE! Maximum checkout time is 6:00 PM.
                                <br>
                                <span class="text-sm text-gray-500">Current checkout: {{ $booking->hotel->formatted_checkout_time }}</span>
                            </p>
                            @if($booking->canRequestLateCheckout())
                                <button wire:click="openLateCheckoutModal"
                                        class="px-6 py-3 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                                    Request Late Checkout
                                </button>
                            @else
                                <p class="text-sm text-gray-500 italic">Late checkout requests are not available at this time.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('my-bookings') }}" wire:navigate
                class="flex-1 bg-white hover:bg-gray-50 text-brand-dark border border-gray-200 px-6 py-4 rounded-xl font-semibold text-center transition-all">
                Back to My Bookings
            </a>
            @if(in_array($booking->status, ['confirmed', 'checked_in']))
                <a href="{{ route('ferry-tickets.browse') }}" wire:navigate
                    class="flex-1 bg-gradient-to-r from-brand-secondary to-brand-primary hover:opacity-90 text-white px-6 py-4 rounded-xl font-semibold text-center transition-all transform hover:scale-105 shadow-lg">
                    Book Ferry Tickets
                </a>
            @endif
        </div>
    </div>

    {{-- Late Checkout Request Modal --}}
    @if($showLateCheckoutModal)
    <div x-data="{ show: @entangle('showLateCheckoutModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-0"
                 @click="show = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal panel --}}
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-10">

                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-display font-bold text-brand-dark" id="modal-title">
                            Request Late Checkout
                        </h3>
                        <button wire:click="closeLateCheckoutModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="submitLateCheckoutRequest">
                    <div class="space-y-4">
                        {{-- Info Alert --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Late Checkout Information</p>
                                    <ul class="list-disc list-inside space-y-0.5">
                                        <li>Late checkout is <strong>FREE</strong></li>
                                        <li>Maximum checkout time: 6:00 PM</li>
                                        <li>Subject to hotel manager approval</li>
                                        <li>Current checkout: {{ $booking->hotel->formatted_checkout_time }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Booking Details --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Booking Details</h4>
                            <dl class="grid grid-cols-2 gap-2 text-sm">
                                <dt class="text-gray-600">Hotel:</dt>
                                <dd class="text-gray-900 font-medium">{{ $booking->hotel->name }}</dd>

                                <dt class="text-gray-600">Room:</dt>
                                <dd class="text-gray-900 font-medium">{{ $booking->room->room_number }}</dd>

                                <dt class="text-gray-600">Checkout Date:</dt>
                                <dd class="text-gray-900 font-medium">{{ $booking->check_out_date->format('M d, Y') }}</dd>
                            </dl>
                        </div>

                        {{-- Requested Time --}}
                        <div>
                            <label for="requestedCheckoutTime" class="block text-sm font-medium text-gray-700 mb-2">
                                Requested Checkout Time <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="time"
                                id="requestedCheckoutTime"
                                wire:model="requestedCheckoutTime"
                                min="{{ \Carbon\Carbon::parse($booking->hotel->default_checkout_time)->format('H:i') }}"
                                max="18:00"
                                required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm"
                            />
                            @error('requestedCheckoutTime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Must be after {{ $booking->hotel->formatted_checkout_time }} and before 6:00 PM
                            </p>
                        </div>

                        {{-- Guest Notes --}}
                        <div>
                            <label for="guestNotes" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Request (Optional)
                            </label>
                            <textarea
                                id="guestNotes"
                                wire:model="guestNotes"
                                rows="3"
                                maxlength="500"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm"
                                placeholder="Let the hotel know why you need late checkout..."></textarea>
                            @error('guestNotes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                {{ strlen($guestNotes) }}/500 characters
                            </p>
                        </div>
                    </div>

                    {{-- Modal Actions --}}
                    <div class="mt-6 flex gap-3">
                        <button type="button"
                                wire:click="closeLateCheckoutModal"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
