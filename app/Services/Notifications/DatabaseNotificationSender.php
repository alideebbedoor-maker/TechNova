<?php

namespace App\Services\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class DatabaseNotificationSender implements NotificationSenderInterface
{
    public function send(User $user, Notification $notification): void
    {
        // حشر الإشعار داخل جدول الـ notifications في الداتابيز للأدمن
        $user->notify($notification);
    }
}