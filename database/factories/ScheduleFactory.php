<?php

namespace Database\Factories;

use App\Models\Poster;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'poster_id' => Poster::factory(),
            'scheduled_at' => now()->addMinutes(10),
            'action' => 'publish',
            'status' => 'pending',
        ];
    }
}