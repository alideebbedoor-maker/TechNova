<?php

namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Services\Notifications\NotificationSenderInterface;
use App\Notifications\EmailArticleNotification; 
use App\Notifications\InAppArticleNotification; 
use App\Models\Article;
use App\Models\User;

class ArticleService
{
    protected $articleRepository;
    protected $notifier;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        NotificationSenderInterface $notifier
    ) {
        $this->articleRepository = $articleRepository;
        $this->notifier          = $notifier;
    }

    public function createArticle(array $data, User $writer): Article
    {
        $data['user_id'] = $writer->id;
        $article = $this->articleRepository->create($data);

        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }

        $article->load('tags');

        $notification = new EmailArticleNotification($article);
        $this->notifier->send($writer, $notification); 

        return $article;
    }

    public function updateStatus(int $articleId, string $status, User $admin): Article
    {
        $article = $this->articleRepository->findById($articleId);
        $article->update(['status' => $status]);
        
        $article->load('author');

        $notification = new InAppArticleNotification($article);
        $this->notifier->send($admin, $notification);

        return $article;
    }
}