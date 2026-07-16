<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
    
            'id' => $this->id,
    
            'title' => $this->title,
    
            'receiver_name' => $this->receiver_name,
    
            'receiver_mobile' => $this->receiver_mobile,
    
            'province' => [
    
                'id' => $this->province?->id,
    
                'name' => $this->province?->name,
    
            ],
    
            'city' => [
    
                'id' => $this->city?->id,
    
                'name' => $this->city?->name,
    
            ],
    
            'address' => $this->address,
    
            'building_number' => $this->building_number,
    
            'unit' => $this->unit,
    
            'postal_code' => $this->postal_code,
    
            'latitude' => $this->latitude,
    
            'longitude' => $this->longitude,
    
            'is_default' => $this->is_default,
    
            'created_at' => $this->created_at,
    
        ];
    }
}
