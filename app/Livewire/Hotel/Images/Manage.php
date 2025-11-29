<?php

namespace App\Livewire\Hotel\Images;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomTypeImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $hotel;
    public $activeTab = 'room_types'; // 'room_types', 'room_specific', or 'galleries'
    public $refreshKey = 0;

    // Room Type Images
    public $selectedRoomType = 'Standard';
    public $roomTypeImages = [];
    public $allRoomTypeImages = []; // Grouped by room type
    public $uploadingRoomTypeImages = [];

    // Room-Specific Images
    public $selectedRoomId = null;
    public $roomSpecificImages = [];
    public $uploadingRoomSpecificImages = [];

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
    public $deletingImageType = null;

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

        $this->loadRoomTypeImages();
        $this->loadAllRoomTypeImages();
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $this->rooms = Room::where('hotel_id', $this->hotel->id)
            ->orderBy('room_number')
            ->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'room_types') {
            $this->loadRoomTypeImages();
        } elseif ($tab === 'room_specific' && $this->selectedRoomId) {
            $this->loadRoomSpecificImages();
        }
    }

    // ==================== ROOM TYPE IMAGES ====================

    public function loadRoomTypeImages()
    {
        $this->roomTypeImages = RoomTypeImage::where('hotel_id', $this->hotel->id)
            ->where('room_type', $this->selectedRoomType)
            ->orderBy('sort_order')
            ->get();
    }

    public function loadAllRoomTypeImages()
    {
        $images = RoomTypeImage::where('hotel_id', $this->hotel->id)
            ->orderBy('room_type')
            ->orderBy('sort_order')
            ->get();

        // Group by room type and convert to array for Livewire
        $this->allRoomTypeImages = $images->groupBy('room_type')->all();
    }

    public function changeRoomType($type)
    {
        $this->selectedRoomType = $type;
        $this->loadRoomTypeImages();
    }

    public function openRoomTypeUploadModal()
    {
        $this->uploadingRoomTypeImages = [];
        $this->dispatch('open-modal', 'upload-room-type-images');
    }

    public function uploadRoomTypeImages()
    {
        $this->validate([
            'uploadingRoomTypeImages.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $sortOrder = RoomTypeImage::where('hotel_id', $this->hotel->id)
            ->where('room_type', $this->selectedRoomType)
            ->count();

        // Check if there's already a primary image
        $hasPrimary = RoomTypeImage::where('hotel_id', $this->hotel->id)
            ->where('room_type', $this->selectedRoomType)
            ->where('is_primary', true)
            ->exists();

        $isFirstImage = true;
        foreach ($this->uploadingRoomTypeImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/room-types/{$this->selectedRoomType}",
                'public'
            );

            RoomTypeImage::create([
                'hotel_id' => $this->hotel->id,
                'room_type' => $this->selectedRoomType,
                'image_path' => $path,
                'is_primary' => !$hasPrimary && $isFirstImage,
                'sort_order' => $sortOrder++,
            ]);

            $isFirstImage = false;
        }

        $this->uploadingRoomTypeImages = [];
        $this->loadRoomTypeImages();
        $this->loadAllRoomTypeImages();
        $this->dispatch('close-modal', 'upload-room-type-images');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Images uploaded successfully!';

        $this->refreshKey++;
    }

    // ==================== ROOM-SPECIFIC IMAGES ====================

    public function loadRoomSpecificImages()
    {
        if (!$this->selectedRoomId) {
            return;
        }

        $this->roomSpecificImages = RoomImage::where('room_id', $this->selectedRoomId)
            ->orderBy('sort_order')
            ->get();
    }

    public function changeRoom($roomId)
    {
        $this->selectedRoomId = $roomId;
        $this->loadRoomSpecificImages();
    }

    public function openRoomSpecificUploadModal()
    {
        $this->uploadingRoomSpecificImages = [];
        $this->dispatch('open-modal', 'upload-room-specific-images');
    }

    public function uploadRoomSpecificImages()
    {
        $this->validate([
            'uploadingRoomSpecificImages.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $sortOrder = RoomImage::where('room_id', $this->selectedRoomId)->count();

        // Check if there's already a primary image
        $hasPrimary = RoomImage::where('room_id', $this->selectedRoomId)
            ->where('is_primary', true)
            ->exists();

        $isFirstImage = true;
        foreach ($this->uploadingRoomSpecificImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/rooms/{$this->selectedRoomId}",
                'public'
            );

            RoomImage::create([
                'room_id' => $this->selectedRoomId,
                'image_path' => $path,
                'is_primary' => !$hasPrimary && $isFirstImage,
                'sort_order' => $sortOrder++,
            ]);

            $isFirstImage = false;
        }

        $this->uploadingRoomSpecificImages = [];
        $this->loadRoomSpecificImages();
        $this->dispatch('close-modal', 'upload-room-specific-images');

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Images uploaded successfully!';

        $this->refreshKey++;
    }

    // ==================== SET PRIMARY IMAGE ====================

    public function setPrimaryImage($imageId, $imageType)
    {
        if ($imageType === 'room_type') {
            // Reset all to non-primary
            RoomTypeImage::where('hotel_id', $this->hotel->id)
                ->where('room_type', $this->selectedRoomType)
                ->update(['is_primary' => false]);

            // Set selected as primary
            RoomTypeImage::find($imageId)->update(['is_primary' => true]);
            $this->loadRoomTypeImages();
            $this->loadAllRoomTypeImages();
        } else {
            // Reset all to non-primary for this room
            RoomImage::where('room_id', $this->selectedRoomId)
                ->update(['is_primary' => false]);

            // Set selected as primary
            RoomImage::find($imageId)->update(['is_primary' => true]);
            $this->loadRoomSpecificImages();
        }

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Primary image updated!';

        $this->refreshKey++;
    }

    // ==================== DELETE IMAGE ====================

    public function confirmDeleteImage($imageId, $imageType)
    {
        $this->deletingImageId = $imageId;
        $this->deletingImageType = $imageType;
    }

    public function deleteImage()
    {
        if (!$this->deletingImageId) {
            return;
        }

        if ($this->deletingImageType === 'room_type') {
            $image = RoomTypeImage::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->deletingImageId);
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->loadRoomTypeImages();
            $this->loadAllRoomTypeImages();
        } elseif ($this->deletingImageType === 'gallery') {
            $image = GalleryImage::findOrFail($this->deletingImageId);
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->loadGalleryImages();
        } else {
            $image = RoomImage::findOrFail($this->deletingImageId);
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->loadRoomSpecificImages();
        }

        $this->deletingImageId = null;
        $this->deletingImageType = null;

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image deleted successfully!';

        $this->refreshKey++;
    }

    // ==================== REORDER IMAGES ====================

    public function updateImageOrder($orderedIds, $imageType)
    {
        // $orderedIds is an array of image IDs in the new order
        if ($imageType === 'room_type') {
            foreach ($orderedIds as $index => $imageId) {
                RoomTypeImage::where('id', $imageId)
                    ->where('hotel_id', $this->hotel->id)
                    ->update(['sort_order' => $index]);
            }
            $this->loadRoomTypeImages();
        } else {
            foreach ($orderedIds as $index => $imageId) {
                RoomImage::where('id', $imageId)
                    ->update(['sort_order' => $index]);
            }
            $this->loadRoomSpecificImages();
        }

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function moveImageUp($imageId, $imageType)
    {
        if ($imageType === 'room_type') {
            $image = RoomTypeImage::find($imageId);
            $prevImage = RoomTypeImage::where('hotel_id', $this->hotel->id)
                ->where('room_type', $this->selectedRoomType)
                ->where('sort_order', '<', $image->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();

            if ($prevImage) {
                $temp = $image->sort_order;
                $image->update(['sort_order' => $prevImage->sort_order]);
                $prevImage->update(['sort_order' => $temp]);
            }

            $this->loadRoomTypeImages();
            $this->loadAllRoomTypeImages();
        } else {
            $image = RoomImage::find($imageId);
            $prevImage = RoomImage::where('room_id', $this->selectedRoomId)
                ->where('sort_order', '<', $image->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();

            if ($prevImage) {
                $temp = $image->sort_order;
                $image->update(['sort_order' => $prevImage->sort_order]);
                $prevImage->update(['sort_order' => $temp]);
            }

            $this->loadRoomSpecificImages();
        }

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
    }

    public function moveImageDown($imageId, $imageType)
    {
        if ($imageType === 'room_type') {
            $image = RoomTypeImage::find($imageId);
            $nextImage = RoomTypeImage::where('hotel_id', $this->hotel->id)
                ->where('room_type', $this->selectedRoomType)
                ->where('sort_order', '>', $image->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();

            if ($nextImage) {
                $temp = $image->sort_order;
                $image->update(['sort_order' => $nextImage->sort_order]);
                $nextImage->update(['sort_order' => $temp]);
            }

            $this->loadRoomTypeImages();
            $this->loadAllRoomTypeImages();
        } else {
            $image = RoomImage::find($imageId);
            $nextImage = RoomImage::where('room_id', $this->selectedRoomId)
                ->where('sort_order', '>', $image->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();

            if ($nextImage) {
                $temp = $image->sort_order;
                $image->update(['sort_order' => $nextImage->sort_order]);
                $nextImage->update(['sort_order' => $temp]);
            }

            $this->loadRoomSpecificImages();
        }

        // Show success toast
        $this->showToast = now()->timestamp;
        $this->toastType = 'success';
        $this->toastMessage = 'Image order updated!';

        $this->refreshKey++;
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

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.images.manage');
    }
}
