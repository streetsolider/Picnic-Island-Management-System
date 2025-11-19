<?php

namespace App\Models\Ferry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryVessel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(FerrySchedule::class);
    }
}
