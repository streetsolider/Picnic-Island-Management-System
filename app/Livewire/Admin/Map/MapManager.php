<?php

namespace App\Livewire\Admin\Map;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\MapMarker;
use App\Models\Hotel;
use App\Models\ThemeParkActivity;
use App\Models\BeachActivity;
use App\Models\MapSetting;
use Illuminate\Support\Facades\Storage;

class MapManager extends Component
{
    use WithFileUploads;

    public $markers = [];
    public $hotels = [];
    public $themeParkActivities = [];
    public $beachActivities = [];
    public $mapImage;
    public $currentMapPath;
    public $showResetConfirmation = false;


    public function mount()
    {
        $this->loadData();
        $this->currentMapPath = MapSetting::getMapImagePath();
    }

    public function loadData()
    {
        $this->markers = MapMarker::with('mappable')->get();
        // Get items that don't have a marker yet
        $this->hotels = Hotel::active()->whereDoesntHave('mapMarker')->get();

        $this->themeParkActivities = ThemeParkActivity::where('is_active', true)
            ->whereDoesntHave('mapMarker')->get();

        $this->beachActivities = BeachActivity::active()
            ->whereDoesntHave('mapMarker')->get();
    }

    public function uploadMapImage()
    {
        $this->validate([
            'mapImage' => 'required|image|mimes:jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        // Delete old map image if it exists and is not the default
        $oldPath = MapSetting::getMapImagePath();
        if ($oldPath !== 'images/map/island.png' && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Store new image
        $path = $this->mapImage->store('maps', 'public');

        // Update setting
        MapSetting::setMapImage($path, auth()->id());

        // Update current path
        $this->currentMapPath = $path;

        // Reset the file input
        $this->reset('mapImage');

        session()->flash('success', 'Map image uploaded successfully!');
    }

    public function saveMarkerPosition($markerId, $x, $y)
    {
        $marker = MapMarker::find($markerId);
        if ($marker) {
            $marker->update([
                'x_position' => $x,
                'y_position' => $y,
            ]);
            $this->loadData(); // Reload markers to reflect the new position
        }
    }

    public function addMarker($type, $id, $x, $y)
    {
        $modelClass = match ($type) {
            'hotel' => Hotel::class,
            'themepark' => ThemeParkActivity::class,
            'beach' => BeachActivity::class,
            default => null,
        };

        if ($modelClass) {
            MapMarker::create([
                'mappable_type' => $modelClass,
                'mappable_id' => $id,
                'x_position' => $x,
                'y_position' => $y,
            ]);
            $this->loadData();
        }
    }

    public function deleteMarker($markerId)
    {
        MapMarker::destroy($markerId);
        $this->loadData();
    }

    public function confirmReset()
    {
        $this->showResetConfirmation = true;
    }

    public function resetAllMarkers()
    {
        // Delete all map markers
        MapMarker::truncate();

        // Reload data
        $this->loadData();

        // Close modal
        $this->showResetConfirmation = false;

        session()->flash('success', 'All markers have been reset!');
    }

    public function cancelReset()
    {
        $this->showResetConfirmation = false;
    }

    public function render()
    {
        return view('livewire.admin.map.map-manager')->layout('layouts.admin');
    }
}
