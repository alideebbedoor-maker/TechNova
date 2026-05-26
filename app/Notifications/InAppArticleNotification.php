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
    protected $articleId; 

    public function __construct($articleId)
    {
        $this->articleId = $articleId;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $article = Article::find($this->articleId);

        return [
            'article_id'   => $article ? $article->id : null,
            'title'        => $article ? $article->title : 'Article deleted',
            'status'       => $article ? $article->status : 'N/A',
            'triggered_at' => now()->toIso8601String(),
            'message'      => "Admin Alert: An article status has been updated.",
        ];
    }
}