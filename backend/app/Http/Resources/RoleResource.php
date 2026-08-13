<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions_count' => $this->whenCounted('permissions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}