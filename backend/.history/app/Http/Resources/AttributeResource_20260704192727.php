<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'type' => $this->type,

            'unit' => $this->unit,

            'is_filterable' => $this->is_filterable,

            'is_variant' => $this->is_variant,

            'is_required' => $this->is_required,

            'sort_order' => $this->sort_order,

            'is_active' => $this->is_active,

            'values' => AttributeValueResource::collection(
                $this->whenLoaded('values')
            ),

        ];
    }
}