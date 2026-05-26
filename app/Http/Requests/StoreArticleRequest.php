<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ArticleStatusRule; 

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['writer', 'admin']);
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'min:10', 'unique:articles,title'],
            'content' => ['required', 'string', 'min:100'],
            'tags'    => ['nullable', 'array'],
            'tags.*'  => ['integer', 'exists:tags,id'],
            'status'  => ['required', 'string', new ArticleStatusRule()], 
        ];
    }

    
    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => ucwords(strtolower(preg_replace('/\s+/', ' ', trim($this->title))))
            ]);
        }

        if ($this->has('content')) {
            $this->merge([
                'content' => preg_replace('/\s+/', ' ', trim($this->content))
            ]);
        }
    }
}