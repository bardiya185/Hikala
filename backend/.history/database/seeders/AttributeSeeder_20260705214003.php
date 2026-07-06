<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'brand' => $this->whenLoaded('brand', function() {
                return [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                    'slug' => $this->brand->slug,
                    'logo' => $this->brand->logo,
                    'description' => $this->brand->description,
                    'sort_order' => $this->brand->sort_order,
                    'is_active' => $this->brand->is_active,
                    'created_at' => $this->brand->created_at,
                    'updated_at' => $this->brand->updated_at,
                ];
            }),
            
            'categories' => $this->whenLoaded('categories', function() {
                return $this->categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'parent_id' => $category->parent_id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'icon_key' => $category->icon_key,
                        'image' => $category->image,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                    ];
                });
            }),
            
            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                        'is_main' => $image->is_main,
                    ];
                });
            }),
            
            'variants' => $this->whenLoaded('variants', function() {
                return $this->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode,
                        'price' => $variant->price,
                        'sale_price' => $variant->sale_price,
                        'stock' => $variant->stock,
                        'weight' => $variant->weight,
                        'is_active' => $variant->is_active,
                        'created_at' => $variant->created_at,
                        'updated_at' => $variant->updated_at,
                        // ===== ویژگی‌های تنوع =====
                        'attributes' => $variant->attributeValues->map(function($attrValue) {
                            return [
                                'id' => $attrValue->id,
                                'attribute_id' => $attrValue->attribute_id,
                                'value' => $attrValue->value,
                                'slug' => $attrValue->slug,
                                'color_code' => $attrValue->color_code,
                                'attribute' => [
                                    'id' => $attrValue->attribute->id ?? null,
                                    'name' => $attrValue->attribute->name ?? null,
                                    'slug' => $attrValue->attribute->slug ?? null,
                                ]
                            ];
                        }),
                    ];
                });
            }),
            
            // قیمت‌های min و max
            'min_price' => $this->variants->min('sale_price') ?? $this->variants->min('price'),
            'max_price' => $this->variants->max('sale_price') ?? $this->variants->max('price'),
        ];
    }
}