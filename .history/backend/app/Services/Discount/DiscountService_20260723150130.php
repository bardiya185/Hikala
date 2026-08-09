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
    ): DiscountResult {

        $allDiscounts = $this->finder->find($variant);

        $basePrice = (float) $variant->base_price;
        $currentPrice = $basePrice;
        $totalDiscountAmount = 0;
        $appliedDiscounts = [];
        $primaryDiscount = null;


        // 🎫 اگه کوپن هست، اول اعمالش کن
        if ($coupon && $coupon->discount) {

            if ($this->validator->validateDiscount($coupon->discount, $quantity)) {

                $couponResult = $this->calculator->calculate(
                    $currentPrice,
                    $coupon->discount,
                    $coupon
                );

                $totalDiscountAmount += $couponResult->discountAmount;
                $currentPrice = max(0, $currentPrice - $couponResult->discountAmount);
                $primaryDiscount = $coupon->discount;
                $appliedDiscounts[] = $coupon->discount;
            }
        }


        // 🎯 تخفیف‌های دیگه رو یکی یکی چک کن
        foreach ($allDiscounts as $discount) {

            // ✅ اگه قبلی‌ها اعمال شدن و این stackable نیست، رد کن
            if (count($appliedDiscounts) > 0 && !$discount->stackable) {
                continue;
            }

            // ✅ اگه اولین تخفیف اعمال شده stackable نبود، متوقف شو
            if (count($appliedDiscounts) > 0 && !$appliedDiscounts[0]->stackable) {
                break;
            }


            // 🔍 ولیدیت‌ها
            if (!$this->validator->validateDiscount($discount, $quantity)) {
                continue;
            }

            if (!$this->validator->validateForUser($discount, $user, $quantity)) {
                continue;
            }


            // 💰 محاسبه روی قیمت فعلی (بعد از تخفیف‌های قبلی)
            $result = $this->calculator->calculate($currentPrice, $discount);

            $totalDiscountAmount += $result->discountAmount;
            $currentPrice = max(0, $currentPrice - $result->discountAmount);
            $appliedDiscounts[] = $discount;

            if (!$primaryDiscount) {
                $primaryDiscount = $discount;
            }


            // 🛑 اگه این تخفیف stackable نیست، بعدش رو ادامه نده
            if (!$discount->stackable) {
                break;
            }
        }


        $finalPrice = max(0, $basePrice - $totalDiscountAmount);

        return new DiscountResult(
            basePrice: $basePrice,
            discountAmount: $totalDiscountAmount,
            price: $finalPrice,
            discount: $primaryDiscount,
            coupon: $coupon
        );
    }


    public function consume(
        DiscountResult $result,
        ?User $user,
        int $quantity = 1
    ) {
        if (!$result->discount) {
            return null;
        }

        if ($result->coupon) {
            return $this->couponUsageService->consume(
                $result->coupon,
                $user,
                $quantity
            );
        }

        return $this->usageService->consume(
            $result->discount,
            $user,
            $quantity
        );
    }
}