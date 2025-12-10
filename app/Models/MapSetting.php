<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
        'updated_by',
    ];

    /**
     * Get the staff member who last updated this setting.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get the current map image path.
     */
    public static function getMapImagePath(): string
    {
        $setting = self::where('setting_key', 'map_image_path')->first();
        return $setting ? $setting->setting_value : 'images/map/island.png'; // Default fallback
    }

    /**
     * Set the map image path.
     */
    public static function setMapImage(string $path, int $userId): void
    {
        self::updateOrCreate(
            ['setting_key' => 'map_image_path'],
            [
                'setting_value' => $path,
                'description' => 'Path to the island map image',
                'updated_by' => $userId,
            ]
        );
    }
}
