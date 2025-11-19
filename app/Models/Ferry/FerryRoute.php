<?php

namespace App\Models\Ferry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'origin',
        'destination',
        'duration_minutes',
        'base_price',
        'operator_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
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
