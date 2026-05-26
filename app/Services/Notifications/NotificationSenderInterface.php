<?php

namespace App\Services\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

interface NotificationSenderInterface
{
    /**
     * إجبار أي كلاس شحن على استقبال المستخدم والإشعار لتنفيذه
     */
    public function send(User $user, Notification $notification): void;
}