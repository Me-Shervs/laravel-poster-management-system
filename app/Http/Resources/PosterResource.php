<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PosterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'expires_at' => $this->expires_at,
            'is_expired' => $this->is_expired,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'categories' => $this->categories,
        ];
    }
}