<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'image' => $this->image_url,
            'mobile_image' => $this->mobile_image_url,
            'alt_text' => $this->alt_text ?? $this->title,
            'url' => $this->url,
            'background_color' => $this->background_color,
            'text_color' => $this->text_color,
            'sort_order' => $this->sort_order,

            // ✅ فقط برای ادمین
            'is_active' => $this->when(
                $request->is('api/admin/*'),
                $this->is_active
            ),
            'click_count' => $this->when(
                $request->is('api/admin/*'),
                $this->click_count
            ),
            'view_count' => $this->when(
                $request->is('api/admin/*'),
                $this->view_count
            ),
            'starts_at' => $this->when(
                $request->is('api/admin/*'),
                $this->starts_at
            ),
            'ends_at' => $this->when(
                $request->is('api/admin/*'),
                $this->ends_at
            ),
            'linkable_type' => $this->when(
                $request->is('api/admin/*'),
                $this->linkable_type
            ),
            'linkable_id' => $this->when(
                $request->is('api/admin/*'),
                $this->linkable_id
            ),
            'custom_url' => $this->when(
                $request->is('api/admin/*'),
                $this->custom_url
            ),

            'position' => new BannerPositionResource(
                $this->whenLoaded('position')
            ),
        ];
    }
}