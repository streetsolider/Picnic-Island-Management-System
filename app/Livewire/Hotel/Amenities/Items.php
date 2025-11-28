<?php

namespace App\Livewire\Hotel\Amenities;

use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public $hotel;

    // Form properties
    public $category_id = '';
    public $name = '';
    public $description = '';
    public $icon = '';
    public $editingId = null;
    public $showForm = false;

    // Filter properties
    public $filterCategory = '';

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }
    }

    protected function rules()
    {
        return [
            'category_id' => 'required|exists:amenity_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
        ];
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->reset(['name', 'description', 'icon', 'category_id', 'editingId']);
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['name', 'description', 'icon', 'category_id', 'editingId']);
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            // Update existing amenity
            $amenity = Amenity::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingId);

            $amenity->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
            ]);

            session()->flash('success', 'Amenity updated successfully!');
        } else {
            // Create new amenity
            Amenity::create([
                'hotel_id' => $this->hotel->id,
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'sort_order' => Amenity::where('category_id', $this->category_id)->count(),
            ]);

            session()->flash('success', 'Amenity created successfully!');
        }

        $this->closeForm();
    }

    public function edit($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $this->editingId = $amenity->id;
        $this->category_id = $amenity->category_id;
        $this->name = $amenity->name;
        $this->description = $amenity->description;
        $this->icon = $amenity->icon;
        $this->showForm = true;
    }

    public function delete($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $amenity->delete();

        session()->flash('success', 'Amenity deleted successfully!');
    }

    public function toggleStatus($amenityId)
    {
        $amenity = Amenity::where('hotel_id', $this->hotel->id)
            ->findOrFail($amenityId);

        $amenity->update(['is_active' => !$amenity->is_active]);
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        $categories = AmenityCategory::where('hotel_id', $this->hotel->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $amenities = Amenity::where('hotel_id', $this->hotel->id)
            ->with('category')
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('livewire.hotel.amenities.items', [
            'categories' => $categories,
            'amenities' => $amenities,
        ]);
    }
}
