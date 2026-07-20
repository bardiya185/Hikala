<?php

namespace App\Services\Discount;


use App\Models\ProductVariant;
use Illuminate\Support\Collection;


class DiscountFinder
{


    public function find(ProductVariant $variant): Collection
    {

        $discounts = collect();



        /*
        |--------------------------------------------------------------------------
        | Variant Discounts
        |--------------------------------------------------------------------------
        */

        $discounts = $discounts->merge(

            $variant
                ->discounts()
                ->get()

        );



        /*
        |--------------------------------------------------------------------------
        | Product Discounts
        |--------------------------------------------------------------------------
        */

        $product = $variant->product;


        if($product){

            $discounts = $discounts->merge(

                $product
                    ->discounts()
                    ->get()

            );

        }



        /*
        |--------------------------------------------------------------------------
        | Category Discounts
        |--------------------------------------------------------------------------
        */

        if($product){


            foreach($product->categories as $category){


                $discounts = $discounts->merge(

                    $category
                        ->discounts()
                        ->get()

                );


            }

        }



        /*
        |--------------------------------------------------------------------------
        | Brand Discounts
        |--------------------------------------------------------------------------
        */


        if($product && $product->brand){


            $discounts = $discounts->merge(

                $product
                    ->brand
                    ->discounts()
                    ->get()

            );


        }



        $priority = new DiscountPriority();


        return $discounts
            ->unique('id')
            ->sortByDesc(function($discount) use ($priority){
        
                return $priority->calculate($discount);
        
            })
            ->values();


    }


}