<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * 📦 Main Order Service (for user-facing operations)
 */
class OrderService
{
    public function __construct(
        private OrderStatusService $statusService
    ) {}

    /**
     * 📋 Get user's orders
     */
    public function getUserOrders(User $user, int $perPage = 10)
    {
        return Order::forUser($user->id)
            ->with(['items', 'address', 'coupon'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * 🔍 Get single order (with authorization)
     */
    public function getUserOrder(User $user, int $orderId): Order
    {
        $order = Order::with([
            'items',
            'address.province',
            'address.city',
            'coupon',
            'statusHistory.changedBy',
        ])->findOrFail($orderId);
        
        if ($order->user_id !== $user->id) {
            throw new \Exception('You do not have access to this order');
        }
        
        return $order;
    }

    /**
     * ❌ Cancel order (by user)
     */
    public function cancelOrder(Order $order, User $user, ?string $reason = null): Order
    {
        if ($order->user_id !== $user->id) {
            throw new \Exception('You do not have access to this order');
        }
        if (!$order->canBeCanceled()) {
            throw new \Exception('This order cannot be canceled');
        }
        
        return DB::transaction(function () use ($order, $user, $reason) {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }
            return $this->statusService->changeStatus(
                $order,
                OrderStatus::CANCELED,
                $user,
                $reason ?? 'Canceled by user'
            );
        });
    }

    /**
     * 🔄 Request refund (by user)
     */
    public function requestRefund(Order $order, User $user, string $reason): Order
    {
        if ($order->user_id !== $user->id) {
            throw new \Exception('You do not have access to this order');
        }
        
        if (!$order->canBeRefunded()) {
            throw new \Exception('This order cannot be refunded');
        }
        
        return $this->statusService->changeStatus(
            $order,
            OrderStatus::REFUNDED,
            $user,
            "Refund requested: {$reason}"
        );
    }
}