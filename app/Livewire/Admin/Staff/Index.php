<?php

namespace App\Livewire\Admin\Staff;

use App\Enums\StaffRole;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $staffId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;
    public $is_active = true;

    protected $queryString = ['search', 'roleFilter', 'statusFilter'];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:' . implode(',', StaffRole::values()),
            'is_active' => 'boolean',
        ];

        if ($this->showCreateModal) {
            $rules['email'] = 'required|email|unique:staff,email';
            $rules['password'] = 'required|min:8|confirmed';
        } elseif ($this->showEditModal) {
            $rules['email'] = 'required|email|unique:staff,email,' . $this->staffId;
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
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

    public function openEditModal($staffId)
    {
        $this->resetForm();
        $staff = Staff::findOrFail($staffId);

        $this->staffId = $staff->id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->role = $staff->role->value;
        $this->is_active = $staff->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($staffId)
    {
        $this->staffId = $staffId;
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
        $this->staffId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createStaff()
    {
        $this->validate();

        Staff::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Staff member created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateStaff()
    {
        $this->validate();

        $staff = Staff::findOrFail($this->staffId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $staff->update($data);

        session()->flash('message', 'Staff member updated successfully.');
        $this->closeModals();
    }

    public function deleteStaff()
    {
        $staff = Staff::findOrFail($this->staffId);

        // Prevent deleting yourself
        if ($staff->id === auth('staff')->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->closeModals();
            return;
        }

        $staff->delete();

        session()->flash('message', 'Staff member deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($staffId)
    {
        $staff = Staff::findOrFail($staffId);

        // Prevent deactivating yourself
        if ($staff->id === auth('staff')->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $staff->update(['is_active' => !$staff->is_active]);

        $status = $staff->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Staff member {$status} successfully.");
    }

    public function resetPassword($staffId)
    {
        $staff = Staff::findOrFail($staffId);

        // Generate a random password
        $newPassword = 'password'; // In production, generate a random password
        $staff->update(['password' => Hash::make($newPassword)]);

        session()->flash('message', "Password reset successfully. New password: {$newPassword}");
    }

    public function render()
    {
        $staff = Staff::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('staff_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.staff.index', [
            'staff' => $staff,
            'roles' => StaffRole::options(),
        ])->layout('layouts.app');
    }
}
