<?php

namespace App\Livewire\Hotel\Policies;

use App\Livewire\Hotel\Traits\HasHotelSelection;
use App\Models\Hotel;
use App\Models\HotelPolicy;
use App\Models\RoomTypePolicyOverride;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Manage extends Component
{
    use HasHotelSelection;

    public $hotel;
    public $policyTypes;
    public $roomTypes;
    public $refreshKey = 0;
    public $showToast = null;

    // Policy form properties
    public $selectedPolicyType = '';
    public $policyTitle = '';
    public $policyDescription = '';
    public $policyIsActive = true;
    public $editingPolicyId = null;
    public $showPolicyForm = false;

    // Override form properties
    public $selectedOverrideRoomType = '';
    public $selectedOverridePolicyType = '';
    public $overrideTitle = '';
    public $overrideDescription = '';
    public $editingOverrideId = null;
    public $showOverrideForm = false;

    // Delete confirmation properties
    public $deletingPolicyId = null;
    public $deletingOverrideId = null;

    public function mount()
    {
        $this->initializeHotelSelection();
        $this->policyTypes = HotelPolicy::getPolicyTypes();
        $this->roomTypes = RoomTypePolicyOverride::getRoomTypes();
    }

    public function onHotelChanged()
    {
        $this->refreshKey++;
    }

    public function getPolicies()
    {
        return HotelPolicy::where('hotel_id', $this->hotel->id)
            ->orderBy('policy_type')
            ->get()
            ->groupBy('policy_type');
    }

    // Policy Methods
    protected function policyRules()
    {
        return [
            'selectedPolicyType' => 'required|string',
            'policyTitle' => 'required|string|max:255',
            'policyDescription' => 'required|string',
            'policyIsActive' => 'boolean',
        ];
    }

    public function openPolicyForm($policyType = null)
    {
        $this->showPolicyForm = true;
        $this->reset(['policyTitle', 'policyDescription', 'editingPolicyId']);
        $this->policyIsActive = true;

        if ($policyType) {
            $this->selectedPolicyType = $policyType;
        }
    }

    public function closePolicyForm()
    {
        $this->showPolicyForm = false;
        $this->reset(['selectedPolicyType', 'policyTitle', 'policyDescription', 'policyIsActive', 'editingPolicyId']);
    }

    public function savePolicy()
    {
        $this->validate($this->policyRules());

        if ($this->editingPolicyId) {
            $policy = HotelPolicy::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingPolicyId);

            $policy->update([
                'policy_type' => $this->selectedPolicyType,
                'title' => $this->policyTitle,
                'description' => $this->policyDescription,
                'is_active' => $this->policyIsActive,
            ]);

            session()->flash('success', 'Policy updated successfully!');
        } else {
            // Check if policy already exists
            $existingPolicy = HotelPolicy::where('hotel_id', $this->hotel->id)
                ->where('policy_type', $this->selectedPolicyType)
                ->first();

            if ($existingPolicy) {
                $policyTypeName = $this->policyTypes[$this->selectedPolicyType] ?? $this->selectedPolicyType;
                $this->showToast = [
                    'type' => 'warning',
                    'title' => 'Policy Already Exists',
                    'message' => "A {$policyTypeName} already exists for this hotel. Please edit the existing policy instead.",
                ];
                return;
            }

            HotelPolicy::create([
                'hotel_id' => $this->hotel->id,
                'policy_type' => $this->selectedPolicyType,
                'title' => $this->policyTitle,
                'description' => $this->policyDescription,
                'is_active' => $this->policyIsActive,
            ]);

            session()->flash('success', 'Policy created successfully!');
        }

        $this->refreshKey++;
        $this->closePolicyForm();
    }

    public function editPolicy($policyId)
    {
        $policy = HotelPolicy::where('hotel_id', $this->hotel->id)
            ->findOrFail($policyId);

        $this->editingPolicyId = $policy->id;
        $this->selectedPolicyType = $policy->policy_type;
        $this->policyTitle = $policy->title;
        $this->policyDescription = $policy->description;
        $this->policyIsActive = $policy->is_active;
        $this->showPolicyForm = true;
    }

    public function confirmDeletePolicy($policyId)
    {
        $this->deletingPolicyId = $policyId;
    }

    public function deletePolicy()
    {
        if (!$this->deletingPolicyId) {
            return;
        }

        $policy = HotelPolicy::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingPolicyId);

        $policy->delete();

        session()->flash('success', 'Policy deleted successfully!');
        $this->deletingPolicyId = null;
        $this->refreshKey++;
    }

    public function togglePolicyStatus($policyId)
    {
        $policy = HotelPolicy::where('hotel_id', $this->hotel->id)
            ->findOrFail($policyId);

        $policy->update(['is_active' => !$policy->is_active]);

        $this->refreshKey++;
    }

    // Override Methods
    protected function overrideRules()
    {
        return [
            'selectedOverrideRoomType' => 'required|string',
            'selectedOverridePolicyType' => 'required|string',
            'overrideTitle' => 'required|string|max:255',
            'overrideDescription' => 'required|string',
        ];
    }

    public function openOverrideForm($policyType = null)
    {
        $this->showOverrideForm = true;
        $this->reset(['selectedOverrideRoomType', 'overrideTitle', 'overrideDescription', 'editingOverrideId']);

        if ($policyType) {
            $this->selectedOverridePolicyType = $policyType;
        }
    }

    public function closeOverrideForm()
    {
        $this->showOverrideForm = false;
        $this->reset(['selectedOverrideRoomType', 'selectedOverridePolicyType', 'overrideTitle', 'overrideDescription', 'editingOverrideId']);
    }

    public function saveOverride()
    {
        $this->validate($this->overrideRules());

        if ($this->editingOverrideId) {
            $override = RoomTypePolicyOverride::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingOverrideId);

            $override->update([
                'room_type' => $this->selectedOverrideRoomType,
                'policy_type' => $this->selectedOverridePolicyType,
                'title' => $this->overrideTitle,
                'description' => $this->overrideDescription,
            ]);

            session()->flash('success', 'Room type override updated successfully!');
        } else {
            // Check if override already exists
            $existingOverride = RoomTypePolicyOverride::where('hotel_id', $this->hotel->id)
                ->where('room_type', $this->selectedOverrideRoomType)
                ->where('policy_type', $this->selectedOverridePolicyType)
                ->first();

            if ($existingOverride) {
                $policyTypeName = $this->policyTypes[$this->selectedOverridePolicyType] ?? $this->selectedOverridePolicyType;
                $roomTypeName = $this->roomTypes[$this->selectedOverrideRoomType] ?? $this->selectedOverrideRoomType;
                $this->showToast = [
                    'type' => 'warning',
                    'title' => 'Override Already Exists',
                    'message' => "A {$policyTypeName} override for {$roomTypeName} rooms already exists. Please edit the existing override instead.",
                ];
                return;
            }

            RoomTypePolicyOverride::create([
                'hotel_id' => $this->hotel->id,
                'room_type' => $this->selectedOverrideRoomType,
                'policy_type' => $this->selectedOverridePolicyType,
                'title' => $this->overrideTitle,
                'description' => $this->overrideDescription,
            ]);

            session()->flash('success', 'Room type override created successfully!');
        }

        $this->refreshKey++;
        $this->closeOverrideForm();
    }

    public function editOverride($overrideId)
    {
        $override = RoomTypePolicyOverride::where('hotel_id', $this->hotel->id)
            ->findOrFail($overrideId);

        $this->editingOverrideId = $override->id;
        $this->selectedOverrideRoomType = $override->room_type;
        $this->selectedOverridePolicyType = $override->policy_type;
        $this->overrideTitle = $override->title;
        $this->overrideDescription = $override->description;
        $this->showOverrideForm = true;
    }

    public function confirmDeleteOverride($overrideId)
    {
        $this->deletingOverrideId = $overrideId;
    }

    public function deleteOverride()
    {
        if (!$this->deletingOverrideId) {
            return;
        }

        $override = RoomTypePolicyOverride::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingOverrideId);

        $override->delete();

        session()->flash('success', 'Room type override deleted successfully!');
        $this->deletingOverrideId = null;
        $this->refreshKey++;
    }

    public function getOverridesForPolicyType($policyType)
    {
        return RoomTypePolicyOverride::where('hotel_id', $this->hotel->id)
            ->where('policy_type', $policyType)
            ->get();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.policies.manage', [
            'assignedHotels' => $this->assignedHotels,
        ]);
    }
}
