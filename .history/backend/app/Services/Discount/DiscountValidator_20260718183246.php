<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Models\User;

class DiscountValidator
{

    public function validateDiscount(Discount $discount): bool
{

    if (!$discount->is_active) {
        return false;
    }


    if (
        $discount->starts_at &&
        now()->lt($discount->starts_at)
    ) {
        return false;
    }



    if (
        $discount->ends_at &&
        now()->gt($discount->ends_at)
    ) {
        return false;
    }



    if(
        $discount->quantity_limit &&
        ($discount->used_quantity + $quantity)
        > $discount->quantity_limit
    ){
        return false;
    }


    return true;

}
public function validateForUser(
    Discount $discount,
    ?User $user,
    int $quantity = 1
): bool
{

    if(!$user){
        return true;
    }



    $limit = $discount
        ->userLimits()
        ->where('user_id',$user->id)
        ->first();



    if(!$limit){

        return true;

    }



    $used = $discount
        ->usages()
        ->where('user_id',$user->id)
        ->sum('quantity');



    if(
        ($used + $quantity)
        > $limit->max_quantity
    ){

        return false;

    }



    return true;

}
}