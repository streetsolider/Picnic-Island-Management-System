<?php

namespace App\Livewire\Admin;

use App\Models\Staff;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\ThemeParkZone;
use App\Models\BeachService;
use App\Enums\StaffRole;
use App\Services\AdminAnalyticsService;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    // Legacy stats
    public array $stats = [];

    // Analytics data
    public array $revenueData = [];
    public array $bookingStats = [];
    public array $paymentStats = [];
    public array $guestStats = [];
    public array $metrics = [];
    public array $ferryStats = [];
    public array $revenueTrend = [];

    // Top performers
    public $topHotels = [];
    public $popularBeachServices = [];

    // Theme park data
    public array $themeParkStats = [];
    public $popularActivities = [];
    public array $activityTypeBreakdown = [];
    public $recentThemeParkTransactions = [];

    // Recent activity
    public $recentBookings = [];
    public $failedPayments = [];

    // Filters
    public string $dateFilter = 'all_time';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public bool $isLoading = false;

    // Auto-refresh
    public int $refreshInterval = 0; // 0 = disabled

    protected AdminAnalyticsService $analyticsService;

    public function boot(AdminAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function mount()
    {
        // Load both legacy stats and new analytics
        $this->loadStats();
        $this->loadAnalytics();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_staff' => Staff::count(),
            'total_guests' => Guest::count(),
            'total_hotels' => Hotel::count(),
            'active_hotels' => Hotel::where('is_active', true)->count(),
            'total_zones' => ThemeParkZone::count(),
            'active_zones' => ThemeParkZone::where('is_active', true)->count(),
            'total_beach_services' => BeachService::count(),
            'active_beach_services' => BeachService::where('is_active', true)->count(),
            'hotel_managers' => Staff::where('role', StaffRole::HOTEL_MANAGER)->count(),
            'ferry_operators' => Staff::where('role', StaffRole::FERRY_OPERATOR)->count(),
            'theme_park_staff' => Staff::where('role', StaffRole::THEME_PARK_STAFF)->count(),
            'administrators' => Staff::where('role', StaffRole::ADMINISTRATOR)->count(),
        ];
    }

    public function loadAnalytics()
    {
        $this->isLoading = true;

        try {
            // Get revenue data
            $this->revenueData = $this->analyticsService->getSystemRevenue($this->startDate, $this->endDate);

            // Get booking stats
            $this->bookingStats = $this->analyticsService->getBookingStats($this->startDate, $this->endDate);

            // Get payment stats - REMOVED as per request
            // $this->paymentStats = $this->analyticsService->getPaymentStats($this->startDate, $this->endDate);

            // Get guest stats
            $this->guestStats = $this->analyticsService->getGuestStats();

            // Get additional business metrics
            $this->metrics = $this->analyticsService->getAdditionalMetrics($this->startDate, $this->endDate);

            // Get ferry statistics (non-revenue)
            $this->ferryStats = $this->analyticsService->getFerryStatistics($this->startDate, $this->endDate);

            // Get revenue trend
            $this->revenueTrend = $this->analyticsService->getDailyRevenueTrend(30);

            // Get top performers
            $this->topHotels = $this->analyticsService->getTopHotelsByRevenue(5);
            $this->popularBeachServices = $this->analyticsService->getPopularBeachServices(5);

            // Get theme park data
            $this->themeParkStats = $this->analyticsService->getThemeParkStatistics($this->startDate, $this->endDate);
            $this->popularActivities = $this->analyticsService->getPopularActivitiesByCredits(10, $this->startDate, $this->endDate);
            $this->activityTypeBreakdown = $this->analyticsService->getActivityTypeBreakdown($this->startDate, $this->endDate);
            $this->recentThemeParkTransactions = $this->analyticsService->getRecentThemeParkTransactions(5);

            // Get recent activity
            $this->recentBookings = $this->analyticsService->getRecentBookings(5);
            $this->failedPayments = $this->analyticsService->getFailedPayments(10);
        } finally {
            $this->isLoading = false;
        }
    }

    public function applyDateFilter(string $filter)
    {
        $this->dateFilter = $filter;

        // Set date range based on filter
        match ($filter) {
            'today' => $this->setDateRange(now()->startOfDay()->toDateString(), now()->endOfDay()->toDateString()),
            'last_7_days' => $this->setDateRange(now()->subDays(7)->startOfDay()->toDateString(), now()->endOfDay()->toDateString()),
            'this_week' => $this->setDateRange(now()->startOfWeek()->toDateString(), now()->endOfDay()->toDateString()),
            'last_30_days' => $this->setDateRange(now()->subDays(30)->startOfDay()->toDateString(), now()->endOfDay()->toDateString()),
            'this_month' => $this->setDateRange(now()->startOfMonth()->toDateString(), now()->endOfDay()->toDateString()),
            'this_quarter' => $this->setDateRange(now()->startOfQuarter()->toDateString(), now()->endOfDay()->toDateString()),
            'this_year' => $this->setDateRange(now()->startOfYear()->toDateString(), now()->endOfDay()->toDateString()),
            'all_time' => $this->setDateRange(null, null),
            default => null,
        };

        $this->loadAnalytics();
    }

    protected function setDateRange(?string $start, ?string $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;
    }

    public function toggleAutoRefresh()
    {
        $this->refreshInterval = $this->refreshInterval === 0 ? 60 : 0;
    }

    #[Computed]
    public function systemRevenue(): float
    {
        return $this->revenueData['total_revenue'] ?? 0;
    }

    #[Computed]
    public function todayRevenue(): float
    {
        return $this->revenueData['today_revenue'] ?? 0;
    }

    #[Computed]
    public function occupancyRate(): float
    {
        return $this->bookingStats['occupancy_rate'] ?? 0;
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}
