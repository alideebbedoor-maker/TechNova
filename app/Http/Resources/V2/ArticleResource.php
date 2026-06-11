<?php

namespace App\Http\Resources\V2;

use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $wordCount = Str::wordCount($this->content);
        $readingTime = max(1, (int) ceil($wordCount / 200)); 

        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'content'        => $this->content,
            'published_at'   => $this->published_at ? \Carbon\Carbon::parse($this->published_at)->toIso8601String() : null,
            
            'author_name'    => $this->author ? $this->author->name : 'Unknown Author', 
            
            'reading_time'   => $readingTime . ' min',
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            'tags'           => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}