<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * 📊 Manages order status changes and history
 */
class OrderStatusService
{
    /**
     * 🔄 Change order status and log to history
     */
    public function changeStatus(
        Order $order,
        OrderStatus $newStatus,
        ?User $changedBy = null,
        ?string $note = null
    ): Order {
        return DB::transaction(function () use ($order, $newStatus, $changedBy, $note) {
            
            $oldStatus = $order->status;
            if ($oldStatus === $newStatus) {
                return $order;
            }
            $order->status = $newStatus;
            $this->setStatusTimestamp($order, $newStatus);
            
            $order->save();
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $oldStatus->value,
                'to_status' => $newStatus->value,
                'changed_by_user_id' => $changedBy?->id,
                'note' => $note,
                'created_at' => now(),
            ]);
            
            return $order->fresh();
        });
    }

    /**
     * ⏰ Set the appropriate timestamp for each status
     */
    private function setStatusTimestamp(Order $order, OrderStatus $status): void
    {
        match ($status) {
            OrderStatus::PAID => $order->paid_at = now(),
            OrderStatus::SHIPPED => $order->shipped_at = now(),
            OrderStatus::DELIVERED => $order->delivered_at = now(),
            OrderStatus::CANCELED => $order->canceled_at = now(),
            OrderStatus::REFUNDED => $order->refunded_at = now(),
            default => null,
        };
    }

    /**
     * 📝 Log initial status (when order created)
     */
    public function logInitialStatus(Order $order, ?User $user = null): void
    {
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => null,
            'to_status' => $order->status->value,
            'changed_by_user_id' => $user?->id,
            'note' => 'Order created',
            'created_at' => now(),
        ]);
    }
}