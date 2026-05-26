<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailArticleNotification extends Notification implements ShouldQueue // 2. التنفيذ
{
    use Queueable;

    public $tries = 3; 
    public $backoff = [60, 120, 180]; // يحاول بعد دقيقة، ثم دقيقتين، ثم 3 دقائق

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Article Update Notification')
            ->greeting("Hello {$notifiable->name},")
            ->line("An action has been taken regarding your article: \"{$this->article->title}\".")
            ->action('View Details', url("/api/articles/{$this->article->id}"))
            ->line('Thank you for using our platform!');
    }
}