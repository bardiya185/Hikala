<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'from_status' => $this->when($this->from_status, [
                'value' => $this->from_status?->value,
                'label' => $this->from_status?->label(),
            ]),
            'to_status' => [
                'value' => $this->to_status->value,
                'label' => $this->to_status->label(),
                'color' => $this->to_status->color(),
                'icon' => $this->to_status->icon(),
            ],
            'note' => $this->note,
            'changed_by' => $this->when($this->changedBy, [
                'id' => $this->changedBy?->id,
                'name' => $this->changedBy?->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}