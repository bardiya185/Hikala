<?php

namespace App\Services\Discount;


use App\Models\ProductVariant;
use App\Services\Discount\DTO\DiscountResult;
use Finde


class DiscountService
{


    public function __construct(

        private DiscountFinder $finder,

        private DiscountValidator $validator,

        private DiscountCalculator $calculator,

    ){}



    public function calculate(
        ProductVariant $variant
    ): DiscountResult
    {


        $discounts = $this->finder->find($variant);



        $activeDiscount = null;



        foreach ($discounts as $discount) {


            if(
                $this->validator->validate($discount)
            ){

                $activeDiscount = $discount;

                break;

            }

        }



        return $this->calculator->calculate(

            $variant->price,

            $activeDiscount

        );

    }


}