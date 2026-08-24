<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerPositionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'max_banners' => $this->max_banners,
            'is_active' => $this->is_active,
            'banners_count' => $this->when(
                isset($this->banners_count),
                $this->banners_count
            ),
            'banners' => BannerResource::collection(
                $this->whenLoaded('activeBanners')
            ),
        ];
    }
}