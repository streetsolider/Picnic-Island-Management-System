<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryTerminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope to filter only active terminals
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the map marker for this terminal
     */
    public function mapMarker(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(MapMarker::class, 'mappable');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically delete map marker when ferry terminal is deleted
        static::deleting(function ($terminal) {
            $terminal->mapMarker()->delete();
        });
    }
}
