<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    
    public function rules(): array
    {
        return [
            'article_id' => ['required', 'integer', 'exists:articles,id'],
            'content'    => ['required', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('content')) {
            $this->merge([
                'content' => \Illuminate\Support\Str::of($this->content)->squish()->toString(),
            ]);
        }
    }
}