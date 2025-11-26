<?php

namespace App\Livewire\Admin\Users;

use App\Enums\UserRole;
use App\Models\User;
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
    public $userId;
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
            'role' => 'required|in:' . implode(',', UserRole::values()),
            'is_active' => 'boolean',
        ];

        if ($this->showCreateModal) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|confirmed';
        } elseif ($this->showEditModal) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->userId;
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

    public function openEditModal($userId)
    {
        $this->resetForm();
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role->value;
        $this->is_active = $user->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->userId = $userId;
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'User created successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        session()->flash('message', 'User updated successfully.');
        $this->closeModals();
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userId);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->closeModals();
            return;
        }

        $user->delete();

        session()->flash('message', 'User deleted successfully.');
        $this->closeModals();
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "User {$status} successfully.");
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);

        // Generate a random password
        $newPassword = 'password'; // In production, generate a random password
        $user->update(['password' => Hash::make($newPassword)]);

        session()->flash('message', "Password reset successfully. New password: {$newPassword}");
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
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

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => UserRole::options(),
        ])->layout('layouts.app');
    }
}
