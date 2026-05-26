<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
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
        return false;
    }

    
    /**
    * Determine whether the user can delete the model.*/
    
    public function delete(User $user, Article $article): bool
    {
       return $user->role === 'admin' || ($user->id === $article->user_id && $article->status !== 'published');
    }
}
    