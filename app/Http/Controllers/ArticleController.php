<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepositoryInterface;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
}