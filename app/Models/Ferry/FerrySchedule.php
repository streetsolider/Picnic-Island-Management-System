<?php

namespace App\Models\Ferry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerrySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'ferry_route_id',
        'ferry_vessel_id',
        'departure_time',
        'arrival_time',
        'days_of_week',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'departure_time' => 'datetime:H:i',
        'arrival_time' => 'datetime:H:i',
    ];

    public function route()
    {
        return $this->belongsTo(FerryRoute::class, 'ferry_route_id');
    }

    public function vessel()
    {
        return $this->belongsTo(FerryVessel::class, 'ferry_vessel_id');
    }
}
