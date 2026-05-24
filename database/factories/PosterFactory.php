<?php

namespace Database\Factories;

use App\Models\Poster;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PosterStatus;
use \App\Models\User;

class PosterFactory extends Factory
{
    protected $model = Poster::class;

    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-120 days', 'now');

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),

            'content' => [
                'text' => fake()->paragraph(),
            ],

            'status' => PosterStatus::Draft,

            'created_at' => $createdAt,
            'updated_at' => $createdAt,

            'published_at' => null,
            'expires_at' => null,
        ];
    }

    /*
    |----------------------------------------
    | STATES (for testing only)
    |----------------------------------------
    */

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PosterStatus::Published,
            'published_at' => now()->subDays(rand(1, 30)),
            'expires_at' => now()->subMinutes(rand(1, 500)),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn () => [
            'status' => PosterStatus::Scheduled,
            'published_at' => null,
            'expires_at' => now()->addDays(rand(1, 10)),
        ]);
    }

    public function oldDraft(): static
    {
        return $this->state(fn () => [
            'status' => PosterStatus::Draft,
            'created_at' => now()->subDays(rand(91, 200)),
            'updated_at' => now()->subDays(rand(91, 200)),
        ]);
    }
}