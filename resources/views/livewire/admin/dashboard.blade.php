<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Analytics Dashboard') }}
    </h2>
</x-slot>

<div wire:poll.{{ $refreshInterval }}s="loadAnalytics">
    {{-- Header with Date Filters --}}
    <div class="mb-6">
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            System-wide performance metrics and insights
        </p>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-admin.button.secondary wire:click="applyDateFilter('today')"
                :class="$dateFilter === 'today' ? 'ring-2 ring-indigo-500' : ''">
                Today
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('last_7_days')"
                :class="$dateFilter === 'last_7_days' ? 'ring-2 ring-indigo-500' : ''">
                Last 7 Days
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('this_week')"
                :class="$dateFilter === 'this_week' ? 'ring-2 ring-indigo-500' : ''">
                This Week
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('last_30_days')"
                :class="$dateFilter === 'last_30_days' ? 'ring-2 ring-indigo-500' : ''">
                Last 30 Days
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('this_month')"
                :class="$dateFilter === 'this_month' ? 'ring-2 ring-indigo-500' : ''">
                This Month
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('this_quarter')"
                :class="$dateFilter === 'this_quarter' ? 'ring-2 ring-indigo-500' : ''">
                This Quarter
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('this_year')"
                :class="$dateFilter === 'this_year' ? 'ring-2 ring-indigo-500' : ''">
                This Year
            </x-admin.button.secondary>

            <x-admin.button.secondary wire:click="applyDateFilter('all_time')"
                :class="$dateFilter === 'all_time' ? 'ring-2 ring-indigo-500' : ''">
                All Time
            </x-admin.button.secondary>

            {{-- Auto-refresh toggle --}}
            <x-admin.button.secondary wire:click="toggleAutoRefresh" class="ml-auto">
                Auto-Refresh: {{ $refreshInterval > 0 ? 'ON' : 'OFF' }}
            </x-admin.button.secondary>
        </div>
    </div>

    {{-- Error Alert --}}
    @if(isset($revenueData['error']) && $revenueData['error'])
        <x-admin.alert.danger class="mb-6" dismissible>
            {{ $revenueData['error_message'] }}
            <button wire:click="loadAnalytics" class="ml-4 underline">Retry</button>
        </x-admin.alert.danger>
    @endif

    {{-- Key Metrics Row (Always Visible) --}}
    @if($isLoading)
        <x-admin.skeleton.card-grid :count="4" class="mb-6" />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <x-admin.card.stat label="Total Revenue"
                value="MVR {{ number_format($revenueData['total_revenue'] ?? 0, 2) }}" color="indigo">
                <x-slot:icon>
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Today's Revenue"
                value="MVR {{ number_format($revenueData['today_revenue'] ?? 0, 2) }}" color="green">
                <x-slot:icon>
                    <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Monthly Revenue"
                value="MVR {{ number_format($revenueData['month_revenue'] ?? 0, 2) }}" color="blue">
                <x-slot:icon>
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Active Guests" value="{{ $guestStats['active_guests'] ?? 0 }}" color="purple">
                <x-slot:icon>
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>
        </div>
    @endif

    {{-- Revenue Breakdown and Booking Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Revenue by Category --}}
        <x-admin.card.base>
            <x-slot name="title">Revenue by Category</x-slot>

            <div class="grid grid-cols-1 gap-4">
                <x-admin.card.stat label="Hotel Bookings"
                    value="MVR {{ number_format($revenueData['hotel_revenue'] ?? 0, 2) }}" color="indigo" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Beach Services"
                    value="MVR {{ number_format($revenueData['beach_revenue'] ?? 0, 2) }}" color="green" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Theme Park Wallet Top-ups"
                    value="MVR {{ number_format($revenueData['theme_park_revenue'] ?? 0, 2) }}" color="purple"
                    size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Ferry Operations (Non-Revenue)
                    </p>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Ferry service is free for hotel guests</p>
                    <div class="flex gap-4 mt-3">
                        <div>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                {{ $ferryStats['total_passengers'] ?? 0 }}</p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">Total Passengers</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                {{ $ferryStats['total_trips'] ?? 0 }}</p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">Total Trips</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card.base>

        {{-- Booking Statistics --}}
        <x-admin.card.base>
            <x-slot name="title">Booking & Occupancy Statistics</x-slot>

            <div class="grid grid-cols-2 gap-4">
                <x-admin.card.stat label="Total Bookings" value="{{ $bookingStats['total_bookings'] ?? 0 }}"
                    color="gray" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Confirmed" value="{{ $bookingStats['confirmed_bookings'] ?? 0 }}"
                    color="green" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Today's Check-ins" value="{{ $bookingStats['today_checkins'] ?? 0 }}"
                    color="blue" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Occupancy Rate"
                    value="{{ number_format($bookingStats['occupancy_rate'] ?? 0, 1) }}%" color="indigo" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>
            </div>
        </x-admin.card.base>
    </div>

    {{-- Key Business Metrics and Payment Analytics --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Key Business Metrics --}}
        <x-admin.card.base>
            <x-slot name="title">Key Business Metrics</x-slot>

            <div class="grid grid-cols-2 gap-4">
                <x-admin.card.stat label="Cancellation Rate"
                    value="{{ number_format($metrics['cancellation_rate'] ?? 0, 1) }}%" color="red" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Avg Booking Value"
                    value="MVR {{ number_format($metrics['average_booking_value'] ?? 0, 2) }}" color="green"
                    size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="No-Show Rate"
                    value="{{ number_format($metrics['no_show_rate'] ?? 0, 1) }}%" color="orange" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="RevPAR" value="MVR {{ number_format($metrics['revpar'] ?? 0, 2) }}"
                    color="indigo" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>
            </div>
        </x-admin.card.base>

        {{-- Payment Analytics --}}
        <x-admin.card.base>
            <x-slot name="title">Payment Analytics</x-slot>

            <div class="grid grid-cols-2 gap-4">
                <x-admin.card.stat label="Total Payments" value="{{ $paymentStats['total_payments'] ?? 0 }}"
                    color="gray" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Completed" value="{{ $paymentStats['completed_payments'] ?? 0 }}"
                    color="green" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Pending" value="{{ $paymentStats['pending_payments'] ?? 0 }}"
                    color="yellow" size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>

                <x-admin.card.stat label="Failed" value="{{ $paymentStats['failed_payments'] ?? 0 }}" color="red"
                    size="sm">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-admin.card.stat>
            </div>
        </x-admin.card.base>
    </div>

    {{-- Revenue Trend Chart --}}
    <x-admin.card.base class="mb-6">
        <x-slot name="title">Revenue Trend (Last 30 Days)</x-slot>

        @if(count($revenueTrend) > 0)
            <div x-data="revenueChart(@js($revenueTrend))" class="h-64">
                <canvas x-ref="chart"></canvas>
            </div>
        @else
            <x-admin.card.empty-state icon="📊" title="No revenue data yet"
                description="Revenue trend will appear here once bookings are made." />
        @endif
    </x-admin.card.base>

    {{-- Top Performers --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Hotels by Revenue --}}
        <x-admin.card.base>
            <x-slot name="title">Top Hotels by Revenue</x-slot>

            @if(count($topHotels) > 0)
                <x-admin.table.wrapper>
                    <thead>
                        <tr>
                            <x-admin.table.header>Hotel</x-admin.table.header>
                            <x-admin.table.header>Revenue</x-admin.table.header>
                            <x-admin.table.header>Bookings</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topHotels as $hotel)
                            <x-admin.table.row>
                                <td class="px-6 py-4">{{ $hotel->name }}</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-semibold">
                                    MVR {{ number_format($hotel->revenue, 2) }}
                                </td>
                                <td class="px-6 py-4">{{ $hotel->booking_count }}</td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>
            @else
                <x-admin.card.empty-state icon="🏨" title="No hotel data yet"
                    description="Revenue data will appear here once bookings are made." />
            @endif
        </x-admin.card.base>

        {{-- Popular Beach Services --}}
        <x-admin.card.base>
            <x-slot name="title">Popular Beach Services</x-slot>

            @if(count($popularBeachServices) > 0)
                <x-admin.table.wrapper>
                    <thead>
                        <tr>
                            <x-admin.table.header>Service</x-admin.table.header>
                            <x-admin.table.header>Bookings</x-admin.table.header>
                            <x-admin.table.header>Revenue</x-admin.table.header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($popularBeachServices as $service)
                            <x-admin.table.row>
                                <td class="px-6 py-4">{{ $service->name }}</td>
                                <td class="px-6 py-4">{{ $service->booking_count }}</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-semibold">
                                    MVR {{ number_format($service->revenue, 2) }}
                                </td>
                            </x-admin.table.row>
                        @endforeach
                    </tbody>
                </x-admin.table.wrapper>
            @else
                <x-admin.card.empty-state icon="🏖️" title="No beach service data yet"
                    description="Activity data will appear here once bookings are made." />
            @endif
        </x-admin.card.base>
    </div>

    {{-- Theme Park Analytics Section --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🎢 Theme Park Analytics</h3>

        {{-- Theme Park Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-admin.card.stat
                label="Total Top-up Revenue"
                value="MVR {{ number_format($themeParkStats['total_revenue'] ?? 0, 2) }}"
                color="purple"
                size="sm" />

            <x-admin.card.stat
                label="Credits Spent"
                value="{{ number_format($themeParkStats['total_credits_spent'] ?? 0) }}"
                color="indigo"
                size="sm" />

            <x-admin.card.stat
                label="Activity Tickets Sold"
                value="{{ number_format($themeParkStats['total_tickets_sold'] ?? 0) }}"
                color="blue"
                size="sm" />

            <x-admin.card.stat
                label="Active Wallets"
                value="{{ $themeParkStats['active_wallets'] ?? 0 }} / {{ $themeParkStats['total_wallets'] ?? 0 }}"
                color="green"
                size="sm" />
        </div>

        {{-- Additional Theme Park Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-admin.card.stat
                label="Avg Top-up Amount"
                value="MVR {{ number_format($themeParkStats['average_top_up_amount'] ?? 0, 2) }}"
                color="gray"
                size="sm" />

            <x-admin.card.stat
                label="Avg Credits per Ticket"
                value="{{ number_format($themeParkStats['average_credits_per_ticket'] ?? 0, 1) }}"
                color="gray"
                size="sm" />

            <x-admin.card.stat
                label="Total Wallet Balance"
                value="MVR {{ number_format($themeParkStats['total_balance_mvr'] ?? 0, 2) }}"
                color="yellow"
                size="sm" />

            <x-admin.card.stat
                label="Total Credits Balance"
                value="{{ number_format($themeParkStats['total_credits_balance'] ?? 0) }}"
                color="yellow"
                size="sm" />
        </div>

        {{-- Activity Type Breakdown --}}
        @if(count($activityTypeBreakdown) > 0)
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Credits Spent by Activity Type</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($activityTypeBreakdown as $type => $data)
                        <x-admin.card.base class="text-center">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format($data['total_credits']) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $data['ticket_count'] }} tickets
                            </div>
                        </x-admin.card.base>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Popular Activities Table --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <x-admin.card.base>
                <x-slot name="title">🎯 Most Popular Activities (by Credits Spent)</x-slot>

                @if(count($popularActivities) > 0)
                    <x-admin.table.wrapper>
                        <thead>
                            <tr>
                                <x-admin.table.header>Activity</x-admin.table.header>
                                <x-admin.table.header>Zone</x-admin.table.header>
                                <x-admin.table.header>Credits</x-admin.table.header>
                                <x-admin.table.header>Tickets</x-admin.table.header>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularActivities as $activity)
                                <x-admin.table.row>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ $activity->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($activity->activity_type) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $activity->zone_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-purple-600 dark:text-purple-400 font-semibold">
                                        {{ number_format($activity->total_credits) }}
                                    </td>
                                    <td class="px-6 py-4">{{ $activity->ticket_count }}</td>
                                </x-admin.table.row>
                            @endforeach
                        </tbody>
                    </x-admin.table.wrapper>
                @else
                    <x-admin.card.empty-state
                        icon="🎢"
                        title="No activity data yet"
                        description="Activity statistics will appear here once guests purchase tickets." />
                @endif
            </x-admin.card.base>

            {{-- Recent Theme Park Transactions --}}
            <x-admin.card.base>
                <x-slot name="title">💳 Recent Wallet Transactions</x-slot>

                @if(count($recentThemeParkTransactions) > 0)
                    <div class="space-y-3">
                        @foreach($recentThemeParkTransactions as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $transaction->user->name ?? 'Guest' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($transaction->transaction_type === 'top_up')
                                        <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            +MVR {{ number_format($transaction->amount_mvr, 2) }}
                                        </div>
                                    @else
                                        <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                            {{ $transaction->credits_amount }} credits
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $transaction->transaction_reference }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-admin.card.empty-state
                        icon="💳"
                        title="No transactions yet"
                        description="Recent wallet transactions will appear here." />
                @endif
            </x-admin.card.base>
        </div>
    </div>

    {{-- Recent Activity --}}
    <x-admin.card.base class="mb-6">
        <x-slot name="title">Recent Activity</x-slot>

        <div class="space-y-4">
            {{-- Recent Bookings --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Recent Bookings
                </h4>
                @if (count($recentBookings) > 0)
                    <div class="space-y-2">
                        @foreach ($recentBookings as $booking)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded">
                                <div>
                                    <span class="font-medium">{{ $booking->guest->name }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        - {{ $booking->hotel->name }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        MVR {{ number_format($booking->total_price, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $booking->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No recent bookings</p>
                @endif
            </div>

            {{-- Failed Payments (if any) --}}
            @if (count($failedPayments) > 0)
                <div>
                    <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-2">
                        ⚠️ Failed Payments Requiring Attention
                    </h4>
                    <div class="space-y-2">
                        @foreach ($failedPayments as $payment)
                            <div
                                class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                                <div>
                                    <span class="font-medium">{{ $payment->transaction_id }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        - {{ class_basename($payment->payable_type) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                        MVR {{ number_format($payment->amount, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $payment->failed_at?->diffForHumans() ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-admin.card.base>

    {{-- System Health (Legacy Stats) --}}
    <x-admin.card.base>
        <x-slot name="title">System Health</x-slot>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-admin.card.stat label="Total Staff" value="{{ $stats['total_staff'] ?? 0 }}" color="gray" size="sm">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Total Hotels" value="{{ $stats['total_hotels'] ?? 0 }}" color="gray"
                size="sm">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Active Zones" value="{{ $stats['active_zones'] ?? 0 }}" color="gray"
                size="sm">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>

            <x-admin.card.stat label="Beach Services" value="{{ $stats['total_beach_services'] ?? 0 }}" color="gray"
                size="sm">
                <x-slot:icon>
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                        </path>
                    </svg>
                </x-slot:icon>
            </x-admin.card.stat>
        </div>
    </x-admin.card.base>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Alpine.data('revenueChart', (data) => ({
            init() {
                const ctx = this.$refs.chart.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.date),
                        datasets: [{
                            label: 'Revenue (MVR)',
                            data: data.map(d => d.amount),
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'MVR ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }));
    </script>
@endpush
