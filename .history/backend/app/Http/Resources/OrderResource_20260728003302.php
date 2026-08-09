<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            
            // 📊 Status
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'color' => $this->status->color(),
                'icon' => $this->status->icon(),
            ],
            
            'payment_status' => [
                'value' => $this->payment_status->value,
                'label' => $this->payment_status->label(),
                'color' => $this->payment_status->color(),
            ],
            
            'payment_method' => [
                'value' => $this->payment_method->value,
                'label' => $this->payment_method->label(),
                'icon' => $this->payment_method->icon(),
            ],
            
            // 💰 Financial
            'subtotal' => (float) $this->subtotal,
            'discount_amount' => (float) $this->discount_amount,
            'coupon_amount' => (float) $this->coupon_amount,
            'shipping_cost' => (float) $this->shipping_cost,
            'total_amount' => (float) $this->total_amount,
            
            // 🔢 Counts
            'items_count' => $this->items_count,
            
            // ⏰ Timestamps
            'paid_at' => $this->paid_at,
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'canceled_at' => $this->canceled_at,
            'created_at' => $this->created_at,
            
            // 📝 Notes
            'customer_note' => $this->customer_note,

            
            
            // 🎯 Actions available
            'can_be_canceled' => $this->canBeCanceled(),
            'can_be_refunded' => $this->canBeRefunded(),
            
            // 🔗 Relations
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'status_history' => OrderStatusHistoryResource::collection(
                $this->whenLoaded('statusHistory')
            ),
        ];
    }
}