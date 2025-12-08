<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MapMarker;
use App\Models\MapSetting;

class MapController extends Controller
{
    public function index()
    {
        $markers = MapMarker::with('mappable')->get();
        $mapImagePath = MapSetting::getMapImagePath();
        return view('guest.map', compact('markers', 'mapImagePath'));
    }
}
