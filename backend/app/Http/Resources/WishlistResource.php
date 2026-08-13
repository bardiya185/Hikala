<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'rating' => $this->rating,
            'is_active' => $this->is_active,

            'added_at' => $this->pivot?->created_at,

            'brand' => new BrandResource(
                $this->whenLoaded('brand')
            ),

            'categories' => ProductCategoryResource::collection(
                $this->whenLoaded('categories')
            ),

            'images' => ProductImageResource::collection(
                $this->whenLoaded('images')
            ),

            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),

            'discounts' => $this->whenLoaded('discounts'),
        ];
    }
}