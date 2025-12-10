<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('Analytics Dashboard') }}
    </h2>
</x-slot>

<div wire:poll.{{ $refreshInterval }}s="loadAnalytics" class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>{{-- Spacer or breadcrumbs if needed --}}</div>

        {{-- Date Filters --}}
        <div class="flex flex-wrap items-center gap-2">
            @php
                $filters = [
                    'today' => 'Today',
                    'last_7_days' => '7 Days',
                    'this_month' => 'This Month',
                    'this_year' => 'This Year',
                    'all_time' => 'All Time',
                ];
            @endphp

            @foreach($filters as $key => $label)
                    <button wire:click="applyDateFilter('{{ $key }}')" wire:key="filter-{{ $key }}" class="px-3 py-1 text-xs font-medium rounded-md transition-colors
                            {{ $dateFilter === $key
                ? 'bg-indigo-600 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' 
                            }}">
                        {{ $label }}
                    </button>
            @endforeach

            <button wire:click="toggleAutoRefresh" class="ml-2 px-3 py-1 text-xs font-medium rounded-md transition-colors border border-gray-200 dark:border-gray-700
                {{ $refreshInterval > 0
    ? 'text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400'
    : 'text-gray-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-400' 
                }}">
                Auto-Refresh: {{ $refreshInterval > 0 ? 'ON' : 'OFF' }}
            </button>
        </div>
    </div>

    {{-- Chart.js is now bundled in app.js --}}

    {{-- Error Alert --}}
    @if(isset($revenueData['error']) && $revenueData['error'])
        <x-admin.alert.danger class="mb-6" dismissible>
            {{ $revenueData['error_message'] }}
            <button wire:click="loadAnalytics" class="ml-4 underline">Retry</button>
        </x-admin.alert.danger>
    @endif

    {{-- Overview Stats Metrics (Compact) --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Revenue --}}
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total
                        Revenue</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        MVR {{ number_format($revenueData['total_revenue'] ?? 0) }}
                    </p>
                </div>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-full">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-xs text-green-600 dark:text-green-500 flex items-center">
                <span>Today: MVR {{ number_format($revenueData['today_revenue'] ?? 0) }}</span>
            </div>
        </div>

        {{-- Occupancy Rate --}}
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Occupancy
                        Rate</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($bookingStats['occupancy_rate'] ?? 0, 1) }}%
                    </p>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-3 dark:bg-gray-700">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $bookingStats['occupancy_rate'] ?? 0 }}%">
                </div>
            </div>
        </div>

        {{-- Active Guests --}}
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current
                        Guests</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $guestStats['active_guests'] ?? 0 }}
                    </p>
                </div>
                <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-full">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Total Registered: {{ $guestStats['total_guests'] ?? 0 }}
            </div>
        </div>

        {{-- Active Services --}}
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active
                        Services</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ ($stats['total_beach_services'] ?? 0) + ($stats['total_zones'] ?? 0) }}
                    </p>
                </div>
                <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-full">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Staff on duty: {{ $stats['total_staff'] ?? 0 }}
            </div>
        </div>
    </div>

    {{-- Main Analytics Charts --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Revenue Trend (Line Chart) --}}
        <div
            class="bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 lg:col-span-2">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Revenue Trend (30 Days)</h3>
            <div class="h-64 relative" wire:key="revenue-trend-chart-{{ $dateFilter }}-{{ now()->timestamp }}" x-data="{
                    init() {
                        const data = {{ Js::from($revenueTrend) }};
                        if (!document.getElementById('revenueTrendCanvas')) return;
                        
                        // Destroy existing chart if any
                        const ctx = document.getElementById('revenueTrendCanvas').getContext('2d');
                        if (window.revenueTrendChart instanceof Chart) {
                            window.revenueTrendChart.destroy();
                        }

                        window.revenueTrendChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.map(d => {
                                    const date = new Date(d.date);
                                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                                }),
                                datasets: [{
                                    label: 'Revenue',
                                    data: data.map(d => d.amount),
                                    borderColor: '#4F46E5', // Indigo 600
                                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    pointRadius: 2,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        callbacks: {
                                            label: function(context) {
                                                return 'MVR ' + context.raw.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0,0,0,0.05)' },
                                        ticks: {
                                            callback: (val) => val >= 1000 ? (val/1000) + 'k' : val,
                                            font: { size: 10 }
                                        }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: { font: { size: 10 } }
                                    }
                                }
                            }
                        });
                    }
                 }">
                <canvas id="revenueTrendCanvas"></canvas>
            </div>
        </div>

        {{-- Revenue Breakdown (Doughnut Chart) --}}
        <div class="bg-white rounded-lg shadow-sm p-4 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Revenue Sources</h3>
            <div class="h-48 relative flex justify-center"
                wire:key="revenue-category-chart-{{ $dateFilter }}-{{ now()->timestamp }}" x-data="{
                    init() {
                        const data = {
                            'Hotels': {{ $revenueData['hotel_revenue'] ?? 0 }},
                            'Beach Services': {{ $revenueData['beach_revenue'] ?? 0 }},
                            'Theme Park': {{ $revenueData['theme_park_revenue'] ?? 0 }}
                        };

                        if (!document.getElementById('revenueCategoryCanvas')) return;
                
                        const ctx = document.getElementById('revenueCategoryCanvas').getContext('2d');
                        
                        if (window.revenueCategoryChart instanceof Chart) {
                            window.revenueCategoryChart.destroy();
                        }

                        const labels = Object.keys(data);
                        const values = Object.values(data);
                        
                        window.revenueCategoryChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: [
                                        '#6366F1', // Indigo 500
                                        '#2DD4BF', // Teal 400
                                        '#A855F7', // Purple 500
                                    ],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return ' MVR ' + context.raw.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                cutout: '70%',
                            }
                        });
                    }
                }">
                <canvas id="revenueCategoryCanvas"></canvas>
            </div>

            {{-- Legend --}}
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center"><span
                            class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>Hotels</span>
                    <span class="font-medium text-gray-900 dark:text-white">MVR
                        {{ number_format($revenueData['hotel_revenue'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center"><span
                            class="w-2 h-2 rounded-full bg-teal-400 mr-2"></span>Beach</span>
                    <span class="font-medium text-gray-900 dark:text-white">MVR
                        {{ number_format($revenueData['beach_revenue'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span>Theme
                        Park</span>
                    <span class="font-medium text-gray-900 dark:text-white">MVR
                        {{ number_format($revenueData['theme_park_revenue'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance Breakdown --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Top Hotels --}}
        <x-admin.card.base padding="p-0">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Performance: Top Hotels</span>
                    <span class="text-xs font-normal text-gray-500">By Revenue</span>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Hotel</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Bookings</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @foreach($topHotels as $hotel)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $hotel->name }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                    {{ $hotel->booking_count }}
                                </td>
                                <td
                                    class="px-6 py-3 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                    MVR {{ number_format($hotel->revenue) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($topHotels) === 0)
                    <div class="p-4 text-center text-sm text-gray-500">No data available</div>
                @endif
            </div>
        </x-admin.card.base>

        {{-- Popular Activities --}}
        <x-admin.card.base padding="p-0">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Popular Activities</span>
                    <span class="text-xs font-normal text-gray-500">By Credits Spent</span>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Activity</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tickets</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Credits</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @foreach($popularActivities->take(5) as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $activity->name }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                    {{ $activity->ticket_count }}
                                </td>
                                <td
                                    class="px-6 py-3 whitespace-nowrap text-sm text-right font-medium text-purple-600 dark:text-purple-400">
                                    {{ number_format($activity->total_credits) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($popularActivities) === 0)
                    <div class="p-4 text-center text-sm text-gray-500">No data available</div>
                @endif
            </div>
        </x-admin.card.base>
    </div>
</div>