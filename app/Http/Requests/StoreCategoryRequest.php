<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth middleware already ensures user is logged in
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')->where('user_id', $this->user()->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'A category name is required.',
            'name.max'       => 'Category name cannot exceed 100 characters.',
            'name.unique'    => 'You already have a category with this name.',
        ];
    }
}