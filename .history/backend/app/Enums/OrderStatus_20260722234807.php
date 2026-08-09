<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user()
{
    return $this->belongsTo(User::class);
}
public function province()
{
    return $this->belongsTo(Province::class);
}
public function city()
{
    return $this->belongsTo(City::class);
}
}
