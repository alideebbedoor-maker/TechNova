<?php

namespace App\Listeners;

use App\Events\ArticleCreatedEvent;
use App\Notifications\EmailArticleNotification;
use App\Notifications\InAppArticleNotification;
use App\Services\Notifications\DatabaseNotificationSender;
use App\Services\Notifications\EmailNotificationSender;
use Illuminate\Contracts\Queue\ShouldQueue; 

class SendArticleNotificationListener implements ShouldQueue 
{
    public $tries = 3;

    public function __construct()
    {
        //
    }

    public function handle(ArticleCreatedEvent $event): void
    {
        $sender = ($event->user->role === 'admin') 
            ? app(DatabaseNotificationSender::class) 
            : app(EmailNotificationSender::class);

        $notification = ($event->user->role === 'admin') 
            ? new InAppArticleNotification($event->article)   
            : new EmailArticleNotification($event->article);



        $sender->send($event->user, $notification->onQueue('high')); 
    }
}