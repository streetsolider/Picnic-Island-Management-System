<?php

namespace App\Services;

use App\Models\BeachServiceBooking;
use App\Models\Guest;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivityTicket;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use App\Models\Ferry\FerryTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminAnalyticsService
{
    /**
     * Get system-wide revenue data
     */
    public function getSystemRevenue(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_revenue_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $hotelRevenue = $this->calculateHotelRevenue($startDate, $endDate);
                $beachRevenue = $this->calculateBeachRevenue($startDate, $endDate);
                $themeParkRevenue = $this->calculateThemeParkRevenue($startDate, $endDate);

                $totalRevenue = $hotelRevenue + $beachRevenue + $themeParkRevenue;

                // Calculate today's and this month's revenue
                $todayRevenue = $this->calculateHotelRevenue(now()->startOfDay()->toDateString(), now()->endOfDay()->toDateString())
                    + $this->calculateBeachRevenue(now()->startOfDay()->toDateString(), now()->endOfDay()->toDateString())
                    + $this->calculateThemeParkRevenue(now()->startOfDay()->toDateString(), now()->endOfDay()->toDateString());

                $monthRevenue = $this->calculateHotelRevenue(now()->startOfMonth()->toDateString(), now()->endOfDay()->toDateString())
                    + $this->calculateBeachRevenue(now()->startOfMonth()->toDateString(), now()->endOfDay()->toDateString())
                    + $this->calculateThemeParkRevenue(now()->startOfMonth()->toDateString(), now()->endOfDay()->toDateString());

                return [
                    'total_revenue' => $totalRevenue,
                    'hotel_revenue' => $hotelRevenue,
                    'beach_revenue' => $beachRevenue,
                    'theme_park_revenue' => $themeParkRevenue,
                    'today_revenue' => $todayRevenue,
                    'month_revenue' => $monthRevenue,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getSystemRevenue', [
                'method' => 'getSystemRevenue',
                'error' => $e->getMessage(),
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

            return [
                'total_revenue' => 0,
                'hotel_revenue' => 0,
                'beach_revenue' => 0,
                'theme_park_revenue' => 0,
                'today_revenue' => 0,
                'month_revenue' => 0,
                'error' => true,
                'error_message' => 'Unable to load revenue data. Please try again.',
            ];
        }
    }

    /**
     * Calculate hotel booking revenue
     * CORRECTED: status NOT IN ('cancelled', 'no-show') instead of only 'completed'
     */
    private function calculateHotelRevenue(?string $startDate, ?string $endDate): float
    {
        $query = HotelBooking::where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled', 'no-show']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('total_price') ?? 0;
    }

    /**
     * Calculate beach service booking revenue
     */
    private function calculateBeachRevenue(?string $startDate, ?string $endDate): float
    {
        $query = BeachServiceBooking::where('payment_status', 'paid')
            ->where('status', 'redeemed');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('total_price') ?? 0;
    }

    /**
     * Calculate theme park wallet top-up revenue
     */
    private function calculateThemeParkRevenue(?string $startDate, ?string $endDate): float
    {
        $query = ThemeParkWalletTransaction::where('transaction_type', 'top_up');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('amount_mvr') ?? 0;
    }

    /**
     * Get revenue breakdown by category
     */
    public function getRevenueByCategory(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_revenue_category_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                return [
                    'hotel_revenue' => $this->calculateHotelRevenue($startDate, $endDate),
                    'beach_revenue' => $this->calculateBeachRevenue($startDate, $endDate),
                    'theme_park_revenue' => $this->calculateThemeParkRevenue($startDate, $endDate),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getRevenueByCategory', [
                'error' => $e->getMessage(),
            ]);

            return [
                'hotel_revenue' => 0,
                'beach_revenue' => 0,
                'theme_park_revenue' => 0,
            ];
        }
    }

    /**
     * Get daily revenue trend for specified number of days
     */
    public function getDailyRevenueTrend(int $days = 30): array
    {
        try {
            $cacheKey = sprintf('admin_revenue_trend_%d_days', $days);

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($days) {
                $startDate = now()->subDays($days)->startOfDay();
                $endDate = now()->endOfDay();

                $hotelRevenue = HotelBooking::where('payment_status', 'paid')
                    ->whereNotIn('status', ['cancelled', 'no-show'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as amount'))
                    ->groupBy('date')
                    ->get()
                    ->keyBy('date');

                $beachRevenue = BeachServiceBooking::where('payment_status', 'paid')
                    ->where('status', 'redeemed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as amount'))
                    ->groupBy('date')
                    ->get()
                    ->keyBy('date');

                $themeParkRevenue = ThemeParkWalletTransaction::where('transaction_type', 'top_up')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_mvr) as amount'))
                    ->groupBy('date')
                    ->get()
                    ->keyBy('date');

                // Merge all revenue streams by date
                $trendData = [];
                for ($i = 0; $i < $days; $i++) {
                    $date = now()->subDays($days - $i - 1)->format('Y-m-d');
                    $amount = ($hotelRevenue[$date]->amount ?? 0)
                        + ($beachRevenue[$date]->amount ?? 0)
                        + ($themeParkRevenue[$date]->amount ?? 0);

                    $trendData[] = [
                        'date' => $date,
                        'amount' => (float) $amount,
                    ];
                }

                return $trendData;
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getDailyRevenueTrend', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get booking statistics
     */
    public function getBookingStats(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_booking_stats_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $query = HotelBooking::query();

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $totalBookings = $query->count();
                $confirmedBookings = (clone $query)->where('status', 'confirmed')->count();
                $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
                $pendingBookings = $totalBookings - $confirmedBookings - $cancelledBookings;

                $todayCheckins = HotelBooking::whereDate('check_in_date', now()->toDateString())->count();
                $todayCheckouts = HotelBooking::whereDate('check_out_date', now()->toDateString())->count();

                $occupancyRate = $this->getOccupancyRate();

                return [
                    'total_bookings' => $totalBookings,
                    'confirmed_bookings' => $confirmedBookings,
                    'cancelled_bookings' => $cancelledBookings,
                    'pending_bookings' => $pendingBookings,
                    'today_checkins' => $todayCheckins,
                    'today_checkouts' => $todayCheckouts,
                    'occupancy_rate' => $occupancyRate,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getBookingStats', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total_bookings' => 0,
                'confirmed_bookings' => 0,
                'cancelled_bookings' => 0,
                'pending_bookings' => 0,
                'today_checkins' => 0,
                'today_checkouts' => 0,
                'occupancy_rate' => 0,
            ];
        }
    }

    /**
     * Get occupancy rate
     * CORRECTED: Sum number_of_rooms instead of counting bookings
     */
    public function getOccupancyRate(): float
    {
        try {
            $totalRooms = Room::where('is_active', true)->count();

            if ($totalRooms === 0) {
                return 0;
            }

            // IMPORTANT: Sum number_of_rooms because one booking can have multiple rooms
            $occupiedRooms = HotelBooking::where('status', 'checked_in')
                ->sum('number_of_rooms');

            return ($occupiedRooms / $totalRooms) * 100;
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getOccupancyRate', [
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Get today's activity
     */
    public function getTodayActivity(): array
    {
        try {
            $todayCheckins = HotelBooking::whereDate('check_in_date', now()->toDateString())->count();
            $todayCheckouts = HotelBooking::whereDate('check_out_date', now()->toDateString())->count();
            $activeGuests = HotelBooking::where('status', 'checked_in')->sum('number_of_guests');

            return [
                'today_checkins' => $todayCheckins,
                'today_checkouts' => $todayCheckouts,
                'active_guests' => $activeGuests,
            ];
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getTodayActivity', [
                'error' => $e->getMessage(),
            ]);

            return [
                'today_checkins' => 0,
                'today_checkouts' => 0,
                'active_guests' => 0,
            ];
        }
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_payment_stats_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $query = Payment::query();

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $totalPayments = $query->count();
                $completedPayments = (clone $query)->where('status', 'completed')->count();
                $pendingPayments = (clone $query)->where('status', 'pending')->count();
                $failedPayments = (clone $query)->where('status', 'failed')->count();

                $successRate = $totalPayments > 0 ? ($completedPayments / $totalPayments) * 100 : 0;

                return [
                    'total_payments' => $totalPayments,
                    'completed_payments' => $completedPayments,
                    'pending_payments' => $pendingPayments,
                    'failed_payments' => $failedPayments,
                    'success_rate' => $successRate,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getPaymentStats', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total_payments' => 0,
                'completed_payments' => 0,
                'pending_payments' => 0,
                'failed_payments' => 0,
                'success_rate' => 0,
            ];
        }
    }

    /**
     * Get guest statistics
     */
    public function getGuestStats(): array
    {
        try {
            $totalGuests = Guest::count();

            $activeGuests = HotelBooking::where('status', 'checked_in')
                ->distinct('guest_id')
                ->count('guest_id');

            // Repeat guests (guests with more than 1 booking)
            $repeatGuests = HotelBooking::select('guest_id')
                ->groupBy('guest_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            $retentionRate = $totalGuests > 0 ? ($repeatGuests / $totalGuests) * 100 : 0;

            return [
                'total_guests' => $totalGuests,
                'active_guests' => $activeGuests,
                'repeat_guests' => $repeatGuests,
                'retention_rate' => $retentionRate,
            ];
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getGuestStats', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total_guests' => 0,
                'active_guests' => 0,
                'repeat_guests' => 0,
                'retention_rate' => 0,
            ];
        }
    }

    /**
     * Get top hotels by revenue
     */
    public function getTopHotelsByRevenue(int $limit = 5): Collection
    {
        try {
            $cacheKey = sprintf('admin_top_hotels_%d', $limit);

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($limit) {
                return DB::table('hotel_bookings')
                    ->join('hotels', 'hotels.id', '=', 'hotel_bookings.hotel_id')
                    ->where('hotel_bookings.payment_status', 'paid')
                    ->whereNotIn('hotel_bookings.status', ['cancelled', 'no-show'])
                    ->select('hotels.id', 'hotels.name')
                    ->selectRaw('SUM(hotel_bookings.total_price) as revenue')
                    ->selectRaw('COUNT(hotel_bookings.id) as booking_count')
                    ->groupBy('hotels.id', 'hotels.name')
                    ->orderByDesc('revenue')
                    ->limit($limit)
                    ->get();
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getTopHotelsByRevenue', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get popular beach services
     */
    public function getPopularBeachServices(int $limit = 5): Collection
    {
        try {
            $cacheKey = sprintf('admin_popular_beach_services_%d', $limit);

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($limit) {
                return DB::table('beach_service_bookings')
                    ->join('beach_services', 'beach_services.id', '=', 'beach_service_bookings.beach_service_id')
                    ->where('beach_service_bookings.payment_status', 'paid')
                    ->where('beach_service_bookings.status', 'redeemed')
                    ->select('beach_services.id', 'beach_services.name')
                    ->selectRaw('COUNT(beach_service_bookings.id) as booking_count')
                    ->selectRaw('SUM(beach_service_bookings.total_price) as revenue')
                    ->groupBy('beach_services.id', 'beach_services.name')
                    ->orderByDesc('booking_count')
                    ->limit($limit)
                    ->get();
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getPopularBeachServices', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get additional business metrics
     */
    public function getAdditionalMetrics(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_additional_metrics_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $query = HotelBooking::query();

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $totalBookings = $query->count();
                $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
                $noShowBookings = (clone $query)->where('status', 'no-show')->count();

                $paidBookings = (clone $query)->where('payment_status', 'paid')
                    ->whereNotIn('status', ['cancelled', 'no-show']);
                $totalRevenue = $paidBookings->sum('total_price');
                $paidBookingsCount = $paidBookings->count();

                // Calculate metrics
                $cancellationRate = $totalBookings > 0 ? ($cancelledBookings / $totalBookings) * 100 : 0;
                $noShowRate = $totalBookings > 0 ? ($noShowBookings / $totalBookings) * 100 : 0;
                $averageBookingValue = $paidBookingsCount > 0 ? $totalRevenue / $paidBookingsCount : 0;

                // RevPAR (Revenue Per Available Room)
                $totalRooms = Room::where('is_active', true)->count();
                $revpar = $totalRooms > 0 ? $totalRevenue / $totalRooms : 0;

                return [
                    'cancellation_rate' => $cancellationRate,
                    'no_show_rate' => $noShowRate,
                    'average_booking_value' => $averageBookingValue,
                    'revpar' => $revpar,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getAdditionalMetrics', [
                'error' => $e->getMessage(),
            ]);

            return [
                'cancellation_rate' => 0,
                'no_show_rate' => 0,
                'average_booking_value' => 0,
                'revpar' => 0,
            ];
        }
    }

    /**
     * Get ferry statistics (operational metrics, not revenue)
     * Ferry service is FREE for hotel guests
     */
    public function getFerryStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_ferry_stats_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $query = FerryTicket::query();

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $totalPassengers = $query->sum('number_of_passengers');
                $totalTrips = $query->where('status', 'used')->count();

                return [
                    'total_passengers' => $totalPassengers,
                    'total_trips' => $totalTrips,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getFerryStatistics', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total_passengers' => 0,
                'total_trips' => 0,
            ];
        }
    }

    /**
     * Get recent bookings
     */
    public function getRecentBookings(int $limit = 5): Collection
    {
        try {
            return HotelBooking::with(['guest', 'hotel'])
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getRecentBookings', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get failed payments
     */
    public function getFailedPayments(int $limit = 10): Collection
    {
        try {
            return Payment::where('status', 'failed')
                ->orderByDesc('failed_at')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getFailedPayments', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get comprehensive theme park statistics
     * Includes: total revenue (top-ups), total credits spent, wallet stats, activity breakdown
     */
    public function getThemeParkStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_theme_park_stats_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                // Total revenue from wallet top-ups
                $totalRevenue = $this->calculateThemeParkRevenue($startDate, $endDate);

                // Total credits spent on activities
                $creditsQuery = ThemeParkActivityTicket::whereIn('status', ['valid', 'redeemed']);
                if ($startDate) {
                    $creditsQuery->where('purchase_datetime', '>=', $startDate);
                }
                if ($endDate) {
                    $creditsQuery->where('purchase_datetime', '<=', $endDate);
                }
                $totalCreditsSpent = $creditsQuery->sum('credits_spent') ?? 0;

                // Total tickets sold
                $totalTicketsSold = $creditsQuery->count();

                // Wallet statistics
                $totalWallets = ThemeParkWallet::count();
                $walletsWithBalance = ThemeParkWallet::where('balance_mvr', '>', 0)->count();
                $totalBalanceAcrossWallets = ThemeParkWallet::sum('balance_mvr') ?? 0;
                $totalCreditsAcrossWallets = ThemeParkWallet::sum('credit_balance') ?? 0;

                // Average transaction values
                $avgTopUpAmount = ThemeParkWalletTransaction::where('transaction_type', 'top_up')
                    ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
                    ->avg('amount_mvr') ?? 0;

                return [
                    // Revenue metrics
                    'total_revenue' => $totalRevenue,
                    'average_top_up_amount' => round($avgTopUpAmount, 2),

                    // Credit usage metrics
                    'total_credits_spent' => $totalCreditsSpent,
                    'total_tickets_sold' => $totalTicketsSold,
                    'average_credits_per_ticket' => $totalTicketsSold > 0
                        ? round($totalCreditsSpent / $totalTicketsSold, 1)
                        : 0,

                    // Wallet metrics
                    'total_wallets' => $totalWallets,
                    'active_wallets' => $walletsWithBalance,
                    'total_balance_mvr' => round($totalBalanceAcrossWallets, 2),
                    'total_credits_balance' => $totalCreditsAcrossWallets,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getThemeParkStatistics', [
                'error' => $e->getMessage(),
            ]);

            return [
                'total_revenue' => 0,
                'average_top_up_amount' => 0,
                'total_credits_spent' => 0,
                'total_tickets_sold' => 0,
                'average_credits_per_ticket' => 0,
                'total_wallets' => 0,
                'active_wallets' => 0,
                'total_balance_mvr' => 0,
                'total_credits_balance' => 0,
                'error' => true,
            ];
        }
    }

    /**
     * Get most popular activities by credits spent
     */
    public function getPopularActivitiesByCredits(int $limit = 10, ?string $startDate = null, ?string $endDate = null): Collection
    {
        try {
            $cacheKey = sprintf('admin_popular_activities_%d_%s_%s', $limit, $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($limit, $startDate, $endDate) {
                $query = DB::table('theme_park_activity_tickets')
                    ->join('theme_park_activities', 'theme_park_activities.id', '=', 'theme_park_activity_tickets.activity_id')
                    ->leftJoin('theme_park_zones', 'theme_park_zones.id', '=', 'theme_park_activities.theme_park_zone_id')
                    ->whereIn('theme_park_activity_tickets.status', ['valid', 'redeemed']);

                if ($startDate) {
                    $query->where('theme_park_activity_tickets.purchase_datetime', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('theme_park_activity_tickets.purchase_datetime', '<=', $endDate);
                }

                return $query->select(
                        'theme_park_activities.id',
                        'theme_park_activities.name',
                        'theme_park_activities.activity_type',
                        'theme_park_zones.name as zone_name'
                    )
                    ->selectRaw('SUM(theme_park_activity_tickets.credits_spent) as total_credits')
                    ->selectRaw('COUNT(theme_park_activity_tickets.id) as ticket_count')
                    ->selectRaw('AVG(theme_park_activity_tickets.credits_spent) as avg_credits')
                    ->groupBy(
                        'theme_park_activities.id',
                        'theme_park_activities.name',
                        'theme_park_activities.activity_type',
                        'theme_park_zones.name'
                    )
                    ->orderByDesc('total_credits')
                    ->limit($limit)
                    ->get();
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getPopularActivitiesByCredits', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get activity type breakdown (credits spent by activity type)
     */
    public function getActivityTypeBreakdown(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $cacheKey = sprintf('admin_activity_type_breakdown_%s_%s', $startDate ?? 'all', $endDate ?? 'now');

            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
                $query = DB::table('theme_park_activity_tickets')
                    ->join('theme_park_activities', 'theme_park_activities.id', '=', 'theme_park_activity_tickets.activity_id')
                    ->whereIn('theme_park_activity_tickets.status', ['valid', 'redeemed']);

                if ($startDate) {
                    $query->where('theme_park_activity_tickets.purchase_datetime', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('theme_park_activity_tickets.purchase_datetime', '<=', $endDate);
                }

                $breakdown = $query->select('theme_park_activities.activity_type')
                    ->selectRaw('SUM(theme_park_activity_tickets.credits_spent) as total_credits')
                    ->selectRaw('COUNT(theme_park_activity_tickets.id) as ticket_count')
                    ->groupBy('theme_park_activities.activity_type')
                    ->orderByDesc('total_credits')
                    ->get()
                    ->keyBy('activity_type')
                    ->map(fn($item) => [
                        'total_credits' => $item->total_credits ?? 0,
                        'ticket_count' => $item->ticket_count ?? 0,
                    ])
                    ->toArray();

                return $breakdown;
            });
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getActivityTypeBreakdown', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get recent theme park transactions
     */
    public function getRecentThemeParkTransactions(int $limit = 10): Collection
    {
        try {
            return ThemeParkWalletTransaction::with('user')
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error - getRecentThemeParkTransactions', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }
}
