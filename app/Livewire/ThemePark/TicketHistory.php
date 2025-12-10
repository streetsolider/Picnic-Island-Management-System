<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Models\ThemeParkActivityTicket;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Ticket History')]
class TicketHistory extends Component
{
    use WithPagination;

    public $selectedActivityId = null;
    public $timeFilter = '24'; // hours
    public $viewMode = 'unredeemed'; // 'unredeemed' or 'redeemed'

    public $isManager = false;

    public function mount()
    {
        // Check if user is a manager
        $this->isManager = auth('staff')->user()->role->value === 'theme_park_manager';

        // Get assigned activities
        $activities = $this->getAssignedActivities();

        // Set default activity
        $this->selectedActivityId = $activities->first()?->id;
    }

    public function updatedSelectedActivityId()
    {
        $this->resetPage();
    }

    public function updatedTimeFilter()
    {
        $this->resetPage();
    }

    public function updatedViewMode()
    {
        $this->resetPage();
    }

    public function switchView($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    private function getAssignedActivities()
    {
        if ($this->isManager) {
            return ThemeParkActivity::where('is_active', true)
                ->with('zone')
                ->orderBy('name')
                ->get();
        } else {
            return ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
                ->where('is_active', true)
                ->with('zone')
                ->orderBy('name')
                ->get();
        }
    }

    public function render()
    {
        $assignedActivities = $this->getAssignedActivities();

        // Build query based on selected activity and time filter
        $query = ThemeParkActivityTicket::with(['guest', 'activity', 'showSchedule'])
            ->where('activity_id', $this->selectedActivityId)
            ->where('purchase_datetime', '>=', now()->subHours((int)$this->timeFilter));

        // Filter by redemption status
        if ($this->viewMode === 'redeemed') {
            $query->where('status', 'redeemed')
                  ->orderBy('redeemed_at', 'desc');
        } else {
            $query->where('status', 'valid')
                  ->orderBy('purchase_datetime', 'desc');
        }

        $tickets = $query->paginate(20);

        // Get counts for badges
        $redeemedCount = ThemeParkActivityTicket::where('activity_id', $this->selectedActivityId)
            ->where('purchase_datetime', '>=', now()->subHours((int)$this->timeFilter))
            ->where('status', 'redeemed')
            ->count();

        $unredeemedCount = ThemeParkActivityTicket::where('activity_id', $this->selectedActivityId)
            ->where('purchase_datetime', '>=', now()->subHours((int)$this->timeFilter))
            ->where('status', 'valid')
            ->count();

        // Get total persons count
        $totalRedeemedPersons = ThemeParkActivityTicket::where('activity_id', $this->selectedActivityId)
            ->where('purchase_datetime', '>=', now()->subHours((int)$this->timeFilter))
            ->where('status', 'redeemed')
            ->sum('quantity');

        $totalUnredeemedPersons = ThemeParkActivityTicket::where('activity_id', $this->selectedActivityId)
            ->where('purchase_datetime', '>=', now()->subHours((int)$this->timeFilter))
            ->where('status', 'valid')
            ->sum('quantity');

        return view('livewire.theme-park.ticket-history', [
            'assignedActivities' => $assignedActivities,
            'tickets' => $tickets,
            'redeemedCount' => $redeemedCount,
            'unredeemedCount' => $unredeemedCount,
            'totalRedeemedPersons' => $totalRedeemedPersons,
            'totalUnredeemedPersons' => $totalUnredeemedPersons,
        ]);
    }
}
