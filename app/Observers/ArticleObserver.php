<?php

namespace App\Observers;

use App\Models\Article;
use App\Events\ArticleCreatedEvent;
use App\Models\User;


class ArticleObserver
{
    public function created(Article $article): void
{
    event(new ArticleCreatedEvent($article, $article->author));
}
    public function updated(Article $article): void
    {
        \Cache::forget('articles_list');
    }
}