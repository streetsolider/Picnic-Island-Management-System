<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelPolicy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hotel_id',
        'policy_type',
        'title',
        'description',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the hotel that owns this policy
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope to filter only active policies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by policy type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('policy_type', $type);
    }

    /**
     * Get the formatted policy type name for display
     */
    public function getPolicyTypeNameAttribute(): string
    {
        return match($this->policy_type) {
            'cancellation' => 'Cancellation Policy',
            'check_in_out' => 'Check-in/Check-out Policy',
            'payment' => 'Payment Policy',
            'house_rules' => 'House Rules',
            'age_restriction' => 'Age Restriction Policy',
            'damage_deposit' => 'Damage & Deposit Policy',
            'special_requests' => 'Special Requests Policy',
            default => ucfirst(str_replace('_', ' ', $this->policy_type)),
        };
    }

    /**
     * Get all available policy types
     */
    public static function getPolicyTypes(): array
    {
        return [
            'cancellation' => 'Cancellation Policy',
            'check_in_out' => 'Check-in/Check-out Policy',
            'payment' => 'Payment Policy',
            'house_rules' => 'House Rules',
            'age_restriction' => 'Age Restriction Policy',
            'damage_deposit' => 'Damage & Deposit Policy',
            'special_requests' => 'Special Requests Policy',
        ];
    }
}
