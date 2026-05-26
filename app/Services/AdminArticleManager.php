<?php

namespace App\Services;

use App\Services\Notifications\NotificationSenderInterface;
use App\Repositories\ArticleRepositoryInterface;
use App\Notifications\InAppArticleNotification; // استيراد إشعار الداتابيز الإنجليزي
use App\Models\User;

class AdminArticleManager
{
    protected $notifier;
    protected $articleRepo;

    public function __construct(NotificationSenderInterface $notifier, ArticleRepositoryInterface $articleRepo)
    {
        $this->notifier = $notifier;
        $this->articleRepo = $articleRepo;
    }

    public function approveAndPublishArticle($articleId, User $admin)
    {
        $article = $this->articleRepo->update($articleId, [
            'status' => 'published',
            'approved_by' => $admin->id,
            'published_at' => now(),
        ]);

        // ✅ هنا الـ Contextual Binding سيعمل 100% ويحقن الـ DatabaseNotificationSender
        $notification = new InAppArticleNotification($article);
        $this->notifier->send($admin, $notification);

        return $article;
    }
}