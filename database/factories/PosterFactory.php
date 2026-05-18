<?php

namespace Database\Factories;

use App\Models\Poster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Poster>
 */
class PosterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(3),
            'content' => [
                'text' => fake()->paragraph(),
            ],
            'status' => 'draft',
            'published_at' => fake()->optional()->dateTime(),
            'expires_at' => fake()->optional()->dateTime(),
        ];
    }
}
