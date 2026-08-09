<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            
            // 📸 Snapshots
            'product_title' => $this->product_title,
            'product_sku' => $this->product_sku,
            'product_image' => $this->image_url,
            'variant_attributes' => $this->variant_attributes,
            
            // 🔢 Numbers
            'quantity' => $this->quantity,
            'base_price' => (float) $this->base_price,
            'final_price' => (float) $this->final_price,
            'discount_amount' => (float) $this->discount_amount,
            'discount_percent' => $this->discount_percent,
            'total' => (float) $this->total,
        ];
    }
}