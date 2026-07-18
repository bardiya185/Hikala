<?php

use App\Models\Discount;
use App\Models\ProductVariant;
use Carbon\Carbon;

private function getActiveDiscount(ProductVariant $variant): ?Discount
{
    return $variant->discounts()

        ->where('is_active', true)

        ->where(function ($query) {
            $query->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', Carbon::now());
        })

        ->where(function ($query) {
            $query->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', Carbon::now());
        })

        ->orderByDesc('priority')

        ->first();
}