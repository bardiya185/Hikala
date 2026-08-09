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

            'delivery' => [
                'preferred_date' => $this->preferred_delivery_date?->format('Y-m-d'),
                'preferred_date_formatted' => $this->preferred_delivery_date?->format('l, F j, Y'),
                'time_slot' => $this->when($this->preferred_delivery_time_slot, [
                    'value' => $this->preferred_delivery_time_slot?->value,
                    'label' => $this->preferred_delivery_time_slot?->label(),
                    'time_range' => $this->preferred_delivery_time_slot?->timeRange(),
                    'icon' => $this->preferred_delivery_time_slot?->icon(),
                ]),
                'estimated' => $this->when($this->estimated_delivery_from, [
                    'from' => $this->estimated_delivery_from,
                    'to' => $this->estimated_delivery_to,
                ]),
            ],
            
            // ✅ جدید: Shipping/Tracking
            'shipping' => [
                'tracking_code' => $this->tracking_code,
                'carrier' => $this->when($this->shipping_carrier, [
                    'value' => $this->shipping_carrier?->value,
                    'label' => $this->shipping_carrier?->label(),
                ]),
                'tracking_url' => $this->tracking_url,
            ],
            
            // ✅ جدید: Reasons
            'cancel_reason' => $this->cancel_reason,
            'refund_reason' => $this->refund_reason,
        ];
            
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