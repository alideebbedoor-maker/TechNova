<?php

namespace App\Http\Controllers\V1 ;

use App\Http\Controllers\Controller;

use App\Repositories\ArticleRepositoryInterface;
use App\Http\Resources\V1\ArticleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        $articles = $this->articleRepository->getPublishedArticles();

        return ArticleResource::collection($articles);
    }

    public function show(int $id): ArticleResource
    {
        $article = $this->articleRepository->findById($id);

        return new ArticleResource($article);
    }
    public function update(Request $request, int $id)
{
    $article = $this->articleRepository->findById($id);

    if ($request->user()->id !== $article->user_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $updated = $this->articleRepository->update($id, $request->all());

    return new ArticleResource($updated);
}

}