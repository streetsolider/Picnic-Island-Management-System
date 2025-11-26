<?php

namespace App\Livewire\Hotel\Amenities;

use App\Models\AmenityCategory;
use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Categories extends Component
{
    public $hotel;
    public $categories;

    // Form properties
    public $name = '';
    public $description = '';
    public $editingId = null;
    public $showForm = false;

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth()->user()->staff->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->withCount('amenities')
            ->orderBy('sort_order')
            ->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->reset(['name', 'description', 'editingId']);
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['name', 'description', 'editingId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            // Update existing category
            $category = AmenityCategory::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingId);

            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            session()->flash('success', 'Category updated successfully!');
        } else {
            // Create new category
            AmenityCategory::create([
                'hotel_id' => $this->hotel->id,
                'name' => $this->name,
                'description' => $this->description,
                'sort_order' => AmenityCategory::where('hotel_id', $this->hotel->id)->count(),
            ]);

            session()->flash('success', 'Category created successfully!');
        }

        $this->loadCategories();
        $this->closeForm();
    }

    public function edit($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showForm = true;
    }

    public function delete($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $category->delete();

        session()->flash('success', 'Category deleted successfully!');
        $this->loadCategories();
    }

    public function toggleStatus($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $category->update(['is_active' => !$category->is_active]);

        $this->loadCategories();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.amenities.categories');
    }
}
