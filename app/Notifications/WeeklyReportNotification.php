<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $articles;

    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = new MailMessage;
        $mail->subject('Weekly Articles Report')
             ->line('Here is the report of articles published this week:')
             ->line('--------------------------------------------------');

        foreach ($this->articles as $article) {
            $mail->line('- ' . $article->title . ' (Status: ' . $article->status . ')');
        }

        return $mail->action('Go to Dashboard', url('/admin/dashboard'))
                    ->line('Thank you for using our platform!');
    }
}