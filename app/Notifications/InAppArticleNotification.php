<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InAppArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    protected $article; 

    public function __construct(Article $article)
    {
        // استقبل Model مباشرة
        $this->article = $article;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'article_id'   => $this->article->id,
            'title'        => $this->article->title,
            'status'       => $this->article->status,
            'triggered_at' => now()->toIso8601String(),
            'message'      => "Admin Alert: An article status has been updated.",
        ];
    }
}
