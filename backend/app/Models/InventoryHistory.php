<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $fillable = [
        'inventory_id',
        'type',
        'quantity',
        'description',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}