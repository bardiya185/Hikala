<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // ===== اطلاعات پایه =====
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

            // ===== 💰 اطلاعات قیمت و تخفیف (از ProductService میاد) =====
            'pricing' => $this->when(
                isset($this->_final_price),
                fn() => [
                    'base_price' => $this->_base_price ?? null,
                    'final_price' => $this->_final_price ?? null,
                    'discount_amount' => $this->_discount_amount ?? 0,
                    'discount_percent' => $this->_discount_percent ?? 0,
                    'has_discount' => ($this->_discount_percent ?? 0) > 0,
                ]
            ),

            // ===== 🎯 اطلاعات کمپین (داینامیک) =====
            'campaign' => $this->when(
                isset($this->_campaign_slug) && $this->_campaign_slug,
                fn() => [
                    'slug' => $this->_campaign_slug,
                    'name' => $this->_campaign_name,
                    'icon' => $this->_campaign_icon,
                    'color' => $this->_campaign_color,
                    'ends_at' => $this->_campaign_ends_at,
                ]
            ),

            // ===== 🔗 روابط =====
            'brand' => new BrandResource($this->whenLoaded('brand')),

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

            // ===== ⭐ نظرات =====
            'reviews_count' => $this->whenCounted('approvedReviews'),
            'reviews' => ReviewResource::collection(
                $this->whenLoaded('approvedReviews')
            ),
        ];
    }
}