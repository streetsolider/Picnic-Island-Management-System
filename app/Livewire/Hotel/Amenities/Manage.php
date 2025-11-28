<?php

namespace App\Livewire\Hotel\Amenities;

use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Manage extends Component
{
    public $hotel;
    public $categories;

    // Category form properties
    public $categoryName = '';
    public $categoryDescription = '';
    public $editingCategoryId = null;
    public $showCategoryForm = false;

    // Amenity form properties
    public $selectedCategoryId = '';
    public $amenityName = '';
    public $amenityDescription = '';
    public $editingAmenityId = null;
    public $showAmenityForm = false;

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->withCount('amenities')
            ->with(['amenities' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
    }

    // Category Methods
    protected function categoryRules()
    {
        return [
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string|max:500',
        ];
    }

    public function openCategoryForm()
    {
        $this->showCategoryForm = true;
        $this->reset(['categoryName', 'categoryDescription', 'editingCategoryId']);
    }

    public function closeCategoryForm()
    {
        $this->showCategoryForm = false;
        $this->reset(['categoryName', 'categoryDescription', 'editingCategoryId']);
    }

    public function saveCategory()
    {
        $this->validate($this->categoryRules());

        if ($this->editingCategoryId) {
            $category = AmenityCategory::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingCategoryId);

            $category->update([
                'name' => $this->categoryName,
                'description' => $this->categoryDescription,
            ]);

            session()->flash('success', 'Category updated successfully!');
        } else {
            AmenityCategory::create([
                'hotel_id' => $this->hotel->id,
                'name' => $this->categoryName,
                'description' => $this->categoryDescription,
                'sort_order' => AmenityCategory::where('hotel_id', $this->hotel->id)->count(),
            ]);

            session()->flash('success', 'Category created successfully!');
        }

        $this->loadCategories();
        $this->closeCategoryForm();
    }

    public function editCategory($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $this->editingCategoryId = $category->id;
        $this->categoryName = $category->name;
        $this->categoryDescription = $category->description;
        $this->showCategoryForm = true;
    }

    public function deleteCategory($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $category->delete();

        session()->flash('success', 'Category deleted successfully!');
        $this->loadCategories();
    }

    public function toggleCategoryStatus($categoryId)
    {
        $category = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->findOrFail($categoryId);

        $category->update(['is_active' => !$category->is_active]);

        $this->loadCategories();
    }

    // Amenity Methods
    protected function amenityRules()
    {
        return [
            'selectedCategoryId' => 'required|exists:amenity_categories,id',
            'amenityName' => 'required|string|max:255',
            'amenityDescription' => 'nullable|string|max:500',
        ];
    }

    public function openAmenityForm($categoryId = null)
    {
        $this->showAmenityForm = true;
        $this->reset(['amenityName', 'amenityDescription', 'editingAmenityId']);

        if ($categoryId) {
            $this->selectedCategoryId = $categoryId;
        }
    }

    public function closeAmenityForm()
    {
        $this->showAmenityForm = false;
        $this->reset(['selectedCategoryId', 'amenityName', 'amenityDescription', 'editingAmenityId']);
    }

    public function saveAmenity()
    {
        $this->validate($this->amenityRules());

        if ($this->editingAmenityId) {
            $amenity = Amenity::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingAmenityId);

            $amenity->update([
                'category_id' => $this->selectedCategoryId,
                'name' => $this->amenityName,
                'description' => $this->amenityDescription,
            ]);

            session()->flash('success', 'Amenity updated successfully!');
        } else {
            Amenity::create([
                'hotel_id' => $this->hotel->id,
                'category_id' => $this->selectedCategoryId,
                'name' => $this->amenityName,
                'description' => $this->amenityDescription,
                'sort_order' => Amenity::where('category_id', $this->selectedCategoryId)->count(),
            ]);

            session()->flash('success', 'Amenity created successfully!');
        }

        $this->loadCategories();
        $this->closeAmenityForm();
    }

    public function editAmenity($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $this->editingAmenityId = $amenity->id;
        $this->selectedCategoryId = $amenity->category_id;
        $this->amenityName = $amenity->name;
        $this->amenityDescription = $amenity->description;
        $this->showAmenityForm = true;
    }

    public function deleteAmenity($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $amenity->delete();

        session()->flash('success', 'Amenity deleted successfully!');
        $this->loadCategories();
    }

    public function toggleAmenityStatus($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $amenity->update(['is_active' => !$amenity->is_active]);

        $this->loadCategories();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.amenities.manage');
    }
}
