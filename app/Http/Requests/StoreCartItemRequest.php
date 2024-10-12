<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'variantId' => 'required|integer|exists:product_variants,id',
            'imageUrl' => 'required|string'
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity field must be an integer.',
            'quantity.min' => 'The quantity field must be at least 1.',
            'variantId.required' => 'The variantId field is required.',
            'variantId.integer' => 'The variantId field must be an integer.',
            'variantId.exists' => 'The selected variantId is invalid.',
            'imageUrl.required' => 'The imageUrl field is required.',
        ];
    }
}
