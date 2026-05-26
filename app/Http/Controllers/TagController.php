<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tags = Tag::withCount(['articles' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return TagResource::collection($tags);
    }
}