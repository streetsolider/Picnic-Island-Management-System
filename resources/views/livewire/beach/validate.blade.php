<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Validate Beach Bookings
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(!$assignedService)
            <!-- No Service Assigned -->
            <x-admin.card.empty-state
                icon="🏖️"
                title="No Service Assigned"
                description="You don't have any beach service assigned to you yet. Please contact your administrator.">
            </x-admin.card.empty-state>
        @else
            <!-- Service Info -->
            <x-admin.card.base>
                <div class="flex items-center gap-3">
                    <span class="text-3xl">{{ $assignedService->category->icon ?? '🏖️' }}</span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $assignedService->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $assignedService->category->name ?? 'Beach Service' }} • {{ $assignedService->operating_hours }}</p>
                    </div>
                </div>
            </x-admin.card.base>

            <!-- Flash Messages -->
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

            @if (session()->has('info'))
                <x-admin.alert.info dismissible>
                    {{ session('info') }}
                </x-admin.alert.info>
            @endif

            <!-- Validation Form -->
            <x-admin.card.base>
                <x-slot name="title">Enter Booking Reference</x-slot>
                <form wire:submit.prevent="validateBooking" class="space-y-4">
                    <div>
                        <label for="searchCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Booking Reference Code
                        </label>
                        <input
                            wire:model="searchCode"
                            type="text"
                            id="searchCode"
                            placeholder="Enter BSB-XXXXXXXX"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-lg font-mono"
                            autofocus>
                        @error('searchCode')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <x-admin.button.primary type="submit" class="flex-1">
                            ✓ Validate & Redeem
                        </x-admin.button.primary>
                        <x-admin.button.secondary wire:click.prevent="checkStatus" class="flex-1">
                            🔍 Check Status Only
                        </x-admin.button.secondary>
                    </div>
                </form>

                @if($searchPerformed && !$booking)
                    <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">No results found.</p>
                    </div>
                @endif
            </x-admin.card.base>

            <!-- Booking Details -->
            @if($booking)
                <x-admin.card.base>
                    <x-slot name="title">Booking Details</x-slot>

                    <div class="space-y-4">
                        <!-- Status Banner -->
                        <div class="p-4 rounded-lg {{ $booking->isRedeemed() ? 'bg-green-100 dark:bg-green-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold {{ $booking->isRedeemed() ? 'text-green-900 dark:text-green-100' : 'text-blue-900 dark:text-blue-100' }}">
                                        {{ $booking->isRedeemed() ? '✓ Booking Redeemed' : '📋 Booking Confirmed' }}
                                    </h4>
                                    @if($booking->isRedeemed())
                                        <p class="text-sm {{ $booking->isRedeemed() ? 'text-green-700 dark:text-green-200' : 'text-blue-700 dark:text-blue-200' }}">
                                            Redeemed on {{ $booking->redeemed_at->format('M j, Y \a\t g:i A') }}
                                        </p>
                                    @endif
                                </div>
                                <span class="text-3xl">{{ $booking->isRedeemed() ? '✓' : '📅' }}</span>
                            </div>
                        </div>

                        <!-- Booking Information -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Booking Reference</label>
                                <p class="text-lg font-mono font-bold text-gray-900 dark:text-gray-100">{{ $booking->booking_reference }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Service</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $booking->service->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Guest Name</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $booking->guest->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->guest->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Booking Date</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $booking->booking_date->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Time Slot</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </p>
                                @if($booking->duration_hours)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->duration_hours }} hour(s)</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Total Price</label>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">MVR {{ number_format($booking->total_price, 2) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($booking->payment_status) }}</p>
                            </div>
                        </div>

                        <!-- Hotel Booking Info -->
                        @if($booking->hotelBooking)
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Hotel Booking</h5>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->hotelBooking->check_in_date->format('M j, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->hotelBooking->check_out_date->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <x-admin.button.secondary wire:click="resetSearch">
                                Search Another Booking
                            </x-admin.button.secondary>
                        </div>
                    </div>
                </x-admin.card.base>
            @endif
        @endif
    </div>
</div>
