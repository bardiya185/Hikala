<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerPositionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'banners' => BannerResource::collection(
                $this->whenLoaded('activeBanners')
            ),
        ];
    }
}