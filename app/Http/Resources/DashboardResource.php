<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource 
{
    public function toArray($request) 
    {
        return [
            'total_articles'  => $this->resource['total_articles'],
            'total_writers'   => $this->resource['total_writers'],
            'total_comments'  => $this->resource['total_comments'],
            'latest_articles' => ArticleResource::collection($this->resource['latest_articles']),
        ];
    }
}