<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest; 
use App\Http\Resources\ArticleResource;
use App\Services\WriterArticleManager; 
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    protected $writerArticleManager;

    public function __construct(WriterArticleManager $writerArticleManager)
    {
        $this->writerArticleManager = $writerArticleManager;
    }

   public function store(StoreArticleRequest $request) 
{
    $article = $this->writerArticleManager->submitArticleForReview(
        $request->validated(), 
        $request->user()
    );

    return response()->json([
        'message' => 'Article submitted for review successfully',
        'data' => $article
    ], 201);
  

        $article = $this->writerArticleManager->submitArticleForReview($validatedData, $request->user());

        return response()->json([
            'message' => 'Article submitted for review successfully',
            'data'    => new ArticleResource($article)
        ], 201);
    }
}