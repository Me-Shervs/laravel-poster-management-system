<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\PosterStatus;

class UpdatePosterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],

            'content' => ['sometimes', 'array'],
            'content.text' => ['sometimes', 'string'],

            'status' => [
                'sometimes',
                new Enum(PosterStatus::class),
            ],

            'expires_at' => ['nullable', 'date'],
        ];
    }
}