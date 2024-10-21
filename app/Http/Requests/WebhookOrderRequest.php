<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookOrderRequest extends FormRequest
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
            'contact_email' => 'required|email',
            'reference' => 'required|string',
            'note_attributes' => 'array',
            'note_attributes.*.name' => 'required|string',
            'note_attributes.*.value' => 'required|string',
        ];
    }
}
