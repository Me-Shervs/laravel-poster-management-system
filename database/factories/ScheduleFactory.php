<?php

namespace Database\Factories;

use App\Models\Poster;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ScheduleAction;
use App\Enums\ScheduleStatus;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'poster_id' => Poster::factory(),

            'scheduled_at' => now()->subMinutes(rand(1, 60)),

            'action' => fake()->randomElement([
                ScheduleAction::Publish,
                ScheduleAction::Unpublish,
            ]),

            'status' => ScheduleStatus::Pending,

            'processed_at' => null,
        ];
    }
}