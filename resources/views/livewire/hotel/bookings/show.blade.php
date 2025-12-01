<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('hotel.bookings.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Booking Details
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Reference: {{ $booking->booking_reference }}
                </p>
            </div>
        </div>

        {{-- Status Badge --}}
        <div>
            @php
                $statusColors = [
                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                    'no-show' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                ];
            @endphp
            <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <x-admin.alert.success dismissible>
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    @if (session()->has('error'))
        <x-admin.alert.danger dismissible>
            {{ session('error') }}
        </x-admin.alert.danger>
    @endif

    {{-- Action Buttons --}}
    @if($booking->status === 'confirmed')
        <div class="flex space-x-3">
            <x-admin.button.success wire:click="markAsCompleted">
                Mark as Completed
            </x-admin.button.success>
            <x-admin.button.warning wire:click="markAsNoShow">
                Mark as No-Show
            </x-admin.button.warning>
            <x-admin.button.danger wire:click="openCancelModal">
                Cancel Booking
            </x-admin.button.danger>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content - Left Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Booking Information --}}
            <x-admin.card.base>
                <x-slot name="title">Booking Information</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Booking Reference</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->booking_reference }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Booking Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->check_in_date->format('l, F d, Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->check_out_date->format('l, F d, Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Nights</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->number_of_nights }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Guests</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->number_of_guests }}</p>
                    </div>

                    @if($booking->special_requests)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Special Requests</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card.base>

            {{-- Guest Information --}}
            <x-admin.card.base>
                <x-slot name="title">Guest Information</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->guest->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->guest->email }}</p>
                    </div>

                    @if($booking->guest->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->guest->phone }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card.base>

            {{-- Room Information --}}
            <x-admin.card.base>
                <x-slot name="title">Room Information</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Number</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->room->room_number }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Type</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($booking->room->room_type) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bed Configuration</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->room->bed_count }} {{ ucfirst($booking->room->bed_size) }}</p>
                    </div>

                    @if($booking->room->view)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">View</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($booking->room->view) }} View</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Occupancy</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->room->max_occupancy }} guests</p>
                    </div>
                </div>

                {{-- Amenities --}}
                @if($booking->room->amenities->isNotEmpty())
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Room Amenities</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->room->amenities as $amenity)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ $amenity->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-admin.card.base>

            {{-- Cancellation Information --}}
            @if($booking->status === 'cancelled')
                <x-admin.card.base>
                    <x-slot name="title">Cancellation Information</x-slot>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cancelled At</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->cancelled_at->format('M d, Y H:i') }}</p>
                        </div>

                        @if($booking->cancellation_reason)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cancellation Reason</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $booking->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </x-admin.card.base>
            @endif
        </div>

        {{-- Sidebar - Right Column --}}
        <div class="space-y-6">
            {{-- Payment Summary --}}
            <x-admin.card.base>
                <x-slot name="title">Payment Summary</x-slot>

                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Number of Nights:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->number_of_nights }}</span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Number of Rooms:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->number_of_rooms }}</span>
                    </div>

                    @if($booking->promo_code)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Promo Code:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">{{ $booking->promo_code }}</span>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Total Amount:</span>
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                MVR {{ number_format($booking->total_price, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                        <span class="font-medium {{ $booking->payment_status === 'paid' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>

                    @if($booking->payment_method)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($booking->payment_method) }}</span>
                        </div>
                    @endif
                </div>
            </x-admin.card.base>

            {{-- Quick Stats --}}
            <x-admin.card.base>
                <x-slot name="title">Quick Stats</x-slot>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Price per Night:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            MVR {{ number_format($booking->total_price / $booking->number_of_nights, 2) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Days Until Check-in:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            @php
                                $daysUntil = now()->diffInDays($booking->check_in_date, false);
                            @endphp
                            @if($daysUntil > 0)
                                {{ $daysUntil }} days
                            @elseif($daysUntil === 0)
                                Today
                            @else
                                {{ abs($daysUntil) }} days ago
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Booked:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $booking->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </x-admin.card.base>
        </div>
    </div>

    {{-- Cancel Booking Modal --}}
    <x-admin.modal.form
        name="cancel-booking"
        title="Cancel Booking"
        submitText="Confirm Cancellation"
        submitColor="danger"
        wire:submit="confirmCancel"
        maxWidth="lg">

        <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                <strong>Warning:</strong> You are about to cancel this booking. This action cannot be undone.
            </p>
        </div>

        <div>
            <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Cancellation Reason <span class="text-red-500">*</span>
            </label>
            <textarea
                id="cancellationReason"
                wire:model="cancellationReason"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Please provide a detailed reason for cancelling this booking (minimum 10 characters)"></textarea>
            @error('cancellationReason')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-admin.button.secondary wire:click="closeCancelModal">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger type="submit" wire:loading.attr="disabled" wire:target="confirmCancel">
                    <span wire:loading.remove wire:target="confirmCancel">Confirm Cancellation</span>
                    <span wire:loading wire:target="confirmCancel">Cancelling...</span>
                </x-admin.button.danger>
            </div>
        </x-slot>
    </x-admin.modal.form>
</div>
