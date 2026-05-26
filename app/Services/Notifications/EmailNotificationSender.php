<?php

namespace App\Services\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class EmailNotificationSender implements NotificationSenderInterface
{
    public function send(User $user, Notification $notification): void
    {
        // استخدام نظام لارافيل الافتراضي لإرسال الإشعار عبر القنوات المحددة (Mail)
        $user->notify($notification);
    }
}