<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Beach Staff Dashboard
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if($assignedServices->isEmpty())
            <!-- No Service Assigned -->
            <x-admin.card.empty-state
                icon="🏖️"
                title="No Service Assigned"
                description="You don't have any beach service assigned to you yet. Please contact your administrator.">
            </x-admin.card.empty-state>
        @else
            <!-- Service Selector (if multiple services) -->
            @if($assignedServices->count() > 1)
                <x-admin.card.base>
                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Service to Manage
                        </label>
                        <select
                            wire:model.live="selectedServiceId"
                            wire:change="selectService($event.target.value)"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($assignedServices as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->category->icon ?? '🏖️' }} {{ $service->name }} ({{ $service->category->name ?? 'Beach Service' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </x-admin.card.base>
            @endif
            <!-- Service Info Card -->
            <x-admin.card.base>
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl">{{ $selectedService->category->icon ?? '🏖️' }}</span>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $selectedService->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedService->category->name ?? 'Beach Service' }}</p>
                            </div>
                        </div>
                        @if($selectedService->description)
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $selectedService->description }}</p>
                        @endif
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Operating Hours</span>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedService->operating_hours }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Booking Type</span>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedService->booking_type === 'fixed_slot' ? 'Fixed Slot' : 'Flexible' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Capacity</span>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedService->concurrent_capacity }}/{{ $selectedService->capacity_limit }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Status</span>
                                <p class="font-semibold {{ $selectedService->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $selectedService->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card.base>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-admin.card.stat
                    title="Today's Bookings"
                    :value="$stats['today_bookings']"
                    icon="📅"
                    color="blue">
                </x-admin.card.stat>

                <x-admin.card.stat
                    title="Redeemed Today"
                    :value="$stats['today_redemptions']"
                    icon="✓"
                    color="green">
                </x-admin.card.stat>

                <x-admin.card.stat
                    title="Today's Revenue"
                    :value="'MVR ' . number_format($stats['today_revenue'], 2)"
                    icon="💰"
                    color="indigo">
                </x-admin.card.stat>
            </div>

            <!-- Quick Actions -->
            <x-admin.card.base>
                <x-slot name="title">Quick Actions</x-slot>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('beach.validate') }}" wire:navigate>
                        <x-admin.button.primary class="w-full justify-center">
                            🎫 Validate Booking
                        </x-admin.button.primary>
                    </a>
                    <a href="{{ route('beach.bookings') }}" wire:navigate>
                        <x-admin.button.secondary class="w-full justify-center">
                            📊 View Calendar
                        </x-admin.button.secondary>
                    </a>
                    <a href="{{ route('beach.service-settings') }}" wire:navigate>
                        <x-admin.button.secondary class="w-full justify-center">
                            ⚙️ Service Settings
                        </x-admin.button.secondary>
                    </a>
                </div>
            </x-admin.card.base>

            <!-- Today's Bookings -->
            <x-admin.card.base>
                <x-slot name="title">Today's Bookings ({{ $todayBookings->count() }})</x-slot>
                @if($todayBookings->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No bookings for today.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($todayBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->guest->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->guest->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                            {{ $booking->booking_reference }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            MVR {{ number_format($booking->total_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($booking->isRedeemed())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✓ Redeemed
                                                </span>
                                            @elseif($booking->isConfirmed())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Confirmed
                                                </span>
                                            @elseif($booking->isCancelled())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-admin.card.base>

            <!-- Upcoming Bookings -->
            <x-admin.card.base>
                <x-slot name="title">Upcoming Bookings (Next 7 Days)</x-slot>
                @if($upcomingBookings->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No upcoming bookings.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($upcomingBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $booking->booking_date->format('M j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->guest->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->guest->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                            {{ $booking->booking_reference }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            MVR {{ number_format($booking->total_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-admin.card.base>
        @endif
    </div>
</div>
