<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ArticleStatusRule; 
class UpdateArticleStatusRequest extends FormRequest 
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }
    
    public function rules(): array
    {
        return [

            'status' => ['required', 'string', new ArticleStatusRule()], 
        ];
    }
}