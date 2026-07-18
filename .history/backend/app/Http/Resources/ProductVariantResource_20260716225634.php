<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
  // app/Http/Resources/ProductVariantResource.php

public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'sku' => $this->sku,
        'barcode' => $this->barcode,
        'price' => $this->price,
        'sale_price' => $this->sale_price,
        'stock' => $this->stock,
        'weight' => $this->weight,
        'is_active' => $this->is_active,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        
        // ✅ اگه اسم رابطه attributeValues هست
        'attributes' => AttributeValueResource::collection(
            $this->whenLoaded('attributeValues')
        ),
    ];
}