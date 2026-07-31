<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'stackable' => $this->stackable,
            'quantity_limit' => $this->quantity_limit,
            'used_quantity' => $this->used_quantity,
            'priority' => $this->priority,
            'is_active' => $this->is_active,

            'campaign' => new DiscountCampaignResource(
                $this->whenLoaded('campaign')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}