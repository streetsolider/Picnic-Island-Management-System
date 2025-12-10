<?php

namespace App\Livewire\ThemePark;

use App\Models\ThemeParkActivity;
use App\Services\ThemeParkValidationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate as ValidateAttribute;

#[Layout('layouts.staff')]
#[Title('Validate Activity Tickets')]
class Validate extends Component
{
    public $selectedActivityId = null;
    public $searchCode = '';
    public $ticket = null;
    public $validationResult = null;
    public $searchPerformed = false;

    public function mount()
    {
        // Get assigned activities
        $activities = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
            ->where('is_active', true)
            ->get();

        // Get selected activity from session or use first
        $this->selectedActivityId = session('selected_activity_id', $activities->first()?->id);
    }

    public function selectActivity($activityId)
    {
        $this->selectedActivityId = $activityId;
        session(['selected_activity_id' => $activityId]);
        $this->resetSearch();
    }

    public function searchRedemption()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        if (!$this->selectedActivityId) {
            session()->flash('error', 'Please select an activity first.');
            return;
        }

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);

        // First, check ticket status without redeeming
        $checkResult = $service->checkTicketStatus($this->searchCode);

        if (!$checkResult['success']) {
            session()->flash('error', $checkResult['message']);
            if (isset($checkResult['ticket'])) {
                $this->ticket = $checkResult['ticket'];
            }
            return;
        }

        // Check if ticket is for the selected activity BEFORE redeeming
        if ($checkResult['ticket']->activity_id !== $this->selectedActivityId) {
            $selectedActivity = ThemeParkActivity::find($this->selectedActivityId);
            session()->flash('error', "This ticket is for '{$checkResult['ticket']->activity->name}', but you selected '{$selectedActivity->name}'. Cannot redeem.");
            $this->ticket = $checkResult['ticket'];
            return;
        }

        // Now redeem the ticket (only if it matches the selected activity)
        $result = $service->validateAndRedeemTicket($this->searchCode, auth('staff')->id());

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
            if (isset($result['ticket'])) {
                $this->ticket = $result['ticket'];
            }
        }

        $this->searchCode = '';
    }

    public function validateTicket()
    {
        // Alias for searchRedemption for backward compatibility
        return $this->searchRedemption();
    }

    public function checkStatus()
    {
        $this->validate([
            'searchCode' => 'required|string|min:8',
        ]);

        $this->searchPerformed = true;
        $this->ticket = null;
        $this->validationResult = null;

        $service = app(ThemeParkValidationService::class);
        $result = $service->checkTicketStatus($this->searchCode);

        $this->validationResult = $result;

        if ($result['success']) {
            $this->ticket = $result['ticket'];
            session()->flash('info', 'Ticket status retrieved (not redeemed).');
        } else {
            session()->flash('error', $result['message']);
        }

        $this->searchCode = '';
    }

    public function resetSearch()
    {
        $this->reset(['searchCode', 'ticket', 'validationResult', 'searchPerformed']);
        $this->resetValidation();
    }

    public function render()
    {
        // Get staff's assigned activities
        $assignedActivities = ThemeParkActivity::where('assigned_staff_id', auth('staff')->id())
            ->where('is_active', true)
            ->with('zone')
            ->get();

        $selectedActivity = $this->selectedActivityId
            ? ThemeParkActivity::with('zone')->find($this->selectedActivityId)
            : null;

        return view('livewire.theme-park.validate', [
            'assignedActivities' => $assignedActivities,
            'selectedActivity' => $selectedActivity,
            'hasAssignedActivities' => $assignedActivities->isNotEmpty(),
        ]);
    }
}
