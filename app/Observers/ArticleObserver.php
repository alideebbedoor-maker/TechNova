<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function created(Article $article): void
    {
       event(new \App\Events\ArticleCreatedEvent($article, $article->user));
    }
    public function updated(Article $article): void
    {
        \Cache::forget('articles_list');
    }
}