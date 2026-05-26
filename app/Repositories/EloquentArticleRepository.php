<?php

namespace App\Repositories;

use App\Models\Article;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function getPublishedArticles()
    {
        return Article::where('status', 'published')
            ->with([
                'author.profile',      
                'comments.user',        
                'tags'                  
            ])
            ->latest('published_at') 
            ->get();
    }

   
    public function findById($id)
    {
        $article = Article::findOrFail($id);
        
        $article->load(['author', 'tags', 'comments.user']);

        return $article;
    }

    public function create(array $data)
    {
        return Article::create($data);
    }

    public function update($id, array $data)
{
    $article = $this->findById($id);  
    $article->update($data);         
    
    $article->load(['author', 'tags']); 
    
    return $article;
}
}