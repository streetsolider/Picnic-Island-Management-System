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

    public $selectedHotelId;
    public $hotel;
    public $refreshKey = 0;

    // Hotel Gallery (separate from room galleries)
    public $hotelGalleryImages = [];
    public $uploadingHotelGalleryImages = [];

    // Room Galleries
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
        $staffId = auth('staff')->id();

        // Get all hotels assigned to this staff member
        $assignedHotels = Hotel::where('manager_id', $staffId)->get();

        if ($assignedHotels->isEmpty()) {
            abort(403, 'You are not assigned to manage any hotel.');
        }

        // Check if there's a selected hotel in session
        $sessionHotelId = session('hotel_selected_hotel_id');

        // Validate that the session hotel is still assigned to this staff
        if ($sessionHotelId && $assignedHotels->contains('id', $sessionHotelId)) {
            $this->selectedHotelId = $sessionHotelId;
        } else {
            // Default to first assigned hotel
            $this->selectedHotelId = $assignedHotels->first()->id;
            session(['hotel_selected_hotel_id' => $this->selectedHotelId]);
        }

        $this->hotel = Hotel::find($this->selectedHotelId);
        $this->loadRooms();
        $this->loadHotelGalleryImages();
    }

    public function selectHotel($hotelId)
    {
        $staffId = auth('staff')->id();

        // Verify this hotel is assigned to this staff member
        $hotel = Hotel::where('id', $hotelId)
            ->where('manager_id', $staffId)
            ->first();

        if ($hotel) {
            $this->selectedHotelId = $hotelId;
            $this->hotel = $hotel;
            session(['hotel_selected_hotel_id' => $hotelId]);

            // Reload data for the new hotel
            $this->loadRooms();
            $this->loadHotelGalleryImages();

            // Reset gallery selection
            $this->selectedGalleryId = null;
            $this->galleryImages = [];

            // Show success toast
            $this->showToast = now()->timestamp;
            $this->toastType = 'success';
            $this->toastMessage = 'Switched to ' . $hotel->name;

            $this->refreshKey++;
        }
    }

    public function loadRooms()
    {
        $this->rooms = Room::where('hotel_id', $this->hotel->id)
            ->orderBy('room_number')
            ->get();
    }

    public function loadHotelGalleryImages()
    {
        if ($this->hotel->hotelGallery) {
            $this->hotelGalleryImages = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
                ->orderBy('sort_order')
                ->get();
        }
    }

    // ==================== HOTEL GALLERY IMAGES ====================

    public function openHotelGalleryUploadModal()
    {
        if (!$this->hotel->hotelGallery) {
            return;
        }

        $this->uploadingHotelGalleryImages = [];
        $this->dispatch('open-modal', 'upload-hotel-gallery-images');
    }

    public function uploadHotelGalleryImages()
    {
        if (!$this->hotel->hotelGallery) {
            return;
        }

        // Check if any images were selected
        if (empty($this->uploadingHotelGalleryImages)) {
            $this->showToast = now()->timestamp;
            $this->toastType = 'warning';
            $this->toastMessage = 'Please select at least one image to upload.';
            return;
        }

        $this->validate([
            'uploadingHotelGalleryImages.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $sortOrder = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)->count();

        // Check if there's already a primary image
        $hasPrimary = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
            ->where('is_primary', true)
            ->exists();

        $isFirstImage = true;
        foreach ($this->uploadingHotelGalleryImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/hotel-gallery",
                'public'
            );

            GalleryImage::create([
                'gallery_id' => $this->hotel->hotelGallery->id,
                'image_path' => $path,
                'is_primary' => !$hasPrimary && $isFirstImage,
                'sort_order' => $sortOrder++,
            ]);

            $isFirstImage = false;
        }

        $this->uploadingHotelGalleryImages = [];
        $this->loadHotelGalleryImages();
        $this->dispatch('close-modal', 'upload-hotel-gallery-images');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Hotel images uploaded successfully!';

        $this->refreshKey++;
    }

    public function setHotelGalleryPrimaryImage($imageId)
    {
        if (!$this->hotel->hotelGallery) {
            return;
        }

        // Reset all to non-primary
        GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
            ->update(['is_primary' => false]);

        // Set selected as primary
        GalleryImage::find($imageId)->update(['is_primary' => true]);
        $this->loadHotelGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Primary hotel image updated!';

        $this->refreshKey++;
    }

    public function moveHotelGalleryImageUp($imageId)
    {
        if (!$this->hotel->hotelGallery) {
            return;
        }

        $image = GalleryImage::find($imageId);
        $prevImage = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
            ->where('sort_order', '<', $image->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($prevImage) {
            $temp = $image->sort_order;
            $image->update(['sort_order' => $prevImage->sort_order]);
            $prevImage->update(['sort_order' => $temp]);
        }

        $this->loadHotelGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function moveHotelGalleryImageDown($imageId)
    {
        if (!$this->hotel->hotelGallery) {
            return;
        }

        $image = GalleryImage::find($imageId);
        $nextImage = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
            ->where('sort_order', '>', $image->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextImage) {
            $temp = $image->sort_order;
            $image->update(['sort_order' => $nextImage->sort_order]);
            $nextImage->update(['sort_order' => $temp]);
        }

        $this->loadHotelGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function deleteHotelGalleryImage($imageId)
    {
        $image = GalleryImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        $this->loadHotelGalleryImages();

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Hotel image deleted successfully!';

        $this->refreshKey++;
    }

    // ==================== ROOM GALLERIES ====================

    /**
     * Computed property for room galleries with counts
     * This ensures counts are always fresh on every render
     */
    public function getGalleriesProperty()
    {
        return Gallery::where('hotel_id', $this->hotel->id)
            ->where('type', Gallery::TYPE_ROOM)
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
            // Create new room gallery
            Gallery::create([
                'hotel_id' => $this->hotel->id,
                'name' => $this->galleryName,
                'description' => $this->galleryDescription,
                'type' => Gallery::TYPE_ROOM,
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
        $this->dispatch('open-modal', 'delete-gallery-modal');
    }

    public function closeDeleteGalleryModal()
    {
        $this->deletingGalleryId = null;
        $this->dispatch('close-modal', 'delete-gallery-modal');
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
            $this->closeDeleteGalleryModal();
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

        $this->closeDeleteGalleryModal();

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
        // Check if any images were selected
        if (empty($this->uploadingGalleryImages)) {
            $this->showToast = now()->timestamp;
            $this->toastType = 'warning';
            $this->toastMessage = 'Please select at least one image to upload.';
            return;
        }

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
        $staffId = auth('staff')->id();

        // Get all hotels assigned to this staff member
        $assignedHotels = Hotel::where('manager_id', $staffId)->get();

        return view('livewire.hotel.images.manage', [
            'assignedHotels' => $assignedHotels,
        ]);
    }
}
