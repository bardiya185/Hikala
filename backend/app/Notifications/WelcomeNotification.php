<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function __construct() {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'    => 'success',
            'title'   => 'Welcome! 🎉',
            'message' => 'Welcome to our store. We are glad to have you with us!',
            'url'     => '/profile',
        ];
    }
}