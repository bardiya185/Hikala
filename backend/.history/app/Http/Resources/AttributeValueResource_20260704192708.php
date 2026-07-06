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

            'value' => $this->value,

            'code' => $this->code,

            'color_code' => $this->color_code,

            'image' => $this->image,

            'sort_order' => $this->sort_order,

            'is_active' => $this->is_active,

        ];
    }
}