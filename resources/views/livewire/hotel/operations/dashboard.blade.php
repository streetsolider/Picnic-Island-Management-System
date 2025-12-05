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
                        <x-admin.table.header>Check-Out Date</x-admin.table.header>
                        <x-admin.table.header>Nights Remaining</x-admin.table.header>
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
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @php
                                    $checkoutTime = $booking->check_out_date->copy()->setTime(12, 0); // Checkout at 12:00 PM
                                    $hoursRemaining = (int) now()->diffInHours($checkoutTime, false);
                                @endphp
                                @if($hoursRemaining < 0)
                                    <span class="text-red-600 dark:text-red-400 font-medium">Overdue</span>
                                @elseif($hoursRemaining < 6)
                                    <span class="text-orange-600 dark:text-orange-400 font-medium">{{ $hoursRemaining }} hours</span>
                                @elseif($hoursRemaining < 24)
                                    <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $hoursRemaining }} hours</span>
                                @else
                                    {{ $hoursRemaining }} hours
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
</div>
