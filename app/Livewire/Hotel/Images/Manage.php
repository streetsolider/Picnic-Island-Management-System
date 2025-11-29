<?php

namespace App\Livewire\Hotel\Images;

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
    public $activeTab = 'room_types'; // 'room_types' or 'room_specific'
    public $refreshKey = 0;

    // Room Type Images
    public $selectedRoomType = 'Standard';
    public $roomTypeImages = [];
    public $uploadingRoomTypeImages = [];

    // Room-Specific Images
    public $selectedRoomId = null;
    public $roomSpecificImages = [];
    public $uploadingRoomSpecificImages = [];

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

        foreach ($this->uploadingRoomTypeImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/room-types/{$this->selectedRoomType}",
                'public'
            );

            RoomTypeImage::create([
                'hotel_id' => $this->hotel->id,
                'room_type' => $this->selectedRoomType,
                'image_path' => $path,
                'is_primary' => false,
                'sort_order' => $sortOrder++,
            ]);
        }

        $this->uploadingRoomTypeImages = [];
        $this->loadRoomTypeImages();
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

        foreach ($this->uploadingRoomSpecificImages as $image) {
            $path = $image->store(
                "hotel-{$this->hotel->id}/rooms/{$this->selectedRoomId}",
                'public'
            );

            RoomImage::create([
                'room_id' => $this->selectedRoomId,
                'image_path' => $path,
                'is_primary' => false,
                'sort_order' => $sortOrder++,
            ]);
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

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.images.manage');
    }
}
