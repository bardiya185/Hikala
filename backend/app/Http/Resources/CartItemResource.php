<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            
            // قیمت‌ها
            'base_price' => (float) $this->base_price,
            'final_price' => (float) $this->final_price,
            'discount_amount' => (float) $this->discount_amount,
            'discount_percent' => $this->discount_percent,
            
            // جمع
            'subtotal' => (float) $this->subtotal,
            'total' => (float) $this->total,
            
            // اطلاعات محصول
            'variant' => [
                'id' => $this->variant->id,
                'sku' => $this->variant->sku,
                'stock' => $this->variant->stock,
                'product' => $this->when($this->variant->product, [
                    'id' => $this->variant->product?->id,
                    'title' => $this->variant->product?->title,
                    'slug' => $this->variant->product?->slug,
                    'main_image' => $this->variant->product?->images?->firstWhere('is_main', true)?->image_path 
                        ? asset('storage/' . $this->variant->product->images->firstWhere('is_main', true)->image_path)
                        : null,
                ]),
            ],
        ];
    }
}