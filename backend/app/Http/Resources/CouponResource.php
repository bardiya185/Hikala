<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'remaining' => $this->usage_limit
                ? max(0, $this->usage_limit - $this->used_count)
                : null,
            'is_active' => $this->is_active,
            
            // اطلاعات تخفیف مرتبط
            'discount' => $this->when($this->discount, function () {
                return [
                    'id' => $this->discount->id,
                    'name' => $this->discount->name,
                    'type' => $this->discount->type,
                    'value' => (float) $this->discount->value,
                    'starts_at' => $this->discount->starts_at,
                    'ends_at' => $this->discount->ends_at,
                    'is_active' => $this->discount->is_active,
                ];
            }),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}