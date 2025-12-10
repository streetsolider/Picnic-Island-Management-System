<?php

namespace App\Models\Ferry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryVessel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_number',
        'vessel_type',
        'capacity',
        'operator_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(FerrySchedule::class);
    }

    public function operator()
    {
        return $this->belongsTo(\App\Models\Staff::class, 'operator_id');
    }
}
