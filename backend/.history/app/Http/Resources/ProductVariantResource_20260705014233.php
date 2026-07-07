<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        public function toArray(Request $request): array
        {
            return [
        
                'id'=>$this->id,
        
                'sku'=>$this->sku,
        
                'barcode'=>$this->barcode,
        
                'price'=>$this->price,
        
                'stock'=>$this->stock,
        
                'weight'=>$this->weight,
        
                'is_active'=>$this->is_active,
        
            ];
        }
    }
}
