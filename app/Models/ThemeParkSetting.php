<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeParkSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
        'updated_by',
    ];

    /**
     * Get the user who last updated this setting.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the current credit price in MVR.
     */
    public static function getCreditPrice(): float
    {
        $setting = self::where('setting_key', 'credit_price_mvr')->first();
        return $setting ? (float) $setting->setting_value : 10.00; // Default 10 MVR per credit
    }

    /**
     * Set the credit price in MVR.
     */
    public static function setCreditPrice(float $price, int $userId): void
    {
        self::updateOrCreate(
            ['setting_key' => 'credit_price_mvr'],
            [
                'setting_value' => $price,
                'description' => 'Price per credit in MVR',
                'updated_by' => $userId,
            ]
        );
    }
}
