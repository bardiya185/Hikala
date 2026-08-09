<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountCampaignResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'banner_image' => $this->banner_url,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'is_currently_active' => $this->isCurrentlyActive(),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            
            // تعداد تخفیف‌ها
            'discounts_count' => $this->when(
                isset($this->discounts_count),
                $this->discounts_count
            ),
            
            // لیست تخفیف‌ها (اگه load شده)
            'discounts' => $this->whenLoaded('discounts'),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}