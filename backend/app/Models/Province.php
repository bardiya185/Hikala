<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [

        'name',

        'slug',

        'code',

        'sort_order',

        'is_active',

    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}