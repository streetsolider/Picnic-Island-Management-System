<?php

namespace App\Livewire\Hotel\Settings;

use App\Livewire\Hotel\Traits\HasHotelSelection;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class HotelSettings extends Component
{
    use HasHotelSelection, WithFileUploads;

    public $showEditModal = false;
    public $checkinTime;
    public $checkoutTime;

    // Hotel Gallery Images
    public $hotelGalleryImages = [];
    public $uploadingHotelGalleryImages = [];
    public $refreshKey = 0;
    public $showUploadModal = false;
    public $showDeleteImageModal = false;
    public $imageToDelete = null;

    public function mount()
    {
        // Initialize hotel selection
        $this->initializeHotelSelection();

        $this->checkinTime = $this->hotel->default_checkin_time
            ? Carbon::parse($this->hotel->default_checkin_time)->format('H:i')
            : '14:00';

        $this->checkoutTime = $this->hotel->default_checkout_time
            ? Carbon::parse($this->hotel->default_checkout_time)->format('H:i')
            : '12:00';

        $this->loadHotelGalleryImages();
    }

    /**
     * Called when hotel is switched
     */
    public function onHotelChanged()
    {
        // Close any open modals and reset their state
        $this->showEditModal = false;
        $this->showUploadModal = false;
        $this->showDeleteImageModal = false;
        $this->uploadingHotelGalleryImages = [];
        $this->imageToDelete = null;
        $this->resetValidation();

        // Reload times for the newly selected hotel
        $this->checkinTime = $this->hotel->default_checkin_time
            ? Carbon::parse($this->hotel->default_checkin_time)->format('H:i')
            : '14:00';

        $this->checkoutTime = $this->hotel->default_checkout_time
            ? Carbon::parse($this->hotel->default_checkout_time)->format('H:i')
            : '12:00';

        $this->loadHotelGalleryImages();

        // Force re-render of content
        $this->refreshKey++;
    }

    public function loadHotelGalleryImages()
    {
        if ($this->hotel->hotelGallery) {
            $this->hotelGalleryImages = GalleryImage::where('gallery_id', $this->hotel->hotelGallery->id)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->hotelGalleryImages = [];
        }
    }

    public function openEditModal()
    {
        $this->checkinTime = $this->hotel->default_checkin_time
            ? Carbon::parse($this->hotel->default_checkin_time)->format('H:i')
            : '14:00';

        $this->checkoutTime = $this->hotel->default_checkout_time
            ? Carbon::parse($this->hotel->default_checkout_time)->format('H:i')
            : '12:00';

        $this->showEditModal = true;
        $this->dispatch('open-modal', 'edit-hotel-times');
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['checkinTime', 'checkoutTime']);
        $this->resetValidation();
        $this->dispatch('close-modal', 'edit-hotel-times');
    }

    public function save()
    {
        // Validate check-in and checkout times
        $this->validate([
            'checkinTime' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
            ],
            'checkoutTime' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
            ],
        ]);

        // Additional validation: check-in time must be after checkout time
        $checkin = Carbon::parse($this->checkinTime);
        $checkout = Carbon::parse($this->checkoutTime);

        if ($checkin->lessThanOrEqualTo($checkout)) {
            session()->flash('error', 'Check-in time must be after checkout time.');
            return;
        }

        try {
            // Update hotel check-in and checkout times
            $this->hotel->default_checkin_time = $this->checkinTime . ':00';
            $this->hotel->default_checkout_time = $this->checkoutTime . ':00';
            $this->hotel->save();

            $this->closeEditModal();

            session()->flash('message', 'Hotel check-in and checkout times updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update hotel settings: ' . $e->getMessage());
        }
    }

    // ==================== HOTEL GALLERY IMAGES ====================

    public function openHotelGalleryUploadModal()
    {
        // Auto-create hotel gallery if it doesn't exist
        if (!$this->hotel->hotelGallery) {
            Gallery::create([
                'hotel_id' => $this->hotel->id,
                'name' => $this->hotel->name . ' Gallery',
                'type' => Gallery::TYPE_HOTEL,
            ]);

            // Refresh the hotel to get the new gallery relationship
            $this->hotel->refresh();
        }

        $this->uploadingHotelGalleryImages = [];
        $this->showUploadModal = true;
        $this->dispatch('open-modal', 'upload-hotel-gallery-images');
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->uploadingHotelGalleryImages = [];
        $this->resetValidation();
        $this->dispatch('close-modal', 'upload-hotel-gallery-images');
    }

    public function uploadHotelGalleryImages()
    {
        if (!$this->hotel->hotelGallery) {
            session()->flash('error', 'Hotel gallery not initialized.');
            return;
        }

        // Check if any images were selected
        if (empty($this->uploadingHotelGalleryImages)) {
            session()->flash('error', 'Please select at least one image to upload.');
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
        $this->closeUploadModal();

        session()->flash('message', 'Hotel images uploaded successfully!');
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

        session()->flash('message', 'Primary hotel image updated!');
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
        $this->refreshKey++;
    }

    public function openDeleteImageModal($imageId)
    {
        $this->imageToDelete = $imageId;
        $this->showDeleteImageModal = true;
        $this->dispatch('open-modal', 'delete-hotel-image');
    }

    public function closeDeleteImageModal()
    {
        $this->showDeleteImageModal = false;
        $this->imageToDelete = null;
        $this->dispatch('close-modal', 'delete-hotel-image');
    }

    public function deleteHotelGalleryImage()
    {
        if (!$this->imageToDelete) {
            return;
        }

        $image = GalleryImage::findOrFail($this->imageToDelete);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        $this->loadHotelGalleryImages();

        $this->closeDeleteImageModal();

        session()->flash('message', 'Hotel image deleted successfully!');
        $this->refreshKey++;
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.settings.hotel-settings', [
            'assignedHotels' => $this->assignedHotels,
        ]);
    }
}
