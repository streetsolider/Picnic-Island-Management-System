<?php

namespace App\Livewire\Admin\Ferry\Vessels;

use App\Models\Ferry\FerryVessel;
use App\Models\Staff;
use App\Enums\StaffRole;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $vesselId;
    public $name;
    public $registration_number;
    public $vessel_type = 'Ferry';
    public $capacity;
    public $operator_id;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        $registrationRule = 'required|string|max:255|unique:ferry_vessels,registration_number';

        if ($this->vesselId) {
            $registrationRule .= ',' . $this->vesselId;
        }

        return [
            'name' => 'required|string|max:255',
            'registration_number' => $registrationRule,
            'vessel_type' => 'required|in:Ferry,Speed Boat,Boat',
            'capacity' => 'required|integer|min:1',
            'operator_id' => 'nullable|exists:staff,id',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($vesselId)
    {
        $this->resetForm();
        $vessel = FerryVessel::findOrFail($vesselId);

        $this->vesselId = $vessel->id;
        $this->name = $vessel->name;
        $this->registration_number = $vessel->registration_number;
        $this->vessel_type = $vessel->vessel_type;
        $this->capacity = $vessel->capacity;
        $this->operator_id = $vessel->operator_id;
        $this->is_active = $vessel->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($vesselId)
    {
        $this->vesselId = $vesselId;
        $this->showDeleteModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->vesselId = null;
        $this->name = '';
        $this->registration_number = '';
        $this->vessel_type = 'Ferry';
        $this->capacity = '';
        $this->operator_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createVessel()
    {
        $this->validate();

        FerryVessel::create([
            'name' => $this->name,
            'registration_number' => $this->registration_number,
            'vessel_type' => $this->vessel_type,
            'capacity' => $this->capacity,
            'operator_id' => $this->operator_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry vessel created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateVessel()
    {
        $this->validate();

        $vessel = FerryVessel::findOrFail($this->vesselId);

        $vessel->update([
            'name' => $this->name,
            'registration_number' => $this->registration_number,
            'vessel_type' => $this->vessel_type,
            'capacity' => $this->capacity,
            'operator_id' => $this->operator_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Ferry vessel updated successfully.');
        $this->closeModals();
    }

    public function deleteVessel()
    {
        $vessel = FerryVessel::findOrFail($this->vesselId);
        $vessel->delete();

        session()->flash('message', 'Ferry vessel deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($vesselId)
    {
        $vessel = FerryVessel::findOrFail($vesselId);
        $vessel->update(['is_active' => !$vessel->is_active]);

        $status = $vessel->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Ferry vessel {$status} successfully.");
    }

    public function render()
    {
        $vessels = FerryVessel::query()
            ->with('operator')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('registration_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $ferryOperators = Staff::where('role', StaffRole::FERRY_OPERATOR)
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.ferry.vessels.index', [
            'vessels' => $vessels,
            'ferryOperators' => $ferryOperators,
        ]);
    }
}
