<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon_key',
        'image',
        'description',
        'sort_order',
        'is_active',
    ];
}
