<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function index(): JsonResponse
    {
        $articles = Article::where('status', 'published')
            ->with(['author', 'tags']) 
            ->withCount('comments')
            ->latest('published_at')
            ->get();

        return response()->json([
            'version' => 'V2 (Mobile App optimized)',
            'data'    => ArticleResource::collection($articles)
        ], 200);
    }
}