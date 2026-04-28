<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ensure the category belongs to the authenticated user
        $category = $this->route('category');
        return $category && $category->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')
                    ->where('user_id', $this->user()->id)
                    ->ignore($category->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A category name is required.',
            'name.max'      => 'Category name cannot exceed 100 characters.',
            'name.unique'   => 'You already have a category with this name.',
        ];
    }
}