<?php

namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Models\User;

class WriterArticleManager
{
    protected $articleRepo;

    public function __construct(ArticleRepositoryInterface $articleRepo)
    {
        $this->articleRepo = $articleRepo;
    }

    public function submitArticleForReview(array $data, User $writer)
    {
        $data['user_id'] = $writer->id;
        $data['status'] = 'pending_review';

        return $this->articleRepo->create($data);
        
    }
}