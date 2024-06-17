<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_graphql_api_id' => 'required|string',
            'handle' => 'required|string',
            'title' => 'required|string',
            'body_html' => 'required|string',

            'variants.*.admin_graphql_api_id' => 'required|string',
            'variants.*.title' => 'required|string',
            'variants.*.price' => 'required|numeric',
            'variants.*.inventory_quantity' => 'required|integer',

            'images.*.admin_graphql_api_id' => 'required|string',
            'images.*.src' => 'required|string',
            'images.*.alt' => 'nullable|string',
            'images.*.id' => 'required|integer',
        ];
    }
}
