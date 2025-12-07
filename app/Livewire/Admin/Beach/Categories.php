<?php

namespace App\Livewire\Admin\Beach;

use App\Models\BeachActivityCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = '';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $categoryId;
    public $name;
    public $description;
    public $icon;
    public $is_active = true;

    protected $queryString = ['search', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:beach_activity_categories,name,' . ($this->categoryId ?? 'NULL'),
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
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

    public function openEditModal($categoryId)
    {
        $this->resetForm();
        $category = BeachActivityCategory::findOrFail($categoryId);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->is_active = $category->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($categoryId)
    {
        $this->categoryId = $categoryId;
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
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->icon = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function createCategory()
    {
        $this->validate();

        BeachActivityCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach activity category created successfully.');
        $this->closeModals();
    }

    public function updateCategory()
    {
        $this->validate();

        $category = BeachActivityCategory::findOrFail($this->categoryId);

        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Beach activity category updated successfully.');
        $this->closeModals();
    }

    public function deleteCategory()
    {
        $category = BeachActivityCategory::findOrFail($this->categoryId);

        // Check if category has services
        if ($category->services()->count() > 0) {
            session()->flash('error', 'Cannot delete category with existing services. Please delete or reassign services first.');
            $this->closeModals();
            return;
        }

        $category->delete();

        session()->flash('message', 'Beach activity category deleted successfully.');
        $this->closeModals();
    }

    public function toggleStatus($categoryId)
    {
        $category = BeachActivityCategory::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Beach activity category {$status} successfully.");
    }

    public function render()
    {
        $categories = BeachActivityCategory::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->withCount('services')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.beach.categories', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
