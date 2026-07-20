<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Coupon extends Model
{

    protected $fillable = [

        'code',

        'discount_id',

        'usage_limit',

        'used_count',

        'is_active',

    ];



    protected $casts = [

        'is_active'=>'boolean',

    ];



    public function discount()
    {
        return $this->belongsTo(
            Discount::class
        );
    }

    public function usages()
{
    return $this->hasMany(
        CouponUsage::class
    );
}

}