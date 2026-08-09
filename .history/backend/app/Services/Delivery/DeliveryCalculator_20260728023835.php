<?php

namespace App\Services\Delivery;

use App\Enums\ShippingFeatureType;
use App\Models\Cart;
use Carbon\Carbon;
use App\Services\Delivery\DeliveryCalculator; 

/**
 * 🚚 Calculates delivery dates based on cart items shipping features
 */
class DeliveryCalculator
{
    // 📅 حداقل روز از الان (buffer time برای پردازش)
    private const BUFFER_HOURS = 2;

    /**
     * 🎯 محاسبه زودترین زمان تحویل
     */
    public function calculateEarliestDelivery(Cart $cart): Carbon
    {
        $maxPreparationDays = 0;

        foreach ($cart->items as $item) {
            $variant = $item->variant;
            
            if (!$variant) continue;
            
            // پیدا کردن سریع‌ترین روش ارسال این محصول
            $fastestShipping = $this->getFastestShippingFeature($variant);
            
            // بیشترین زمان بین همه محصولات
            $maxPreparationDays = max(
                $maxPreparationDays,
                $fastestShipping->minPreparationDays()
            );
        }

        // اگه محصول با ارسال فوری بود، از الان چند ساعت buffer بذار
        if ($maxPreparationDays === 0) {
            return now()->addHours(self::BUFFER_HOURS);
        }

        return now()->addDays($maxPreparationDays);
    }

    /**
     * 🎯 محاسبه دیرترین زمان تحویل (برای بازه)
     */
    public function calculateLatestDelivery(Cart $cart): Carbon
    {
        $maxPreparationDays = 0;

        foreach ($cart->items as $item) {
            $variant = $item->variant;
            
            if (!$variant) continue;
            
            $slowestShipping = $this->getSlowestShippingFeature($variant);
            
            $maxPreparationDays = max(
                $maxPreparationDays,
                $slowestShipping->maxPreparationDays()
            );
        }

        return now()->addDays(max(1, $maxPreparationDays));
    }

    /**
     * 📅 لیست روزهای قابل انتخاب
     */
    public function getAvailableDates(Cart $cart, int $windowDays = 7): array
    {
        $earliestDate = $this->calculateEarliestDelivery($cart);
        
        // اگه ساعت الان بعد از 14 هست، از فردا شروع کن
        if ($earliestDate->isToday() && now()->hour >= 14) {
            $earliestDate = now()->addDay()->startOfDay();
        }
        
        $dates = [];
        
        for ($i = 0; $i < $windowDays; $i++) {
            $date = $earliestDate->copy()->addDays($i);
            
            $dates[] = [
                'value' => $date->format('Y-m-d'),
                'label' => $date->format('l, F j'),
                'day_name' => $date->format('l'),
                'is_today' => $date->isToday(),
                'is_tomorrow' => $date->isTomorrow(),
                'is_earliest' => $i === 0,
            ];
        }
        
        return $dates;
    }

    /**
     * 🚀 سریع‌ترین روش ارسال یه variant
     */
    private function getFastestShippingFeature($variant): ShippingFeatureType
    {
        $features = $variant->shippingFeatures ?? collect();
        
        // اگه هیچ shipping feature نداره، STANDARD
        if ($features->isEmpty()) {
            return ShippingFeatureType::STANDARD;
        }
        
        // پیدا کردن کمترین preparation days
        $fastest = ShippingFeatureType::STANDARD;
        $minDays = PHP_INT_MAX;
        
        foreach ($features as $feature) {
            if (!$feature->is_active) continue;
            
            $type = $feature->type;
            
            if ($type->minPreparationDays() < $minDays) {
                $minDays = $type->minPreparationDays();
                $fastest = $type;
            }
        }
        
        return $fastest;
    }

    /**
     * 🐢 کندترین روش ارسال یه variant
     */
    private function getSlowestShippingFeature($variant): ShippingFeatureType
    {
        $features = $variant->shippingFeatures ?? collect();
        
        if ($features->isEmpty()) {
            return ShippingFeatureType::STANDARD;
        }
        
        $slowest = ShippingFeatureType::SAME_DAY;
        $maxDays = 0;
        
        foreach ($features as $feature) {
            if (!$feature->is_active) continue;
            
            $type = $feature->type;
            
            if ($type->maxPreparationDays() > $maxDays) {
                $maxDays = $type->maxPreparationDays();
                $slowest = $type;
            }
        }
        
        return $slowest;
    }

    /**
     * 📊 خلاصه اطلاعات تحویل برای نمایش
     */
    public function getDeliveryInfo(Cart $cart): array
    {
        $earliest = $this->calculateEarliestDelivery($cart);
        $latest = $this->calculateLatestDelivery($cart);
        
        return [
            'earliest_date' => $earliest->format('Y-m-d'),
            'earliest_date_formatted' => $earliest->format('l, F j'),
            'latest_date' => $latest->format('Y-m-d'),
            'latest_date_formatted' => $latest->format('l, F j'),
            'estimated_days' => now()->diffInDays($earliest) . '-' . now()->diffInDays($latest) . ' days',
            'is_same_day_available' => $earliest->isToday(),
        ];
    }
}