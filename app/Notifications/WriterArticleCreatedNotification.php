<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WriterArticleCreatedNotification extends Notification
{
    use Queueable;

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
            ->subject('Article Successfully Submitted for Review')
            ->greeting("Dear Writer, {$notifiable->name}")
            ->line("Your new article has been successfully registered and is now pending review.")
            ->line("Proposed Title: \"{$this->article->title}\"")
            ->action('Preview Draft', url("/api/articles/{$this->article->id}"))
            ->line('The administration team will review your content and publish it shortly.');
    }
}