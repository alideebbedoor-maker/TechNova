<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateArticleStatusRequest;
use App\Http\Resources\ArticleResource;
use App\Services\AdminArticleManager;
use App\Repositories\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use App\Jobs\SendArticlePublishedEmailJob; 


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
      $articles = Article::all();

        return response()->json([
            'message' => 'Articles retrieved successfully for admin review',
            'data'    => ArticleResource::collection($articles)
        ], 200);
    }

    public function changeStatus(UpdateArticleStatusRequest $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        
        $oldStatus = $article->status;
        
        $validated = $request->validated();

        $article->update([
            'status' => $validated['status']
        ]);

        if ($validated['status'] === 'published' && $oldStatus !== 'published') {
            SendArticlePublishedEmailJob::dispatch($article);
        }

            return response()->json([
            'message' => 'Article status updated successfully',
            'data' => [
             'id' => $article->id,
            'status' => $article->status
                    ]
            ], 200);   
        }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            'message' => 'Article deleted successfully'
        ], 200);
    }
}
