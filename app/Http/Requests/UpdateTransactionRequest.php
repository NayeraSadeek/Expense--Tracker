<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        return $transaction && $transaction->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'type'        => ['required', Rule::in(['income', 'expense'])],
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:999999999'],
            'description' => ['nullable', 'string', 'max:255'],
            'occurred_at' => ['required', 'date', 'before_or_equal:today'],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'Please select income or expense.',
            'type.in'              => 'Type must be either income or expense.',
            'amount.required'      => 'An amount is required.',
            'amount.numeric'       => 'Amount must be a number.',
            'amount.min'           => 'Amount must be at least 0.01.',
            'occurred_at.required' => 'A date is required.',
            'occurred_at.date'     => 'Please enter a valid date.',
            'occurred_at.before_or_equal' => 'Date cannot be in the future.',
            'category_id.exists'   => 'Selected category is invalid.',
        ];
    }
}