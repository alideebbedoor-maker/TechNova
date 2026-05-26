<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateArticleStatusRequest;
use App\Http\Resources\ArticleResource;
use App\Services\AdminArticleManager; 
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    protected $adminArticleManager;
    protected $articleRepository;

    public function __construct(AdminArticleManager $adminArticleManager, ArticleRepositoryInterface $articleRepository)
    {
        $this->adminArticleManager = $adminArticleManager;
        $this->articleRepository   = $articleRepository;
    }

    public function index(): JsonResponse
    {
        $articles = $this->articleRepository->getPublishedArticles(); 

        return response()->json([
            'message' => 'Articles retrieved successfully for admin review',
            'data'    => ArticleResource::collection($articles)
        ], 200);
    }

    public function changeStatus(UpdateArticleStatusRequest $request, int $id): JsonResponse
    {
        $article = $this->adminArticleManager->approveAndPublishArticle($id, $request->user());

        return response()->json([
            'message' => 'Article status updated successfully by Admin',
            'data'    => new ArticleResource($article)
        ], 200);
    }
}