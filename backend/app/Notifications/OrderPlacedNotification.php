<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'    => 'info',
            'title'   => 'Order Confirmed 🛍️',
            'message' => "Your order #{$this->order->id} has been placed successfully.",
            'url'     => "/orders/{$this->order->id}",
        ];
    }
}