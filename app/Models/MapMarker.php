<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'mappable_id',
        'mappable_type',
        'x_position',
        'y_position',
    ];

    /**
     * Get the owning mappable model.
     */
    public function mappable()
    {
        return $this->morphTo();
    }
}
