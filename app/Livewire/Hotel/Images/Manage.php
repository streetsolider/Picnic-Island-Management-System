<?php

namespace App\Livewire\Hotel\Images;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $hotel;
    public $refreshKey = 0;

    // Galleries
    public $selectedGalleryId = null;
    public $galleryImages = [];
    public $uploadingGalleryImages = [];

    // Gallery Form
    public $showGalleryForm = false;
    public $editingGalleryId = null;
    public $galleryName = '';
    public $galleryDescription = '';
    public $deletingGalleryId = null;

    // Room Assignment
    public $showAssignRoomsModal = false;
    public $assignableRooms = [];
    public $selectedRoomIds = [];
    public $assignedRooms = [];
    public $removingRoomIds = [];
    public $roomSearchTerm = '';
    public $filterRoomType = '';
    public $filterView = '';

    // Shared properties
    public $deletingImageId = null;

    // Available options
    public $roomTypes = ['Standard', 'Superior', 'Deluxe', 'Suite', 'Family'];
    public $rooms = [];

    // Toast notifications
    public $showToast = null;
    public $toastType = 'success';
    public $toastMessage = '';
    public $toastTitle = null;

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)->first();

        if (!$this->hotel) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        $this->loadRooms();
    }

    public function loadRooms()
    {
        $this->rooms = Room::where('hotel_id', $this->hotel->id)
            ->orderBy('room_number')
            ->get();
    }

    // ==================== GALLERIES ====================

    /**
     * Computed property for galleries with counts
     * This ensures counts are always fresh on every render
     */
    public function getGalleriesProperty()
    {
        return Gallery::where('hotel_id', $this->hotel->id)
            ->withCount('images')
            ->withCount('rooms')
            ->get();
    }

    public function openGalleryForm($galleryId = null)
    {
        $this->resetGalleryForm();

        if ($galleryId) {
            $gallery = Gallery::where('hotel_id', $this->hotel->id)->findOrFail($galleryId);
            $this->editingGalleryId = $gallery->id;
            $this->galleryName = $gallery->name;
            $this->galleryDescription = $gallery->description;

            // Load assigned rooms for editing
            $this->loadAssignedRooms();
        }

        $this->showGalleryForm = true;
        $this->dispatch('open-modal', 'gallery-form');
    }

    public function saveGallery()
    {
        $this->validate([
            'galleryName' => 'required|string|max:255',
            'galleryDescription' => 'nullable|string|max:1000',
        ]);

        if ($this->editingGalleryId) {
            // Update existing gallery
            $gallery = Gallery::where('hotel_id', $this->hotel->id)->findOrFail($this->editingGalleryId);
            $gallery->update([
                'name' => $this->galleryName,
                'description' => $this->galleryDescription,
            ]);

            // Handle room removals (rooms that were unchecked)
            if (!empty($this->removingRoomIds)) {
                Room::whereIn('id', $this->removingRoomIds)
                    ->where('gallery_id', $this->editingGalleryId)
                    ->update(['gallery_id' => null]);
            }

            $message = 'Gallery updated successfully!';
        } else {
            // Create new gallery
            Gallery::create([
                'hotel_id' => $this->hotel->id,
                'name' => $this->galleryName,
                'description' => $this->galleryDescription,
            ]);

            $message = 'Gallery created successfully!';
        }

        $this->resetGalleryForm();
        $this->showGalleryForm = false;
        $this->dispatch('close-modal', 'gallery-form');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = $message;

        $this->refreshKey++;
    }

    public function resetGalleryForm()
    {
        $this->editingGalleryId = null;
        $this->galleryName = '';
        $this->galleryDescription = '';
        $this->assignedRooms = [];
        $this->removingRoomIds = [];
        $this->resetValidation();
    }

    public function confirmDeleteGallery($galleryId)
    {
        $this->deletingGalleryId = $galleryId;
    }

    public function deleteGallery()
    {
        if (!$this->deletingGalleryId) {
            return;
        }

        $gallery = Gallery::where('hotel_id', $this->hotel->id)->findOrFail($this->deletingGalleryId);

        // Check if gallery is assigned to rooms
        if ($gallery->rooms()->exists()) {
            $this->showToast = now()->timestamp;
            $this->toastType = 'danger';
            $this->toastMessage = 'Cannot delete gallery that is assigned to rooms. Please unassign it first.';
            $this->deletingGalleryId = null;
            return;
        }

        // If deleting the currently selected gallery, clear the selection
        if ($this->selectedGalleryId == $this->deletingGalleryId) {
            $this->selectedGalleryId = null;
            $this->galleryImages = [];
        }

        // Delete all images in the gallery
        $gallery->images()->each(function ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        });

        $gallery->delete();

        $this->deletingGalleryId = null;

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Gallery deleted successfully!';

        $this->refreshKey++;
    }

    // ==================== ROOM ASSIGNMENT ====================

    public function openAssignRoomsModal()
    {
        if (!$this->selectedGalleryId) {
            return;
        }

        $this->resetAssignmentForm();
        $this->loadAssignableRooms();
        $this->showAssignRoomsModal = true;
        $this->dispatch('open-modal', 'assign-rooms-modal');
    }

    public function loadAssignableRooms()
    {
        if (!$this->selectedGalleryId) {
            return;
        }

        $query = Room::where('hotel_id', $this->hotel->id)
            ->where(function ($q) {
                $q->whereNull('gallery_id')
                  ->orWhere('gallery_id', '!=', $this->selectedGalleryId);
            });

        // Apply filters
        if ($this->roomSearchTerm) {
            $query->where('room_number', 'like', '%' . $this->roomSearchTerm . '%');
        }

        if ($this->filterRoomType) {
            $query->where('room_type', $this->filterRoomType);
        }

        if ($this->filterView) {
            $query->where('view', $this->filterView);
        }

        $this->assignableRooms = $query->orderBy('room_number')->get();
    }

    public function assignRoomsToGallery()
    {
        if (empty($this->selectedRoomIds)) {
            $this->showToast = now()->timestamp;
            $this->toastType = 'warning';
            $this->toastMessage = 'Please select at least one room.';
            return;
        }

        Room::whereIn('id', $this->selectedRoomIds)
            ->where('hotel_id', $this->hotel->id)
            ->update(['gallery_id' => $this->selectedGalleryId]);

        $count = count($this->selectedRoomIds);
        $this->resetAssignmentForm();
        $this->showAssignRoomsModal = false;
        $this->dispatch('close-modal', 'assign-rooms-modal');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = "Gallery assigned to {$count} room(s)!";

        $this->refreshKey++;
    }

    public function loadAssignedRooms()
    {
        if (!$this->editingGalleryId) {
            return;
        }

        $this->assignedRooms = Room::where('hotel_id', $this->hotel->id)
            ->where('gallery_id', $this->editingGalleryId)
            ->orderBy('room_number')
            ->get();
    }

    public function toggleRoomRemoval($roomId)
    {
        if (in_array($roomId, $this->removingRoomIds)) {
            // Remove from removal list (checkbox checked again)
            $this->removingRoomIds = array_diff($this->removingRoomIds, [$roomId]);
        } else {
            // Add to removal list (checkbox unchecked)
            $this->removingRoomIds[] = $roomId;
        }
    }

    public function resetAssignmentForm()
    {
        $this->selectedRoomIds = [];
        $this->assignableRooms = [];
        $this->roomSearchTerm = '';
        $this->filterRoomType = '';
        $this->filterView = '';
    }

    // React to filter changes
    public function updatedRoomSearchTerm()
    {
        $this->loadAssignableRooms();
    }

    public function updatedFilterRoomType()
    {
        $this->loadAssignableRooms();
    }

    public function updatedFilterView()
    {
        $this->loadAssignableRooms();
    }

    // ==================== GALLERY IMAGES ====================

    public function selectGallery($galleryId)
    {
        $this->selectedGalleryId = $galleryId;
        $this->loadGalleryImages();
    }

    public function loadGalleryImages()
    {
        if (!$this->selectedGalleryId) {
            return;
        }

        $this->galleryImages = GalleryImage::where('gallery_id', $this->selectedGalleryId)
            ->orderBy('sort_order')
            ->get();
    }

    public function openGalleryUploadModal()
    {
        if (!$this->selectedGalleryId) {
            return;
        }

        $this->uploadingGalleryImages = [];
        $this->dispatch('open-modal', 'upload-gallery-images');
    }

    public function uploadGalleryImages()
    {
        $this->validate([
            'uploadingGalleryImages.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $sortOrder = GalleryImage::where('gallery_id', $this->selectedGalleryId)->count();

        // Check if there's already a primary image
        $hasPrimary = GalleryImage::where('gallery_id', $this->selectedGalleryId)
            ->where('is_primary', true)
            ->exists();

        $isFirstImage = true;
        foreach ($this->uploadingGalleryImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/galleries/{$this->selectedGalleryId}",
                'public'
            );

            GalleryImage::create([
                'gallery_id' => $this->selectedGalleryId,
                'image_path' => $path,
                'is_primary' => !$hasPrimary && $isFirstImage,
                'sort_order' => $sortOrder++,
            ]);

            $isFirstImage = false;
        }

        $this->uploadingGalleryImages = [];
        $this->loadGalleryImages();
        $this->dispatch('close-modal', 'upload-gallery-images');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Images uploaded successfully!';

        $this->refreshKey++;
    }

    public function setGalleryPrimaryImage($imageId)
    {
        // Reset all to non-primary
        GalleryImage::where('gallery_id', $this->selectedGalleryId)
            ->update(['is_primary' => false]);

        // Set selected as primary
        GalleryImage::find($imageId)->update(['is_primary' => true]);
        $this->loadGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Primary image updated!';

        $this->refreshKey++;
    }

    public function moveGalleryImageUp($imageId)
    {
        $image = GalleryImage::find($imageId);
        $prevImage = GalleryImage::where('gallery_id', $this->selectedGalleryId)
            ->where('sort_order', '<', $image->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($prevImage) {
            $temp = $image->sort_order;
            $image->update(['sort_order' => $prevImage->sort_order]);
            $prevImage->update(['sort_order' => $temp]);
        }

        $this->loadGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function moveGalleryImageDown($imageId)
    {
        $image = GalleryImage::find($imageId);
        $nextImage = GalleryImage::where('gallery_id', $this->selectedGalleryId)
            ->where('sort_order', '>', $image->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextImage) {
            $temp = $image->sort_order;
            $image->update(['sort_order' => $nextImage->sort_order]);
            $nextImage->update(['sort_order' => $temp]);
        }

        $this->loadGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function confirmDeleteImage($imageId)
    {
        $this->deletingImageId = $imageId;
    }

    public function deleteImage()
    {
        if (!$this->deletingImageId) {
            return;
        }

        $image = GalleryImage::findOrFail($this->deletingImageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        $this->loadGalleryImages();

        $this->deletingImageId = null;

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image deleted successfully!';

        $this->refreshKey++;
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.images.manage');
    }
}
