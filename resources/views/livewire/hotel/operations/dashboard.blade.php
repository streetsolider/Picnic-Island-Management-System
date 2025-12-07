<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daily Operations</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Manage check-ins, check-outs, and in-house guests for {{ $hotel->name }}
        </p>
    </div>

    {{-- Success Messages --}}
    @if (session()->has('success'))
        <x-admin.alert.success dismissible class="mb-4">
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        {{-- Today's Arrivals --}}
        <x-admin.card.stat
            title="Today's Arrivals"
            :value="$todayArrivals->count()"
            icon="📥"
            color="blue"
        />

        {{-- Today's Departures --}}
        <x-admin.card.stat
            title="Today's Departures"
            :value="$todayDepartures->count()"
            icon="📤"
            color="purple"
        />

        {{-- In-House Guests --}}
        <x-admin.card.stat
            title="In-House Guests"
            :value="$inHouseGuests->count()"
            icon="🏨"
            color="green"
        />

        {{-- Occupancy Rate --}}
        <x-admin.card.stat
            title="Occupancy Rate"
            :value="$occupancyStats['rate'] . '%'"
            :subtitle="$occupancyStats['occupied'] . ' / ' . $occupancyStats['total'] . ' rooms'"
            icon="📊"
            color="indigo"
        />
    </div>

    {{-- Pending Late Checkout Requests Section --}}
    @if($pendingLateCheckoutRequests->isNotEmpty())
        <x-admin.card.base class="mb-6 border-2 border-yellow-200 dark:border-yellow-800">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">⏰</span>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Late Checkout Requests</h3>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full text-sm font-semibold">
                        {{ $pendingLateCheckoutRequests->count() }} pending
                    </span>
                </div>
            </x-slot>

            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest Name</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Checkout Date</x-admin.table.header>
                        <x-admin.table.header>Requested Time</x-admin.table.header>
                        <x-admin.table.header>Guest Notes</x-admin.table.header>
                        <x-admin.table.header>Next Booking</x-admin.table.header>
                        <x-admin.table.header class="text-right">Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingLateCheckoutRequests as $request)
                        <x-admin.table.row>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $request->booking->booking_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $request->booking->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                Room {{ $request->booking->room->room_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $request->booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $request->formatted_requested_time }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs">
                                @if($request->guest_notes)
                                    <span class="line-clamp-2" title="{{ $request->guest_notes }}">
                                        {{ $request->guest_notes }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">No notes</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($request->has_next_booking)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 rounded text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Conflict
                                    </span>
                                @else
                                    <span class="text-green-600 dark:text-green-400 text-xs">✓ Available</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.button.success
                                        size="sm"
                                        wire:click="openApproveLateCheckoutModal({{ $request->id }})"
                                    >
                                        Approve
                                    </x-admin.button.success>
                                    <x-admin.button.danger
                                        size="sm"
                                        wire:click="openRejectLateCheckoutModal({{ $request->id }})"
                                    >
                                        Reject
                                    </x-admin.button.danger>
                                </div>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </x-admin.card.base>
    @endif

    {{-- Today's Arrivals Section --}}
    <x-admin.card.base class="mb-6">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Today's Arrivals</h3>
                <span class="text-sm text-gray-500">{{ $todayArrivals->count() }} guests</span>
            </div>
        </x-slot>

        @if($todayArrivals->isEmpty())
            <x-admin.card.empty-state
                icon="📥"
                title="No arrivals today"
                description="There are no guests scheduled to check in today."
            />
        @else
            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest Name</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Check-In Date</x-admin.table.header>
                        <x-admin.table.header>Check-Out Date</x-admin.table.header>
                        <x-admin.table.header>Guests</x-admin.table.header>
                        <x-admin.table.header>Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayArrivals as $booking)
                        <x-admin.table.row>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $booking->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                Room {{ $booking->room->room_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_in_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->number_of_guests }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <x-admin.button.primary
                                    size="sm"
                                    wire:click="openCheckInModal({{ $booking->id }})"
                                >
                                    Check In
                                </x-admin.button.primary>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        @endif
    </x-admin.card.base>

    {{-- Today's Departures Section --}}
    <x-admin.card.base class="mb-6">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Today's Departures</h3>
                <span class="text-sm text-gray-500">{{ $todayDepartures->count() }} guests</span>
            </div>
        </x-slot>

        @if($todayDepartures->isEmpty())
            <x-admin.card.empty-state
                icon="📤"
                title="No departures today"
                description="There are no guests scheduled to check out today."
            />
        @else
            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest Name</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Checked In</x-admin.table.header>
                        <x-admin.table.header>Check-Out Date</x-admin.table.header>
                        <x-admin.table.header>Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayDepartures as $booking)
                        <x-admin.table.row>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $booking->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                Room {{ $booking->room->room_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->checked_in_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <x-admin.button.warning
                                    size="sm"
                                    wire:click="openCheckOutModal({{ $booking->id }})"
                                >
                                    Check Out
                                </x-admin.button.warning>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        @endif
    </x-admin.card.base>

    {{-- In-House Guests Section --}}
    <x-admin.card.base class="mb-6">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">In-House Guests</h3>
                <span class="text-sm text-gray-500">{{ $inHouseGuests->count() }} guests</span>
            </div>
        </x-slot>

        @if($inHouseGuests->isEmpty())
            <x-admin.card.empty-state
                icon="🏨"
                title="No in-house guests"
                description="There are currently no guests checked in."
            />
        @else
            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest Name</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Checked In</x-admin.table.header>
                        <x-admin.table.header>Checkout Time</x-admin.table.header>
                        <x-admin.table.header>Time Remaining</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inHouseGuests as $booking)
                        <x-admin.table.row>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $booking->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                Room {{ $booking->room->room_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->checked_in_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $effectiveCheckoutTime = $booking->getEffectiveCheckoutTime();
                                @endphp
                                <div>
                                    <div class="text-gray-900 dark:text-white font-medium">
                                        {{ $effectiveCheckoutTime->format('M d, Y') }}
                                    </div>
                                    <div class="text-gray-600 dark:text-gray-400 text-xs">
                                        {{ $effectiveCheckoutTime->format('g:i A') }}
                                        @if($booking->hasApprovedLateCheckoutRequest())
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded text-xs font-semibold ml-1">
                                                Late Checkout
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $effectiveCheckoutTime = $booking->getEffectiveCheckoutTime();
                                    $diff = now()->diff($effectiveCheckoutTime);
                                    $isPast = now()->greaterThan($effectiveCheckoutTime);
                                    $totalHours = (int) now()->diffInHours($effectiveCheckoutTime, false);
                                @endphp
                                @if($isPast)
                                    <span class="text-red-600 dark:text-red-400 font-semibold">Overdue</span>
                                    <div class="text-xs text-red-500 dark:text-red-400">{{ abs($totalHours) }}h past</div>
                                @else
                                    @if($totalHours < 6)
                                        <span class="text-orange-600 dark:text-orange-400 font-semibold">
                                    @elseif($totalHours < 24)
                                        <span class="text-yellow-600 dark:text-yellow-400 font-semibold">
                                    @else
                                        <span class="text-gray-900 dark:text-white font-medium">
                                    @endif
                                        @if($diff->days > 0)
                                            {{ $diff->days }}d {{ $diff->h }}h
                                        @else
                                            {{ $diff->h }}h {{ $diff->i }}m
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        @endif
    </x-admin.card.base>

    {{-- Upcoming Bookings Section --}}
    <x-admin.card.base>
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Arrivals (Next 7 Days)</h3>
                <span class="text-sm text-gray-500">{{ $upcomingBookings->count() }} bookings</span>
            </div>
        </x-slot>

        @if($upcomingBookings->isEmpty())
            <x-admin.card.empty-state
                icon="📅"
                title="No upcoming arrivals"
                description="There are no confirmed bookings in the next 7 days."
            />
        @else
            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest Name</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Check-In Date</x-admin.table.header>
                        <x-admin.table.header>Check-Out Date</x-admin.table.header>
                        <x-admin.table.header>Days Until</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingBookings as $booking)
                        <x-admin.table.row>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $booking->guest->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                Room {{ $booking->room->room_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_in_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ now()->diffInDays($booking->check_in_date) }} days
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        @endif
    </x-admin.card.base>

    {{-- Check-In Modal --}}
    <x-overlays.modal name="check-in" maxWidth="2xl" focusable>
        <form wire:submit="confirmCheckIn">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Check In Guest</h3>
            </div>

            <div class="px-6 py-4">
        @if($selectedBooking)
            <div class="space-y-4">
                {{-- Guest Information --}}
                <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Guest Information</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Name:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->guest->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Booking Ref:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->booking_reference }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Room:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->room->room_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Guests:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->number_of_guests }}</span>
                        </div>
                    </div>
                </div>

                {{-- Check-in Notes --}}
                <div>
                    <label for="checkInNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Check-In Notes (Optional)
                    </label>
                    <textarea
                        id="checkInNotes"
                        wire:model="checkInNotes"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Any special requests, early check-in notes, or observations..."
                    ></textarea>
                    @error('checkInNotes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <x-admin.button.secondary type="button" x-on:click="$dispatch('close-modal', 'check-in')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="confirmCheckIn">
                    Confirm Check-In
                </x-admin.button.primary>
            </div>
        </form>
    </x-overlays.modal>

    {{-- Check-Out Modal --}}
    <x-overlays.modal name="check-out" maxWidth="2xl" focusable>
        <form wire:submit="confirmCheckOut">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Check Out Guest</h3>
            </div>

            <div class="px-6 py-4">
        @if($selectedBooking)
            <div class="space-y-4">
                {{-- Guest Information --}}
                <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Guest Information</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Name:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->guest->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Booking Ref:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->booking_reference }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Room:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->room->room_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Checked In:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedBooking->checked_in_at ? $selectedBooking->checked_in_at->format('M d, H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Check-out Notes --}}
                <div>
                    <label for="checkOutNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Check-Out Notes (Optional)
                    </label>
                    <textarea
                        id="checkOutNotes"
                        wire:model="checkOutNotes"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Any observations, feedback, or issues to note..."
                    ></textarea>
                    @error('checkOutNotes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <x-admin.button.secondary type="button" x-on:click="$dispatch('close-modal', 'check-out')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.warning type="submit" wire:loading.attr="disabled" wire:target="confirmCheckOut">
                    Confirm Check-Out
                </x-admin.button.warning>
            </div>
        </form>
    </x-overlays.modal>

    {{-- Approve Late Checkout Modal --}}
    <x-overlays.modal name="approve-late-checkout" maxWidth="2xl" focusable>
        <form wire:submit="approveLateCheckout">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Approve Late Checkout Request</h3>
            </div>

            <div class="px-6 py-4">
        @if($selectedLateCheckoutRequest)
            <div class="space-y-4">
                {{-- Request Information --}}
                <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Request Details</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Guest:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->guest->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Room:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->room->room_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Checkout Date:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->check_out_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Requested Time:</span>
                            <span class="ml-2 font-semibold text-green-700 dark:text-green-400">{{ $selectedLateCheckoutRequest->formatted_requested_time }}</span>
                        </div>
                    </div>

                    @if($selectedLateCheckoutRequest->guest_notes)
                        <div class="mt-3 pt-3 border-t border-green-200 dark:border-green-800">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">Guest Notes:</span>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->guest_notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Next Booking Warning --}}
                @if($selectedLateCheckoutRequest->has_next_booking)
                    <x-admin.alert.warning>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold">Next Booking Conflict</p>
                                <p class="text-sm mt-1">There is another booking for this room on the same day. Please coordinate the transition carefully.</p>
                            </div>
                        </div>
                    </x-admin.alert.warning>
                @endif

                {{-- Manager Notes --}}
                <div>
                    <label for="lateCheckoutManagerNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Manager Notes (Optional)
                    </label>
                    <textarea
                        id="lateCheckoutManagerNotes"
                        wire:model="lateCheckoutManagerNotes"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500"
                        placeholder="Add any notes or conditions for the guest..."
                    ></textarea>
                    @error('lateCheckoutManagerNotes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <x-admin.button.secondary type="button" x-on:click="$dispatch('close-modal', 'approve-late-checkout')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.success type="submit" wire:loading.attr="disabled" wire:target="approveLateCheckout">
                    Approve Request
                </x-admin.button.success>
            </div>
        </form>
    </x-overlays.modal>

    {{-- Reject Late Checkout Modal --}}
    <x-overlays.modal name="reject-late-checkout" maxWidth="2xl" focusable>
        <form wire:submit="rejectLateCheckout">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Reject Late Checkout Request</h3>
            </div>

            <div class="px-6 py-4">
        @if($selectedLateCheckoutRequest)
            <div class="space-y-4">
                {{-- Request Information --}}
                <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Request Details</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Guest:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->guest->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Room:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->room->room_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Checkout Date:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->booking->check_out_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Requested Time:</span>
                            <span class="ml-2 font-semibold text-red-700 dark:text-red-400">{{ $selectedLateCheckoutRequest->formatted_requested_time }}</span>
                        </div>
                    </div>

                    @if($selectedLateCheckoutRequest->guest_notes)
                        <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-800">
                            <span class="text-gray-600 dark:text-gray-400 text-sm">Guest Notes:</span>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedLateCheckoutRequest->guest_notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Rejection Reason (Required) --}}
                <div>
                    <label for="lateCheckoutManagerNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Reason for Rejection <span class="text-red-600">*</span>
                    </label>
                    <textarea
                        id="lateCheckoutManagerNotes"
                        wire:model="lateCheckoutManagerNotes"
                        rows="4"
                        required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500"
                        placeholder="Please provide a clear reason for rejecting this request (required)..."
                    ></textarea>
                    @error('lateCheckoutManagerNotes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">The guest will see this message.</p>
                </div>

                <x-admin.alert.danger>
                    This will notify the guest that their late checkout request has been denied. Please provide a clear explanation.
                </x-admin.alert.danger>
            </div>
        @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <x-admin.button.secondary type="button" x-on:click="$dispatch('close-modal', 'reject-late-checkout')">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger type="submit" wire:loading.attr="disabled" wire:target="rejectLateCheckout">
                    Reject Request
                </x-admin.button.danger>
            </div>
        </form>
    </x-overlays.modal>
</div>
