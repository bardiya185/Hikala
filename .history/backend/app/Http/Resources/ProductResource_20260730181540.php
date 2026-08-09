<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DiscountResource;


class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'status' => $this->status,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'view_count' => $this->view_count,
            'rating' => $this->rating,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

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

           'discounts' => DiscountResource::collection(
                $this->whenLoaded('discounts')
            ),

            'campaign' => $this->whenLoaded('campaign', function () {
                return [
                    'id' => $this->campaign?->id,
                    'name' => $this->campaign?->name,
                    'slug' => $this->campaign?->slug,
                    'description' => $this->campaign?->description,
                    'icon' => $this->campaign?->icon,
                    'color' => $this->campaign?->color,
                    'banner_image' => $this->campaign?->banner_image,
                    'starts_at' => $this->campaign?->starts_at,
                    'ends_at' => $this->campaign?->ends_at,
                    'priority' => $this->campaign?->priority,
                    'is_active' => $this->campaign?->is_active,
                ];
            }),
           
        ];
    }
}