<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'province_id',
        'city_id',
        'title',
        'receiver_name',
        'receiver_mobile',
        'address',
        'building_number',
        'unit',
        'postal_code',
        'latitude',
        'longitude',
        'is_default',
    ];
    protected $casts = [
        'is_default' => 'boolean',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * 📍 Full address as a single string
     * 
     * Usage: $address->full_address
     * Output: "Tehran, Valiasr Street, No. 12, Unit 3"
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->province?->name,
            $this->city?->name,
            $this->address,
        ];

        if ($this->building_number) {
            $parts[] = "No. {$this->building_number}";
        }

        if ($this->unit) {
            $parts[] = "Unit {$this->unit}";
        }

        return implode(', ', array_filter($parts));
    }

    /**
     * ⭐ Get only default addresses
     * 
     * Usage: Address::default()->first()
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * 👤 Get addresses of specific user
     * 
     * Usage: Address::forUser($userId)->get()
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}