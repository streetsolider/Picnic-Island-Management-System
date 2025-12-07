<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Bookings Calendar
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(!$assignedService)
            <!-- No Service Assigned -->
            <x-admin.card.empty-state
                icon="🏖️"
                title="No Service Assigned"
                description="You don't have any beach service assigned to you yet. Please contact your administrator.">
            </x-admin.card.empty-state>
        @else
            <!-- Date Selector -->
            <x-admin.card.base>
                <div class="flex items-center justify-between">
                    <x-admin.button.secondary wire:click="changeDate('prev')">
                        ← Previous Day
                    </x-admin.button.secondary>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $selectedDateFormatted }}</div>
                        @if($isToday)
                            <span class="text-sm text-green-600 dark:text-green-400 font-semibold">Today</span>
                        @endif
                        <div class="mt-2">
                            <input wire:model.live="selectedDate" type="date" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if(!$isToday)
                            <x-admin.button.secondary wire:click="goToToday">
                                Today
                            </x-admin.button.secondary>
                        @endif
                        <x-admin.button.secondary wire:click="changeDate('next')">
                            Next Day →
                        </x-admin.button.secondary>
                    </div>
                </div>
            </x-admin.card.base>

            <!-- Service Info -->
            <x-admin.card.base>
                <div class="flex items-center gap-3">
                    <span class="text-3xl">{{ $assignedService->category->icon ?? '🏖️' }}</span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $assignedService->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $assignedService->operating_hours }} • Capacity: {{ $assignedService->concurrent_capacity }}/{{ $assignedService->capacity_limit }}</p>
                    </div>
                </div>
            </x-admin.card.base>

            <!-- Timeline View -->
            <x-admin.card.base>
                <x-slot name="title">Timeline View</x-slot>

                @if(empty($timeSlots))
                    <x-admin.card.empty-state
                        icon="⏰"
                        title="No Operating Hours Set"
                        description="Please configure opening and closing times in Service Settings.">
                    </x-admin.card.empty-state>
                @else
                    <!-- Legend -->
                    <div class="mb-6 flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Confirmed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">✓ Redeemed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Cancelled</span>
                        </div>
                    </div>

                    <!-- Timeline Slots -->
                    <div class="space-y-2">
                        @foreach($timeSlots as $slot)
                            <div class="flex items-start border-b border-gray-200 dark:border-gray-700 pb-3">
                                <!-- Time Label -->
                                <div class="w-24 flex-shrink-0 font-semibold text-gray-700 dark:text-gray-300 pt-2">
                                    {{ $slot['time'] }}
                                </div>

                                <!-- Bookings for this slot -->
                                <div class="flex-1 flex flex-wrap gap-2">
                                    @forelse($slot['bookings'] as $booking)
                                        <div
                                            wire:click="showBookingDetails({{ $booking->id }})"
                                            class="px-3 py-2 rounded-lg cursor-pointer border transition hover:shadow-md
                                                @if($booking->isRedeemed()) bg-green-100 dark:bg-green-900 border-green-300 dark:border-green-700
                                                @elseif($booking->isCancelled()) bg-red-100 dark:bg-red-900 border-red-300 dark:border-red-700
                                                @else bg-blue-100 dark:bg-blue-900 border-blue-300 dark:border-blue-700
                                                @endif">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $booking->guest->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                                    </div>
                                                </div>
                                                @if($booking->isRedeemed())
                                                    <span class="text-green-600 dark:text-green-400 font-bold text-lg">✓</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-gray-400 dark:text-gray-500 text-sm italic pt-2">No bookings</span>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-admin.card.base>
        @endif
    </div>
</div>

<!-- Booking Details Modal -->
@if($showBookingModal && $selectedBooking)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="closeModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Booking Details</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Status Banner -->
                <div class="mb-4 p-4 rounded-lg {{ $selectedBooking->isRedeemed() ? 'bg-green-100 dark:bg-green-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                    <h4 class="text-lg font-bold {{ $selectedBooking->isRedeemed() ? 'text-green-900 dark:text-green-100' : 'text-blue-900 dark:text-blue-100' }}">
                        {{ $selectedBooking->isRedeemed() ? '✓ Redeemed' : '📋 Confirmed' }}
                    </h4>
                    @if($selectedBooking->isRedeemed())
                        <p class="text-sm {{ $selectedBooking->isRedeemed() ? 'text-green-700 dark:text-green-200' : 'text-blue-700 dark:text-blue-200' }}">
                            {{ $selectedBooking->redeemed_at->format('M j, Y \a\t g:i A') }}
                            @if($selectedBooking->redeemedByStaff)
                                by {{ $selectedBooking->redeemedByStaff->name }}
                            @endif
                        </p>
                    @endif
                </div>

                <!-- Booking Info -->
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Reference</label>
                            <p class="text-lg font-mono font-bold text-gray-900 dark:text-gray-100">{{ $selectedBooking->booking_reference }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Date</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedBooking->booking_date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Guest</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedBooking->guest->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedBooking->guest->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Time</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($selectedBooking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($selectedBooking->end_time)->format('g:i A') }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Price</label>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">MVR {{ number_format($selectedBooking->total_price, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Payment</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($selectedBooking->payment_status) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end">
                    <x-admin.button.secondary wire:click="closeModal">
                        Close
                    </x-admin.button.secondary>
                </div>
            </div>
        </div>
    </div>
@endif
