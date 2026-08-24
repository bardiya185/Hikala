<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'items_count' => $this->items_count,
            'items' => CartItemResource::collection(
                $this->whenLoaded('items')
            ),
            'coupon' => $this->when($this->coupon, [
                'code' => $this->coupon?->code,
                'discount_amount' => $this->coupon_discount,
            ]),
            'summary' => [
                'subtotal' => (float) $this->subtotal,
                'products_discount' => (float) $this->products_discount,
                'total_before_coupon' => (float) $this->total_before_coupon,
                'coupon_discount' => (float) $this->coupon_discount,
                'final_total' => (float) $this->final_total,
            ],
            
            'is_empty' => $this->isEmpty(),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}