<?php

namespace App\Services;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivitySchedule;
use App\Models\ThemeParkZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ThemeParkActivityService
{
    /**
     * Create a new activity.
     */
    public function createActivity(array $data, ?int $staffId = null): array
    {
        try {
            DB::beginTransaction();

            // If staff is provided, verify they manage the zone
            if ($staffId) {
                $zone = ThemeParkZone::find($data['theme_park_zone_id']);
                if (!$zone || $zone->assigned_staff_id !== $staffId) {
                    return [
                        'success' => false,
                        'message' => 'You are not authorized to create activities in this zone.',
                    ];
                }
            }

            $activity = ThemeParkActivity::create($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Activity created successfully.',
                'activity' => $activity->load('zone'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create activity: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update an activity.
     */
    public function updateActivity(int $activityId, array $data, ?int $staffId = null): array
    {
        try {
            DB::beginTransaction();

            $activity = ThemeParkActivity::with('zone')->find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            // If staff is provided, verify they manage the zone
            if ($staffId && $activity->zone->assigned_staff_id !== $staffId) {
                return [
                    'success' => false,
                    'message' => 'You are not authorized to update this activity.',
                ];
            }

            $activity->update($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Activity updated successfully.',
                'activity' => $activity->fresh('zone'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update activity: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Toggle activity active status.
     */
    public function toggleActive(int $activityId, ?int $staffId = null): array
    {
        try {
            $activity = ThemeParkActivity::with('zone')->find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            // If staff is provided, verify they manage the zone
            if ($staffId && $activity->zone->assigned_staff_id !== $staffId) {
                return [
                    'success' => false,
                    'message' => 'You are not authorized to modify this activity.',
                ];
            }

            $activity->is_active = !$activity->is_active;
            $activity->save();

            $status = $activity->is_active ? 'activated' : 'deactivated';

            return [
                'success' => true,
                'message' => "Activity {$status} successfully.",
                'activity' => $activity,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to toggle activity status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete an activity.
     */
    public function deleteActivity(int $activityId, ?int $staffId = null): array
    {
        try {
            DB::beginTransaction();

            $activity = ThemeParkActivity::with(['zone', 'redemptions'])->find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            // If staff is provided, verify they manage the zone
            if ($staffId && $activity->zone->assigned_staff_id !== $staffId) {
                return [
                    'success' => false,
                    'message' => 'You are not authorized to delete this activity.',
                ];
            }

            // Check if there are any redemptions
            if ($activity->redemptions()->count() > 0) {
                return [
                    'success' => false,
                    'message' => 'Cannot delete activity with existing redemptions.',
                ];
            }

            $activity->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Activity deleted successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete activity: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create a schedule for an activity.
     */
    public function createSchedule(int $activityId, array $data): array
    {
        try {
            $activity = ThemeParkActivity::find($activityId);

            if (!$activity) {
                return [
                    'success' => false,
                    'message' => 'Activity not found.',
                ];
            }

            $schedule = ThemeParkActivitySchedule::create([
                'activity_id' => $activityId,
                'schedule_date' => $data['schedule_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'available_slots' => $data['available_slots'] ?? $activity->capacity_per_session,
                'booked_slots' => 0,
            ]);

            return [
                'success' => true,
                'message' => 'Schedule created successfully.',
                'schedule' => $schedule->load('activity'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create schedule: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get schedules for an activity.
     */
    public function getActivitySchedules(int $activityId, ?string $date = null): Collection
    {
        $query = ThemeParkActivitySchedule::where('activity_id', $activityId);

        if ($date) {
            $query->where('schedule_date', $date);
        } else {
            $query->where('schedule_date', '>=', now()->toDateString());
        }

        return $query->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();
    }
}
