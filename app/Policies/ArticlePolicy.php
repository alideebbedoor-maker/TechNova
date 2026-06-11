<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function view(?User $user, Article $article): bool
    {
        if ($article->status === 'published') {
            return true;
        }

        if ($user) {
            return $user->role === 'admin' || $user->id === $article->user_id;
        }

        return false;
    }

    
    public function update(User $user, Article $article): bool
    {
        return $user->role === 'admin' || $user->id === $article->user_id;
    }

   
    public function delete(User $user, Article $article): bool
    {
        return $user->role === 'admin' || ($user->id === $article->user_id && $article->status !== 'published');
    }
}