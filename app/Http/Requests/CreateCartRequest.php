<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variantId' => 'nullable|exists:product_variants,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'items.*.productId.required' => 'Product ID is required',
            'items.*.productId.exists' => 'Product ID does not exist',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.integer' => 'Quantity must be an integer',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.variantId.exists' => 'Variant ID does not exist',
        ];
    }
}
