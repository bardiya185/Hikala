<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attribute_id' => $this->attribute_id,
            'attribute_name' => $this->attribute ? $this->attribute->name : null,
            'attribute_slug' => $this->attribute ? $this->attribute->slug : null,
            'value' => $this->value,
            'slug' => $this->slug,
            'color_code' => $this->color_code,
            'image' => $this->image,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}