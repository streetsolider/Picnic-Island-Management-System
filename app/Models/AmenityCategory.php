<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmenityCategory extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel that owns this category
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the amenities in this category
     */
    public function amenities(): HasMany
    {
        return $this->hasMany(Amenity::class, 'category_id')->orderBy('sort_order');
    }
}
