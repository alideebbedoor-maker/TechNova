<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * منطق اختيار القناة بناءً على رتبة المستخدم
     */
    public function via($notifiable): array
    {
        if ($notifiable->role === 'admin') {
            return ['database'];
        }

        return ['mail'];
    }

}