<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('articles-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'category_id'=>"required|array",
            // 'category_id.*'=>"required|exists:categories,id",
            // 'is_featured'=>"required|in:0,1",
            // 'slug'=>"required|max:190|unique:categories,slug",           
            // 'title'=>"required|max:190",
            // 'description'=>"nullable|max:10000",
            // 'meta_description'=>"nullable|max:10000",

            
           
        ];
    }
}
