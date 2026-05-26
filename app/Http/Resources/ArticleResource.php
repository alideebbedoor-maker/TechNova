<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource; // <-- استيراد ريسورس المستخدم

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'content'     => $this->content,
            'status'      => $this->status,
            'created_at'  => $this->created_at->toIso8601String(),
            
            // التعديل السحري: حماية النظام من الانهيار ومنع الـ N+1 Query
            // لن يتم عرض الكاتب إلا إذا كانت العلاقة محملة مسبقاً ونظيفة
            'author'      => new UserResource($this->whenLoaded('author')),
            
            'tags'        => JsonResource::collection($this->whenLoaded('tags')),
            'attachments' => JsonResource::collection($this->whenLoaded('attachments')),
            'comments'    => JsonResource::collection($this->whenLoaded('comments')),
        ];
    }
}