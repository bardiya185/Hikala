<?php

namespace App\Services\Notification;

use App\Models\Product;
use App\Models\User;
use App\Notifications\PromotionNotification;
use Illuminate\Support\Facades\Notification;

class AdminNotificationService
{
    // ================================================================
    // Send broadcast notification to all users
    // ================================================================
    public function sendBroadcastNotification(string $title, string $message, ?string $url = null): void
    {
        $notification = new PromotionNotification(
            title: $title,
            message: $message,
            url: $url,
            type: 'info'
        );

        User::chunk(500, function ($users) use ($notification) {
            Notification::send($users, $notification);
        });
    }

    // ================================================================
    // Send notification to users who wishlisted a specific product
    // ================================================================
    public function notifyWishlistUsersProductDiscounted(Product $product): int
    {
        $users = $product->wishlistedByUsers;

        if ($users->isEmpty()) {
            return 0;
        }

        $notification = new PromotionNotification(
            title: '🔥 Special Offer on Favorited Item!',
            message: "The product '{$product->title}' from your wishlist is now on sale!",
            url: "/products/{$product->slug}",
            type: 'warning'
        );

        Notification::send($users, $notification);

        return $users->count();
    }
}