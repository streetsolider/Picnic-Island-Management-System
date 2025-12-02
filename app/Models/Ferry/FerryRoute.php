<?php

namespace App\Models\Ferry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    /**
     * Auto-generate route name based on origin and destination
     */
    public function getNameAttribute(): string
    {
        return "{$this->origin} → {$this->destination}";
    }

    /**
     * Validate that either origin or destination must be "Picnic Island"
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($route) {
            $picnicIsland = 'Picnic Island';

            if ($route->origin !== $picnicIsland && $route->destination !== $picnicIsland) {
                throw new \Exception('Either origin or destination must be "Picnic Island".');
            }

            if ($route->origin === $picnicIsland && $route->destination === $picnicIsland) {
                throw new \Exception('Origin and destination cannot both be "Picnic Island".');
            }
        });
    }

    public function schedules()
    {
        return $this->hasMany(FerrySchedule::class);
    }
}
