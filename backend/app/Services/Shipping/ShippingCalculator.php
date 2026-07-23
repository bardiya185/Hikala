<?php

namespace App\Services\Shipping;

use App\Enums\ShippingMethod;

/**
 * 🚚 Shipping Cost Calculator
 * Calculates shipping cost for orders
 */
class ShippingCalculator
{
    private const FREE_SHIPPING_THRESHOLD = 100.00;
    private const STANDARD_COST = 5.00;
    private const EXPRESS_COST = 15.00;


    /**
     * Calculate shipping cost
     */
    public function calculate(
        float $cartTotal,
        ShippingMethod $method = ShippingMethod::STANDARD
    ): float {
        
        // Free shipping for large orders
        if ($cartTotal >= self::FREE_SHIPPING_THRESHOLD) {
            return 0.00;
        }
        
        return match($method) {
            ShippingMethod::EXPRESS  => self::EXPRESS_COST,
            ShippingMethod::STANDARD => self::STANDARD_COST,
            ShippingMethod::FREE     => 0.00,
        };
    }


    /**
     * Get all available shipping methods
     */
    public function getAvailableMethods(float $cartTotal): array
    {
        $methods = [];
        
        foreach (ShippingMethod::cases() as $method) {
            // Only show FREE if eligible
            if ($method === ShippingMethod::FREE 
                && $cartTotal < self::FREE_SHIPPING_THRESHOLD) {
                continue;
            }
            
            $methods[] = [
                'value' => $method->value,
                'label' => $method->label(),
                'cost' => $this->calculate($cartTotal, $method),
                'estimated_days' => $method->estimatedDays(),
                'color' => $method->color(),
            ];
        }
        
        return $methods;
    }


    /**
     * Get free shipping progress info
     */
    public function getFreeShippingInfo(float $cartTotal): array
    {
        $isEligible = $cartTotal >= self::FREE_SHIPPING_THRESHOLD;
        $amountNeeded = max(0, self::FREE_SHIPPING_THRESHOLD - $cartTotal);
        
        return [
            'threshold' => self::FREE_SHIPPING_THRESHOLD,
            'is_eligible' => $isEligible,
            'amount_needed' => $amountNeeded,
            'message' => $isEligible 
                ? '🎉 You qualify for FREE shipping!' 
                : "💡 Add \${$amountNeeded} more for FREE shipping!",
        ];
    }
}