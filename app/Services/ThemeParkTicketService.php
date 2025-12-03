<?php

namespace App\Services;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkTicketRedemption;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ThemeParkTicketService
{
    /**
     * Redeem tickets for an activity.
     */
    public function redeemTickets(int $userId, int $activityId): array
    {
        try {
            DB::beginTransaction();

            // Get activity with zone
            $activity = ThemeParkActivity::with('zone')->find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            if (!$activity->is_active) {
                return [
                    'success' => false,
                    'message' => 'This activity is currently inactive.',
                ];
            }

            // Check if zone is currently open
            $zone = $activity->zone;
            $now = now()->format('H:i:s');
            if ($now < $zone->opening_time || $now > $zone->closing_time) {
                return [
                    'success' => false,
                    'message' => "This zone is closed. Opening hours: {$zone->opening_time} - {$zone->closing_time}.",
                ];
            }

            // Get wallet
            $wallet = ThemeParkWallet::getOrCreateForUser($userId);

            // Check if user has sufficient tickets
            if (!$wallet->hasSufficientTickets($activity->ticket_cost)) {
                return [
                    'success' => false,
                    'message' => "Insufficient tickets. You need {$activity->ticket_cost} ticket(s) but have {$wallet->ticket_balance}.",
                ];
            }

            // Deduct tickets from wallet
            $wallet->ticket_balance -= $activity->ticket_cost;
            $wallet->total_tickets_redeemed += $activity->ticket_cost;
            $wallet->save();

            // Create redemption record
            $redemption = ThemeParkTicketRedemption::create([
                'user_id' => $userId,
                'activity_id' => $activityId,
                'tickets_redeemed' => $activity->ticket_cost,
                'status' => 'pending',
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully redeemed {$activity->ticket_cost} ticket(s) for {$activity->name}.",
                'redemption' => $redemption->load('activity'),
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to redeem tickets: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate a redemption by code (staff function).
     */
    public function validateRedemption(string $redemptionCode, int $staffId): array
    {
        try {
            DB::beginTransaction();

            $redemption = ThemeParkTicketRedemption::where('redemption_reference', $redemptionCode)
                ->with(['activity', 'user'])
                ->first();

            if (!$redemption) {
                return [
                    'success' => false,
                    'message' => 'Redemption code not found.',
                ];
            }

            if ($redemption->status === 'validated') {
                return [
                    'success' => false,
                    'message' => 'This redemption has already been validated.',
                    'redemption' => $redemption,
                ];
            }

            if ($redemption->status === 'cancelled') {
                return [
                    'success' => false,
                    'message' => 'This redemption has been cancelled.',
                    'redemption' => $redemption,
                ];
            }

            // Validate the redemption
            $redemption->validate($staffId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Redemption validated successfully.',
                'redemption' => $redemption->fresh(['activity', 'user', 'validatedBy']),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to validate redemption: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get statistics for staff's assigned zone.
     */
    public function getStaffStats(int $zoneId): array
    {
        $zone = ThemeParkZone::with('activities')->find($zoneId);

        if (!$zone) {
            return [];
        }

        $activityIds = $zone->activities->pluck('id');

        $totalRedemptions = ThemeParkTicketRedemption::whereIn('activity_id', $activityIds)->count();
        $pendingRedemptions = ThemeParkTicketRedemption::whereIn('activity_id', $activityIds)
            ->where('status', 'pending')
            ->count();
        $validatedRedemptions = ThemeParkTicketRedemption::whereIn('activity_id', $activityIds)
            ->where('status', 'validated')
            ->count();
        $totalActivities = $zone->activities->count();
        $activeActivities = $zone->activities->where('is_active', true)->count();

        return [
            'zone' => $zone,
            'total_activities' => $totalActivities,
            'active_activities' => $activeActivities,
            'total_redemptions' => $totalRedemptions,
            'pending_redemptions' => $pendingRedemptions,
            'validated_redemptions' => $validatedRedemptions,
        ];
    }

    /**
     * Get recent redemptions for a zone.
     */
    public function getRecentRedemptions(int $zoneId, int $limit = 10): Collection
    {
        $zone = ThemeParkZone::with('activities')->find($zoneId);

        if (!$zone) {
            return collect([]);
        }

        $activityIds = $zone->activities->pluck('id');

        return ThemeParkTicketRedemption::whereIn('activity_id', $activityIds)
            ->with(['activity', 'user', 'validatedBy'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
