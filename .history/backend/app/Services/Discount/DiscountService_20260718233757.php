<?php

namespace App\Services\Discount;


use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Coupon;
use App\Services\Discount\DTO\DiscountResult;


class DiscountService
{
    public function __construct(
        private DiscountFinder $finder,
        private DiscountValidator $validator,
        private DiscountCalculator $calculator,
        private DiscountUsageService $usageService,
        private \App\Services\Coupon\CouponUsageService $couponUsageService
    ) {}



    public function calculate(
        ProductVariant $variant,
        ?User $user = null,
        int $quantity = 1,
        ?Coupon $coupon = null
    ): DiscountResult
    {
    
        $discounts = $this->finder
            ->find($variant);
    
    
    
        $discount = $coupon
            ? $coupon->discount
            : $discounts->first();
    
    
    
        if(!$discount){
    
            return $this->calculator
                ->calculate(
                    $variant->price,
                    null
                );
    
        }
    
    
    
        if(
            !$this->validator
                ->validateDiscount(
                    $discount,
                    $quantity
                )
        ){
    
            return $this->calculator
                ->calculate(
                    $variant->price,
                    null
                );
    
        }
    
    
    
        if(
            !$this->validator
                ->validateForUser(
                    $discount,
                    $user,
                    $quantity
                )
        ){
    
            return $this->calculator
                ->calculate(
                    $variant->price,
                    null
                );
    
        }
    
    
    
        return $this->calculator->calculate(
            $variant->price,
            $discount,
            $coupon
        );
    
    }




    public function consume(
        DiscountResult $result,
        ?User $user,
        int $quantity = 1
    )
    {
    
    
        if(!$result->discount){
    
            return null;
    
        }
    
    
    
        if($result->coupon){
    
            return $this->couponUsageService
                ->consume(
                    $result->coupon,
                    $user,
                    $quantity
                );
    
        }
    
    
    
        return $this->usageService
            ->consume(
                $result->discount,
                $user,
                $quantity
            );
    
    }
}