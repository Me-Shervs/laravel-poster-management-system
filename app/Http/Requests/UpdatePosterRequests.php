<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePosterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],

            'content' => ['sometimes', 'array'],

            'status' => [
                'sometimes',
                'in:draft,scheduled,published,expired'
            ],

            'expires_at' => ['nullable', 'date'],
        ];
    }
}