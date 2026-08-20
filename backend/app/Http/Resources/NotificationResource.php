<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->data['type'] ?? 'info',
            'title'      => $this->data['title'] ?? '',
            'message'    => $this->data['message'] ?? '',
            'url'        => $this->data['url'] ?? null,
            'is_read'    => $this->read_at !== null,
            'read_at'    => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}