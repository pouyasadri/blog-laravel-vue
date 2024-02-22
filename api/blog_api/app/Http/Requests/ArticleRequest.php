<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->isMethod('put')) {
            return [
                'title' => 'required|string|max:255',
                'content' => 'required',
                'category' => 'required|in:news,updates,reports,stories',
                'status' => 'required|in:draft,published,archive',
            ];
        }
        return [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category' => 'required|in:news,updates,reports,stories',
            'status' => 'required|in:draft,published,archive',
            'image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A title is required',
            'content.required' => 'Content is required',
            'category.required' => 'Category is required',
            'status.required' => 'Status is required',
            'image.required' => 'Image is required',
        ];
    }
}
