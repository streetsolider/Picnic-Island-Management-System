<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Bookings Management</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage all hotel bookings and reservations
            </p>
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

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Today's Check-ins --}}
        <x-admin.card.stat
            title="Today's Check-ins"
            :value="$stats['today_check_ins']"
            icon="📥"
            color="blue" />

        {{-- Today's Check-outs --}}
        <x-admin.card.stat
            title="Today's Check-outs"
            :value="$stats['today_check_outs']"
            icon="📤"
            color="green" />

        {{-- Current Guests --}}
        <x-admin.card.stat
            title="Current Guests"
            :value="$stats['current_guests']"
            icon="👥"
            color="indigo" />

        {{-- Upcoming Bookings --}}
        <x-admin.card.stat
            title="Upcoming Bookings"
            :value="$stats['upcoming_bookings']"
            icon="📅"
            color="purple" />
    </div>

    {{-- Overall Stats --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Bookings --}}
        <x-admin.card.base>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <span class="text-2xl">📊</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_bookings']) }}</p>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Confirmed --}}
        <x-admin.card.base>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Confirmed</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['confirmed_bookings']) }}</p>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Cancelled --}}
        <x-admin.card.base>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <span class="text-2xl">❌</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cancelled</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['cancelled_bookings']) }}</p>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Total Revenue --}}
        <x-admin.card.base>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">MVR {{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </x-admin.card.base>
    </div>

    {{-- Filters --}}
    <x-admin.card.base>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            {{-- Search --}}
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Search
                </label>
                <input
                    type="text"
                    id="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Booking ref, guest name, email..."
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="filterStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status
                </label>
                <select
                    id="filterStatus"
                    wire:model.live="filterStatus"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Room Type Filter --}}
            <div>
                <label for="filterRoomType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Room Type
                </label>
                <select
                    id="filterRoomType"
                    wire:model.live="filterRoomType"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Room Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label for="filterDateFrom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Check-in From
                </label>
                <input
                    type="date"
                    id="filterDateFrom"
                    wire:model.live="filterDateFrom"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Date To --}}
            <div>
                <label for="filterDateTo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Check-out To
                </label>
                <input
                    type="date"
                    id="filterDateTo"
                    wire:model.live="filterDateTo"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>
    </x-admin.card.base>

    {{-- Bookings Table --}}
    @if($bookings->isEmpty())
        <x-admin.card.empty-state
            icon="📅"
            title="No bookings found"
            description="No bookings match your current filters.">
        </x-admin.card.empty-state>
    @else
        <x-admin.card.base padding="p-0">
            <x-admin.table.wrapper>
                <thead>
                    <tr>
                        <x-admin.table.header>Booking Ref</x-admin.table.header>
                        <x-admin.table.header>Guest</x-admin.table.header>
                        <x-admin.table.header>Room</x-admin.table.header>
                        <x-admin.table.header>Check-in</x-admin.table.header>
                        <x-admin.table.header>Check-out</x-admin.table.header>
                        <x-admin.table.header>Nights</x-admin.table.header>
                        <x-admin.table.header>Guests</x-admin.table.header>
                        <x-admin.table.header>Total</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <x-admin.table.header>Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($bookings as $booking)
                        <x-admin.table.row>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('hotel.bookings.show', $booking->id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    {{ $booking->booking_reference }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $booking->guest->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->guest->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Room {{ $booking->room->room_number }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($booking->room->room_type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $booking->check_in_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $booking->check_out_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $booking->number_of_nights }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $booking->number_of_guests }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                MVR {{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'no-show' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('hotel.bookings.show', $booking->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    View
                                </a>
                                @if($booking->status === 'confirmed')
                                    <button
                                        wire:click="openCancelModal({{ $booking->id }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                        Cancel
                                    </button>
                                @endif
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.pagination :paginator="$bookings" />
            </div>
        </x-admin.card.base>
    @endif

    {{-- Cancel Booking Modal --}}
    <x-admin.modal.form
        name="cancel-booking"
        :show="$cancellingBookingId !== null"
        title="Cancel Booking"
        submitText="Confirm Cancellation"
        submitColor="danger"
        wire:submit="confirmCancel"
        maxWidth="lg">

        @if($cancellingBooking)
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>Warning:</strong> You are about to cancel the following booking:
                </p>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p><strong>Booking Reference:</strong> {{ $cancellingBooking->booking_reference }}</p>
                    <p><strong>Guest:</strong> {{ $cancellingBooking->guest->name }}</p>
                    <p><strong>Room:</strong> {{ $cancellingBooking->room->room_number }} - {{ ucfirst($cancellingBooking->room->room_type) }}</p>
                    <p><strong>Check-in:</strong> {{ $cancellingBooking->check_in_date->format('M d, Y') }}</p>
                    <p><strong>Check-out:</strong> {{ $cancellingBooking->check_out_date->format('M d, Y') }}</p>
                </div>
            </div>
        @endif

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
