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
        ];
    }
}