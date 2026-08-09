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
    
            // قیمت اصلی
            'base_price' => $discount->price,
    
            // قیمت بعد از تخفیف
            'price' => $discount->finalPrice,
    
            // مبلغ تخفیف
            'discount_amount' => $discount->discountAmount,
    
            // درصد تخفیف
            'discount_percent' => $discount->price > 0
                ? round(($discount->discountAmount / $discount->price) * 100)
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