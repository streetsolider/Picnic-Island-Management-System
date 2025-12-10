<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\MapMarker;
use App\Models\MapSetting;

#[Layout('layouts.visitor')]
#[Title('Island Map')]
class Map extends Component
{
    public function render()
    {
        $markers = MapMarker::with('mappable')->get();
        $mapImagePath = MapSetting::getMapImagePath();

        return view('livewire.visitor.map', compact('markers', 'mapImagePath'));
    }
}
