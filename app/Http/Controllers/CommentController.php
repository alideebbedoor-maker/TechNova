<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $article = Article::findOrFail($validated['article_id']);

        $comment = $article->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $validated['content']  
        ]);

        return response()->json([
            'message' => 'Comment posted successfully',
            'data'    => new CommentResource($comment->load('user'))
        ], 201);
    }
}