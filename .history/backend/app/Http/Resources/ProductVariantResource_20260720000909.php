<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Discount\DiscountService;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $discount = app(DiscountService::class)
            ->calculate($this);

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'barcode' => $this->barcode,

            // قیمت قبل از تخفیف
            'base_price' => $discount->originalPrice,

            // قیمت بعد از تخفیف
            'price' => $discount->finalPrice,

            // مبلغ تخفیف
            'discount_amount' => $discount->discountAmount,

            // درصد تخفیف
            'discount_percent' => $discount->originalPrice > 0
                ? round(
                    ($discount->discountAmount / $discount->originalPrice) * 100
                )
                : 0,

            'stock' => $this->stock,
            'weight' => $this->weight,
            'is_active' => $this->is_active,

            'attributes' => AttributeValueResource::collection(
                $this->whenLoaded('attributeValues')
            ),
        ];
    }
}