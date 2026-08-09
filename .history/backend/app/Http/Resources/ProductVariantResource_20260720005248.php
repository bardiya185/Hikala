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
            ->calculate($this->resource);
            return [
                'id' => $this->id,
                'sku' => $this->sku,
                'barcode' => $this->barcode,
            
                // قیمت اصلی قبل از تخفیف
                'base_price' => $discount->basePrice,
            
                // قیمت نهایی بعد از تخفیف
                'price' => $discount->price,
            
                // مقدار تخفیف
                'discount_amount' => $discount->discountAmount,
            
                'discounts' => DiscountResource::collection(
                    $this->whenLoaded('discounts')
                ),
            
                'stock' => $this->stock,
                'weight' => $this->weight,
                'is_active' => $this->is_active,
            
                'attributes' => AttributeValueResource::collection(
                    $this->whenLoaded('attributeValues')
                ),
            ];
    }
     }