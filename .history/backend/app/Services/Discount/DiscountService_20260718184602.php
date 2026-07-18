<?php

namespace App\Services\Discount;


use App\Models\ProductVariant;
use App\Models\User;
use App\Services\Discount\DTO\DiscountResult;


class DiscountService
{

    public function __construct(
        private DiscountFinder $finder,
        private DiscountValidator $validator,
        private DiscountCalculator $calculator
    ) {}



    public function calculate(
        ProductVariant $variant,
        ?User $user = null,
        int $quantity = 1
    ): DiscountResult
    {


        // پیدا کردن تخفیف‌ها

        $discounts = $this->finder
            ->find($variant);



        // انتخاب بهترین تخفیف

        $discount = $discounts
            ->first();



        // اگر تخفیفی نبود

        if(!$discount){

            return $this->calculator->calculate(
                $variant->price,
                null
            );

        }



        // بررسی اعتبار تخفیف

        if(
            !$this->validator
                ->validateDiscount(
                    $discount,
                    $quantity
                )
        ){

            return $this->calculator->calculate(
                $variant->price,
                null
            );

        }




        // بررسی محدودیت کاربر

        if(
            !$this->validator
                ->validateForUser(
                    $discount,
                    $user,
                    $quantity
                )
        ){

            return $this->calculator->calculate(
                $variant->price,
                null
            );

        }



        // محاسبه قیمت نهایی

        return $this->calculator->calculate(

            $variant->price,

            $discount

        );


    }


}