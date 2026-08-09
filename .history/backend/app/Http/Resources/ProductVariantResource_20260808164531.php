<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Discount\DiscountService;

class ProductVariantResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $pricing = app(\App\Services\Discount\DiscountService::class)
            ->calculate($this->resource);
    
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
    
            'base_price' => $pricing->basePrice,
            'final_price' => $pricing->price,
            'discount_amount' => $pricing->discountAmount,
            'discount_percent' => $pricing->basePrice > 0
                ? round(($pricing->discountAmount / $pricing->basePrice) * 100)
                : 0,
    
            'stock' => $this->stock,
            
            'weight' => $this->weight,
    
            // ✅ اضافه شد - برای انتخاب درست در فرانت
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
    
            'attributes' => AttributeValueResource::collection(
                $this->whenLoaded('attributeValues')
            ),

            'shipping_features' => ShippingFeatureResource::collection(
                $this->whenLoaded('shippingFeatures')   
),
        ];
    }
     }