<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(!$assignedService)
            <!-- No Service Assigned -->
            <div class="max-w-2xl mx-auto mt-12">
                <x-admin.card.empty-state
                    icon="🏖️"
                    title="No Service Assigned"
                    description="You don't have any beach service assigned to you yet. Please contact your administrator.">
                </x-admin.card.empty-state>
            </div>
        @else
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Bookings Calendar</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and manage your daily bookings schedule</p>
            </div>

            <!-- Service Selector Card -->
            @if($allServices->count() > 1)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900 dark:to-indigo-800 rounded-xl flex items-center justify-center">
                                <span class="text-3xl">{{ $assignedService->category->icon ?? '🏖️' }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Service
                            </label>
                            <select wire:model.live="selectedServiceId"
                                class="block w-full text-lg font-semibold rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 transition">
                                @foreach($allServices as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-shrink-0 text-right hidden lg:block">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Operating Hours</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $assignedService->operating_hours ?? 'Not set' }}</div>
                        </div>
                        <div class="flex-shrink-0 text-right hidden lg:block">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Capacity</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $assignedService->concurrent_capacity }} / {{ $assignedService->capacity_limit }}</div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Single Service Banner -->
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 dark:from-indigo-700 dark:to-indigo-800 rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-white/20 rounded-xl flex items-center justify-center">
                                <span class="text-5xl">{{ $assignedService->category->icon ?? '🏖️' }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-white">{{ $assignedService->name }}</h2>
                            <p class="text-indigo-100 mt-1">{{ $assignedService->operating_hours ?? 'Operating hours not set' }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right hidden sm:block">
                            <div class="text-indigo-200 text-sm">Capacity</div>
                            <div class="text-3xl font-bold text-white">{{ $assignedService->concurrent_capacity }}<span class="text-xl text-indigo-200">/{{ $assignedService->capacity_limit }}</span></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Date Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 flex items-center justify-between">
                    <button wire:click="changeDate('prev')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </button>

                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selectedDateFormatted }}</div>
                            @if($isToday)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Today
                                </span>
                            @endif
                        </div>
                        <div>
                            <input wire:model.live="selectedDate" type="date"
                                class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if(!$isToday)
                            <button wire:click="goToToday"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900 transition">
                                Today
                            </button>
                        @endif
                        <button wire:click="changeDate('next')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Next
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Timeline -->
            @if(empty($timeSlots))
                <x-admin.card.empty-state
                    icon="⏰"
                    title="No Operating Hours Set"
                    description="Please configure opening and closing times in Service Settings to view the calendar.">
                </x-admin.card.empty-state>
            @else
                <!-- Legend -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                    <div class="flex items-center gap-6 flex-wrap">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Confirmed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Redeemed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cancelled</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline Grid -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($timeSlots as $slot)
                            <div class="flex hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- Time Column -->
                                <div class="w-32 flex-shrink-0 px-6 py-4 border-r border-gray-200 dark:border-gray-700">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $slot['time'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">1 hour</div>
                                </div>

                                <!-- Bookings Column -->
                                <div class="flex-1 px-6 py-4">
                                    @forelse($slot['bookings'] as $bookingData)
                                        @php
                                            $booking = $bookingData['booking'];
                                            $durationHours = $bookingData['duration_hours'];
                                        @endphp
                                        <div wire:click="showBookingDetails({{ $booking->id }})"
                                            class="mb-2 last:mb-0 group cursor-pointer"
                                            style="min-height: {{ $durationHours * 60 }}px;">
                                            <div class="flex items-start gap-3 p-4 rounded-lg border-2 transition-all h-full
                                                @if($booking->isRedeemed())
                                                    bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700 hover:border-green-400 dark:hover:border-green-600 hover:shadow-md
                                                @elseif($booking->isCancelled())
                                                    bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 hover:border-red-400 dark:hover:border-red-600 hover:shadow-md
                                                @else
                                                    bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-md
                                                @endif">

                                                <!-- Left Border Duration Indicator -->
                                                <div class="flex-shrink-0 flex flex-col items-center gap-1">
                                                    <div class="w-3 h-3 rounded-full
                                                        @if($booking->isRedeemed()) bg-green-500
                                                        @elseif($booking->isCancelled()) bg-red-500
                                                        @else bg-blue-500
                                                        @endif">
                                                    </div>
                                                    @if($durationHours > 1)
                                                        <div class="flex-1 w-0.5 rounded-full
                                                            @if($booking->isRedeemed()) bg-green-400
                                                            @elseif($booking->isCancelled()) bg-red-400
                                                            @else bg-blue-400
                                                            @endif">
                                                        </div>
                                                        <div class="w-3 h-3 rounded-full border-2
                                                            @if($booking->isRedeemed()) border-green-500
                                                            @elseif($booking->isCancelled()) border-red-500
                                                            @else border-blue-500
                                                            @endif">
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Booking Info -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                                {{ $booking->guest->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                                                <span class="mx-2">•</span>
                                                                <span class="font-semibold">{{ $durationHours }}h</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 text-right">
                                                            @if($booking->isRedeemed())
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Redeemed
                                                                </span>
                                                            @elseif($booking->isCancelled())
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                    Cancelled
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                                    Confirmed
                                                                </span>
                                                            @endif
                                                            <div class="text-sm font-semibold text-gray-900 dark:text-white mt-1">
                                                                MVR {{ number_format($booking->total_price, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs font-mono text-gray-500 dark:text-gray-400">
                                                        {{ $booking->booking_reference }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-gray-400 dark:text-gray-500 italic py-2">
                                            No bookings for this time slot
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Booking Details Modal -->
@if($showBookingModal && $selectedBooking)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeModal">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Booking Details</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Reference: {{ $selectedBooking->booking_reference }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Status Banner -->
                <div class="mb-6 p-4 rounded-lg
                    @if($selectedBooking->isRedeemed()) bg-green-100 dark:bg-green-900/50
                    @elseif($selectedBooking->isCancelled()) bg-red-100 dark:bg-red-900/50
                    @else bg-blue-100 dark:bg-blue-900/50
                    @endif">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            @if($selectedBooking->isRedeemed()) bg-green-500
                            @elseif($selectedBooking->isCancelled()) bg-red-500
                            @else bg-blue-500
                            @endif">
                            @if($selectedBooking->isRedeemed())
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold
                                @if($selectedBooking->isRedeemed()) text-green-900 dark:text-green-100
                                @elseif($selectedBooking->isCancelled()) text-red-900 dark:text-red-100
                                @else text-blue-900 dark:text-blue-100
                                @endif">
                                @if($selectedBooking->isRedeemed()) Redeemed
                                @elseif($selectedBooking->isCancelled()) Cancelled
                                @else Confirmed
                                @endif
                            </h4>
                            @if($selectedBooking->isRedeemed())
                                <p class="text-sm text-green-700 dark:text-green-200">
                                    {{ $selectedBooking->redeemed_at->format('M j, Y \a\t g:i A') }}
                                    @if($selectedBooking->redeemedByStaff)
                                        by {{ $selectedBooking->redeemedByStaff->name }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Information Grid -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Guest</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $selectedBooking->guest->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedBooking->guest->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $selectedBooking->booking_date->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Time</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                            {{ \Carbon\Carbon::parse($selectedBooking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($selectedBooking->end_time)->format('g:i A') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</label>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">MVR {{ number_format($selectedBooking->total_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ ucfirst($selectedBooking->payment_status) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Reference</label>
                        <p class="text-lg font-mono font-bold text-gray-900 dark:text-white mt-1">{{ $selectedBooking->booking_reference }}</p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
